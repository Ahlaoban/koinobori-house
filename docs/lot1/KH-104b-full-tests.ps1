<#
  KH-104b-full - Gate bilingue WooCommerce : matrice 10 URLs FR/EN. Version PowerShell.
  Couvre docs/lot1/KH-104b-full-plan.md sections 3-4 :
    10 URLs (boutique/shop, produit, panier/cart, commande/checkout, mon-compte/my-account)
    + langue servie + pas de croisement /fr/<->/en/ + canonical self + hreflang serie unique.
  Complement : KH-104b-pre-tests.sh (racine A-E) + KH-109-seo-tests.ps1 (sitemaps F).

  Windows PowerShell 5.1 compatible. Script ASCII pur (eviter mojibake ANSI).

  Pre-requis : 1 produit test bilingue publie (slug 'test' par defaut), /fr/produit/test/ et /en/produit/test/ = 200.

  Usage :
    .\KH-104b-full-tests.ps1 -Auth "staging:MOTDEPASSE"
    .\KH-104b-full-tests.ps1 -Auth "staging:MOTDEPASSE" -Slug "test"

  NE PAS committer la sortie (URL staging + slugs).
#>
[CmdletBinding()]
param(
  [string]$Base = $(if ($env:BASE) { $env:BASE } else { "https://staging.koinoborihouse.com" }),
  [string]$Auth = $env:AUTH,
  [string]$Slug = $(if ($env:SLUG) { $env:SLUG } else { "test" })
)

$ErrorActionPreference = "Stop"
$Base = $Base.TrimEnd('/')
$Log  = "KH-104b-full-$(Get-Date -Format 'yyyyMMdd-HHmmss').log"

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

function Invoke-Raw {
  param([string]$Url, [string]$Method = "GET", [hashtable]$ExtraHeaders = @{})
  $req = [System.Net.HttpWebRequest]::Create($Url)
  $req.Method = $Method
  $req.AllowAutoRedirect = $false
  $req.Timeout = 20000
  $req.UserAgent = "KH-104b-tester"
  if ($Auth) { $req.Headers["Authorization"] = "Basic " + [Convert]::ToBase64String([Text.Encoding]::ASCII.GetBytes($Auth)) }
  foreach ($k in $ExtraHeaders.Keys) {
    if ($k -ieq "User-Agent") { $req.UserAgent = $ExtraHeaders[$k] } else { $req.Headers[$k] = $ExtraHeaders[$k] }
  }
  $resp = $null
  try { $resp = $req.GetResponse() }
  catch [System.Net.WebException] { $resp = $_.Exception.Response; if (-not $resp) { return [pscustomobject]@{ Status = 0; Body = ""; Location = "" } } }
  $status = [int]$resp.StatusCode
  $body = ""
  try { $sr = New-Object IO.StreamReader($resp.GetResponseStream()); $body = $sr.ReadToEnd(); $sr.Close() } catch {}
  $loc = $resp.Headers["Location"]
  $xrb = $resp.Headers["x-redirect-by"]
  $sc  = $resp.Headers["Set-Cookie"]
  $resp.Close()
  [pscustomobject]@{ Status = $status; Body = $body; Location = $loc; XRedirectBy = $xrb; SetCookie = $sc }
}

$script:PASS = 0; $script:FAIL = 0; $script:WARN = 0
function Ok { param($m) Write-Host "  PASS  $m" -ForegroundColor Green;  $script:PASS++ }
function Ko { param($m) Write-Host "  FAIL  $m" -ForegroundColor Red;    $script:FAIL++ }
function Wn { param($m) Write-Host "  WARN  $m" -ForegroundColor Yellow; $script:WARN++ }
function LogH { param($t, $c) Add-Content -Path $Log -Value "`n===== $t =====`n$c" }

function Test-Hreflang {
  param($Name, $Body)
  $hFr = ([regex]::Matches($Body, '(?i)hreflang="fr(-FR)?"')).Count
  $hEn = ([regex]::Matches($Body, '(?i)hreflang="en(-US|-GB)?"')).Count
  $hXd = ([regex]::Matches($Body, '(?i)hreflang="x-default"')).Count
  if ($hFr -ge 1 -and $hEn -ge 1 -and $hXd -ge 1 -and $hFr -le 1 -and $hEn -le 1 -and $hXd -le 1) {
    Ok "$Name hreflang serie unique (fr=$hFr en=$hEn xd=$hXd)"
  } elseif ($hFr -gt 1 -or $hEn -gt 1 -or $hXd -gt 1) {
    Ko "$Name DOUBLON hreflang (fr=$hFr en=$hEn xd=$hXd)"
  } else {
    Ko "$Name hreflang incomplet (fr=$hFr en=$hEn xd=$hXd)"
  }
}

Write-Host "BASE=$Base   slug=$Slug   log=$Log`n"
Write-Host "--- Matrice 10 URLs WC (statut + langue + croisement + canonical + hreflang) ---`n"

$urls = @(
  @{ lang = "fr"; path = "/fr/boutique/";        kind = "page" },
  @{ lang = "en"; path = "/en/shop/";            kind = "page" },
  @{ lang = "fr"; path = "/fr/produit/$Slug/";   kind = "product" },
  @{ lang = "en"; path = "/en/produit/$Slug/";   kind = "product" },
  @{ lang = "fr"; path = "/fr/panier/";          kind = "page" },
  @{ lang = "en"; path = "/en/cart/";            kind = "page" },
  @{ lang = "fr"; path = "/fr/commande/";        kind = "checkout" },
  @{ lang = "en"; path = "/en/checkout/";        kind = "checkout" },
  @{ lang = "fr"; path = "/fr/mon-compte/";      kind = "account" },
  @{ lang = "en"; path = "/en/my-account/";      kind = "account" }
)

foreach ($u in $urls) {
  $lang  = $u.lang
  $other = if ($lang -eq "fr") { "en" } else { "fr" }
  $r = Invoke-Raw "$Base$($u.path)"
  LogH "$($u.path)" "Status=$($r.Status)  Location=$($r.Location)"
  Write-Host "[$($u.path)]  (kind=$($u.kind))"

  # --- croisement de langue (hard fail) ---
  if ($r.Location -match "(?i)/$other/") { Ko "  croisement -> /$other/ (HARD FAIL)"; continue }

  # --- statut ---
  switch ($r.Status) {
    200 {
      Ok "  200"
      # langue servie
      if ($r.Body -match "(?i)<html[^>]*\blang=`"$lang") { Ok "  langue servie = $lang" }
      else { Ko "  mauvaise langue servie (attendu lang=$lang)" }
      # canonical self
      $link = [regex]::Match($r.Body, '(?i)<link[^>]*rel="?canonical"?[^>]*>')
      if ($link.Success) {
        $href = [regex]::Match($link.Value, '(?i)href="([^"]+)"').Groups[1].Value
        if ($href -match "(?i)/$lang/" -and $href -notmatch "(?i)/$other/") { Ok "  canonical self ($href)" }
        else { Ko "  canonical inattendu : $href" }
      } else { Ko "  aucun canonical" }
      # hreflang
      Test-Hreflang "  " $r.Body
    }
    302 {
      if ($u.kind -eq "checkout" -or $u.kind -eq "account") {
        Wn "  302 same-lang (panier vide / login) -> $($r.Location) [WC normal]"
      } else {
        Ko "  302 inattendu pour $($u.kind) -> $($r.Location)"
      }
    }
    301 { Ko "  301 (HARD FAIL doctrine)" }
    404 { Ko "  404 (URL absente)" }
    default { Ko "  statut inattendu = $($r.Status)" }
  }
  Write-Host ""
}

# --- Racine : 302 par Accept-Language + cookie (rappel KH-107) ---
Write-Host "--- Racine / (rappel KH-107) ---"
$r = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "fr-FR,fr;q=0.9" }
LogH "racine-fr" "Status=$($r.Status)  Location=$($r.Location)  Set-Cookie=$($r.SetCookie)  x-redirect-by=$($r.XRedirectBy)"
if ($r.Status -eq 302 -and $r.Location -match "(?i)/fr/" -and $r.Status -ne 301) { Ok "racine AL=fr -> 302 /fr/" } else { Ko "racine AL=fr -> attendu 302 /fr/ (statut=$($r.Status))" }
if ($r.XRedirectBy -match "(?i)koino-lang-redirect") { Ok "x-redirect-by koino-lang-redirect (KH-107)" } else { Ko "x-redirect-by absent/incorrect" }
if ($r.SetCookie -match "(?i)koino_lang_pref=fr") { Ok "cookie koino_lang_pref pose" } else { Ko "cookie koino_lang_pref absent" }
$r2 = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "en-US,en;q=0.9" }
if ($r2.Status -eq 302 -and $r2.Location -match "(?i)/en/") { Ok "racine AL=en -> 302 /en/" } else { Ko "racine AL=en -> attendu 302 /en/ (statut=$($r2.Status))" }

# --- Googlebot UA ---
Write-Host "`n--- Googlebot UA ---"
$gb = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
$rg = Invoke-Raw "$Base/" "GET" @{ "User-Agent" = $gb; "Accept-Language" = "en-US" }
if ($rg.Status -eq 302 -and $rg.Location -match "(?i)/en/") { Ok "Googlebot / -> 302 /en/" } else { Ko "Googlebot / -> attendu 302 /en/ (statut=$($rg.Status))" }
$rgf = Invoke-Raw "$Base/fr/" "GET" @{ "User-Agent" = $gb }
if ($rgf.Status -eq 200) { Ok "Googlebot /fr/ -> 200" } else { Ko "Googlebot /fr/ statut=$($rgf.Status)" }
$rge = Invoke-Raw "$Base/en/" "GET" @{ "User-Agent" = $gb }
if ($rge.Status -eq 200) { Ok "Googlebot /en/ -> 200" } else { Ko "Googlebot /en/ statut=$($rge.Status)" }

Write-Host "`n================  RESULTAT KH-104b-full  ================"
Write-Host "  PASS=$($script:PASS)   FAIL=$($script:FAIL)   WARN=$($script:WARN)"
Write-Host "  Rappel : completer avec KH-104b-pre-tests.sh (A-E, 25/27) + KH-109-seo-tests.ps1 (F, 12/12) + Site Health"
if ($script:FAIL -eq 0) {
  Write-Host "  => 10 URLs OK. GO KH-104b-full sous reserve scripts complementaires + preuves visuelles + Site Health." -ForegroundColor Green
} else {
  Write-Host "  => NO-GO : voir FAIL (HARD FAIL = 301 / croisement / mauvaise langue / canonical cross-lang / doublon hreflang / 404)." -ForegroundColor Red
}
Write-Host "  WARN = checkout/mon-compte en 302 same-lang (panier vide / login) = WC normal, non bloquant."
Write-Host "  Log detaille : $Log"
exit $(if ($script:FAIL -eq 0) { 0 } else { 1 })
