<#
  KH-104b-pre - tests racine i18n (KH-104 Polylang + KH-107 mu-plugin). Version PowerShell.
  Port fidele de KH-104b-pre-tests.sh : memes 27 assertions, groupes A/B/C/D/E.
  Sert aussi de V15 (non-regression KH-107) apres install SEOPress (KH-109).

  Windows PowerShell 5.1 compatible. Script ASCII pur (eviter mojibake ANSI).

  Usage :
    .\KH-104b-pre-tests.ps1 -Auth "staging:MOTDEPASSE"

  Attendu post-SEOPress = 25/27 inchange (A11 Vary + D2 wp-login = exceptions connues).
  NE PAS committer la sortie (URL staging).
#>
[CmdletBinding()]
param(
  [string]$Base = $(if ($env:BASE) { $env:BASE } else { "https://staging.koinoborihouse.com" }),
  [string]$Auth = $env:AUTH
)

$ErrorActionPreference = "Stop"
$Base = $Base.TrimEnd('/')
$Log  = "KH-104b-pre-ps-$(Get-Date -Format 'yyyyMMdd-HHmmss').log"
$Googlebot = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'

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
  catch [System.Net.WebException] { $resp = $_.Exception.Response; if (-not $resp) { return [pscustomobject]@{ Status = 0; Headers = @{}; Location = ""; SetCookie = ""; CacheControl = ""; Vary = ""; XRedirectBy = "" } } }
  $status = [int]$resp.StatusCode
  $hdrs = @{}
  foreach ($n in $resp.Headers.Keys) { $hdrs[$n] = $resp.Headers[$n] }
  $resp.Close()
  [pscustomobject]@{
    Status = $status; Headers = $hdrs;
    Location     = $hdrs["Location"];
    SetCookie    = $hdrs["Set-Cookie"];
    CacheControl = $hdrs["Cache-Control"];
    Vary         = $hdrs["Vary"];
    XRedirectBy  = $hdrs["x-redirect-by"]
  }
}

function Count-Redirects {
  param([string]$Url, [hashtable]$ExtraHeaders = @{})
  $n = 0; $cur = $Url
  for ($i = 0; $i -lt 6; $i++) {
    $r = Invoke-Raw $cur "GET" $ExtraHeaders
    if ($r.Status -ge 300 -and $r.Status -lt 400 -and $r.Location) {
      $n++
      if ($r.Location -match '^https?://') { $cur = $r.Location } else { $cur = $Base + $r.Location }
    } else { break }
  }
  return $n
}

$script:PASS = 0; $script:FAIL = 0
function Ok { param($m) Write-Host "  PASS  $m" -ForegroundColor Green; $script:PASS++ }
function Ko { param($m) Write-Host "  FAIL  $m" -ForegroundColor Red;   $script:FAIL++ }
function LogH { param($t, $r) Add-Content -Path $Log -Value "`n===== $t =====`nStatus=$($r.Status) Location=$($r.Location) Set-Cookie=$($r.SetCookie) Cache-Control=$($r.CacheControl) Vary=$($r.Vary) x-redirect-by=$($r.XRedirectBy)" }

function Is302 { param($r) ($r.Status -eq 302) }
function LocTo { param($Name, $r, $Frag)
  if ((Is302 $r) -and ($r.Location -match $Frag)) { Ok $Name } else { Ko "$Name (statut=$($r.Status) loc=$($r.Location))" }
}
function St200NoRedir { param($Name, $r)
  if ($r.Status -eq 200 -and -not $r.Location) { Ok $Name } else { Ko "$Name (statut=$($r.Status) loc=$($r.Location))" }
}

Write-Host "BASE=$Base   log=$Log`n"

Write-Host "--- Groupe A : racine / (KH-107) ---"
$r = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "fr-FR,fr;q=0.9" }; LogH "A1" $r
LocTo "A1  fr* sans cookie -> /fr/" $r "/fr/"
if ($r.SetCookie -match "(?i)koino_lang_pref=fr") { Ok "A10 Set-Cookie koino_lang_pref" } else { Ko "A10 Set-Cookie koino_lang_pref" }
if ($r.SetCookie -match "(?i)samesite=lax" -and $r.SetCookie -match "(?i)secure") { Ok "A10 cookie SameSite=Lax + Secure" } else { Ko "A10 cookie SameSite=Lax + Secure" }
if ($r.Vary -match "(?i)cookie" -and $r.Vary -match "(?i)accept-language") { Ok "A11 Vary: Cookie, Accept-Language" } else { Ko "A11 Vary: Cookie, Accept-Language (exception connue: LiteSpeed strippe Vary)" }
if ($r.CacheControl -match "(?i)no-cache|no-store|max-age=0") { Ok "A12 Cache-Control no-cache" } else { Ko "A12 Cache-Control no-cache" }

$r = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "en-US,en;q=0.9" }; LogH "A2" $r
LocTo "A2  en sans cookie -> /en/" $r "/en/"
$r = Invoke-Raw "$Base/"; LogH "A3" $r
LocTo "A3  sans Accept-Language -> /en/ (fallback)" $r "/en/"
$r = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "de-DE" }; LogH "A4" $r
LocTo "A4  de-DE -> /en/ (fallback)" $r "/en/"
$r = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "fr-CA" }; LogH "A5" $r
LocTo "A5  fr-CA (fr*) -> /fr/" $r "/fr/"
$r = Invoke-Raw "$Base/" "GET" @{ "Cookie" = "koino_lang_pref=en"; "Accept-Language" = "fr-FR" }; LogH "A6" $r
LocTo "A6  cookie=en prime sur AL=fr -> /en/" $r "/en/"
$r = Invoke-Raw "$Base/" "GET" @{ "Cookie" = "koino_lang_pref=fr"; "Accept-Language" = "en-US" }; LogH "A7" $r
LocTo "A7  cookie=fr prime sur AL=en -> /fr/" $r "/fr/"
$r = Invoke-Raw "$Base/" "GET" @{ "Cookie" = "koino_lang_pref=xx"; "Accept-Language" = "fr" }; LogH "A8" $r
LocTo "A8  cookie invalide ignore -> header fr -> /fr/" $r "/fr/"
$r = Invoke-Raw "$Base/" "GET" @{ "Accept-Language" = "en-US" }; LogH "A9" $r
if ($r.Status -eq 301) { Ko "A9  JAMAIS 301 sur / (HARD FAIL)" } else { Ok "A9  jamais 301 sur /" }
$r = Invoke-Raw "$Base/?utm_source=x" "GET" @{ "Accept-Language" = "fr-FR" }; LogH "A13" $r
LocTo "A13 query preservee -> /fr/?utm_source=x" $r "/fr/\?utm_source=x"
$r = Invoke-Raw "$Base/" "HEAD" @{ "Accept-Language" = "fr-FR" }; LogH "A14" $r
LocTo "A14 HEAD / -> /fr/" $r "/fr/"

Write-Host "`n--- Groupe B : /fr/ et /en/ directs (jamais croises) ---"
$r = Invoke-Raw "$Base/fr/"; LogH "B1" $r; St200NoRedir "B1  /fr/ -> 200 sans redirection" $r
$r = Invoke-Raw "$Base/en/"; LogH "B2" $r; St200NoRedir "B2  /en/ -> 200 sans redirection" $r
$r = Invoke-Raw "$Base/fr/" "GET" @{ "Accept-Language" = "en-US" }; LogH "B3" $r
if ($r.Location -match "(?i)/en/") { Ko "B3  /fr/ + AL=en NON renegocie (HARD FAIL -> /en/)" } else { St200NoRedir "B3  /fr/ + AL=en reste 200 /fr/" $r }
$r = Invoke-Raw "$Base/en/" "GET" @{ "Cookie" = "koino_lang_pref=fr" }; LogH "B4" $r
if ($r.Location -match "(?i)/fr/") { Ko "B4  /en/ + cookie=fr NON renegocie (HARD FAIL -> /fr/)" } else { St200NoRedir "B4  /en/ + cookie=fr reste 200 /en/" $r }

Write-Host "`n--- Groupe C : Googlebot UA ---"
$r = Invoke-Raw "$Base/" "GET" @{ "User-Agent" = $Googlebot; "Accept-Language" = "en-US" }; LogH "C1" $r
LocTo "C1  Googlebot / -> 302 /en/" $r "/en/"
$r = Invoke-Raw "$Base/fr/" "GET" @{ "User-Agent" = $Googlebot }; LogH "C2fr" $r; St200NoRedir "C2  Googlebot /fr/ -> 200" $r
$r = Invoke-Raw "$Base/en/" "GET" @{ "User-Agent" = $Googlebot }; LogH "C2en" $r; St200NoRedir "C2  Googlebot /en/ -> 200" $r

Write-Host "`n--- Groupe D : admin / API non perturbes ---"
$r = Invoke-Raw "$Base/wp-admin/"; LogH "D1" $r
if ($r.Location -match "(?i)/(fr|en)/") { Ko "D1  /wp-admin/ redirige vers langue (FAIL)" } else { Ok "D1  /wp-admin/ non perturbe (login natif)" }
$r = Invoke-Raw "$Base/wp-login.php"; LogH "D2" $r
if ($r.Status -eq 200) { Ok "D2  /wp-login.php -> 200" } else { Ko "D2  /wp-login.php -> 200 (exception connue)" }
$r = Invoke-Raw "$Base/wp-json/"; LogH "D3" $r
if ($r.Location -match "(?i)/(fr|en)/") { Ko "D3  /wp-json/ redirige vers langue (FAIL)" } else { Ok "D3  /wp-json/ non perturbe" }

Write-Host "`n--- Groupe E : integrite Polylang (pas de double saut) ---"
$n = Count-Redirects "$Base/" @{ "Accept-Language" = "fr-FR" }
Add-Content -Path $Log -Value "`nE1 num_redirects=$n"
if ($n -eq 1) { Ok "E1  / -> 1 seul saut de redirection" } else { Ko "E1  / -> $n sauts (attendu 1 ; >1 = conflit Polylang/mu-plugin)" }
$nf = Count-Redirects "$Base/fr/"
$ne = Count-Redirects "$Base/en/"
Add-Content -Path $Log -Value "E2 /fr/ redirs=$nf  /en/ redirs=$ne"
if ($nf -eq 0 -and $ne -eq 0) { Ok "E2  /fr/ et /en/ sans boucle (0 redir)" } else { Ko "E2  /fr/=$nf /en/=$ne (attendu 0 ; boucle suspecte)" }

Write-Host "`n================  RESULTAT  ================"
Write-Host "  PASS=$($script:PASS)   FAIL=$($script:FAIL)"
if ($script:FAIL -eq 0) {
  Write-Host "  => 27/27. GO (au-dela du 25/27 attendu)." -ForegroundColor Green
} elseif ($script:FAIL -le 2) {
  Write-Host "  => $($script:PASS)/27. Verifier que les FAIL = exceptions connues (A11 Vary + D2 wp-login). Si oui = NON-REGRESSION OK (25/27)." -ForegroundColor Yellow
} else {
  Write-Host "  => NO-GO : $($script:FAIL) FAIL. Verifier d'abord les HARD FAIL (301 / croisement /fr/<->/en/ / double saut)." -ForegroundColor Red
}
Write-Host "  Log detaille : $Log"
exit $(if ($script:FAIL -eq 0) { 0 } else { 1 })
