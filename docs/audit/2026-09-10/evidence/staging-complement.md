# S17 — complément après confirmation « staging ouvert »

10 septembre 2026, session Chrome administrateur. Navigation et lecture du DOM uniquement ; aucune soumission, aucun ajout au panier ni enregistrement. Les résultats ne certifient pas les états anonymes, mobiles ou transactionnels.

## Liaisons de pages

Sources : `/wp-admin/edit.php?post_type=page`, pagination1 et2. Compteurs :43 pages hors corbeille,40 publiées,3 brouillons,1 en corbeille ; cohérent avec44 objets page tous statuts dans S16.

Trois paires publiées proposent chacune Ajouter une traduction vers l’autre langue malgré l’existence des deux contenus : Mentions légales FR232 / Legal Notice EN234 ; Politique de cookies FR235 / Cookie Policy EN236 ; Livraison USA FR230 / Shipping to USA EN231.

Les paires accueil318/319, compte77/87, panier75/83, confidentialité252/272, retours249/263, livraison248/258 et CGV238/276 présentent des liens de traduction dans les lignes inspectées. Sur `/fr/mentions-legales/`, titre et `lang=fr-FR` présents, aucun `link rel=alternate` avec hreflang dans le DOM. Recommandation : réconcilier les liaisons en conservant IDs/URLs, sans recréer les pages. Aucune modification effectuée.

## Formulaires publics

`/fr/retours/` rend `form#fluentform_11` ; `/en/returns/` rend `form#fluentform_12` et des hreflang vers les deux pages. Champs visibles dans chaque langue : prénom, nom, e-mail, numéro de commande, date de réception, produits, précisions facultatives ; cases de déclaration de rétractation et de traitement des informations. Boutons « Confirmer ma rétractation » / « Confirm my withdrawal ». Explications et lien CGV présents.

Aucune soumission : validation serveur, caractère obligatoire exact des champs, confirmation après envoi, accusé durable horodaté et réception restent non vérifiés. L’absence de `required` natif sur certains champs ne constitue pas à elle seule un défaut : Fluent Forms peut valider autrement.

## Panier anglais

`/en/cart/` : titre Cart, `html lang=en-US`, mais panier vide affichant « Votre panier est actuellement vide ! » et « Nouveau dans la boutique ». Recommandations produits et boutons SELECT OPTIONS / ADD TO CART en anglais. Aucun bouton d’achat activé. Défaut limité à cet état rendu ; origine exacte des chaînes dans les blocs sauvegardés ou leur traduction non démontrée. Vérifier page83, panier rempli et checkout avant correction globale.
