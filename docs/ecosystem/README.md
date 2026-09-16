# Écosystème KH — agents autour de koinoborihouse.com

- **Ouvert le** : 2026-09-11 (brief KH Social Agent d'Alain)
- **Principe** : le site WordPress / WooCommerce reste le cœur transactionnel et la source de vérité des ventes. Les agents sont des **services séparés** (repos, hébergement, bases propres) reliés au site et entre eux par des interfaces stables : identifiants partagés, événements versionnés, APIs en lecture seule côté site.
- **Ordre** : le site se lance le **30 septembre 2026** ; aucun chantier écosystème ne consomme le chemin critique du lancement.

## Agents

| Agent | Rôle | Statut | Documentation |
|---|---|---|---|
| **KH Social Agent** | créer l'audience : contenus multi-plateformes, validation, publication, mesure, écoute, apprentissage | 🟡 Phase 1 livrée (architecture + MVP), en attente de validation | [kh-social-agent/](kh-social-agent/) |
| KH Lead Intelligence / KH Sales & Follow-up Agent | qualifier et suivre les leads B2B / B2G / Custom captés par le site et les réseaux | à cadrer (brief frère annoncé par Alain) | — |
| KH CRM, KH Projects, KH Custom | à cadrer | — | — |

## Contrats partagés

Le premier contrat à figer est l'événement **`lead.detected` v1** émis par le Social Agent et consommé par le Sales Agent (définition dans [kh-social-agent/01-architecture.md](kh-social-agent/01-architecture.md) §9). Les schémas JSON seront versionnés dans `docs/ecosystem/contracts/` au moment de l'implémentation.

Identifiants communs : `campaign_id` / `campaign.code`, `content_id`, `variant_id`, `lead_id` (`LEAD-<SOURCE>-<NNN>`), `order_id` WooCommerce, `form_entry_id` Fluent Forms.

## Doctrine commune

La doctrine éditoriale de [CLAUDE.md](../../CLAUDE.md) s'applique à **tout** agent qui produit du texte public : la Chine n'est jamais citée, aucune mention de production ou d'atelier, jamais « fabriqué en France », jamais de volume global de stock, vocabulaire livraison encadré, Kaïro sans date pour les épisodes non vendus. Le Social Agent l'implémente sous forme de « Doctrine Gate » réutilisable par les autres agents.
