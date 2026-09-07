# Répartition des tâches — Manus / Claude / Alain

- **Date** : 2026-07-27
- **Horizon** : lancement 15 août 2026, soit 19 jours. G4 le 10/08 est le juge de paix visuel.
- **Objet** : qui possède quoi, à quel niveau de fichier, pour que personne ne défasse le travail de l'autre.

---

## 1. Le principe

**Manus possède la présentation. Claude possède la structure et les données. Alain possède ce que personne d'autre ne peut produire.**

Ce n'est pas un partage par compétence supposée, c'est un partage **par fichier**. Un fichier a un seul propriétaire. Celui qui ne le possède pas n'y touche pas, même pour une virgule — il ouvre une demande.

La raison est simple : le seul risque réel d'un travail à deux intelligences sur un même dépôt est que l'une écrase l'autre sans le voir. Une frontière floue produit ce résultat en trois jours.

---

## 2. Ce que possède Manus

**Toute la couche visuelle, livrée en code, pas en image.**

| Périmètre | Fichiers |
|---|---|
| Feuilles de style | `wp/themes/koinobori-child/assets/css/**` |
| Déclarations éditeur | `wp/themes/koinobori-child/theme.json` |
| Gabarits et compositions | patterns de blocs, gabarits de page pour les 11 mouvements, footer, carte produit, pages Mondes, archive Lifestyle |
| Images de la charte | `Koinobori_House_Assets_Claude/**` — production et arbitrage |

### Travaux attendus

1. **Migrer les 3 couleurs** vers la palette v2.0 : `--kh-washi` `#F7F3EC` → `#F8F4EE`, `--kh-or` `#C9A96E` → `#B8860B`, `--kh-indigo` `#1B2B5E` → `#2B3A6B`. Ajouter `--kh-white` `#FFFDFC` pour les cartes. Répercuter dans `theme.json`.
2. **Gagner la cascade contre Kadence.** Aujourd'hui les titres s'affichent en Lora alors que `h1,h2,h3` demande Cormorant. C'est le premier diagnostic à faire : il conditionne tout le reste.
3. **Écrire l'échelle typographique et le rythme d'espacement.** Ils n'existent nulle part — la spec ne contient que 11 valeurs numériques au total. C'est le cœur de la valeur ajoutée de Manus : ces nombres-là sont sa signature, et personne ne peut les deviner à sa place.
4. **Composer** : les 11 mouvements de la page d'accueil, le footer ligne d'horizon (la spec §11.4 fournit 31 lignes de CSS de base), la carte produit, les 5 pages Mondes, les gabarits Lifestyle.
5. **Porter le header Shoji V2** : « Mon compte » passe de corail `#E05A5A` à vermillon `#C8311A`. Le design du header n'est pas rouvert — C5 l'a verrouillé.

### Ce dont Manus dispose déjà et n'a pas à refaire

`kh-foundations.css` contient **les 4 familles auto-hébergées** en woff2, sous-ensembles latin et latin-ext, unicode-ranges corrects, avec Cormorant Garamond en 300/400/600 normal et italique. Plus les jetons `--kh-font-display|body|ui|jp|kanji` et les classes `.kh-title-hero`, `.kh-body`, `.kh-ui`, `.kh-kanji` avec leurs valeurs. `tools/fetch-fonts.py` régénère les woff2 si besoin.

**Manus ne repart pas de zéro. Il reprend une fondation posée et écrit ce qui manque au-dessus.**

---

## 3. Ce que possède Claude

**Tout ce qui se vérifie, et toute la plomberie.**

| Périmètre | Fichiers |
|---|---|
| Documentation | `docs/**` sauf ce que Manus produit |
| Textes légaux | `docs/lot0/KH-017-documents-legaux/**` |
| Catalogue | `docs/lot2/**`, `catalog/**` |
| Extensions maison | `wp/plugins/**`, `wp/mu-plugins/**` |
| Outils | `tools/**` |
| Contenu WordPress | pages, produits, traductions, menus, réglages |

### Travaux attendus

1. **Installer et configurer le socle** : Stripe, PayPal, FluentSMTP + Brevo, Fluent Forms, Complianz, LiteSpeed Cache, et **réactiver Wordfence**.
2. **Finir les pages** : 8 légales et confiance restantes, plus le maillage des traductions FR↔EN.
3. **Construire les 4 formulaires** : contact, B2B, B2G, et le formulaire de rétractation en ligne, obligatoire depuis le 19/06/2026.
4. **Coder les deux cases à cocher du checkout numérique** (point B-7) : accord exprès à la mise à disposition immédiate et renoncement exprès à la rétractation, deux cases distinctes non précochées. La logique est juridique, donc à moi ; **l'habillage est à Manus**.
5. **Créer le menu à 7 entrées** et brancher le footer sur la structure arbitrée O-1/O-2. La structure est à moi, **le style est à Manus**.
6. **Téléverser les visuels produits** quand Alain les fournit, et corriger Sakura Rouge dont l'image dort dans le dépôt.
7. **Vérifier** : balayage doctrine avant chaque publication, contrôle contre les critères §14, revue de ce que Manus livre.
8. **Tenir git** : branches, PR, revue, historique.

---

## 4. L'interface entre nous

C'est le point qui fait échouer ce genre de partage. Il est donc écrit.

### Cas A — Manus peut committer dans le dépôt

Le plus simple. Manus travaille sur des branches préfixées `design/`, ouvre une PR, je relis contre les critères §14 et la doctrine, Alain merge.

### Cas B — Manus ne peut pas accéder au dépôt

Manus livre des fichiers. **Toujours du CSS ou du HTML, jamais une image de maquette.** Je les intègre sans les réinterpréter, dans une PR qui ne contient que son travail, et je lui renvoie des captures de staging à toutes les largeurs.

Dans les deux cas, la règle est la même : **ce que Manus livre en code entre tel quel.** Si je dois le modifier, je le signale au lieu de le corriger en silence — sinon la fidélité qu'on cherche à protéger se perd exactement là.

### Le contrat de balisage

Pour styler sans se marcher dessus, il faut un vocabulaire de classes partagé. Il existe déjà : `.kh-title-hero`, `.kh-body`, `.kh-ui`, `.kh-kanji`, et les jetons `--kh-*`.

**Règle : je produis le balisage avec les classes convenues, Manus les style.** Quand j'ai besoin d'une classe qui n'existe pas, je la demande plutôt que de l'inventer. Quand Manus a besoin d'un conteneur supplémentaire, il le demande plutôt que de modifier mon gabarit.

Toute classe nouvelle est consignée dans un fichier unique, `docs/charte-graphique/CONTRAT-CLASSES.md`, que nous tenons tous les deux. C'est le seul document à écriture partagée.

---

## 5. Zones de contact, arbitrées d'avance

| Zone | Propriétaire | Pourquoi |
|---|---|---|
| `functions.php` | **Claude** | Enqueues, hooks, logique. Manus demande un enqueue, je l'ajoute |
| `style.css` du thème | **Manus** | C'est de la présentation |
| Cases à cocher du checkout | **Claude** pour la logique, **Manus** pour le style | Obligation légale d'un côté, apparence de l'autre |
| Menu à 7 entrées | **Claude** pour la structure, **Manus** pour le style | C4 fixe les entrées, Manus décide de leur allure |
| Footer | **Claude** pour les liens, **Manus** pour la mise en forme | O-1/O-2 fixent le contenu, §11 fixe la forme |
| Fiche produit | **Claude** pour les textes et données, **Manus** pour la composition | La doctrine régit les mots, pas la mise en page |
| Header Shoji V2 | **Manus** | Verrouillé par C5, seul le corail passe en vermillon |
| Images produits | **Alain** | Personne ne peut les inventer |

---

## 6. Ce que possède Alain, et que personne ne peut faire à sa place

1. **Les 9 photos produits manquantes** — les 6 de la collection Mer, les 3 Motifs. Absentes de staging et du dépôt. C'est le premier blocage du lancement, avant toute question de charte.
2. **Le KYC Stripe.**
3. **Les arbitrages** : UX et direction artistique restent son arbitrage exclusif.
4. **La validation de Catherine** avant toute publication du portrait de la page L'Atelier.
5. **La BD Kaïro** : les planches, le prix, la version anglaise éventuelle.
6. **REP / IDU** : déterminer si les koinobori relèvent d'une filière. Question réglementaire jamais instruite.
7. **Les tests Colissimo USA**, s'il veut activer la zone.

---

## 7. Séquencement sur 19 jours

Les deux chantiers sont **parallèles et indépendants** jusqu'au 10/08. C'est tout l'intérêt du partage : ni Manus ni moi n'attendons l'autre.

| Période | Manus | Claude | Alain |
|---|---|---|---|
| **28/07 – 31/07** | Diagnostic de la cascade Kadence. Migration des 3 couleurs. Échelle typographique et rythme | Socle d'extensions : Stripe, SMTP, Complianz, Wordfence, LiteSpeed. Fin des 8 pages | **Photos produits.** KYC Stripe |
| **01/08 – 03/08** | Footer ligne d'horizon. Carte produit. **G2 le 03/08** | 4 formulaires, dont la rétractation en ligne. Maillage des traductions | Arbitrages au fil de l'eau |
| **04/08 – 06/08** | Les 11 mouvements de la page d'accueil. **G3 le 06/08** | Cases à cocher du checkout. Menu 7 entrées. Téléversement des visuels | REP / IDU |
| **07/08 – 10/08** | Pages Mondes, gabarits Lifestyle. **G4 le 10/08 — juge de paix** | Vérification contre §14. Balayage doctrine. Tests de paiement | Arbitrage G4 |
| **11/08 – 14/08** | Correctifs issus de G4 | Recette, performance, sauvegardes. **G5 le 14/08** | Soft privé le 12/08 |
| **15/08** | — | Mise en production | Lancement |

---

## 8. Ce qui ferait échouer ce partage, et comment on l'évite

| Risque | Parade |
|---|---|
| Les deux modifient le même fichier | Un fichier, un propriétaire. Écrit au §2 et §3, sans zone grise |
| Manus livre des images au lieu du code | Règle explicite : du CSS, jamais une maquette. C'est la condition de la fidélité |
| Je « corrige » silencieusement le CSS de Manus | Je signale, je ne corrige pas. Sinon l'écart qu'on veut éviter revient par ma main |
| Manus attend du contenu pour styler | Je livre les pages en premier. C'est déjà ma trajectoire |
| Manus ne peut pas tester sur staging | Je déploie et je renvoie des captures à 360, 768 et 1280 px |
| Conflits git | Branches séparées, répertoires séparés. `design/` pour Manus, `feat/` et `docs/` pour moi |
| Le rythme typographique diverge entre pages | Manus l'écrit **une fois** en jetons, pas page par page |

---

## 9. Le point à trancher avant de démarrer

**Manus livre-t-il du CSS, ou des maquettes ?**

Si c'est du CSS, la fidélité à sa créativité est garantie par construction : son code entre tel quel, aucune interprétation n'a lieu, et la question qui a motivé ce partage disparaît.

Si ce sont des maquettes, quiconque les implémente devra inventer l'échelle typographique et le rythme, parce que la spec ne les contient pas — 1 bloc CSS, 6 hexadécimaux, 11 valeurs numériques sur 32 sections. L'écart reviendra, quel que soit l'exécutant.

**C'est la seule question qui détermine si ce partage tient ses promesses.**
