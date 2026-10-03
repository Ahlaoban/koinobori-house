# Journal PC11 — adresse publique du vendeur remplacée sur le staging (2026-10-03)

Décision Alain du 2026-10-03 : « 1 rue du Marais, 29730 Treffiagat » est remplacée **partout** sur le site
(CGV, mentions légales, confidentialité, factures, emails, adresses de retour comprises) par
« 34 quater rue de la Marine, 29730 Le Guilvinec ». Ternand reste la seconde adresse de retour.
Signalé avant décision : le registre (SIRET 945 241 545 00017) ne déclare que l'adresse de Treffiagat.

## Recherche (lecture seule, `tools/staging/pc11/adresse-search.php`)

59 mentions dans 377 colonnes de texte. Hors données client et historique : 6 pages publiées (232
Mentions légales, 234 Legal Notice, 238 CGV, 252 Politique de confidentialité, 272 Privacy Policy,
276 Terms and Conditions of Sale), 3 réglages (`woocommerce_store_address`, `woocommerce_store_city`,
`wpo_wcpdf_settings_general`), 2 messages de formulaires Fluent Forms (meta 76 formulaire 11, meta 84
formulaire 12 : « Treffiagat ou Ternand »).

Laissés tels quels, volontairement : adresses des clients (commandes, adresses de commande, sessions,
`wc_customer_lookup`, profil utilisateur d'Alain), 2 caches Stripe de sessions de paiement d'essai,
révisions des pages (historique), journaux FluentSMTP (historique, purgé après 14 jours).

## Remplacement (`tools/staging/pc11/adresse-replace.php`, déposé sous le nom `adresse-replace-pc11b.php`)

- Dry-run : 11 changements, extraits contrôlés.
- Application 2026-10-03 18:19:44 UTC par Alain : `Success: 11 change(s) applied.`
  Sauvegarde `~/kh2027-private/backups/pc11-adresse-20261003T181944Z.json`
  (SHA-256 `eec5bf83b1612a0d9b05b40bc752a844cbded925825792185756178ea32ded32`).
  Retour arrière : `KH_APPLY=1 KH_CONFIRM=restore wp eval-file …/pc4/restore.php <sauvegarde>`.
- Nouvelle recherche : plus aucune mention hors données client et historique.

## Reste

- Date de version des CGV FR/EN sur le staging (le dépôt indique désormais 3 octobre 2026).
- Adresse de l'entreprise dans les comptes Stripe, PayPal et Brevo (Alain).
- Profil client d'Alain dans l'admin (facultatif, non public).
