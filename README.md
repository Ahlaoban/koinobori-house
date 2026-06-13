# Koinobori House

Repository complet du projet **Koinobori House** — site e-commerce WordPress / WooCommerce destiné à la vente de koinobori originaux signés **BCDG**.

> Ce dépôt couvre **tout le projet**, pas seulement le Lot 0. Le Lot 0 est l'état actuel ; le repo accueillera aussi le futur code opérationnel (thème enfant, configuration, scripts, intégrations).

## Objectif

Construire un site e-commerce bilingue **FR / EN** hébergé sur **o2switch** :

- **B2C transactionnel** : catalogue indexable + panier + paiement Stripe / PayPal natif WooCommerce.
- **B2B / B2G** (MVP) : captation qualifiée par pages dédiées + formulaires. Aucune transaction directe, pas d'espace privé (phase 2).

## Stack cible

| Couche | Choix |
|--------|-------|
| CMS | WordPress 6.6+ |
| E-commerce | WooCommerce (dernière stable) |
| Thème | Kadence Free + thème enfant |
| Multilingue | Polylang + Polylang for WooCommerce |
| Paiement | WooCommerce Stripe + PayPal (officiels) |
| Email | FluentSMTP + Brevo |
| Cache | LiteSpeed Cache (o2switch) |
| SEO | SEOPress Free |
| Analytics | Plausible Analytics (sans cookies, RGPD-friendly) |

Stack verrouillée détaillée : voir `CLAUDE.md`.

## Statut actuel

**Lot 0 avancé.** Synthèse des tickets :

| Ticket | Sujet | Statut |
|--------|-------|--------|
| KH-001 | Hébergement o2switch | ✅ fait |
| KH-002 | Domaines | ✅ fait / surveillance |
| KH-003 | Stripe | ✅ provisoire (intégration WC différée Lot 1) |
| KH-004 | PayPal | ✅ fermé |
| KH-005 | Brevo SMTP | ✅ fermé (clé hors repo) |
| KH-010 | Catalogue / SKU | 🔄 en cours avancé |
| KH-011 | Médiateur CM2C | ✅ fermé |
| KH-012 | Shipping / incentives | 🔒 décision verrouillée |
| KH-015 | USA shipping | ⏸ différé — tests Colissimo USA non réalisés, checkout USA non activé, mode « nous contacter » |
| KH-017 | Documents légaux FR/EN | ✅ fermé (ne pas recréer) |

## Règle de sécurité — NO SECRETS IN REPO

Ce dépôt est **privé** et ne doit contenir **aucun** :

- clé Stripe / PayPal / Brevo (API ou SMTP) ;
- mot de passe, token, clé API ;
- document bancaire, facture, attestation privée ;
- fichier fournisseur confidentiel ;
- fichier d'impression HD / BAT / CMJN / source fournisseur ;
- capture contenant des credentials.

Voir `.gitignore`. Les credentials vivent dans un gestionnaire de mots de passe hors repo.

## Doctrine (rappel court)

- **Koinobori House** = maison / canal e-commerce. **BCDG** = signature créative des produits.
- Ne **jamais** mentionner publiquement la Chine comme production, atelier, fournisseur, sourcing ou fabrication.
- Formulation production autorisée (bas de fiche) : *« Produit par un atelier partenaire selon les fichiers et spécifications BCDG. »*
- Jamais de mention de stock global public.

Doctrine complète : `CLAUDE.md`.

## Structure du repo

```
.
├── CLAUDE.md                 # Contexte projet + doctrine verrouillée
├── README.md                 # Ce fichier
├── .gitignore                # Règle no-secrets
├── brand/                    # Identité marque (logos site, hors produits)
│   └── koinoborihouse/
│       ├── logo-color.png    # Logo principal couleur (PNG transparent 1172×213)
│       ├── favicon-200.png   # Favicon tampon rouge 鯉のぼり (PNG transparent 200×200)
│       └── favicon-512.png   # Favicon 512×512 (icône de site WordPress, upscale du 200)
├── catalog/                  # Catalogue produits (voir catalog/README.md)
│   ├── master.csv            # Source de vérité SKU MVP
│   ├── examples.csv
│   ├── images/               # Visuels produits e-commerce uniquement
│   └── README.md
├── wp/                       # Code WordPress versionné (déployé manuellement)
│   └── themes/
│       └── koinobori-child/  # Thème enfant Kadence (KH-103)
└── docs/
    ├── lot0/                 # Cadrages Lot 0 (tickets KH-xxx)
    │   └── KH-017-documents-legaux/   # Légaux FR/EN (publics)
    ├── lot1/                 # Docs d'exécution Lot 1 (KH-102+)
    └── handoff/
        └── CODEX-HANDOFF.md  # Reprise future par Codex
```

## Liens

- Cadrages Lot 0 : [`docs/lot0/`](docs/lot0/)
- Catalogue : [`catalog/README.md`](catalog/README.md)
- Handoff Codex : [`docs/handoff/CODEX-HANDOFF.md`](docs/handoff/CODEX-HANDOFF.md)
