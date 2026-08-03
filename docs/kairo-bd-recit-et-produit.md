# Kaïro — la BD illustrée : récit, produit, et moteur de vente

- **Date** : 2026-07-27
- **Statut** : 🟡 **cadrage ouvert** — la BD est **en cours de réalisation par Alain**. Elle ne sera pas prête au lancement du 15/08.
- **Décisions rendues** : la BD **sera vendue** (prix non déterminé) · elle couvre **les 15 épisodes**, le récit précède donc la commercialisation des koi · objectif = **meilleure visibilité et meilleure vente, de la BD et des koi**.

## 1. Ce que change ce projet

La BD n'est pas une rubrique éditoriale. C'est un **second produit qui vend le premier**.

Le cycle *Kaïro des Vents Rouges, Le Navire Sans Nom* compte 15 épisodes. **4 koi sont dessinés et en vente** (ép. 1-4, `KH-KAI-001` à `004`). Les 11 autres n'existent pas encore comme produits. La BD racontant les 15, elle crée la demande avant l'offre — c'est délibéré.

Conséquence : Kaïro cesse d'être une collection parmi cinq. C'est la seule gamme du catalogue à porter un récit continu, donc la seule capable de faire revenir un client épisode après épisode.

## 2. Recommandation d'emplacement

La navigation v2.0 est verrouillée à **7 entrées** par le critère de sortie §14, et G4 tombe le 10/08. Ajouter une 8ᵉ entrée casserait ce critère quatorze jours avant le gate.

Dispositif recommandé, qui donne plus de visibilité utile qu'une entrée de menu :

| Point de contact | Rôle |
|---|---|
| **Page Kaïro autonome** | Le hub du récit. Héberge la BD en lecture, chaque planche renvoyant au koi correspondant. C'est le tunnel de conversion |
| **Homepage, mouvement 4** | Kaïro y a déjà sa place (O-9). Devient la porte d'entrée du récit depuis l'accueil |
| **Boutique** | La BD est un produit vendu, aux côtés des koi |
| **Chaque fiche produit Kaïro** | Lien « lire l'épisode » vers la planche correspondante. 4 points d'entrée aujourd'hui, 15 à terme |

Le maillage depuis les fiches produits convertit mieux qu'une entrée de menu : il capte le visiteur déjà intéressé par un koi et lui donne la suite de l'histoire.

⚠️ Si Alain veut malgré tout une entrée de nav dédiée, c'est une **dérogation explicite au §14**, à trancher avant G4 et à documenter comme telle.

## 3. Points ouverts

| # | Point | Nature |
|---|---|---|
| ~~B-1~~ | ~~Physique ou numérique ?~~ | ✅ **Tranché 2026-07-27 : numérique téléchargeable**, format PDF, lisible sur téléphone, PC, Mac et iPad. Produit WooCommerce **virtuel + téléchargeable**, aucun frais de port |
| ~~B-2~~ | ~~Clause « contenu numérique » absente des CGV~~ | ✅ **Rédigée 2026-07-27** — CGV FR §2.1, §7, §8.4 bis et §8.4 ter, et équivalents dans les Terms EN. Base : art. L221-28, 13° du code de la consommation, plus l'obligation d'information sur la fonctionnalité et l'interopérabilité du contenu numérique. **À relire par Alain** |
| **B-7** | **Deux cases à cocher au checkout.** Les CGV engagent désormais le site à recueillir, avant validation, l'accord exprès à la mise à disposition immédiate **et** le renoncement exprès au droit de rétractation, par deux cases distinctes non précochées. WooCommerce ne le fait pas nativement | **Bloquant** avant la première vente numérique. À implémenter dans le thème enfant ou via extension |
| **B-8** | **Livraison du fichier sans SMTP.** WooCommerce envoie le lien de téléchargement par email, et aucun plugin SMTP n'est installé : l'email ne partirait pas. Le client verrait toutefois ses téléchargements sur la page de confirmation et dans son compte | **Bloquant** avant la première vente numérique |
| **B-9** | **Le PDF circulera.** Aucune protection technique n'est prévue, et les CGV l'annoncent (c'est un choix assumé, favorable au client). Un tatouage discret à l'email de l'acheteur reste possible si tu veux dissuader le partage | Arbitrage Alain, non bloquant |
| **B-3** | **Où ranger la BD dans le catalogue ?** La taxonomie est figée à 5 collections depuis le 2026-06-18, toutes des koinobori. Une BD n'en est pas un. Piste : la ranger dans **Kaïro** sans toucher la taxonomie | Arbitrage Alain |
| **B-4** | **Wording des 11 épisodes non commercialisés.** Le récit les montre, la boutique ne les vend pas. Ne jamais annoncer de date ni promettre une sortie : présenter des chapitres, pas un catalogue à venir | Doctrine |
| **B-5** | **Prix** non déterminé | Alain |
| **B-6** | **Version EN de la BD ?** Le site est bilingue dès J0. Une BD FR seule crée une asymétrie sur la moitié du catalogue narratif | Arbitrage Alain |

## 4. Calendrier

La BD **n'est pas au lancement**. Ce qui peut l'être :

1. La **page Kaïro** avec le récit cadré et les 4 épisodes disponibles, prête à accueillir les planches.
2. Le **maillage** depuis les fiches produits et depuis le mouvement 4.
3. La **fiche produit BD** en brouillon, ouverte le jour où la BD existe.

Rien de tout cela ne dépend des planches. Tout peut être construit pendant que la BD se fait.

## Liens

- [ux-architecture.md](ux-architecture.md) §9 O-9 — place de Kaïro sur la homepage
- [lot2/lot2-catalogue-plan.md](lot2/lot2-catalogue-plan.md) — taxonomie figée à 5 collections
- [lot0/KH-017-documents-legaux/03-CGV-B2C-FR.md](lot0/KH-017-documents-legaux/03-CGV-B2C-FR.md) §8 — rétractation
