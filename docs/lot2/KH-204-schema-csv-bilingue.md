# KH-204 — Schéma CSV bilingue WooCommerce / Polylang (Lot 2)

- **Date** : 2026-06-15
- **Statut** : ✅ **WORKFLOW VALIDÉ sur pilote (2026-06-18)** — voir §G. FR par CSV + EN par « + traduire » (même SKU, variation/prix/stock synchronisés). Prêt pour industrialisation après pré-requis (attributs globaux + collections EN).
- **But** : préparer le workflow d'import du catalogue sans saisir les ~50 produits. Le **pilote Sakura Rouge** sert de banc d'essai du workflow complet.

---

## A. Colonnes CSV (WooCommerce Product CSV Importer natif)

Pour un **produit variable + variation Taille** = **2 lignes** (1 parent + 1 variation) :

| Colonne | Parent (variable) | Variation (Taille 75) |
|---|---|---|
| `Type` | `variable` | `variation` |
| `SKU` | `KH-HAN-001-075` | `KH-HAN-001-075-T75` |
| `Name` | `Sakura Rouge Koinobori — by BCDG` | *(vide / hérite)* |
| `Published` | `1` | `1` |
| `Is featured?` | `0` | |
| `Visibility in catalog` | `visible` | |
| `Short description` | *(short_desc FR — §KH-203)* | |
| `Description` | *(long_desc 3 blocs, HTML Gutenberg)* | |
| `In stock?` | `1` | `1` |
| `Stock` | | `50` |
| `Regular price` | | `29` |
| `Categories` | `Hanami` | |
| `Images` | *(vide — visuels à venir)* | |
| `Attribute 1 name` | `Taille` | `Taille` |
| `Attribute 1 value(s)` | `75` (ou `50, 75, 100`) | `75` |
| `Attribute 1 global` | `1` | `1` |
| `Parent` | | `KH-HAN-001-075` (SKU parent) |

> Stock + prix portés par la **variation** (KH-010). Le parent porte les descriptions + l'attribut.

---

## B. 🔴 Finding bloquant à valider — liaison des traductions Polylang

**L'importateur CSV natif WooCommerce ne gère PAS nativement la liaison de traduction Polylang.** Il n'a pas de colonne « langue » ni « traduction de ». Conséquence : importer un CSV FR + un CSV EN crée **deux produits non liés**.

**Approches à tester sur le pilote (objectif : trancher le workflow)** :

| Option | Principe | Risque |
|---|---|---|
| **A. FR par CSV + EN par « + traduire »** | Importer le FR par CSV ; créer l'EN via le bouton Polylang « + » (comme le produit test) | EN manuel → pas scalable pour 50 |
| **B. 2 CSV (FR puis EN) + liaison** | Importer CSV FR (langue par défaut) puis CSV EN ; **lier** les traductions (manuel admin, ou outil Polylang bulk) | la liaison de masse via CSV = à vérifier (peut-être non dispo en Free) |
| **C. Colonne langue custom** | Ajouter `meta:_lang`/colonne via hook ou plugin d'import compatible Polylang | dépend d'un plugin (stack verrouillé → à éviter) |

→ **Le pilote tranche** : on importe Sakura FR, on teste la création/liaison EN, on note la méthode retenue **avant** d'industrialiser. C'est exactement le « vérifier le workflow complet » demandé.

---

## C. 🟠 Finding — unicité SKU FR/EN

WooCommerce **impose des SKU uniques**. Deux posts (FR + EN) avec le même SKU `KH-HAN-001-075` → possible **conflit**.
- **À vérifier sur le pilote** : Polylang for WooCommerce est-il « translation-aware » sur l'unicité SKU (autorise le même SKU sur une traduction) ?
  - **Si oui** → SKU identique FR/EN (propre, recommandé).
  - **Si non** → convention de repli : EN = SKU + suffixe (ex. `…-EN`) — moins propre, à documenter.

---

## D. Autres points workflow
- **Slug EN** : WP force l'unicité → `…/produit/sakura-rouge/` FR vs possible `sakura-rouge-2`/autre EN (cf produit test `test`→`test-2`). Acceptable, à constater.
- **Descriptions longues 3 blocs** : en CSV, le champ `Description` = HTML. Reproduire les 3 blocs Gutenberg via `<!-- wp:paragraph -->` ou simples `<p>` séparés. À valider visuellement (rendu neutre).
- **Catégories bilingues** : la catégorie `Hanami` doit exister + être traduite (Polylang) avant import, sinon créée en langue par défaut.
- **Images** : colonne `Images` laissée **vide** (visuels hors scope) → import sans visuel, ajout ultérieur.
- **Encodage** : CSV **UTF-8** (accents FR + éventuels caractères JP).

---

## E. Plan de test pilote (KH-204 sur Sakura Rouge)
1. Je fournis le **CSV FR** Sakura (parent + variation) — données KH-203.
2. Tu importes (WC → Produits → Importer) sur **staging**.
3. On teste la **création EN** (option A/B) + **liaison Polylang** + **unicité SKU**.
4. On vérifie : 2 URLs produit 200, traduction liée, stock/variation OK, 3 blocs rendus, doctrine (KH-214 mini), sitemap/hreflang (KH-215 mini).
5. **Méthode retenue figée** → industrialisation des 50.

---

## F. Risques avant industrialisation (synthèse)
1. **Liaison traduction Polylang via CSV** (§B) — le plus structurant. Si non automatisable → EN semi-manuel pour 50 produits (coût à anticiper).
2. **Unicité SKU FR/EN** (§C).
3. **Rendu 3 blocs** via CSV HTML (§D).
4. **Catégories/attributs** à créer + traduire avant import.
5. **Variations** (parent/enfant SKU) bien importées + stock/prix sur variation (KH-010).
6. **Doctrine à l'échelle** : audit grep KH-214 obligatoire sur les 50 (FR+EN).
7. **Slugs EN** non maîtrisés (`-2`) — impact SEO mineur (staging noindex).
8. **Images absentes** : vérifier que les fiches sans visuel restent publiables/indexables proprement.

---

## G. Résultats du pilote (2026-06-18) — ✅ WORKFLOW VALIDÉ

Pilote **Sakura Rouge** : FR importé par CSV, EN créé par Polylang « + traduire ». Verdict : **méthode validée**.

| Point | Résultat |
|---|---|
| Import FR (CSV) | ✅ produit variable, nom (tiret simple), 4 paragraphes, short_desc, catégorie Hanami, langue FR, SKU `KH-HAN-001-075` |
| 🔴 Liaison traduction FR↔EN | ✅ **fonctionne** via « + traduire » Polylang (FR et EN reliés) |
| 🟠 Même SKU FR/EN | ✅ **accepté** (Polylang for WC translation-aware) → **pas de suffixe `-EN`, même SKU partout** |
| **Sync variation/prix/stock EN** | ✅ **automatique** depuis le FR (Polylang for WC, KH-010) → l'EN n'a eu **aucune ressaisie** de variation/prix/stock |
| Affichage front FR + EN | ✅ les deux fiches s'affichent, bascule langue OK |

**Findings confirmés (à intégrer au process des 50)** :
1. **Pré-créer les attributs globaux** (Taille + termes 50/75/100) **avant** l'import — sinon la variation ne se génère pas.
2. **Variation via CSV** : l'import attache l'attribut mais ne coche pas « utilisé pour les variations » → étape manuelle « cocher + générer » par produit, **ou** ajuster la colonne `Parent` (ex. `id:`) — **à retester** avec l'attribut global pré-créé.
3. **Traduire les 6 collections en EN** (Polylang) avant de catégoriser les produits EN.
4. **Slug** = auto depuis le nom (gardé, riche en mots-clés).
5. **Titre SEO** : corriger le modèle SEOPress produit (tiret orphelin) — fix global.

**Coût EN par produit (allégé)** : « + traduire » → variation/prix/stock **synchronisés** → **coller uniquement les textes** (nom, descriptions, meta) → publier. Pas de ressaisie commerciale.

**Méthode industrialisation retenue** :
1. Pré-créer attributs globaux (Taille) + traduire les 6 collections en EN.
2. Importer les produits FR par CSV (lots par collection).
3. Régler les variations FR (selon retest finding 2).
4. EN par « + traduire » + coller textes EN + publier.
5. Audit doctrine KH-214 (FR+EN) + indexabilité KH-215.
