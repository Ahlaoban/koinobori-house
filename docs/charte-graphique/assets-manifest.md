# Manifeste des assets de la charte — v2.0

**Établi le 2026-07-27** par relevé automatique du disque. Empreintes SHA-256 calculées sur les fichiers réels, jamais recopiées à la main.

## Pourquoi ce document existe

Les binaires du pack Manus sont **volontairement hors suivi git** (`.gitignore`) : 103 fichiers, **317 Mo**. Ce manifeste est la contrepartie documentaire de cette exclusion. Il permet de vérifier qu'un fichier retrouvé est bien l'original, et de constater une perte ou une altération.

> ⚠️ **Sauvegarde externe à conserver par Alain.** Ce manifeste prouve l'intégrité, il ne restaure rien. Le dépôt ne contient aucun de ces binaires et le disque de travail est un point de défaillance unique. Une copie hors dépôt **et** hors disque de travail doit exister.

## Emplacement local de référence

```
C:\dev\Koinobori\Koinobori_House_Assets_Claude\
```

Tous les chemins ci-dessous sont relatifs à ce dossier.

## Vérification d'intégrité

```bash
cd "/c/dev/Koinobori/Koinobori_House_Assets_Claude" && find . -type f -printf '%P\n' | sort | while read -r f; do printf '%s  %s\n' "$(sha256sum "$f" | cut -c1-64)" "$f"; done
```

Comparer la sortie aux colonnes SHA-256 de ce document. Toute divergence signale un fichier altéré, remplacé ou absent.

## Légende des statuts

| Statut | Sens |
|---|---|
| ✅ retenu | Fait partie de la charte applicable |
| 🔶 exploratoire | Disponible, pas la version de production, ou hors périmètre MVP |
| ⚠️ sous réserve | Retenu mais soumis à une validation préalable |
| ⛔ non utilisé | Interdit de publication |

## Volumétrie

| Dossier | Fichiers | Taille |
|---|---:|---:|
| `Koinobori_House_charte_graphique/01_slides_finales` | 20 | 36,4 Mo |
| `Koinobori_House_charte_graphique/02_slides_sources` | 9 | 3,3 Mo |
| `Koinobori_House_charte_graphique/03_fonds_et_textures` | 1 | 4,9 Mo |
| `Koinobori_House_charte_graphique/04_illustrations_editoriales` | 9 | 44,8 Mo |
| `Koinobori_House_charte_graphique/05_ornements` | 5 | 1,0 Mo |
| `Koinobori_House_charte_graphique/99_archive_ne_pas_utiliser` | 1 | 0,4 Mo |
| `Koinobori_House_charte_graphique/` (docs et planches) | 6 | 0,5 Mo |
| `koinobori-house-images/` (pack v1 hérité) | 52 | 225,3 Mo |
| **Total** | **103** | **317 Mo** |

Aucun doublon : les 103 empreintes SHA-256 sont distinctes.

## Inventaire

### Koinobori_House_charte_graphique/01_slides_finales

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `slide-01.png` | 2680457 | `8594488986bae4a679a03acf5b8d4b8ee99666540f2db439fb258062bd7dc91e` | Couverture originale Koinobori House / Ma — référence absolue du logo, du koinobori vermillon, du papier et de l'atmosphère | ✅ retenu — référence visuelle finale |
| `slide-02.png` | 1584253 | `5cbf85020cde4be728d2ead9d1757510d710ca2c5447c7d4fc0af876f741327f` | Le concept directeur — respiration, grands blancs, panneau éditorial latéral | ✅ retenu — référence visuelle finale |
| `slide-03.png` | 1262085 | `8ce52554810b1f855b0b0f75f5a1e1d4281286cca0c1f4af3a6fd45fc8fba4b5` | Système de design — palette, polices, composants, traits, micro-éléments | ✅ retenu — référence visuelle finale |
| `slide-04.png` | 2636855 | `a2f741342fec2b8954bbe09fcd5550a20bae4c917e974bdf02fab0d8ef300edc` | Hero « L'envol silencieux » — modèle de hero immersif | ✅ retenu — référence visuelle finale |
| `slide-05.png` | 2653017 | `11dff479971dcf9b170a501c6909a3455f27fc5ba81fa307c83ad8cc26cc9ec1` | Les cinq mondes — navigation éditoriale entre les univers | ✅ retenu — référence visuelle finale |
| `slide-06.png` | 1956744 | `966956cdcb95924d7e8ce6d49cb502821e528a5b86c84ebfbd6d454b020c508b` | Créations BCDG — référence des cartes produits et des CTA vermillon | ✅ retenu — référence visuelle finale |
| `slide-07.png` | 1257615 | `9345775aadae2d97e4343b52263b3a6bfd8124fdce7c2f35a7c6d45ea9e3f195` | Le manifeste — mise en page éditoriale numérotée, citation de clôture | ✅ retenu — référence visuelle finale |
| `slide-08.png` | 2567659 | `1eaf07c534140460760a1b72ee5692a5f9ed33b045162aa6d970cb9519889005` | Architecture de la page d'accueil — structure narrative, articulation B2C/B2G/B2B | ✅ retenu — référence visuelle finale |
| `slide-09.png` | 2011059 | `0a708a9bc87b61d6fde3365f04b229f985dc1efa0cf1bd6ba0c514c2f608f133` | Header Fusuma — ouverture des panneaux, logo centré, navigation révélée | ✅ retenu — référence visuelle finale |
| `slide-10.png` | 1256516 | `018d710f17a202375a9e0d5b567f4fbbcf47dbc9603a0bd094c012240d5bc5d1` | Roadmap WordPress — remplace définitivement l'ancienne page Shopify | ✅ retenu — référence visuelle finale |
| `slide-11.png` | 1292207 | `23c6d788fec315da856ad7e6e22c0297a44ec27c9f11f261f2f0343b4656efb9` | Une maison plus vaste — introduction des nouvelles rubriques | ✅ retenu — référence visuelle finale |
| `slide-12.png` | 3755544 | `2913d9a7b84ab7b2ab432f7d4f0cd36bff357a52d93ec698dfabce015904a15e` | L'Atelier, Alain et Catherine — page À propos, grande illustration verticale | ✅ retenu — référence visuelle finale |
| `slide-13.png` | 2798101 | `b848cad6801e40a0fa8a1600f80e121324a0ba450885987afafa333b499f4adb` | Lifestyle & Koi — archive éditoriale, organisation du magazine | ✅ retenu — référence visuelle finale |
| `slide-14.png` | 1446085 | `99cf97d049763f4f9a2d5597c0ec2437aade09e2460cf265c0c9cee5510d5c4e` | Modèle d'article — structure en trois mouvements | ✅ retenu — référence visuelle finale |
| `slide-15.png` | 2372427 | `1d4acd612103827b90a89dfabd6c17e7661ce388f03209ef979c7aae76334428` | Arts de vivre — extension future vers les objets décoratifs | ✅ retenu — référence visuelle finale |
| `slide-16.png` | 1289419 | `c2e3f3cda095f073df36ba9897123df4ba7161af2193daaa12bfea5e565a7301` | Nouvelle promenade d'accueil — ordre des sections enrichi | ✅ retenu — référence visuelle finale |
| `slide-17.png` | 1297465 | `e775bb7c45d27789a90feafed334827106eec0e8be815865ae13992282af4baf` | Footer « La ligne d'horizon » — footer minimaliste, sans gros bloc sombre | ✅ retenu — référence visuelle finale |
| `slide-18.png` | 1241673 | `e74526f14af4e8b2e128ff913c243d4db1a62cef7ac50d16b72cdee0a27f541d` | WordPress sans effet de thème — contraintes Kadence et WooCommerce | ✅ retenu — référence visuelle finale |
| `slide-19.png` | 1458882 | `545c3778a7737d46b309f72abd12e4352ba055f3f1cd99fa4f4713c2b4f08aa2` | Contrat visuel pour Claude — invariants graphiques et critères de refus | ✅ retenu — référence visuelle finale |
| `slide-20.png` | 1352191 | `2b551f07a569a7cbad281946d7a844aa4a7bcd023aec9c650ee4001a0031a191` | HTML, CSS et JavaScript — répartition des responsabilités | ✅ retenu — référence visuelle finale |

### Koinobori_House_charte_graphique/02_slides_sources

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `original_01.jpg` | 481878 | `3737514ab18e41e36ad4b223edcc7380ed1d8ea3d2a4406ee6af0ad2ec3e1a47` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_02.jpg` | 324408 | `f10e9b8ce169745ad2ff738073cd413469d4ee594fd1035ca82b25d0f49f009b` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_03.jpg` | 252760 | `ed9edb8988d6dd3c0af1ffe8a616a5dd5feed3d280255dffefe98722ad8e7c67` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_04.jpg` | 473561 | `29a085e9e8f863cb8d48de662214013b12f13136e0c65e92a7d1c9cdc25f2e70` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_05.jpg` | 508015 | `2543bffc21793bf003b0dde1c065b2764ce5a0410392bebcb456cfbc43476f51` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_06.jpg` | 349168 | `2b09f919f06cc5969ffb50d69b31cc21fb65361488c1541a3d9f95f729f01f24` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_07.jpg` | 245119 | `0b59fca7f6e4358246bd35c797f3d9b6bc2f42824aba1370480571ae3ad1558d` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_08.jpg` | 489644 | `c6c6435afaa74cc9efde6892362ad2c112ad5af8da5a776481b0677b3802c19a` | — | ✅ retenu — référence stricte de la charte d'origine |
| `original_09.jpg` | 358383 | `d608b2bbd76f8e0919d3d51e7ef57e6556fb3d7130aa1a8ec63befe18e7b7e04` | — | ✅ retenu — référence stricte de la charte d'origine |

### Koinobori_House_charte_graphique/03_fonds_et_textures

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `fond_washi_koinobori_house.png` | 5122222 | `afb5be13ba286c33043fadb13f816692c3d68b10b7d911075487bd023a19cdc1` | Fond washi natif nettoyé — arrière-plan global des sections, en image de fond CSS avec repli ivoire. Texture, jamais image éditoriale | ✅ retenu |

### Koinobori_House_charte_graphique/04_illustrations_editoriales

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `README_HERO.md` | 1561 | `ce51d2cf926437e17ceea5a3257eb063f89960b6bf7244e9cb7494a9fadd6e60` | Consigne Hero de Manus, non annoncée dans les specs : recommande `hero_fond_web_16x9.png`, ajoute `min-height: min(900px, 100svh)`, confirme le repli `#f3ebdd` (cf O-6) | ✅ retenu — documentation |
| `arts_de_vivre_ceramique.png` | 5610462 | `97a9261cb4cccd25ec57f0a8a88fcf8d497dca9368f1546144e8a23b89a6fa1f` | Carte ou catégorie Céramique | 🔶 exploratoire au MVP — Arts de vivre est une page « bientôt » (C6), aucune fiche produit à illustrer |
| `arts_de_vivre_lampe.png` | 6199290 | `d4ec07ae5683a60adf59d299bab5fde8a06a8b65c48d3f544233ba946f898a2b` | Carte ou catégorie Luminaires | 🔶 exploratoire au MVP — Arts de vivre est une page « bientôt » (C6), aucune fiche produit à illustrer |
| `arts_de_vivre_textile.png` | 7436188 | `fa2b7e520df3f94a51a0fa854f27ab5791e475a412e3d298c7f07e5b673ad1e2` | Carte ou catégorie Textile | 🔶 exploratoire au MVP — Arts de vivre est une page « bientôt » (C6), aucune fiche produit à illustrer |
| `atelier_alain_catherine.png` | 7587759 | `a12109c573337f16b714029aa258b09ee9a86c4cc90d16446974c26528f73d14` | Visuel vertical de la page L'Atelier | ⚠️ retenu **sous réserve** — validation Alain et Catherine requise avant publication (O-4). Ne pas présenter une illustration comme un portrait de personnes réelles |
| `hero_fond_propre_4x3.png` | 6201865 | `6a4f8b74939c519a1783c307eaf991f90193a5e02702ddc6d2f30dc2b8d24c9b` | Fond Hero nettoyé, cadrage proche de la source, sans texte ni logo — maquette ou cadrage personnalisé | 🔶 exploratoire — référence ou secours, pas la production |
| `hero_fond_web_16x9.png` | 6633318 | `89db8574bf9f2333cff739ad2cd0e16fbbbda7ec202150c26d7b52bd73131dd1` | **Fond Hero recommandé pour WordPress** — zone sûre à gauche pour logo et tagline en HTML | ✅ retenu |
| `hero_slide4_extraction_exacte_avec_logo.jpg` | 812347 | `7dc83dd2310d3efb99dfb9b089cc1d5977bdf13c8ed84f00ccf6bf50c5e46bf9` | Référence exacte de la slide 4, logo et textes incrustés — référence ou solution de secours, jamais la version de production si le texte doit être adaptatif | 🔶 exploratoire — référence ou secours, pas la production |
| `lifestyle_koi_hero.png` | 6485984 | `50e4ec1ac46c9d07390c985a002e53bdb3c0f1745a440c7e49a5e22f3add0165` | Hero ou article vedette du magazine Lifestyle & Koi | ✅ retenu |

### Koinobori_House_charte_graphique/05_ornements

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `bandeau_encre_sumi.png` | 816269 | `6d12fb63ba43db743d4280d645dfc87ae783507d52fc6a1ffd47b9e915aa3b11` | Bandeau sombre texturé — recommandation ou conclusion exceptionnelle | ✅ retenu — accent ponctuel, ne jamais multiplier |
| `cartouche_koi_vertical.png` | 90084 | `fed7b004506533aeea2a734d404d0d109fad19738cc8d9e87b80f7582de66154` | Cartouche décoratif vertical, avec parcimonie | ✅ retenu — accent ponctuel, ne jamais multiplier |
| `sceau_bcdg.png` | 81169 | `0d7308c1973887cac5e7ff440d6956ce1ae2a169a363ce81366f8518c0688062` | Signature BCDG ou repère d'auteur | ✅ retenu — accent ponctuel, ne jamais multiplier |
| `trait_encre_vertical.png` | 54154 | `53c010c1ac4ca33acbe7a71be4c44e5ee53a0bc665aa60ac949f981c98b3f9d0` | Séparateur vertical entre colonnes ou étapes | ✅ retenu — accent ponctuel, ne jamais multiplier |
| `trait_pinceau_vermillon.png` | 50318 | `9d7a142b022fa6fb2ab4d33e9d00afbd2923a3fe7e58acc156fac6069248a2d7` | Soulignement de titre, séparation courte, accent ponctuel | ✅ retenu — accent ponctuel, ne jamais multiplier |

### Koinobori_House_charte_graphique/99_archive_ne_pas_utiliser

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `original_10_shopify_ne_pas_utiliser.jpg` | 391320 | `f32258683c8cb2a73750e5c32bf7b11665695f22a3d6dd78553dfed3863bab5f` | ⛔ Ancien slide 10 « recommandation Shopify » — archive documentaire | ⛔ **non utilisé — interdit** (réintroduirait Shopify) |

### Koinobori_House_charte_graphique

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `Koinobori House — Spécifications créatives et fonctionnelles pour Claude (1).md` | 19743 | `affbb4bd4462c164c9496234fa8910a55c80d851d26f885f1924018aa577527d` | Spec v2.0 créative — **commitée** sous `KH-000b-charte-v2-Ma-Intervalle-enchante-CREATIVE.md` | ✅ retenu — documentation |
| `README_assets.md` | 9982 | `4593069ab99f82f01433d33ef36884869185081b28986df7137f56920eff3b53` | Inventaire du pack v3.1 — **commité** dans ce dossier sous `KH-000b-pack-assets-v3.1-README.md` | ✅ retenu — documentation |
| `Specifications_Claude_WordPress.md` | 20871 | `8f4db33604ed64b23d67674b9e02e677c6261a938b7ed24f7f8dfb100dd83702` | Spec v2.0 WordPress — **commitée** sous `KH-000b-charte-v2-Ma-Intervalle-enchante-WORDPRESS.md` | ✅ retenu — documentation |
| `apercu_20_slides.jpg` | 461373 | `77f45c3e978c308282541b8e5cf281753c57ea174056169c51b2e44280d8c5f9` | Planche de contact des 20 slides finales | ✅ retenu — documentation |
| `inventaire_dimensions.txt` | 1133 | `074b6361d7c4589d5d0f6bb1005f462cf6270c309690928745c2ab854a908613` | Dimensions, format et poids des images du pack | ✅ retenu — documentation |
| `manifest_fichiers.txt` | 1846 | `21725098f77fb3d08ec1767f0c9844442bbebbfbaa81bc53e8d38d47744118ca` | Liste des fichiers du pack, établie par Manus | ✅ retenu — documentation |

### koinobori-house-images/backgrounds

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `bg-boutique-fond.png` | 4618471 | `4da8e72d2e57b56b53b4ace77de606ebe43c711267500615b47ea7a38ed3b5b8` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-collections.png` | 5395152 | `d8c5379d8a85eb7ec3139cc1b649a0e6403c001c5f7fb606eae3c1e9ed85995a` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-hero-encre.png` | 5864230 | `037717e32ea092c65743e8c4003e8c7aac72f610c7497a812768fd115d78736e` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-hero-sky.png` | 4046028 | `a8916c8fabe4d82a0dc31104a01709078856b5f983172d38e0c44315fd300bd3` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-indigo-waves.png` | 4665053 | `e397fecfab9c17e9cbd3d5eb82f488898c6aac2426a2c73bd5fba48907591409` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-kairo-section.png` | 4285023 | `3005ffddd0d89a09fb3bb789844184237cb8d74ec18005e35ec46cf043a80e78` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-manifeste.png` | 4979563 | `2ee6bf59f13ba810b6656f759ecb50c05b9318c9cdcdab4f2575a1fec42d2763` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-newsletter.png` | 4701975 | `c329aa0f931073f75ceb2d8c3f350cfd50fc4d5166adde51b447af5c17f9877e` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-product-page.png` | 3936484 | `5b38a37adb136f33c5fd69aa71d46f4e08962fff0976416e6b1ac56ba507ecdd` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `bg-washi-dark.png` | 5667990 | `2e5536967dd651f198eb5a17bf28c249d213087a79e03cfbaa7c67833597deb6` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `compte-hero.png` | 4144280 | `2bc99e92e6278c930937242e8c40020a87efcf138f4b36482b940761a09910c9` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `newsletter-hero.png` | 4314046 | `08c45137991b69de0ade0e3bc787526ba3be1052002fe6d1f71c7d5733d9382d` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `texture-washi-v2.png` | 7373440 | `6f66a6cab7621c3b830bf4187309477855d2267a5a26c40c6cc84e73465407d9` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `texture-washi.png` | 4361027 | `871dafc7ba07eb5dd759184d764354220b4a2373471caedd401c78ccd4281834` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `charte-preview-1.png` | 5449469 | `c5f269425dd5e5049f596025d7dd64a7847518b752f83cfa76260c4a716a087a` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `charte-preview-2.png` | 5395152 | `b7ea1d53cc6ecdc52a4fbc6f7b6cc1460ba867da64b22852e01af6899de67125` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images/collections/hanami

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `cat-floral-ambiance.png` | 4273432 | `ac25bc74f1aeb0d2a57428a45ef071d1e874e3fc79bac708b73075103bc53d9f` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `cat-floral-hero.png` | 4571151 | `ddd0d1b5984fbb37d493440238d437b60c3185cd81358344cb39bbe7254a36b1` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images/collections/kairo

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `cat-kairo-hero.png` | 3919949 | `3d362c9fb984443e36f8bdaec59c63f205aa26cdf93b216cb7fe0eb2f9ae508b` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `collection-kairo-banner.png` | 4134387 | `d1562a1751db0ada4483771a0deb6adb7fcdd928219e6a0aaf88d247d1ed3072` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images/collections/mer

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `cat-mer-ambiance.png` | 5590980 | `e78eb12fafa48638183d58f25f212065dc55423d36161848da7e744d23a62ba2` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `cat-mer-hero.png` | 6687015 | `77bb7b5e51dc389e3be9e7e927cc0a383659bb810ad09ccfe1fb393654485df3` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `collection-okusai-hero.png` | 6344536 | `3f6277a147a03aef75d13a3b239fac169be7bde11481d2b1158a54ae009c68dd` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images/collections/motifs

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `cat-motifs-ambiance.png` | 5439964 | `ca494b169649cfbe431ab2d7ebf60ec4f300f485155d1827cb0b3116af8ea215` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `cat-motifs-detail.png` | 3701598 | `5da9e1ebf50d15ed6f704be5c4e697c753c609875a7932abebf0874484e2b984` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `cat-motifs-hero.png` | 7625214 | `b820a194b0aa490ea5c9230462221503fbffa4e33b917a25f99f4363e4b65c92` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images/collections/territoires

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `cat-territoires-ambiance.png` | 5386996 | `7cebc29bb56bc90a2608309bef5029c9425e13f23b8c7f527cd633bf449f4214` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `cat-territoires-hero.png` | 6222137 | `3b8073de9489f4756ac328b6dae0fdd60b686f5e8b7540e4120b5e907838c47b` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `collection-banner.png` | 6753779 | `dd0ab1512c9c9bfe84d1e695c532908182425bf8bc9dc3ca29807dd7fd361ccc` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `header-shoji.html` | 7258 | `8979a28b67107539e2115082e9215b4fd9a0a7eb7a95dee1a38fead6cb701aac` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `hero-koinobori.png` | 2921740 | `4049e1cc6ce8da9c79e60cc370a1d8cefb7ad2e2e13abc4ea7d732f3d328f4e1` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `homepage-section1-hero.png` | 6644773 | `832e86fc2e365c4e91540c337a9928d56ef5ffee24efe2aa2f853c5b28223310` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `homepage-section2-mondes.png` | 5969829 | `d619d727ebe8a41b827da6b15eb3209df161f5c8cda314e29b1075e16e91f90e` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `homepage-section3-produits.png` | 4657414 | `46d1611c7c75a7c2569c84c81331851b5439892d0e1811afc06d91c01939220e` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `koi-noir-header.png` | 3471438 | `39b0a4458c7c904631321f922e8aeefc3ee036720877e326f8607edab1ed765d` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `koi-rouge-header.png` | 3855869 | `694d1e54c9eee8267b0cfec69d9950c9bddd1317a577fa0e587960c38ef41c82` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `logo-color.png` | 77104 | `f0fc8aae68174ba754b2e431e82af1c54af8b81d27833d2e6c72c71a06461333` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `logo-left.png` | 41042 | `713f33cf3093f1829df49ede4d5d48623f0712d3e35a46851417b8f5c941e3a8` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `logo-right.png` | 35854 | `8b255be71709e0fa2c76909c28e829bf299b881b87b3fb19d3253422109d6c97` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `prod-kairo-horizon.png` | 1984284 | `41439596da09534546174c20cb101367e4e055ee2e90d2b7d989bb5ff3c1139c` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `prod-kairo-vaisseau.png` | 1857966 | `cc0ac5246474b3e9f1c8f4d1c641dd0d1c0ae9c5554f53745520141fcd8454ec` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `product-koinobori-1.png` | 3682667 | `7ef60acf1c04075c4379ea92db6df6976fd6dfa0eaaef6acf6b772f10c2053bc` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

### koinobori-house-images/rubriques

| Fichier | Octets | SHA-256 | Rôle | Statut |
|---|---:|---|---|---|
| `apropos-hero.png` | 4135574 | `f854feaf86fb6fe4e181adcde49d673d9928bbb51c4bb814132049f9aaea5238` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `apropos-manifeste.png` | 4422046 | `1a446fb2cd8cc5e113dd69a00da87c3eaec119a59d00fd7eda3dfab2779df8c1` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `boutique-hero.png` | 6832171 | `77b6323cff6089e447de65d36e71967028972a8e98064a0fe00141080fda6e96` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `hero-alt-atelier.png` | 4286504 | `ef8b2fcf05ec2e7fd1c30b1d3b7e4305e9de73cf337bf2e7b898614d45b0faf3` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `hero-alt-ciel.png` | 4136033 | `ef2a6e1cd45b40046604613e5666be77d03e48050095cdea2e3ad42c0041621e` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `hero-kairo.png` | 6842671 | `b0d835664eea0ba729f24cfd76af40beca4e5d00f77d95ddb0fba0ee6a3cd5f6` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `lifestyle-exterieur.png` | 4750429 | `0ca68047a17ca7641c84d7d5a73f8498a9835915b7328dbdfa5550a8e46f310f` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `lifestyle-interieur-1.png` | 4665835 | `e1befffb961071891411e7a1db5e1dd01139d50dc9beb2010599a51eb0c8cfb6` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `lifestyle-interieur-2.png` | 5571264 | `7b67db546120a71006e0646d83e675a36e122e28167f655c30cba70326c3c2b2` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |
| `packaging-paiement.png` | 5550693 | `e51ce844aae75dad7a3e394cd9e341b305b6279a09bb5024c6280d915d7ca4c9` | Rôle documenté dans [KH-001-Image-Map.md](KH-001-Image-Map.md) | 🔶 hérité v1 (KH-001 Image Map) — non ré-arbitré par la v2.0 |

## Nota sur le pack v1 `koinobori-house-images/`

Ces 52 fichiers (225 Mo, soit 71 % du volume) sont l'**ancien pack**, celui cartographié par [KH-001-Image-Map.md](KH-001-Image-Map.md). Ils sont déjà exclus de git par une règle antérieure (`koinobori-house-images/`), indépendamment de celle ajoutée pour le pack v2.0.

La charte v2.0 ne les mentionne pas et ne les remplace pas explicitement. Leur sort n'a **pas été arbitré** : plusieurs restent référencés par KH-001, notamment `bg-hero-sky.png`, les `cat-*-hero.png` des cinq mondes et `apropos-hero.png`. Ils sont donc conservés en l'état, sans statut de production, jusqu'à arbitrage.

⚠️ `apropos-hero.png` était l'illustration de la page « Univers », renommée « L'Atelier » (C2). La v2.0 fournit `atelier_alain_catherine.png` pour ce rôle. Doublon fonctionnel à trancher au moment de créer la page (cf O-8).
