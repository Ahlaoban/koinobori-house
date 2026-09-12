# KH Social Agent — dossier Phase 1

Livrables demandés par Alain le 2026-09-11 (brief §13) avant tout développement lourd.

| # | Livrable du brief | Où |
|---|---|---|
| 1 | Architecture cible | [01-architecture.md](01-architecture.md) §1-§2 |
| 2 | Diagramme des composants | [01-architecture.md](01-architecture.md) §2.1 (Mermaid) |
| 3 | Stack recommandée | [01-architecture.md](01-architecture.md) §3 |
| 4 | APIs par plateforme | [02-apis-plateformes.md](02-apis-plateformes.md) §2-§6 |
| 5 | Limites API / risques | [02-apis-plateformes.md](02-apis-plateformes.md) §1, §10, §11 |
| 6 | Schéma de données | [01-architecture.md](01-architecture.md) §4 |
| 7 | Workflow de validation | [01-architecture.md](01-architecture.md) §5 |
| 8 | Modèle de mémoire / apprentissage | [01-architecture.md](01-architecture.md) §6 |
| 9 | Backlog priorisé | [03-mvp-backlog.md](03-mvp-backlog.md) §3 |
| 10 | Estimation de complexité | [03-mvp-backlog.md](03-mvp-backlog.md) §4 |
| 11 | Définition précise du MVP | [03-mvp-backlog.md](03-mvp-backlog.md) §1 |
| 12 | Points nécessitant décision humaine | [04-decisions.md](04-decisions.md) |
| + | Audit des skills et du corpus existants (Phase 1 du brief §12) | [05-audit-existant.md](05-audit-existant.md) |

Compléments : [01-architecture.md](01-architecture.md) §7 (Doctrine Gate), §8 (attribution kh.com), §9 (interfaces écosystème), §10 (sécurité) ; [02-apis-plateformes.md](02-apis-plateformes.md) §7 (liens et attribution), §8 (agrégateurs), §9 (avatars et étiquetage IA).

Statut : 🟡 **en attente de validation d'Alain**. Aucun code écrit. Décisions bloquantes : D1, D2, D3 dans [04-decisions.md](04-decisions.md). Un point de conformité du site, indépendant de l'agent, est à traiter avant le 30/09 : D14 lot 1 (cookies d'attribution WooCommerce sans consentement).

Méthode : faits API vérifiés sur pages officielles (source-driven, URLs en fin de section) ; architecture passée en revue adversariale à contexte frais le 2026-09-12 (25 constats, 23 intégrés, 2 nuancés : budget et choix de modèles renvoyés à D11). Revue croisée par un autre modèle (ChatGPT / DeepSeek via `triangulate`) non faite : à la main d'Alain.
