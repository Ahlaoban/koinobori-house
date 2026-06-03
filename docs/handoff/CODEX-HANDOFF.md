# CODEX-HANDOFF — Koinobori House

Document de reprise destiné à un agent **Codex** (ou tout contributeur) reprenant le projet. À lire **entièrement** avant toute action.

> **Règle d'or n°1** : Codex doit **proposer un plan avant toute modification**. Pas de modification directe sans plan validé par Alain.
> **Règle d'or n°2** : **aucun secret dans le repo** (clé, token, password, credential, fichier fournisseur, fichier impression HD).

---

## 1. Nature du projet

- **Koinobori House** = site / maison e-commerce / canal commercial propriétaire.
- **BCDG** = marque / signature créative des produits.
- Site **WordPress / WooCommerce** sur **o2switch**, **bilingue FR / EN**, vente de koinobori originaux signés BCDG.
- **B2C transactionnel** (panier + paiement natif WC). **B2B / B2G** = captation qualifiée MVP (pages + formulaires, aucune transaction directe).
- Lancement cible : soft launch ~15 juillet 2026, hard launch ~29 juillet 2026.

## 2. État réel du projet (au 2026-06)

**Lot 0 avancé. Aucun code WordPress / WooCommerce encore écrit.** Le repo contient à ce stade : doctrine (`CLAUDE.md`), documentation Lot 0, catalogue CSV, ce handoff.

### Tickets fermés
- **KH-001** o2switch — hébergement opérationnel.
- **KH-002** domaines — fait / surveillance.
- **KH-003** Stripe — compte production accessible, KYC OK. Intégration WooCommerce **différée Lot 1**.
- **KH-004** PayPal — Business + banque + 2FA OK.
- **KH-005** Brevo SMTP — domaine authentifié, DKIM/DMARC OK, clé SMTP **stockée hors repo**.
- **KH-011** Médiateur CM2C — retenu, abonnement valide.
- **KH-017** Documents légaux FR/EN — 9 docs FR + EN prêts. **Ne pas recréer.**

### Tickets ouverts / en cours
- **KH-010** Catalogue / SKU — **en cours avancé**. 5 SKUs pilotes dans `catalog/master.csv` (Stars & Stripes + Kaïro épisodes 1–4). Données manquantes par SKU : `main_image_path`, `stock_qty` (Kaïro), statut test impression. `export_to_wc` reste `no` tant que données incomplètes.
- **KH-012** Shipping / incentives — **décision verrouillée** (cf doc). Devis transporteurs réels en attente côté Alain.
- **KH-015** USA shipping — tests Colissimo faits ; **checkout USA automatique NON activé** tant que décision commerciale non finalisée. Mode par défaut MVP = USA « nous contacter ».

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
| `catalog/master.csv` | Source de vérité SKU MVP (5 pilotes). |
| `catalog/README.md` | Conventions catalogue. |
| `docs/lot0/` | Cadrages Lot 0 par ticket. |
| `docs/lot0/KH-017-documents-legaux/` | Légaux FR/EN (publics, destinés au site). |

## 5. Doctrine éditoriale impérative

- ❌ **Jamais** mentionner la Chine publiquement comme production / atelier / fournisseur / sourcing / fabrication / logistique interne.
  - Exception bornée : Chine comme **pays / culture** uniquement, contexte **B2G culturel** (pages `/fr/collectivites/` + `/en/institutions/`). Jamais comme lieu de production.
- ❌ Jamais « atelier chinois », « fabriqué en France », fabrication interne prétendue.
- ✅ Formulation production autorisée (bas de fiche) :
  - FR : *« Produit par un atelier partenaire selon les fichiers et spécifications BCDG. »*
  - EN : *« Produced by a partner workshop according to BCDG files and specifications. »*
- ❌ Jamais de mention de **volume global de stock** (stock par SKU uniquement).
- ❌ « US Flag » = nom fournisseur **interne** seulement, jamais public (produit public = « Stars & Stripes Koinobori — by BCDG »).
- ✅ Wording fiche produit : description courte SANS mention atelier partenaire ; description longue en 3 blocs (design/usage → signature BCDG → transparence production en bas).

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

1. **Finaliser KH-010** : compléter `main_image_path` + `stock_qty` (Kaïro) + statut test impression des 5 SKUs pilotes, puis basculer `export_to_wc=yes` SKU par SKU une fois les données complètes. Aucune création de nouveau modèle.
2. Ne pas démarrer Lot 1 (WordPress) sans GO Alain ; quand GO, commencer par KH-104b (test bloquant Polylang for WC).

## 10. Rappels finaux

- **Proposer un plan avant toute modification.**
- **Aucun secret dans le repo.**
- Repo **privé**, pas de licence open source, pas de fichiers fournisseur / impression HD.
- En cas de doute doctrine : `CLAUDE.md` prime.
