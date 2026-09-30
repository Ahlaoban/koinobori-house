# Relevé du socle transactionnel — staging, 2026-09-30 10:42 UTC

Lecture seule (`tools/staging/pc10/socle-report.php`, déposé sur le serveur dans `~/kh2027-private/tools/w1322/tools/staging/pc9/`, sortie `~/kh2027-private/pc10-socle.txt`). Gardes toutes vraies, `php -l` sans erreur, rien d'écrit. Aucune clé lue : seulement « renseigné » ou « vide ». Session cPanel ouverte par Alain.

| Domaine | Mesuré | Conséquence |
|---|---|---|
| **Stripe** | passerelle désactivée ; mode test coché ; **aucune clé**, ni test ni réelle | à connecter entièrement (clés saisies par Alain) |
| **PayPal** | passerelle PayPal et bouton carte **activés**, **bac à sable** ; identifiant et secret renseignés ; identifiant marchand non relevé | fonctionne en test ; bascule en réel à faire à la mise en production |
| Autres paiements | virement, chèque, contre-remboursement désactivés | conforme |
| **Commandes** | 1 terminée, 4 brouillons de paiement | une commande d'essai a déjà abouti |
| **Envoi des emails** | **FluentSMTP non configuré** (aucun réglage, journal vide) | tout part par la fonction `mail()` du serveur : risque de spam ou de non-distribution |
| Emails de commande | 22 modèles, 18 actifs (désactivés : annulation client, facture, 2 modèles caisse) | textes à relire, FR et EN |
| Formulaires | 16 notifications, **toutes actives** (2 par formulaire) | partent aussi par `mail()` tant que Brevo n'est pas relié |
| **Expédition** | une seule zone, France : forfait 5 €, gratuit dès 55 € ; aucune méthode ailleurs | **commande impossible hors France** ; forfait 5 € à comparer aux tarifs relevés (5,74 € / 7,91 €) |
| Vente | tous pays autorisés, commande sans compte autorisée, page CGV reliée (238), pas de TVA, stock géré | restreindre les pays au périmètre livré |
| **Cookies** | Complianz : 1 bandeau, **assistant jamais terminé** | à terminer |
| Référencement | plan du site SEOPress activé ; site non indexable (normal sur le staging) | à rendre indexable en production seulement |
| Cache | LiteSpeed : cache de page désactivé | à activer après la recette |
| **Sécurité** | Wordfence : pare-feu, protection de connexion et analyses planifiées actifs ; 1 administrateur, double authentification active | en place |
| **Sauvegardes** | UpdraftPlus : fichiers chaque semaine, base chaque jour, Google Drive ; dernière sauvegarde réussie le 30/09 à 07:30 UTC | en place |

## Reste à faire, par ordre

1. Stripe en mode test (clés par Alain), commande d'essai carte FR et EN ; PayPal : rejouer une commande d'essai.
2. FluentSMTP relié à Brevo (clé par Alain), email d'essai, puis relecture des emails de commande et des 16 notifications.
3. Zones d'expédition UE et périmètre des pays (tarifs à approuver par Alain), restriction des pays de vente.
4. Assistant Complianz.
5. Cache LiteSpeed, puis recette complète.
6. Procédure de mise en production (clés réelles, indexation, domaines).

## Suivi — envoi des emails relié à Brevo (2026-09-30, 11:17 UTC)

Alain a relié FluentSMTP à Brevo dans l'admin du staging (fournisseur Brevo, expéditeur `contact@koinoborihouse.com`, nom « Koinobori House », clé API saisie par lui, stockée chiffrée). Compte Brevo vérifié par lui : expéditeur vérifié, DKIM `koinoborihouse.com` valide, DMARC configuré. Premier essai en échec (« Key not found » : clé non reconnue par Brevo), second essai réussi avec une clé API : email reçu en boîte de réception Gmail. Second relevé en lecture seule : 1 connexion, fournisseur `sendinblue`, clé renseignée, journal = 1 échec (13:09) puis 1 envoi réussi (13:15, heure de Paris).

Conséquence : les 18 emails de commande actifs et les 16 notifications de formulaires partent désormais par Brevo (plan gratuit, 300 emails par jour). À faire à la mise en production : refaire ce réglage sur le site en ligne (clé dédiée).

## Suivi — Stripe en mode test (2026-09-30, 17:19 UTC)

Alain avait d'abord connecté le compte Stripe **réel** au staging (passerelle activée, mode test impossible) : signalé comme risque de vrai débit. Il a ensuite connecté l'« environnement de test » de son compte et coché le mode test. Relevé n°3 : Stripe activé, `testmode = yes`, clés de test et webhook de test renseignés ; **les clés réelles de la première connexion restent aussi enregistrées** sur le staging (non utilisées tant que le mode test est coché). PayPal inchangé (bac à sable).

Points ouverts : retirer les clés réelles du staging (déconnexion du compte réel) ; deux moyens de paiement par carte proposés (carte via PayPal et carte via Stripe) : en garder un seul (décision Alain) ; commandes d'essai carte et PayPal, FR et EN.

Relevé n°4 (17:33 UTC), après déconnexion du compte réel et reconnexion de l'environnement de test par Alain : Stripe activé, mode test, clés de test et webhook de test renseignés, **clés réelles vides**. Le staging ne porte plus aucune clé Stripe réelle.
