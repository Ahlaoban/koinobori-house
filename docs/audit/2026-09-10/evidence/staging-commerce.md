# S18 — réglages du commerce et correspondance aux pages publiques

10 septembre 2026, poursuite F0. Session Chrome administrateur, onglet staging connu802966709 uniquement. Navigation et lecture des champs DOM ; aucun enregistrement, changement de méthode, ajout au panier, paiement ou envoi. Aucune clé de passerelle ni donnée client lue.

## Général — wc-settings

- Pays boutique : France ; devise EUR.
- Pays autorisés à la vente : tous. Destinations de livraison : tous les pays où la boutique vend. Adresse client par défaut : pays/région de la boutique.
- `woocommerce_calc_taxes` non coché ; coupons activés.

La désactivation du calcul des taxes est cohérente avec la franchise en base déclarée dans les documents KH. Ce constat ne vérifie ni l’éligibilité fiscale actuelle ni les obligations par destination. Aucune activation automatique des taxes n’est recommandée sans règles commerciales/fiscales validées.

## Livraison — wc-settings, tab=shipping

Une seule zone nommée France métropolitaine, composition France, zone_id1. Deux méthodes actives : Forfait et Livraison gratuite. La zone Reste du monde ne contient aucune méthode.

Lecture des paramètres via les liens observés `instance_id=1` et `instance_id=2` :

| Paramètre | Valeur enregistrée |
|---|---|
| Forfait | 5 EUR ; statut de taxe Taxable |
| Gratuité | Condition montant minimum de commande ; minimum55 EUR |
| Ignorer remises pour le minimum | Non coché |

Le statut Taxable d’une méthode ne prouve pas qu’une taxe est actuellement facturée : le calcul des taxes est désactivé globalement. Aucun montant de checkout réel n’a été mesuré. La composition pays France ne doit pas être extrapolée aux codes pays des territoires ultramarins.

## Page publique /fr/livraison/

- France : gratuité dès55 EUR, frais en dessous affichés au panier ; La Poste/Colissimo et option Mondial Relay ; délai indicatif2 jours ouvrables après expédition.
- Union européenne : Colissimo international ; Mondial Relay pour certains pays ; frais calculés au panier ; délai3–8 jours ouvrés.
- DROM-COM : Colissimo Outre-Mer et frais réels affichés au panier, gratuité55 EUR exclue.
- États-Unis : traitement sur demande ; Royaume-Uni/Suisse/autres : consulter KH.

**Écart vérifié de configuration et de promesse :** les modes de livraison au panier annoncés pour UE et DROM-COM ne correspondent à aucune méthode dans les zones inspectées. L’absence de méthode hors France ne contredit pas en elle-même la procédure sur demande annoncée pour USA/Royaume-Uni/Suisse. Aucune commande étrangère n’a été soumise ; le comportement final du checkout reste à tester. Les tarifs par pays/poids et la procédure relais ne sont pas inventés pour combler cet écart.

## Paiements — wc-settings, tab=checkout

L’écran affiche : Bouton de carte standard Active ; PayPal Compte de test ; Stripe Action requise avec Terminer la configuration. Cela complète S16 : l’indicateur de compte de test PayPal est maintenant observé, mais aucun webhook, encaissement ou remboursement n’est démontré. Aucun bouton de connexion/installation n’a été utilisé.

## Comptes et confidentialité — wc-settings, tab=account

Achat invité, rappel de connexion au checkout, inscription au checkout et sur Mon compte, génération de mot de passe : cochés.

Champs de durée tous vides : suppression comptes inactifs, mise à la corbeille commandes en attente/échouées/annulées, anonymisation commandes remboursées/terminées, conservation Stripe. Options d’effacement automatique des données de commande et téléchargements lors d’une demande non cochées ; suppression personnelle en masse non cochée.

La politique KH FR §7, `docs/lot0/KH-017-documents-legaux/05-politique-confidentialite-RGPD-FR.md:85`, prévoit suppression/anonymisation après3 ans d’inactivité. La checklist juridique:78 attribue cette durée à une décision Alain du24/07. Aucun réglage WC de cette durée n’est renseigné. Une procédure humaine ou une automatisation hors cet écran reste possible mais n’est pas documentée par les preuves actuelles : ne pas conclure à une absence totale de traitement ni activer une purge irréversible pendant l’audit.

La présence de réglages d’inscription ne prouve pas la vérification d’identité, la récupération des achats invités ou le cloisonnement des futurs dossiers. Aucun compte test créé.
