# Clauses « contenu numérique » à réinsérer dans les CGV au lancement de la BD Kaïro

Statut : **mises de côté le 2026-09-07**. La BD Kaïro (PDF téléchargeable) sort **à l'automne 2026, date à préciser** (arbitrage Alain 2026-09-07), après le lancement du site. Les CGV v2026-09-07 ne mentionnent donc aucun contenu numérique. Ces clauses, issues de la version du 2026-08-03, sont à réintégrer, renumérotées, dans une nouvelle version datée des CGV (article 26) le jour où la BD est mise en vente, avec les deux prérequis techniques B-7 et B-8 de [docs/kairo-bd-recit-et-produit.md](../../kairo-bd-recit-et-produit.md).

## À insérer dans l'article 3 (Produits)

Koinobori House propose également des contenus numériques fournis sans support matériel, mis à disposition par téléchargement après paiement.

Ces contenus sont diffusés au format PDF. Ils sont lisibles sur ordinateur, tablette et téléphone, avec toute application capable d'ouvrir un fichier PDF, sans logiciel propriétaire ni abonnement. Aucune mesure technique de protection ne restreint la lecture du fichier sur les appareils du client.

La fiche produit précise, pour chaque contenu numérique, son format, son nombre de pages et sa taille approximative.

## À insérer dans l'article 8 (Livraison)

Les contenus numériques ne font l'objet d'aucune expédition ni d'aucuns frais de livraison. Ils sont mis à disposition par téléchargement dans les conditions prévues à l'article 14.

## À insérer dans l'article 14 (Exceptions au droit de rétractation)

Conformément à l'article L. 221-28, 13° du Code de la consommation, le droit de rétractation ne peut pas être exercé pour la fourniture d'un contenu numérique non fourni sur un support matériel dont l'exécution a commencé après accord préalable exprès du consommateur et renoncement exprès à son droit de rétractation.

En conséquence, avant la validation de sa commande portant sur un contenu numérique, le Client doit :

- donner son **accord exprès** pour que le téléchargement soit mis à disposition immédiatement, c'est-à-dire avant l'expiration du délai de quatorze jours ;
- **reconnaître expressément** qu'il perd, de ce fait, son droit de rétractation sur ce contenu.

Ces deux confirmations sont recueillies par deux cases à cocher distinctes, non précochées, à l'étape de validation de la commande. Le Client en reçoit confirmation sur le support durable qui accompagne sa commande.

À défaut de ces confirmations, la mise à disposition du fichier est différée jusqu'à l'expiration du délai de rétractation de quatorze jours.

Cette exclusion ne prive le Client d'aucune garantie légale : un fichier corrompu, illisible ou non conforme à sa description ouvre droit aux recours prévus aux articles 15 et 16.

## À insérer dans l'article 20 (Propriété intellectuelle)

Le fichier est destiné à l'usage personnel et privé du Client. Son achat ne confère aucun droit de reproduction, de diffusion, de partage public, de revente, de prêt, d'adaptation ni d'exploitation commerciale.

Le lien de téléchargement est personnel et rattaché à la commande du Client.

## Prérequis techniques avant la première vente numérique

- **B-7** : deux cases à cocher distinctes, non précochées, au checkout (logique = Claude, habillage = Manus).
- **B-8** : SMTP opérationnel (FluentSMTP + Brevo) pour l'envoi du lien de téléchargement.
- Version EN des clauses à produire en même temps.

---

## Réserves relevées par la revue de code du 2026-09-09

Trois points à traiter **avant** de réinsérer ces clauses au lancement de la BD Kaïro. Aucun ne bloque le lancement du 30 septembre, puisque la BD est hors périmètre.

### 1. 🔴 La version EN a été supprimée, pas parquée

129 lignes sont sorties de `04-terms-conditions-B2C-EN.md` lors de la réécriture, dont les clauses de contenu numérique et de renoncement. Seule la moitié FR a atterri ici. La ligne « Version EN des clauses à produire en même temps » est un reste-à-faire non assigné, pas un texte préservé.

Conséquence : au lancement de la BD, les clauses FR se recollent en quelques minutes pendant que les clauses EN doivent être réécrites et recontrôlées depuis zéro, ou retrouvées par archéologie dans l'historique antérieur au commit `8050e68`, ce que personne ne pensera à faire. La boutique anglaise risque alors de vendre un PDF téléchargeable sans exclusion de rétractation valable.

**À faire** : récupérer le texte EN supprimé (`git show 8050e68^:docs/lot0/KH-017-documents-legaux/04-terms-conditions-B2C-EN.md`) et le parquer ici à côté du FR.

### 2. La rédaction de l'exclusion L. 221-28 13° est antérieure à mai 2022

Le numéro d'article est bon, mais le texte reproduit est la version d'avant l'ordonnance n° 2021-1734 du 22 décembre 2021. La version en vigueur ajoute une **troisième condition** que la clause ne mentionne pas : le professionnel doit avoir fourni une **confirmation de l'accord du consommateur** conformément au deuxième alinéa de l'article L. 221-13.

Présentée avec deux cases à cocher seulement, l'exclusion pourrait être jugée inopposable. Source vérifiée : Légifrance, article L221-28, version en vigueur au 09/09/2026.

### 3. Mauvais régime de garantie, et un encadré obligatoire manquant

- Le renvoi « les recours prévus aux articles 15 et 16 » pointe vers la garantie légale de conformité **des biens**. Un PDF fourni sans support matériel relève de la garantie de conformité **des contenus et services numériques**, articles **L. 224-25-12 à L. 224-25-26**, dont la présomption est de **1 an** à compter de la fourniture, pas 2 ans.
- Il manque un prérequis : l'article **D. 211-3** impose un **second encadré obligatoire**, distinct de celui de D. 211-2 déjà présent dans les CGV, conforme à l'annexe de cet article, avec la durée contractuelle de fourniture en lieu et place du « X ». Son absence est un manquement constatable sur simple lecture du document.

**À ajouter aux prérequis techniques** : encadré D. 211-3 rédigé et inséré.
