# Charte graphique — index et préséance

État au **2026-07-27**. Ce dossier contient deux générations de charte. Lire cet index avant d'ouvrir un fichier.

## Fichiers

| Fichier | Version | Statut |
|---|---|---|
| `KH-000b-charte-v2-Ma-Intervalle-enchante-WORDPRESS.md` | v2.0 (Manus, 2026-07-25) | ✅ **RÉFÉRENCE UNIQUE** pour les couches visuelles |
| `KH-000b-charte-v2-Ma-Intervalle-enchante-CREATIVE.md` | v2.0 (Manus, 2026-07-25) | Archive. Identique à la version WORDPRESS **moins** le §4.1 (asset Hero) |
| `KH-000b-pack-assets-v3.1-README.md` | v3.1 (Manus, 2026-07-25) | Inventaire du pack d'images : 20 slides, fonds, illustrations, ornements |
| `KH-000 — Koinobori House Design System.md` | v1.2 « Ma (間) » | 🔶 **Superseded pour les couches visuelles** par la v2.0. Reste la base des fondations déployées (G1 PASS) |
| `KH-000-Design-System.pdf` | v1.2 | Idem, rendu PDF |
| `KH-001-Image-Map.md` / `.pdf` | — | Cartographie des images, toujours valide |

## Préséance (arbitrage Alain 2026-07-27)

1. **v2.0 supersède l'application de KH-000 v1.2 pour tout ce qui est visuel** : palette, typographies, grille, header, footer, boutique, fiches, pages éditoriales.
2. **Les fondations KH-000 déployées restent la base technique** (G1 PASS) : ce sont les *tokens* qui sont réajustés, pas l'architecture d'intégration.
3. Entre les deux fichiers v2.0 : la **créative fait foi sur l'intention**, la **WordPress sur l'implémentation**. Le diff des deux étant vide hors §4.1, la version WORDPRESS est retenue comme référence unique et la créative n'est conservée que pour archive.

## Pack d'assets

Les fichiers maîtres du pack Manus sont sur disque local uniquement :

```
C:\dev\Koinobori\Koinobori_House_Assets_Claude\Koinobori_House_charte_graphique\
```

Ce dossier est **volontairement hors suivi git** (`.gitignore`) : volume binaire important, et `Charte-graphique-KH.pptx` pèse à lui seul ~31 Mo. Conséquence assumée : **pas de versionnement des masters**, la médiathèque WordPress et le disque local sont les deux seules copies.

⚠️ `99_archive_ne_pas_utiliser/` contient l'ancien slide 10 « recommandation Shopify » : archive documentaire, **ne jamais publier ni intégrer**.

## Arbitrages C1-C6 (2026-07-27)

| # | Objet | Verdict |
|---|---|---|
| C1 | Fiche produit, 2ᵉ mouvement éditorial | « **Détails et matières** » — remplace « Détails et fabrication » du §10.2. Doctrine : jamais *fabrication* |
| C2 | Nom de la rubrique éditoriale | « **L'Atelier** » retenu. Garde-fou : le contenu ne parle jamais de production, fabrication ni origine |
| C3 | Palette | Valeurs **v2.0 §5.1 adoptées partout**, tokens déployés migrés. Annule l'or `#C9A96E` et la palette v1 (ivoire `#FDF8F0`, corail `#E05A5A`, or `#C6A15B`) |
| C4 | Navigation | **v2.0 §3 confirmée** : Accueil · Boutique · Arts de vivre · Lifestyle · L'Atelier · Professionnels · Contact. **Annule A-1, A-2, A-4** |
| C5 | Header | **Shoji V2 verrouillé** = réalisation du principe Fusuma §6, enrichi des exigences v2.0 (clavier, mobile simplifié, `prefers-reduced-motion`) |
| C6 | Arts de vivre | Page « **manifeste / bientôt** » au MVP. **Aucune 6ᵉ catégorie WooCommerce** : la taxonomie 5 collections reste figée |

### Précisions rendues avec le GO

- **Or `#B8860B` : aucun conflit doctrinal.** La v2.0 §5.1 réserve l'or aux *détails précieux et citations*, le vermillon aux actions. CLAUDE.md n'interdit l'or que sur boutons, panier, liens et aplats massifs. Conforme à l'arbitrage A du 2026-06-29.
- **A-6 n'est pas annulé.** `/fr/entreprises/` et `/fr/collectivites/` avec formulaires distincts restent obligatoires (doctrine B2B/B2G). L'entrée « Professionnels » de la nav v2.0 est une **page d'aiguillage** vers les deux. Ne jamais fusionner.
- **Header Shoji V2** : le corail `#E05A5A` de « Mon compte » passera vermillon `#C8311A` au portage Lot 7. Le design n'est pas rouvert pour autant.
- **Catherine** : rien d'inventé. Aucune donnée personnelle, date, parcours ni rôle n'est publié sans que Alain le fournisse ou le valide (spec §7.1 et §7.3). Le texte d'ouverture §7.2 est un **brouillon à valider**.

## Nota sur le §2 de la spec

Le §2 « État de départ » décrit un site vierge, sans page ni article publié. C'est l'état **public** observé le 25 juillet 2026 : le staging est derrière authentification, Manus ne pouvait pas le voir. En réalité les fondations KH-000 sont actives et le catalogue FR est publié à 17/17. La v2.0 s'applique **par-dessus l'existant**, ce n'est pas un greenfield.
