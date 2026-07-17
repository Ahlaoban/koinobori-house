#!/usr/bin/env bash
#
# KH-109 — vérification SEO i18n (SEOPress Free + Polylang) sur staging.
# Couvre la matrice §8 du plan docs/lot1/KH-109-seopress-seo.md (Groupe F) :
#   canonical self par langue · hreflang sans doublon · sitemaps par langue (FR+EN)
#   + non-régression racine KH-107.
#
# Usage (Git Bash sous Windows, ou SSH o2switch) :
#   BASE="https://staging.koinoborihouse.com" AUTH="StagingKH:MOTDEPASSE" bash KH-109-seo-tests.sh
#
# Optionnel (sinon auto-découverte via le sitemap produits) :
#   PROD_FR_URL=".../fr/produit/<slug>/"  PROD_EN_URL=".../en/produit/<slug>/"
#
# Staging = basic auth + certificat auto-signé -> --user (via AUTH) + curl -k.
# NE PAS committer la sortie (contient l'URL staging + slugs). Le script n'écrit aucun secret.
#
# Périmètre : V1-V14 automatisés. V15 = relancer KH-104b-pre-tests.sh (script séparé).
#             V16 = Site Health (manuel, admin WP).
#
set -u

BASE="${BASE:-https://staging.koinoborihouse.com}"
AUTH="${AUTH:-}"
PROD_FR_URL="${PROD_FR_URL:-}"
PROD_EN_URL="${PROD_EN_URL:-}"
LOG="KH-109-seo-$(date '+%Y%m%d-%H%M%S').log"

# --- curl helpers ---------------------------------------------------------
HOPTS=(-sS -k -D - -o /dev/null --max-time 20)   # en-têtes seuls, ne suit pas les redirections
BOPTS=(-sS -k --max-time 20)                     # corps (HTML/XML)
[ -n "$AUTH" ] && { HOPTS+=(--user "$AUTH"); BOPTS+=(--user "$AUTH"); }

hpath(){ curl "${HOPTS[@]}" "$BASE$1"; }   # en-têtes pour un chemin relatif
hurl(){  curl "${HOPTS[@]}" "$1"; }        # en-têtes pour une URL absolue
bpath(){ curl "${BOPTS[@]}" "$BASE$1"; }   # corps pour un chemin relatif
burl(){  curl "${BOPTS[@]}" "$1"; }        # corps pour une URL absolue

PASS=0; FAIL=0; SKIP=0
ok(){ echo "  PASS  $1"; PASS=$((PASS+1)); }
ko(){ echo "  FAIL  $1"; FAIL=$((FAIL+1)); }
sk(){ echo "  SKIP  $1"; SKIP=$((SKIP+1)); }
has(){ grep -iqE "$2" <<<"$1"; }
logh(){ printf '\n===== %s =====\n%s\n' "$1" "$2" >>"$LOG"; }

st200(){ # NAME  HEADERS
  if has "$2" '^HTTP/[0-9.]+ 200'; then ok "$1"; else ko "$1 (pas de 200)"; fi
}

echo "BASE=$BASE   log=$LOG"
echo

# =========================================================================
echo "--- Groupe F1 : codes HTTP pages clés (V1-V6) ---"
H=$(hpath /fr/);            logh V1 "$H"; st200 "V1  /fr/ 200" "$H"
H=$(hpath /en/);            logh V2 "$H"; st200 "V2  /en/ 200" "$H"
H=$(hpath /fr/boutique/);   logh V3 "$H"; st200 "V3  /fr/boutique/ 200" "$H"
H=$(hpath /en/shop/);       logh V4 "$H"; st200 "V4  /en/shop/ 200" "$H"

# =========================================================================
echo
echo "--- Groupe F2 : sitemaps (V10-V13) [auto-découverte index + enfants] ---"
# SEOPress varie selon version : on sonde plusieurs noms d'index.
SMINDEX=""; SMBODY=""
for cand in /sitemaps.xml /sitemap.xml /sitemap_index.xml; do
  B=$(bpath "$cand")
  if grep -qiE '<(sitemapindex|urlset)' <<<"$B"; then SMINDEX="$cand"; SMBODY="$B"; break; fi
done
if [ -n "$SMINDEX" ]; then ok "V10 sitemap index trouvé ($SMINDEX, XML valide)"; logh V10 "$SMBODY"
else ko "V10 aucun index sitemap (sondé /sitemaps.xml /sitemap.xml /sitemap_index.xml)"; fi

# Agrège le corps de l'index + de tous les sous-sitemaps <loc>.
ALLSM="$SMBODY"
if [ -n "$SMINDEX" ]; then
  CHILD=$(grep -oiE '<loc>[^<]+</loc>' <<<"$SMBODY" | sed -E 's#</?loc>##g')
  while IFS= read -r u; do
    [ -z "$u" ] && continue
    case "$u" in *.xml|*sitemap*) ALLSM="$ALLSM"$'\n'"$(burl "$u")";; esac
  done <<<"$CHILD"
fi
logh "ALLSM-locs(extrait)" "$(grep -oiE '<loc>[^<]+</loc>' <<<"$ALLSM" | head -n 40)"

if grep -qiE '<loc>[^<]*/(page|boutique|shop|mentions|contact)[^<]*</loc>' <<<"$ALLSM" \
   || grep -qiE 'page-sitemap' <<<"$SMBODY"; then ok "V11 sitemap pages présent"
else ko "V11 pages absentes du sitemap (vérifier inclusion post type page)"; fi

if grep -qiE '<loc>[^<]*/produit/[^<]+</loc>' <<<"$ALLSM" \
   || grep -qiE 'product-sitemap' <<<"$SMBODY"; then ok "V12 sitemap produits présent"
else ko "V12 produits absents (cocher post type 'product' dans SEOPress XML Sitemap)"; fi

NFR=$(grep -ociE '<loc>[^<]*/fr/' <<<"$ALLSM")
NEN=$(grep -ociE '<loc>[^<]*/en/' <<<"$ALLSM")
echo "    (sitemap: $NFR URLs /fr/, $NEN URLs /en/)"
if [ "${NFR:-0}" -ge 1 ] && [ "${NEN:-0}" -ge 1 ]; then ok "V13 sitemap couvre FR + EN"
else ko "V13 une seule langue couverte (FR=$NFR EN=$NEN) — attendu les deux"; fi

# Découverte URL produit test (si non fournie en env), via le sitemap.
[ -z "$PROD_FR_URL" ] && PROD_FR_URL=$(grep -oiE 'https?://[^<]*/fr/produit/[^<]+' <<<"$ALLSM" | head -n1)
[ -z "$PROD_EN_URL" ] && PROD_EN_URL=$(grep -oiE 'https?://[^<]*/en/produit/[^<]+' <<<"$ALLSM" | head -n1)

echo
echo "--- Groupe F1 (suite) : fiches produit (V5-V6) ---"
if [ -n "$PROD_FR_URL" ]; then H=$(hurl "$PROD_FR_URL"); logh V5 "$PROD_FR_URL"$'\n'"$H"; st200 "V5  produit FR 200 ($PROD_FR_URL)" "$H"
else sk "V5  pas d'URL produit FR (fournir PROD_FR_URL ou créer un produit test)"; fi
if [ -n "$PROD_EN_URL" ]; then H=$(hurl "$PROD_EN_URL"); logh V6 "$PROD_EN_URL"$'\n'"$H"; st200 "V6  produit EN 200 ($PROD_EN_URL)" "$H"
else sk "V6  pas d'URL produit EN (fournir PROD_EN_URL)"; fi

# =========================================================================
echo
echo "--- Groupe F3 : canonical self par langue (V7-V8) ---"
canon(){ # NAME  PATH  EXPECTED_FRAG  FORBIDDEN_FRAG
  local body; body=$(bpath "$2"); logh "$1-canonical" "$(grep -oiE '<link[^>]*rel=.canonical.[^>]*>' <<<"$body")"
  local href; href=$(grep -oiE '<link[^>]*rel=.canonical.[^>]*>' <<<"$body" | grep -oiE 'href="[^"]+"' | head -n1)
  if [ -z "$href" ]; then ko "$1 (aucun canonical trouvé)"; return; fi
  if grep -qiE "$3" <<<"$href" && ! grep -qiE "$4" <<<"$href"; then ok "$1 ($href)"
  else ko "$1 canonical inattendu : $href"; fi
}
canon "V7  canonical /fr/boutique/ self" /fr/boutique/ '/fr/boutique' '/en/'
canon "V8  canonical /en/shop/ self"     /en/shop/     '/en/shop'     '/fr/'

# =========================================================================
echo
echo "--- Groupe F4 : hreflang série unique (V9) ---"
BFR=$(bpath /fr/); logh V9-fr "$(grep -oiE '<link[^>]*rel=.alternate.[^>]*hreflang=[^>]*>' <<<"$BFR")"
h_fr=$(grep -ociE 'hreflang="fr(-FR)?"' <<<"$BFR")
h_en=$(grep -ociE 'hreflang="en(-US|-GB)?"' <<<"$BFR")
h_xd=$(grep -ociE 'hreflang="x-default"' <<<"$BFR")
echo "    (/fr/ hreflang: fr=$h_fr en=$h_en x-default=$h_xd)"
if [ "${h_fr:-0}" -ge 1 ] && [ "${h_en:-0}" -ge 1 ] && [ "${h_xd:-0}" -ge 1 ]; then
  if [ "${h_fr:-0}" -le 1 ] && [ "${h_en:-0}" -le 1 ] && [ "${h_xd:-0}" -le 1 ]; then
    ok "V9  hreflang fr+en+x-default, série unique"
  else
    ko "V9  DOUBLON hreflang (fr=$h_fr en=$h_en x-default=$h_xd) — SEOPress + Polylang émettent en double, désactiver l'un"
  fi
else
  ko "V9  hreflang incomplet (fr=$h_fr en=$h_en x-default=$h_xd) — attendu chacun ≥1"
fi

# =========================================================================
echo
echo "--- Groupe F5 : non-régression racine KH-107 (V14) ---"
H=$(hpath / -H 'Accept-Language: fr-FR'); logh V14 "$H"
if has "$H" '^HTTP/[0-9.]+ 302' && has "$H" '^x-redirect-by:[[:space:]]*koino-lang-redirect' && ! has "$H" '^HTTP/[0-9.]+ 301'; then
  ok "V14 racine / 302 x-redirect-by: koino-lang-redirect (KH-107 intact)"
else
  ko "V14 racine altérée — KH-107 doit rester 302 koino-lang-redirect, jamais 301"
fi

# =========================================================================
echo
echo "================  RESULTAT KH-109  ================"
echo "  PASS=$PASS   FAIL=$FAIL   SKIP=$SKIP"
echo "  Rappel manuel : V15 = relancer KH-104b-pre-tests.sh (attendu 25/27 inchangé)"
echo "                  V16 = Site Health (admin WP, aucune nouvelle anomalie critique)"
if [ "$FAIL" -eq 0 ]; then
  echo "  => automatisé OK. GO KH-109 sous réserve V15 (25/27) + V16 + preuves visuelles."
else
  echo "  => NO-GO automatisé : voir FAIL ci-dessus + $LOG. Ajuster réglages SEOPress (canonical/hreflang/sitemap) puis re-run."
fi
echo "  Log détaillé : $LOG"
exit $([ "$FAIL" -eq 0 ] && echo 0 || echo 1)
