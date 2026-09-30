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
