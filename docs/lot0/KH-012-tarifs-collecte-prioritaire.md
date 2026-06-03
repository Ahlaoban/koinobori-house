# KH-012 — Mini-grille tarifaire prioritaire

Sous-ensemble actionnable de `KH-012-shipping-data-template.csv`. **5 cas B2C prioritaires × 3 transporteurs MVP** = 15 lignes à remplir tarifairement par Alain (1ère vague devis).

## 1. Cas à tester (5)

Configurations B2C couvertes par les enveloppes E1 / E2 actuelles, toutes **≤ 3 cm épaisseur confirmé Alain → boîte aux lettres OK**.

| # | Configuration | Poids total | Enveloppe |
|---|---------------|------------:|-----------|
| 1 | 1 × 50 cm | 48 g | E1 (18 × 23 cm, 30 g) |
| 2 | 2 × 50 cm | 66 g | E1 (18 × 23 cm, 30 g) |
| 3 | 1 × 75 cm | 77 g | E2 (23 × 29 cm, 47 g, type Amazon) |
| 4 | 1 × 100 cm | 92 g | E2 (23 × 29 cm, 47 g, type Amazon) |
| 5 | 3 × 75 cm | 137 g | E2 (23 × 29 cm, 47 g, type Amazon) |

## 2. Structure CSV (11 colonnes)

| # | Colonne | Type | Pré-rempli ? |
|---|---------|------|--------------|
| 1 | `cas` | string | ✅ libellé court |
| 2 | `poids_total_g` | int (g) | ✅ calculé |
| 3 | `dim_enveloppe_cm` | string | ✅ 18×23 ou 23×29 |
| 4 | `epaisseur_cm` | string | ✅ `≤ 3 cm (BAL OK Alain)` |
| 5 | `transporteur` | enum | ✅ La Poste lettre suivie / Colissimo / Mondial Relay |
| 6 | `cout_france_eur` | decimal € | ❌ devis Alain |
| 7 | `cout_ue_eur` | decimal € | ❌ devis Alain (préciser pays référence en remarques si tarif zone-dépendant) |
| 8 | `cout_usa_eur` | decimal € | ❌ devis Alain (vide pour Mondial Relay, non desservi) |
| 9 | `suivi_yn` | enum | ❌ devis Alain |
| 10 | `delai_indicatif` | string | ❌ devis Alain (format libre, ex `3-5 j FR / 5-7 j UE / 7-15 j USA`) |
| 11 | `remarques` | string | ⚠️ pré-rempli `USA non desservi` pour Mondial Relay |

### Déviation vs spec stricte 12 colonnes

User spec a listé 12 colonnes : `destination France / destination UE / destination USA / coût`. Interprétation choisie pour usabilité : les 3 colonnes destination + coût ont été fusionnées en **3 colonnes `cout_X_eur`** (une par zone). Cela évite de multiplier 15 → 40 rows pour chaque combinaison (cas × transporteur × destination).

Si tu préfères structure stricte (1 row par destination testée), demander expansion. Sinon, structure actuelle = 15 rows lisibles.

## 3. Cible Alain — devis prioritaire

**Objectif** : obtenir tarifs réels pour les 15 combinaisons ci-dessous, classer transporteur retenu par zone.

**Sources** :
- **La Poste lettre suivie** : laposte.fr ou compte La Poste Pro
- **Colissimo** : colissimo.fr ou compte Colissimo Pro
- **Mondial Relay** : mondialrelay.fr ou compte MR Pro

Alternative pour comparaison : **Boxtal Pro** (compte gratuit) renvoie multi-devis Colissimo + MR + autres en une requête.

## 4. Garde-fous

- ❌ Pas d'invention tarifs
- ❌ Pas d'autre transporteur (DHL, FedEx, UPS, Chronopost, Zonos = phase 2 uniquement)
- ❌ Pas de chantier B2B/B2G dans ce fichier (chantier séparé)
- ✅ Captures écran devis horodatées hors-repo
- ✅ Si tarif zone-dépendant (UE par pays par exemple) noter pays référence en remarques

## 5. Une fois rempli — livrables

1. Calculer marge nette par cas par zone (prix_produit − cout_carrier − emballage − cogs)
2. Décider transporteur retenu par zone selon coût × suivi × délai
3. Définir grille tarifaire client affichée boutique (forfait simple ou réel)
4. Compléter §5 recommandation MVP note KH-012/KH-015 avec choix final
5. Drafter wording page Livraison USA avec formulation prudente §1bis (KH-204)

## 6. Liens

- Cadrage parent : [KH-012-KH-015-shipping-usa-seo.md](KH-012-KH-015-shipping-usa-seo.md)
- Template SKU-centrique complet : [KH-012-shipping-data-template.csv](KH-012-shipping-data-template.csv) + [.md](KH-012-shipping-data-template.md)
- Grille prioritaire : [KH-012-tarifs-collecte-prioritaire.csv](KH-012-tarifs-collecte-prioritaire.csv) (ce fichier)
