# KH-015 — Fiche tests Colissimo USA en ligne

Companion court pour `KH-015-tests-colissimo-usa.csv`. 3 tests à effectuer Alain sur laposte.fr Colissimo USA en ligne.

## ⚠️ Statut — Tests Colissimo USA différés (Alain 2026-05-29)

**Tests Colissimo USA différés** — bloquants pour **activation checkout USA uniquement**, **non bloquants pour avancement Lot 0**.

- Tant que les 3 tests T1/T2/T3 ne sont pas réalisés, **USA reste en mode "nous contacter" / devis manuel par défaut**
- Site continue à avancer sur autres tickets Lot 0 (catalogue, légaux, B2B/B2G, médiateur, PayPal, etc.)
- Ne pas activer USA checkout WC
- Ne pas chercher DHL/UPS/FedEx/Chronopost maintenant (fallback uniquement si Colissimo USA test échoue)
- Réactivation chantier USA actif = quand Alain réalise les 3 tests ci-dessous

## 1. Panier des 3 tests

| Test | Composition | Valeur produit | Poids colis (E2 + produit) |
|------|-------------|---------------:|---------------------------:|
| **T1** | 1 × Kaïro 75 cm (KH-KAI-001-075) | **35 €** | 30 + 47 = **77 g** |
| **T2** | 1 × Stars & Stripes 100 cm (KH-EDS-001-100) | **49 €** | 45 + 47 = **92 g** |
| **T3** | 3 × Kaïro 75 cm (KH-KAI-001/002/003-075) | **105 €** | 90 + 47 = **137 g** |

Tous < 650 € → éligibles Colissimo USA en ligne.

## 2. Procédure

1. Aller sur **laposte.fr → Envoyer un colis → États-Unis → Colissimo en ligne**
2. Saisir poids + valeur produit + dimensions (E2 = 23 × 29 × ≤3 cm)
3. Indiquer code HTSUS interne (6307.90 hypothèse, à confirmer source officielle, **ne jamais publier public**)
4. Capturer écran horodaté (devis + paiement droits/taxes)
5. Remplir CSV avec les valeurs réelles

## 3. Champs CSV (18 cols)

| # | Colonne | Statut | Source |
|---|---------|--------|--------|
| 1 | `test_id` | ✅ pré-rempli `T1/T2/T3` | — |
| 2 | `panier` | ✅ pré-rempli | — |
| 3 | `composition` | ✅ pré-rempli SKUs | master.csv |
| 4 | `product_value_eur` | ✅ pré-rempli (35/49/105) | master.csv |
| 5 | `parcel_total_weight_g` | ✅ pré-rempli (77/92/137) | calcul Alain |
| 6 | `carrier_cost_eur` | ❌ à remplir | test laposte.fr |
| 7 | `laposte_management_fees_eur` | ❌ à remplir (peut être 0) | test laposte.fr |
| 8 | `us_duties_taxes_eur` | ❌ à remplir | test laposte.fr (calcul Colissimo upfront) |
| 9 | `packaging_handling_cost_eur` | ❌ à remplir (hypothèse Alain 1,00 € à valider) | estimation Alain |
| 10 | `delai_indicatif` | ❌ à remplir | test laposte.fr |
| 11 | `suivi_inclus_yn` | ❌ à remplir (`oui` / `non`) | test laposte.fr |
| 12 | `assurance` | ❌ à remplir (`incluse` / `optionnelle X €` / `non`) | test laposte.fr |
| 13 | `cout_total_avant_marge_eur` | ❌ calculé : `carrier + laposte_mgmt + us_duties + packaging_handling` | calcul |
| 14 | `international_overhead_rate` | ✅ pré-rempli `0.06` | doctrine §5bis |
| 15 | `displayed_shipping_USA_eur` | ❌ calculé via formule | calcul (§4) |
| 16 | `source_url` | ✅ `https://www.laposte.fr/` | — |
| 17 | `source_checked_at` | ❌ date YYYY-MM-DD du test | Alain |
| 18 | `remarques` | ❌ note libre | Alain |

## 4. Formule de calcul `displayed_shipping_USA_eur`

```
displayed_shipping_USA_eur =
    carrier_cost_eur
  + laposte_management_fees_eur
  + us_duties_taxes_eur
  + packaging_handling_cost_eur
  + 0.06 × (product_value_eur + carrier_cost_eur)
```

**Marge 6 % calculée sur `product_value + carrier_cost` uniquement** (droits/taxes = pass-through pas couvert par marge).

### Exemple T1 (illustration sans valeurs réelles)

- `product_value_eur` = 35
- `carrier_cost_eur` = X (à remplir)
- `laposte_management_fees_eur` = Y
- `us_duties_taxes_eur` = Z
- `packaging_handling_cost_eur` = 1,00 € hypothèse
- `cout_total_avant_marge_eur` = X + Y + Z + 1,00
- marge 6 % = 0,06 × (35 + X)
- `displayed_shipping_USA_eur` = X + Y + Z + 1,00 + 0,06 × (35 + X)
- Arrondi commercial possible (ex au € supérieur)

## 5. Critères passage USA actif post-tests

- ✅ Affranchissement Colissimo USA en ligne possible pour T1, T2, T3
- ✅ `displayed_shipping_USA_eur` **stable et acceptable** par cas
- ✅ Marge produit + 6 % préservées vs experience Etsy
- ✅ Délai indicatif acceptable (7-15 j ouvrés probable)
- ✅ Statut territoires associés US clarifié (carve-out si nécessaire)

Si OK → USA actif WC, Colissimo USA en ligne service principal MVP.
Si KO → USA "nous contacter" + formulaire contact dédié.

## 6. Garde-fous

- ❌ Pas de DHL/UPS/FedEx/Chronopost testés maintenant (fallback uniquement si Colissimo inadapté, §1ter)
- ❌ Pas d'invention valeurs `carrier_cost / laposte_mgmt / us_duties`
- ❌ Pas de publication HTSUS code ni pourcentage droits/taxes public (§1bis)
- ✅ Captures écran laposte.fr horodatées hors-repo
- ✅ Source URL + date renseignées

## 7. Liens

- Doctrine USA actualisée : [KH-012-KH-015-shipping-usa-seo.md §1ter](KH-012-KH-015-shipping-usa-seo.md)
- Template shipping complet : [KH-012-shipping-data-template.csv](KH-012-shipping-data-template.csv) + [.md](KH-012-shipping-data-template.md)
- Grille prioritaire France/UE : [KH-012-tarifs-collecte-prioritaire.csv](KH-012-tarifs-collecte-prioritaire.csv)
- CSV tests : [KH-015-tests-colissimo-usa.csv](KH-015-tests-colissimo-usa.csv) (ce fichier)
