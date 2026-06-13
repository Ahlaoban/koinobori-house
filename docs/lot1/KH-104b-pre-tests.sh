#!/usr/bin/env bash
#
# KH-104b-pré — tests automatiques i18n racine (KH-104 Polylang Free + KH-107 mu-plugin).
# Couvre les groupes A/B/C/D/E du plan docs/lot1/KH-104b-pre-plan.md.
# (Groupe F hreflang/canonical/sitemaps = N/A tant que KH-109 RankMath non fait.)
#
# Usage (Git Bash sous Windows, ou SSH o2switch) :
#   BASE="https://staging.koinoborihouse.com" AUTH="StagingKH:MOTDEPASSE" bash KH-104b-pre-tests.sh
#
# Staging = basic auth + certificat auto-signé  ->  --user (via AUTH) + curl -k.
# Ne PAS committer la sortie (contient l'URL staging). Le script n'écrit aucun secret.
#
set -u

BASE="${BASE:-https://staging.koinoborihouse.com}"
AUTH="${AUTH:-}"
GOOGLEBOT='Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
LOG="KH-104b-pre-$(date '+%Y%m%d-%H%M%S').log"

# curl de base : récupère les en-têtes (-D -), jette le corps (-o /dev/null), ne suit PAS les redirections.
COMMON=(-sS -k -D - -o /dev/null --max-time 20)
[ -n "$AUTH" ] && COMMON+=(--user "$AUTH")
# curl qui SUIT les redirections (pour compter les sauts).
FOLLOW=(-sS -k -o /dev/null --max-time 20)
[ -n "$AUTH" ] && FOLLOW+=(--user "$AUTH")

PASS=0; FAIL=0
ok(){ echo "  PASS  $1"; PASS=$((PASS+1)); }
ko(){ echo "  FAIL  $1"; FAIL=$((FAIL+1)); }
has(){ grep -iqE "$2" <<<"$1"; }   # has "$HEADERS" 'regex'

req(){ local m="$1" p="$2"; shift 2; curl "${COMMON[@]}" -X "$m" "$@" "$BASE$p"; }

# log brut + assertions
logh(){ printf '\n===== %s =====\n%s\n' "$1" "$2" >>"$LOG"; }

is302(){ has "$1" '^HTTP/[0-9.]+ 302' && ! has "$1" '^HTTP/[0-9.]+ 301'; }
loc(){ # loc NAME HEADERS REGEX_FRAGMENT  (ex: /fr/)
  if is302 "$2" && has "$2" "^location:[[:space:]]*https?://[^[:space:]]*$3"; then ok "$1"; else ko "$1"; fi
}
st200_noredir(){ # NAME HEADERS
  if has "$2" '^HTTP/[0-9.]+ 200' && ! has "$2" '^location:'; then ok "$1"; else ko "$1"; fi
}

echo "BASE=$BASE   log=$LOG"
echo

echo "--- Groupe A : racine / (KH-107) ---"
H=$(req GET / -H 'Accept-Language: fr-FR,fr;q=0.9'); logh A1 "$H"
loc "A1  fr* sans cookie -> /fr/" "$H" '/fr/'
if has "$H" '^set-cookie:[[:space:]]*koino_lang_pref=fr'; then ok "A10 Set-Cookie koino_lang_pref"; else ko "A10 Set-Cookie koino_lang_pref"; fi
if has "$H" '^set-cookie:.*samesite=lax' && has "$H" '^set-cookie:.*secure'; then ok "A10 cookie SameSite=Lax + Secure"; else ko "A10 cookie SameSite=Lax + Secure"; fi
if has "$H" '^vary:.*cookie' && has "$H" '^vary:.*accept-language'; then ok "A11 Vary: Cookie, Accept-Language"; else ko "A11 Vary: Cookie, Accept-Language"; fi
if has "$H" '^cache-control:.*(no-cache|no-store|max-age=0)'; then ok "A12 Cache-Control no-cache"; else ko "A12 Cache-Control no-cache"; fi

H=$(req GET / -H 'Accept-Language: en-US,en;q=0.9'); logh A2 "$H"
loc "A2  en sans cookie -> /en/" "$H" '/en/'

H=$(req GET /); logh A3 "$H"
loc "A3  sans Accept-Language -> /en/ (fallback)" "$H" '/en/'

H=$(req GET / -H 'Accept-Language: de-DE'); logh A4 "$H"
loc "A4  de-DE -> /en/ (fallback)" "$H" '/en/'

H=$(req GET / -H 'Accept-Language: fr-CA'); logh A5 "$H"
loc "A5  fr-CA (fr*) -> /fr/" "$H" '/fr/'

H=$(req GET / -H 'Cookie: koino_lang_pref=en' -H 'Accept-Language: fr-FR'); logh A6 "$H"
loc "A6  cookie=en prime sur AL=fr -> /en/" "$H" '/en/'

H=$(req GET / -H 'Cookie: koino_lang_pref=fr' -H 'Accept-Language: en-US'); logh A7 "$H"
loc "A7  cookie=fr prime sur AL=en -> /fr/" "$H" '/fr/'

H=$(req GET / -H 'Cookie: koino_lang_pref=xx' -H 'Accept-Language: fr'); logh A8 "$H"
loc "A8  cookie invalide ignore -> header fr -> /fr/" "$H" '/fr/'

H=$(req GET / -H 'Accept-Language: en-US'); logh A9 "$H"
if has "$H" '^HTTP/[0-9.]+ 301'; then ko "A9  JAMAIS 301 sur / (HARD FAIL)"; else ok "A9  jamais 301 sur /"; fi

H=$(req GET '/?utm_source=x' -H 'Accept-Language: fr-FR'); logh A13 "$H"
loc "A13 query preservee -> /fr/?utm_source=x" "$H" '/fr/\?utm_source=x'

H=$(req HEAD / -H 'Accept-Language: fr-FR'); logh A14 "$H"
loc "A14 HEAD / -> /fr/" "$H" '/fr/'

echo
echo "--- Groupe B : /fr/ et /en/ directs (jamais croises) ---"
H=$(req GET /fr/); logh B1 "$H"; st200_noredir "B1  /fr/ -> 200 sans redirection" "$H"
H=$(req GET /en/); logh B2 "$H"; st200_noredir "B2  /en/ -> 200 sans redirection" "$H"
H=$(req GET /fr/ -H 'Accept-Language: en-US'); logh B3 "$H"
if has "$H" '^location:.*/en/'; then ko "B3  /fr/ + AL=en NON renegocie (HARD FAIL: -> /en/)"; else st200_noredir "B3  /fr/ + AL=en reste 200 /fr/" "$H"; fi
H=$(req GET /en/ -H 'Cookie: koino_lang_pref=fr'); logh B4 "$H"
if has "$H" '^location:.*/fr/'; then ko "B4  /en/ + cookie=fr NON renegocie (HARD FAIL: -> /fr/)"; else st200_noredir "B4  /en/ + cookie=fr reste 200 /en/" "$H"; fi

echo
echo "--- Groupe C : Googlebot UA ---"
H=$(req GET / -A "$GOOGLEBOT" -H 'Accept-Language: en-US'); logh C1 "$H"
loc "C1  Googlebot / -> 302 /en/" "$H" '/en/'
H=$(req GET /fr/ -A "$GOOGLEBOT"); logh C2fr "$H"; st200_noredir "C2  Googlebot /fr/ -> 200" "$H"
H=$(req GET /en/ -A "$GOOGLEBOT"); logh C2en "$H"; st200_noredir "C2  Googlebot /en/ -> 200" "$H"

echo
echo "--- Groupe D : admin / API non perturbes ---"
H=$(req GET /wp-admin/); logh D1 "$H"
if has "$H" '^location:.*/(fr|en)/'; then ko "D1  /wp-admin/ redirige vers langue (FAIL)"; else ok "D1  /wp-admin/ non perturbe (login natif)"; fi
H=$(req GET /wp-login.php); logh D2 "$H"
if has "$H" '^HTTP/[0-9.]+ 200'; then ok "D2  /wp-login.php -> 200"; else ko "D2  /wp-login.php -> 200"; fi
H=$(req GET /wp-json/); logh D3 "$H"
if has "$H" '^location:.*/(fr|en)/'; then ko "D3  /wp-json/ redirige vers langue (FAIL)"; else ok "D3  /wp-json/ non perturbe"; fi

echo
echo "--- Groupe E : integrite Polylang Free (pas de double saut) ---"
N=$(curl "${FOLLOW[@]}" -L -w '%{num_redirects}' -H 'Accept-Language: fr-FR' "$BASE/")
echo "E1 num_redirects=$N" >>"$LOG"
if [ "$N" = "1" ]; then ok "E1  / -> 1 seul saut de redirection"; else ko "E1  / -> $N sauts (attendu 1 ; >1 = conflit Polylang/mu-plugin)"; fi
NF=$(curl "${FOLLOW[@]}" -L -w '%{num_redirects}' "$BASE/fr/")
NE=$(curl "${FOLLOW[@]}" -L -w '%{num_redirects}' "$BASE/en/")
echo "E2 /fr/ redirs=$NF  /en/ redirs=$NE" >>"$LOG"
if [ "$NF" = "0" ] && [ "$NE" = "0" ]; then ok "E2  /fr/ et /en/ sans boucle (0 redir)"; else ko "E2  /fr/=$NF /en/=$NE (attendu 0 ; boucle suspecte)"; fi

echo
echo "================  RESULTAT  ================"
echo "  PASS=$PASS   FAIL=$FAIL"
if [ "$FAIL" -eq 0 ]; then
  echo "  => GO KH-104b-pre (sous reserve preuves visuelles + F=N/A tant que KH-109 absent)"
else
  echo "  => NO-GO : voir les FAIL ci-dessus + $LOG (verifier d'abord les HARD FAIL 301 / croisement /fr/<->/en/ / double saut)"
fi
echo "  Log detaille : $LOG"
exit $([ "$FAIL" -eq 0 ] && echo 0 || echo 1)
