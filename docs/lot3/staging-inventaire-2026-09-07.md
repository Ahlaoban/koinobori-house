# Staging : inventaire des pages et formulaires posés le 2026-09-07

Mesuré sur `staging.koinoborihouse.com` en fin de session. Source des textes : `docs/lot3/pages-confiance-FR-EN.md` et `docs/lot0/KH-017-documents-legaux/` (CGV v2026-09-07). Toutes les paires sont liées dans Polylang (hreflang vérifié dans les deux sens).

## Pages

| Page | FR (id, slug) | EN (id, slug) |
|---|---|---|
| Livraison | 248 `/fr/livraison/` | 258 `/en/shipping/` |
| Livraison USA | 230 `/fr/livraison-usa/` | 231 `/en/shipping-to-usa/` |
| Retours et rétractation (formulaire de rétractation en ligne) | 249 `/fr/retours/` | 263 `/en/returns/` |
| Contact (formulaire) | 250 `/fr/contact/` | 266 `/en/contact-us/` |
| Sur-mesure | 251 `/fr/sur-mesure/` | 269 `/en/custom/` |
| Mentions légales | 232 `/fr/mentions-legales/` | 234 `/en/legal-notice/` |
| CGV v2026-09-07 (encadré D. 211-2 inclus) | 238 `/fr/conditions-generales-de-vente/` | 276 `/en/terms-and-conditions/` |
| Politique de confidentialité | 252 `/fr/politique-de-confidentialite/` | 272 `/en/privacy-policy/` |
| Politique de cookies | 235 `/fr/politique-cookies/` | 236 `/en/cookie-policy/` |

Slugs EN `contact-us` et `privacy-policy` : WordPress impose l'unicité des slugs entre langues (Polylang Free) ; le brouillon WordPress par défaut « Privacy Policy » (id 3) a été mis à la corbeille pour libérer `privacy-policy`.

**Manquent encore** : Professionnels (aiguillage), Entreprises, Collectivités, Arts de vivre (« bientôt »), L'Atelier, Lifestyle & Koi, homepage 11 mouvements, menu 7 entrées, footer 2 étages.

## Formulaires Fluent Forms

| Usage | FR | EN | Notifications |
|---|---|---|---|
| Contact | 5 (page 250) | 6 (page 266) | interne → contact@koinoborihouse.com + accusé de réception client |
| Entreprises / Business (B2B) | 7 | 8 | idem, à poser sur `/fr/entreprises/` et `/en/business/` |
| Collectivités / Institutions (B2G) | 9 | 10 | idem, à poser sur `/fr/collectivites/` et `/en/institutions/` |
| Rétractation / Withdrawal | 11 (page 249) | 12 (page 263) | interne + accusé de réception valant support durable (art. 10.3 CGV) |

Champs conformes à CLAUDE.md §Champs formulaires. Chaque formulaire porte une case de consentement RGPD liée à la politique de confidentialité. Bouton vermillon `#C8311A`. Les emails partent via `wp_mail` tant que FluentSMTP n'est pas connecté à Brevo.

## Extensions activées ce jour

Stripe Gateway (compte à connecter en mode test par Alain), FluentSMTP (clé Brevo à saisir par Alain), Fluent Forms, Complianz (assistant à dérouler), LiteSpeed Cache (cache ON, racine `^/$` exclue, mobile ON), Wordfence (licence gratuite à enregistrer par Alain). Réglages : page CGV WooCommerce = 238 (case d'acceptation au checkout), page de confidentialité WordPress = 252 (Polylang sert 272 en EN).
