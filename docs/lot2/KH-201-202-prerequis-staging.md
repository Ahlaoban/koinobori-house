# KH-201 / KH-202 — Prérequis staging avant import catalogue (Lot 2)

- **Date** : 2026-06-18
- **Statut** : 🟡 **à exécuter (Alain, WP admin staging)** avant tout import CSV de masse.
- **Pourquoi** : le pilote a montré que sans attributs globaux pré-créés et collections traduites en EN, l'import CSV ne génère pas les variations et les produits EN ne peuvent pas être catégorisés.
- **Environnement** : staging uniquement.

---

## KH-202 — Attribut global Taille
1. **Produits → Attributs**
2. Nom : `Taille` · Slug : `taille` → **Ajouter l'attribut**
3. Ligne Taille → **Configurer les termes** → ajouter les termes : **50**, **75**, **100**
4. **Traduire en EN** (Polylang) : pour chaque terme (50/75/100), créer la traduction EN (mêmes valeurs) afin que les produits EN puissent utiliser l'attribut.
   - Libellé d'attribut : FR « Taille » / EN « Size » (à confirmer).

→ Une fois fait, l'import CSV pourra rattacher la variation et la générer.

## KH-201 — Collections (5 catégories produit) bilingues — FIGÉ (Alain 2026-06-18)
1. **Produits → Catégories** → créer les 5 (FR) + traduire EN via le « + » Polylang :

| FR | EN | Code SKU | Contenu |
|---|---|---|---|
| Mer | Sea | MER | 6 koi mer (50/75) |
| Motifs | Patterns | MOT | 3 koi (ex-divers) (50/75) |
| Floral | Floral | FLO | Sakura Rouge (75) |
| Kaïro | Kaïro | KAI | 4 koi (75, à venir) |
| Territoires | Lands | TER | Stars & Stripes (100) + 2 Bretons (50/75) |

*(La catégorie « Floral » remplace « Hanami » ; reclasser le produit pilote Sakura — voir SKU ci-dessous.)*

→ Plus de sous-catégories. « Territoires » regroupe régions/drapeaux (USA, Bretagne). « Série limitée » = mention bloc 2 BCDG, pas une collection.

## KH-205 — Vérif post-setup
- Attribut Taille global présent + termes 50/75/100 + traduits EN.
- 6 collections présentes FR + liées EN.
- Catégorie de base produit `/produit/` inchangée (limitation Pro skip OK).

---

## Séquence d'industrialisation (rappel)
1. **Prérequis ci-dessus** (attributs + collections EN). ← *on est ici*
2. Alain fournit la **liste des ~50 produits** (nom, collection, taille(s), prix, stock).
3. Je génère les **CSV FR par collection** (gabarit KH-203, 2 blocs).
4. Import FR → vérif variations.
5. EN par « + traduire » + coller textes EN (je les rédige).
6. Audit doctrine KH-214 + indexabilité KH-215.
