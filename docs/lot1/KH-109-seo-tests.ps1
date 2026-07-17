<#
  KH-109 - verification SEO i18n (SEOPress Free + Polylang) sur staging. Version PowerShell.
  Couvre la matrice section 8 du plan docs/lot1/KH-109-seopress-seo.md (Groupe F) :
    canonical self par langue, hreflang sans doublon, sitemaps par langue (FR+EN)
    + non-regression racine KH-107.

  Windows PowerShell 5.1 compatible (HttpWebRequest : gere cert auto-signe + basic auth + 302 sans suivre).
  Script ASCII pur (PS 5.1 lit en ANSI : pas d'accents ni tirets longs pour eviter tout mojibake).

  Usage :
    .\KH-109-seo-tests.ps1 -Auth "staging:MOTDEPASSE"
  ou avec env :
    $env:AUTH="staging:MOTDEPASSE"; .\KH-109-seo-tests.ps1
  URL produit test optionnelle (sinon auto-decouverte via sitemap) :
    .\KH-109-seo-tests.ps1 -Auth "staging:MOTDEPASSE" -ProdFrUrl ".../fr/produit/x/" -ProdEnUrl ".../en/produit/x/"

  NE PAS committer la sortie (URL staging + slugs). Le script n'ecrit aucun secret.
  Perimetre : V1-V14 auto. V15 = relancer KH-104b-pre-tests.sh. V16 = Site Health (manuel admin WP).
#>
[CmdletBinding()]
param(
  [string]$Base      = $(if ($env:BASE) { $env:BASE } else { "https://staging.koinoborihouse.com" }),
  [string]$Auth      = $env:AUTH,
  [string]$ProdFrUrl = $env:PROD_FR_URL,
  [string]$ProdEnUrl = $env:PROD_EN_URL
)

$ErrorActionPreference = "Stop"
$Base = $Base.TrimEnd('/')
$Log  = "KH-109-seo-$(Get-Date -Format 'yyyyMMdd-HHmmss').log"

# --- Accepter le certificat auto-signe du staging (PS 5.1) ---
if (-not ("TrustAllPolicy" -as [type])) {
  Add-Type @"
using System.Net; using System.Security.Cryptography.X509Certificates;
public class TrustAllPolicy : ICertificatePolicy {
  public bool CheckValidationResult(ServicePoint s, X509Certificate c, WebRequest r, int p) { return true; }
}
"@
}
[System.Net.ServicePointManager]::CertificatePolicy = New-Object TrustAllPolicy
[System.Net.ServicePointManager]::SecurityProtocol  = [System.Net.SecurityProtocolType]::Tls12

# --- Requete bas niveau : statut + en-tetes + corps, sans suivre les redirections ---
function Invoke-Raw {
  param([string]$Url, [string]$Method = "GET", [hashtable]$ExtraHeaders = @{})
  $req = [System.Net.HttpWebRequest]::Create($Url)
  $req.Method = $Method
  $req.AllowAutoRedirect = $false
  $req.Timeout = 20000
  $req.UserAgent = "KH-109-tester"
  if ($Auth) {
    $b64 = [Convert]::ToBase64String([Text.Encoding]::ASCII.GetBytes($Auth))
    $req.Headers["Authorization"] = "Basic $b64"
  }
  foreach ($k in $ExtraHeaders.Keys) {
    if ($k -ieq "User-Agent") { $req.UserAgent = $ExtraHeaders[$k] }
    else                      { $req.Headers[$k] = $ExtraHeaders[$k] }
  }
  $resp = $null
  try { $resp = $req.GetResponse() }
  catch [System.Net.WebException] {
    $resp = $_.Exception.Response
    if (-not $resp) { return [pscustomobject]@{ Status = 0; Headers = @{}; Body = ""; Location = "" } }
  }
  $status = [int]$resp.StatusCode
  $hdrs = @{}
  foreach ($n in $resp.Headers.Keys) { $hdrs[$n] = $resp.Headers[$n] }
  $body = ""
  try { $sr = New-Object IO.StreamReader($resp.GetResponseStream()); $body = $sr.ReadToEnd(); $sr.Close() } catch {}
  $loc = $resp.Headers["Location"]
  $resp.Close()
  [pscustomobject]@{ Status = $status; Headers = $hdrs; Body = $body; Location = $loc }
}
function Get-Body { param([string]$Url) (Invoke-Raw -Url $Url).Body }

# --- Compteurs / sortie ---
$script:PASS = 0; $script:FAIL = 0; $script:SKIP = 0
function Ok { param($m) Write-Host "  PASS  $m" -ForegroundColor Green;  $script:PASS++ }
function Ko { param($m) Write-Host "  FAIL  $m" -ForegroundColor Red;    $script:FAIL++ }
function Sk { param($m) Write-Host "  SKIP  $m" -ForegroundColor Yellow; $script:SKIP++ }
function LogH { param($t, $c) Add-Content -Path $Log -Value "`n===== $t =====`n$c" }

Write-Host "BASE=$Base   log=$Log`n"

# =====================================================================
Write-Host "--- Groupe F1 : codes HTTP pages cles (V1-V6) ---"
foreach ($t in @(
    @{ n = "V1  /fr/ 200";          p = "/fr/" },
    @{ n = "V2  /en/ 200";          p = "/en/" },
    @{ n = "V3  /fr/boutique/ 200"; p = "/fr/boutique/" },
    @{ n = "V4  /en/shop/ 200";     p = "/en/shop/" })) {
  $r = Invoke-Raw "$Base$($t.p)"; LogH $t.n "Status=$($r.Status)"
  if ($r.Status -eq 200) { Ok $t.n } else { Ko "$($t.n) (statut=$($r.Status))" }
}

# =====================================================================
Write-Host "`n--- Groupe F2 : sitemaps (V10-V13) [auto-decouverte index + enfants] ---"
$smIndex = ""; $smBody = ""
foreach ($cand in @("/sitemaps.xml", "/sitemap.xml", "/sitemap_index.xml")) {
  $b = Get-Body "$Base$cand"
  if ($b -match "(?i)<(sitemapindex|urlset)") { $smIndex = $cand; $smBody = $b; break }
}
if ($smIndex) { Ok "V10 sitemap index trouve ($smIndex, XML valide)"; LogH "V10" $smBody }
else { Ko "V10 aucun index sitemap (sonde /sitemaps.xml /sitemap.xml /sitemap_index.xml)" }

# Agrege index + sous-sitemaps
$allSm = $smBody
if ($smIndex) {
  foreach ($m in [regex]::Matches($smBody, "(?i)<loc>([^<]+)</loc>")) {
    $u = $m.Groups[1].Value
    if ($u -match "(?i)\.xml|sitemap") { $allSm += "`n" + (Get-Body $u) }
  }
}
$locsSample = ([regex]::Matches($allSm, "(?i)<loc>[^<]+</loc>") | Select-Object -First 40 | ForEach-Object { $_.Value }) -join "`n"
LogH "ALLSM-locs(extrait)" $locsSample

if ($allSm -match "(?i)<loc>[^<]*/(page|boutique|shop|mentions|contact)[^<]*</loc>" -or $smBody -match "(?i)page-sitemap") {
  Ok "V11 sitemap pages present"
} else { Ko "V11 pages absentes du sitemap (verifier inclusion post type page)" }

if ($allSm -match "(?i)<loc>[^<]*/produit/[^<]+</loc>" -or $smBody -match "(?i)product-sitemap") {
  Ok "V12 sitemap produits present"
} else { Ko "V12 produits absents (cocher post type 'product' dans SEOPress XML Sitemap)" }

$nFr = ([regex]::Matches($allSm, "(?i)<loc>[^<]*/fr/")).Count
$nEn = ([regex]::Matches($allSm, "(?i)<loc>[^<]*/en/")).Count
Write-Host "    (sitemap: $nFr URLs /fr/, $nEn URLs /en/)"
if ($nFr -ge 1 -and $nEn -ge 1) { Ok "V13 sitemap couvre FR + EN" }
else { Ko "V13 une seule langue couverte (FR=$nFr EN=$nEn) - attendu les deux" }

# Decouverte URL produit test via sitemap (si non fournie)
if (-not $ProdFrUrl) { $m = [regex]::Match($allSm, "(?i)https?://[^<]*/fr/produit/[^<]+"); if ($m.Success) { $ProdFrUrl = $m.Value } }
if (-not $ProdEnUrl) { $m = [regex]::Match($allSm, "(?i)https?://[^<]*/en/produit/[^<]+"); if ($m.Success) { $ProdEnUrl = $m.Value } }

Write-Host "`n--- Groupe F1 (suite) : fiches produit (V5-V6) ---"
if ($ProdFrUrl) { $r = Invoke-Raw $ProdFrUrl; LogH "V5" "$ProdFrUrl Status=$($r.Status)"; if ($r.Status -eq 200) { Ok "V5  produit FR 200 ($ProdFrUrl)" } else { Ko "V5  produit FR statut=$($r.Status)" } }
else { Sk "V5  pas d'URL produit FR (fournir -ProdFrUrl ou creer un produit test)" }
if ($ProdEnUrl) { $r = Invoke-Raw $ProdEnUrl; LogH "V6" "$ProdEnUrl Status=$($r.Status)"; if ($r.Status -eq 200) { Ok "V6  produit EN 200 ($ProdEnUrl)" } else { Ko "V6  produit EN statut=$($r.Status)" } }
else { Sk "V6  pas d'URL produit EN (fournir -ProdEnUrl)" }

# =====================================================================
Write-Host "`n--- Groupe F3 : canonical self par langue (V7-V8) ---"
function Test-Canon {
  param($Name, $Path, $Expect, $Forbid)
  $b = Get-Body "$Base$Path"
  $link = [regex]::Match($b, '(?i)<link[^>]*rel="?canonical"?[^>]*>')
  LogH "$Name-canonical" $link.Value
  if (-not $link.Success) { Ko "$Name (aucun canonical trouve)"; return }
  $href = [regex]::Match($link.Value, '(?i)href="([^"]+)"').Groups[1].Value
  if ($href -match $Expect -and $href -notmatch $Forbid) { Ok "$Name ($href)" }
  else { Ko "$Name canonical inattendu : $href" }
}
Test-Canon "V7  canonical /fr/boutique/ self" "/fr/boutique/" "/fr/boutique" "/en/"
Test-Canon "V8  canonical /en/shop/ self"     "/en/shop/"     "/en/shop"     "/fr/"

# =====================================================================
Write-Host "`n--- Groupe F4 : hreflang serie unique (V9) ---"
$bFr = Get-Body "$Base/fr/"
$altSample = ([regex]::Matches($bFr, '(?i)<link[^>]*rel="?alternate"?[^>]*hreflang=[^>]*>') | ForEach-Object { $_.Value }) -join "`n"
LogH "V9-fr" $altSample
$hFr = ([regex]::Matches($bFr, '(?i)hreflang="fr(-FR)?"')).Count
$hEn = ([regex]::Matches($bFr, '(?i)hreflang="en(-US|-GB)?"')).Count
$hXd = ([regex]::Matches($bFr, '(?i)hreflang="x-default"')).Count
Write-Host "    (/fr/ hreflang: fr=$hFr en=$hEn x-default=$hXd)"
if ($hFr -ge 1 -and $hEn -ge 1 -and $hXd -ge 1) {
  if ($hFr -le 1 -and $hEn -le 1 -and $hXd -le 1) { Ok "V9  hreflang fr+en+x-default, serie unique" }
  else { Ko "V9  DOUBLON hreflang (fr=$hFr en=$hEn x-default=$hXd) - SEOPress + Polylang emettent en double, desactiver l'un" }
} else { Ko "V9  hreflang incomplet (fr=$hFr en=$hEn x-default=$hXd) - attendu chacun au moins 1" }

# =====================================================================
Write-Host "`n--- Groupe F5 : non-regression racine KH-107 (V14) ---"
$r = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "fr-FR" }
LogH "V14" "Status=$($r.Status)  x-redirect-by=$($r.Headers['x-redirect-by'])  Location=$($r.Location)"
if ($r.Status -eq 302 -and $r.Headers["x-redirect-by"] -match "(?i)koino-lang-redirect") {
  Ok "V14 racine / 302 x-redirect-by: koino-lang-redirect (KH-107 intact)"
} elseif ($r.Status -eq 301) {
  Ko "V14 racine / renvoie 301 (HARD FAIL - doctrine : jamais 301 sur /)"
} else {
  Ko "V14 racine alteree (statut=$($r.Status), x-redirect-by=$($r.Headers['x-redirect-by']))"
}

# =====================================================================
Write-Host "`n================  RESULTAT KH-109  ================"
Write-Host "  PASS=$($script:PASS)   FAIL=$($script:FAIL)   SKIP=$($script:SKIP)"
Write-Host "  Rappel manuel : V15 = relancer KH-104b-pre-tests.sh (attendu 25/27 inchange)"
Write-Host "                  V16 = Site Health (admin WP, aucune nouvelle anomalie critique)"
if ($script:FAIL -eq 0) {
  Write-Host "  => automatise OK. GO KH-109 sous reserve V15 (25/27) + V16 + preuves visuelles." -ForegroundColor Green
} else {
  Write-Host "  => NO-GO automatise : voir FAIL + $Log. Ajuster reglages SEOPress (canonical/hreflang/sitemap) puis re-run." -ForegroundColor Red
}
Write-Host "  Log detaille : $Log"
exit $(if ($script:FAIL -eq 0) { 0 } else { 1 })
