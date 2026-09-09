# CODEX-HANDOFF — Koinobori House

Document de reprise destiné à un agent **Codex** (ou tout contributeur) reprenant le projet. À lire **entièrement** avant toute action.

> **Règle d'or n°1** : Codex doit **proposer un plan avant toute modification**. Pas de modification directe sans plan validé par Alain.
> **Règle d'or n°2** : **aucun secret dans le repo** (clé, token, password, credential, fichier fournisseur, fichier impression HD).

---

## 0. Reprise rapide (TL;DR — lire en premier)

**Repo** : `Ahlaoban/koinobori-house` — https://github.com/Ahlaoban/koinobori-house — **PRIVATE**, branche `main`. Commit initial `d4ddae7`.

Résumé de reprise (20 lignes max) :
1. Koinobori House = site e-commerce WordPress/WooCommerce (o2switch), bilingue FR/EN.
2. Vend des koinobori originaux signés BCDG. BCDG = signature créative, pas la maison.
3. B2C transactionnel (panier + paiement natif WC). B2B/B2G = captation par pages + formulaires (pas de transaction).
4. État : **Lot 0 avancé**, aucun code WP/WC encore écrit.
5. Le repo couvre **tout le projet**, pas que Lot 0 (accueillera le futur code opérationnel).
6. Tickets fermés : KH-001, 002, 003 (Stripe provisoire), 004, 005, 009, 011, 017.
7. KH-010 (catalogue/SKU) = **fermable** : 7 lignes dans `catalog/master.csv` (5 pilotes + collection Hanami parent/variation). 4 pilotes + Hanami en `export_to_wc=yes` ; Kaïro ép.3 reste `no` (test impression mockup juin 2026).
8. KH-012 shipping = décision verrouillée ; KH-015 USA = checkout auto non activé.
9. Secrets (Stripe/PayPal/Brevo) vivent **hors repo** (gestionnaire de mots de passe).
10. **Règle d'or** : proposer un plan avant toute modification ; aucun secret dans le repo.
11. Ne **jamais** mentionner la Chine publiquement, **dans aucun contexte, sans aucune exception** (arbitrage Alain 2026-09-08). Ni production, atelier, fournisseur ou fabrication, ni pays ou culture. Référent culturel unique : le Japon.
12. **Aucune mention atelier/production sur les fiches** (FR+EN, rév. 2026-06-18). « atelier partenaire » autorisé seulement *hors fiches* ; jamais « atelier chinois ».
13. Jamais de mention de stock global ; stock par SKU uniquement.
14. « US Flag » = nom fournisseur interne ; public = « Stars & Stripes Koinobori — by BCDG ».
15. EUR only, pas de multi-devise au MVP.
16. Stack figée : WordPress, WooCommerce, Kadence, Polylang, Stripe/PayPal, FluentSMTP+Brevo, LiteSpeed, **SEOPress** (remplace RankMath — incompat Polylang, décision 2026-06-13).
17. i18n : `/fr/` + `/en/`, racine `/` en 302, jamais 301 sur `/`.
18. Prochaine tâche : GO Lot 1 après review Codex + achat licence Polylang for WC ; premier ticket KH-104b.
19. Lot 1 (WordPress) = **pas sans GO Alain** ; quand GO, commencer par KH-104b (test Polylang for WC).
20. Doctrine complète et prioritaire : `CLAUDE.md`.

**Lire en premier, dans l'ordre** :
1. `README.md` (vue d'ensemble + stack + statut)
2. `docs/handoff/CODEX-HANDOFF.md` (ce fichier, en entier)
3. `CLAUDE.md` (doctrine verrouillée — source de vérité)
4. `catalog/README.md` puis `catalog/master.csv` (catalogue / SKU)
5. `docs/lot0/` (cadrages tickets, au besoin selon la tâche)

---

## 1. Nature du projet

- **Koinobori House** = site / maison e-commerce / canal commercial propriétaire.
- **BCDG** = marque / signature créative des produits.
- Site **WordPress / WooCommerce** sur **o2switch**, **bilingue FR / EN**, vente de koinobori originaux signés BCDG.
- **B2C transactionnel** (panier + paiement natif WC). **B2B / B2G** = captation qualifiée MVP (pages + formulaires, aucune transaction directe).
- Lancement cible : soft launch ~15 juillet 2026, hard launch ~29 juillet 2026.

## 2. État réel du projet (au 2026-06)

**Lot 0 avancé. Aucun code WordPress / WooCommerce encore écrit.** Le repo contient à ce stade : doctrine (`CLAUDE.md`), documentation Lot 0, catalogue CSV + images produits (`catalog/images/`), assets marque (`brand/`), ce handoff.

### Tickets fermés
- **KH-001** o2switch — hébergement opérationnel.
- **KH-002** domaines — fait / surveillance.
- **KH-003** Stripe — compte production accessible, KYC OK. Intégration WooCommerce **différée Lot 1**.
- **KH-004** PayPal — Business + banque + 2FA OK.
- **KH-005** Brevo SMTP — compte créé, domaine authentifié, DKIM/DMARC OK, clé SMTP **stockée hors repo** (prêt pour intégration FluentSMTP Lot 1).
- **KH-009** Identité marque — logo principal `brand/koinoborihouse/logo-color.png` + favicon `brand/koinoborihouse/favicon-200.png` déposés. Distinction Koinobori House (logo site) vs BCDG (signature produits).
- **KH-011** Médiateur CM2C — retenu, abonnement valide.
- **KH-017** Documents légaux FR/EN — 9 docs FR + EN prêts. **Ne pas recréer.**

### Tickets ouverts / en cours
- **KH-010** Catalogue / SKU — **fermable**. 7 lignes dans `catalog/master.csv` : 5 pilotes (Stars & Stripes + Kaïro épisodes 1–4) + collection **Hanami** (parent `KH-HAN-001` variable + variation `KH-HAN-001-075`). Images déposées sous `catalog/images/`, stock renseigné. `export_to_wc=yes` sur 4 pilotes + Hanami ; **Kaïro ép.3 reste `no`** (test impression fond noir #1A1A1A conditionné réception mockup courant juin 2026).
- **KH-012** Shipping / incentives — **décision verrouillée** (cf doc). France métropolitaine offerte dès 55 € ; DROM-COM/UE frais réels. Devis transporteurs réels en attente côté Alain.
- **KH-015** USA shipping — **tests Colissimo USA différés** (non réalisés à ce stade) ; **checkout USA NON activé**, zone désactivée WC. Mode par défaut MVP = USA « nous contacter ». Bascule « USA actif » conditionnée aux 3 tests Colissimo USA en ligne (Alain).

### Prochaine étape projet (après Lot 0)
- **Lot 1** = fondation WordPress bilingue (KH-100 à KH-115). **Bloqué par le test KH-104b** (Polylang for WooCommerce sur 10 URLs FR/EN). Lots 2-8 différés tant que KH-104b non validé.
- **Ne pas ouvrir Lot 1 sans GO explicite Alain.**

## 3. Décisions verrouillées (ne pas rouvrir sans Alain)

- Stack technique figée (cf `CLAUDE.md` §Stack). Interdits : Next.js, Supabase, Vercel, Elementor, WPML, WP Rocket, Jetpack complet.
- i18n : `/fr/` + `/en/`, racine `/` en 302 selon Accept-Language, jamais 301 sur `/`, jamais redirection `/fr/` ↔ `/en/`.
- EUR only au MVP (pas de multi-devise).
- Source de vérité ventes = WooCommerce Orders (pas de webhook Stripe custom).
- Transporteurs MVP : La Poste / Colissimo + Mondial Relay ; USA = Colissimo en ligne sous conditions ; pas d'express international par défaut.
- Tarification livraison : config manuelle WC par zone, pas de tarifs temps réel, pas de plugin API transporteur (sauf exception Lot 1 MR/Colissimo étiquettes).

## 4. Fichiers importants

| Fichier | Rôle |
|---------|------|
| `CLAUDE.md` | Contexte + doctrine complète verrouillée. **Source de vérité.** |
| `README.md` | Présentation projet + stack + statut. |
| `.gitignore` | Règle no-secrets. |
| `catalog/master.csv` | Source de vérité SKU MVP (7 lignes : 5 pilotes + Hanami parent/variation). |
| `catalog/README.md` | Conventions catalogue. |
| `brand/koinoborihouse/` | Logo + favicon Koinobori House (identité site, distincte de la signature BCDG). |
| `docs/lot0/` | Cadrages Lot 0 par ticket. |
| `docs/lot0/KH-017-documents-legaux/` | Légaux FR/EN (publics, destinés au site). |

## 5. Doctrine éditoriale impérative

- ❌ **Jamais mentionner la Chine publiquement, dans aucun contexte, sans exception** (arbitrage Alain 2026-09-08). Ni production / atelier / fournisseur / sourcing / fabrication / logistique interne, ni comme pays ou culture.
  - ⚠️ **L'exception bornée « B2G culturel » est levée** : elle autorisait la Chine sur `/fr/collectivites/` + `/en/institutions/`, elle ne s'applique plus. Référent culturel unique = le **Japon**.
- ❌ Jamais « atelier chinois », « fabriqué en France », fabrication interne prétendue.
- ❌ **Aucune mention d'atelier/production sur les fiches** (FR + EN, rév. 2026-06-18 ; remplace l'ancienne formule « atelier partenaire » bas de fiche). « atelier partenaire » / « atelier confidentiel » autorisé *uniquement hors fiches* ; jamais « atelier chinois ».
- ❌ Jamais de mention de **volume global de stock** (stock par SKU uniquement).
- ❌ « US Flag » = nom fournisseur **interne** seulement, jamais public (produit public = « Stars & Stripes Koinobori — by BCDG »).
- ✅ Wording fiche produit : description courte SANS mention atelier ; description longue en **2 blocs** (design/usage → signature BCDG). **Plus de bloc transparence/atelier** (supprimé 2026-06-18).

## 6. Règles de sécurité (no secrets in repo)

Ne **jamais** committer :
- clés Stripe / PayPal / Brevo (API ou SMTP), tokens, mots de passe, clés API ;
- `wp-config.php`, `.env*` ;
- documents bancaires, factures, attestations privées (ex CM2C) ;
- fichiers fournisseur confidentiels ;
- fichiers impression HD / BAT / CMJN / sources fournisseur ;
- captures contenant des credentials.

Le `.gitignore` applique des globs larges (`*stripe*`, `*paypal*`, `*brevo*`, `*smtp*`, `*secret*`, `*password*`, `*token*`, `*api-key*`).
**Effet de bord à connaître** : un futur *document* légitime portant ces mots (ex `docs/lot1/KH-1xx-stripe-integration.md`) sera ignoré par défaut. Si le doc est vérifié **sans secret réel**, l'ajouter via une ligne de négation `!chemin/du/doc.md` dans `.gitignore` (modèle déjà appliqué pour `docs/lot0/KH-005-brevo-smtp.md`).

## 7. Interdits (forbidden touches)

- Multi-devise (phase 2).
- Espace privé / comptes organisations B2B/B2G (phase 2).
- Upload B2B/B2G sans validation (phase 2).
- Likes, calculateur douanier maison (phase 2).
- Mentions Chine publiques (jamais).
- Mention volume global stock (jamais).
- « fabriqué en France » sans validation (jamais).
- « US Flag » public (jamais).

## 8. Ce qu'il ne faut JAMAIS modifier sans validation Alain

- `CLAUDE.md` (doctrine).
- `catalog/master.csv` (catalogue / SKU) — ne pas basculer `export_to_wc=yes` sans données complètes, ne pas ajouter de nouveaux modèles, ne pas modifier le schéma.
- Documents légaux `docs/lot0/KH-017-documents-legaux/` (fermés).
- Décisions verrouillées §3.
- Visibilité du repo (doit rester **PRIVATE**).

## 9. Prochaine tâche recommandée

1. **KH-010 fermable** : 7 lignes catalogue complétées (images + stock), `export_to_wc=yes` sur 4 pilotes + Hanami. Seul reste **Kaïro ép.3** en `no` (test impression mockup juin 2026). KH-018 a harmonisé la documentation Lot 0 (shipping France 55 €, USA « nous contacter », Brevo, retours, traductions EN, références CM2C).
2. Ne pas démarrer Lot 1 (WordPress) sans GO Alain ; quand GO, commencer par KH-104b (test bloquant Polylang for WC). Prérequis dur : licence Polylang for WooCommerce achetée.

## 10. Rappels finaux

- **Proposer un plan avant toute modification.**
- **Aucun secret dans le repo.**
- Repo **privé**, pas de licence open source, pas de fichiers fournisseur / impression HD.
- En cas de doute doctrine : `CLAUDE.md` prime.
