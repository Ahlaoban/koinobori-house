# Réconciliation Git et tableau d'arbitrage — 2026-09-16

Rapport produit par Claude (Claude Code) à la demande d'Alain, sur revue Codex du 16/09.
Objet : réintégrer le commit `2b0f4d1` (KH Social Agent, Phase 1) dans la ligne de reprise
`codex/kh2027-reprise`, mettre CLAUDE.md en cohérence, et lister les arbitrages produit/UX
ouverts avant lancement du 30/09.

**Lecture** : les sections 1 à 11 décrivent la **préparation avant exécution** (matin et après-midi du 16/09 ; la branche `recon` qu'elles mentionnent est un brouillon depuis supprimé). **L'état final fait foi au §12** (soir du 16/09 : push FF, PR #15 fusionnée, branche `integ/kh2027-main`). Le §13 liste ce qui reste à aligner.

## 1. Sauvegardes locales (hors dépôt)

Dossier : `C:\dev\_koinobori-safety-2026-09-16\`

| Fichier | Contenu | SHA-256 |
|---|---|---|
| `codex-kh2027-reprise-45c2adc.bundle` | Bundle complet de la branche `codex/kh2027-reprise` au HEAD `45c2adc` (`git bundle verify` : OK, historique complet) | `1b076b1e…926c2f1f` |
| `kh-single-variation-display-uncommitted.patch` | Diff non committé du plugin (1.0.0 → 1.1.0, 45+/19-) | `6876729e…c592ddb` |
| `anonymize_clone.php` | Copie du fichier non suivi (17 649 octets) | `2d2e56a7…f43824` |
| `HEAD.txt` | `45c2adc7d0d7c138fe54aae6e588fe3aa25f6ad7` | — |
| `SHA256SUMS.txt` | Empreintes complètes | — |

Les deux fichiers d'origine incertaine (`kh-single-variation-display.php` modifié,
`anonymize_clone.php` non suivi) sont **laissés intacts** dans le worktree
`.worktrees/kh2027-reprise`. Ils ne sont pas repris sur la branche de réconciliation.

## 2. Branche de réconciliation `recon` (état historique — brouillon supprimé, remplacé par `integ/kh2027-main`, cf §12)

| Élément | Valeur |
|---|---|
| Worktree | `C:\dev\Koinobori\.worktrees\recon-social-agent` |
| Branche | `recon/kh2027-social-agent` (locale uniquement) |
| Base | `45c2adc` (HEAD de `codex/kh2027-reprise`) |
| Cherry-pick de `2b0f4d1` | `88b8d1e` — **auto-merge propre, aucun conflit** (le conflit CLAUDE.md attendu ne s'est pas produit : les deux modifications touchaient des zones disjointes, en-tête vs fin de fichier) |
| Nature de la réintégration | **Réintégration de contenu, pas fusion topologique.** Le commit original `2b0f4d1` ne fait **toujours pas** partie de l'ascendance de cette branche ; son contenu est porté par `88b8d1e`. `docs/kh-social-agent-architecture` et PR #15 restent non fusionnées |
| Réconciliation CLAUDE.md | `d47b8ec` (HEAD avant ajout du rapport) |
| Rapport de réconciliation | `c456a73` |
| Corrections Codex (point de contrôle 1) | voir §8 |
| HEAD actuel | voir §8 (SHA post-correction) |

Fichiers ajoutés par le cherry-pick (1 621 lignes, 8 fichiers) : `docs/ecosystem/README.md`,
`docs/ecosystem/kh-social-agent/{README,01-architecture,02-apis-plateformes,03-mvp-backlog,04-decisions,05-audit-existant}.md`, plus 5 lignes dans CLAUDE.md.

Conflits résolus : **aucun**.

## 3. CLAUDE.md — diff `main..recon/kh2027-social-agent`

Résumé des modifications (diff complet : `git diff main..recon/kh2027-social-agent -- CLAUDE.md`) :

1. **Amendement Alain 13/09 conservé** (header 5 entrées, logo textuel, icônes) — provient de `d7c6d2f` déjà publié sur `origin/codex/kh2027-reprise`.
2. **Ajout** sous l'amendement : périmètre borné au **header seul** ; Accueil joignable par le logo ; Arts de vivre reste page (C6) + mouvement 7 homepage ; **la doctrine footer O-1/O-2 n'est pas modifiée** et renvoie au tableau d'arbitrage ci-dessous.
3. **C4 et C5** : mention « ⚠️ Révisé 2026-09-13 » ajoutée, texte d'origine conservé.
4. **« Navigation MVP (7 entrées) »** : remplacé par la version 5 entrées ; l'ancienne ligne est conservée barrée et marquée **PÉRIMÉ**, avec rappel que les pages Accueil / Arts de vivre existent toujours.
5. **« la nav reste à 7 entrées »** (paragraphe Kaïro) : passé à 5, avec note de lecture du critère v2.0 §14.
6. **Section « Écosystème KH »** (de `2b0f4d1`) conservée et renforcée : *développement DIFFÉRÉ, hors périmètre 30/09, aucune ligne de code avant validation D1-D3 et site lancé*.
7. **Non touché** : doctrine footer, doctrine éditoriale, palette, catalogue, B2B/B2G, livraison.

Point signalé, non modifié : la ligne `Co-Authored-By: Claude Opus 5` (§Conventions PR / Git) est une convention figée qui ne reflète plus le modèle réellement utilisé. À mettre à jour par Alain si souhaité.

## 4. Contrôle des secrets

Périmètre : lignes ajoutées de `git diff main..HEAD` + noms de fichiers suivis.

| Contrôle | Résultat |
|---|---|
| Motifs `xkeysib-`, `sk_live_`, `sk_test_`, `pk_live_`, `whsec_`, `AKIA…`, clés privées PEM, `Bearer …`, `password=…`, `api_key=…` | **0 valeur réelle**. Seuls hits : références à la constante `FLUENTMAIL_SMTP_PASSWORD` (définie dans un fichier serveur hors webroot, permissions 0600) et un regex dans un script de scan |
| `tools/recovery/capture_mail_credentials.sh` (suivi) | Script de **saisie interactive** (stty -echo) qui écrit `/home3/…/kh2027-private/web-control/secrets/fluent-smtp-credentials.php`. Aucune valeur en dur |
| `docs/audit/2026-09-10/evidence/secret-pattern-scan.json` (suivi) | `"findings": []` sur 105 fichiers |
| Fichiers `.env`, `wp-config.php`, `*.pem`, `*.key`, `*backup_code*` suivis | Aucun |
| `.gitignore` | Présent, règle « NO SECRETS IN REPO », globs `*smtp*`, `*brevo*`, `*stripe*`, `secrets/`, `credentials*` |

Verdict : **propre**. Limite : scan sur diff et noms, pas sur l'historique des blobs.

## 5. Tableau d'arbitrage unique

Pour chaque point : état actuel (vérifié dans le code de la branche), recommandation, alternative, impact si non tranché avant le 30/09.

| # | Point | État actuel (vérifié) | Recommandation | Alternative | Impact si non tranché avant lancement |
|---|---|---|---|---|---|
| A1 | **Footer : Arts de vivre** | `inc/footer.php:43-47` — groupe « La maison » = Boutique · L'Atelier · Lifestyle & Koi. **Arts de vivre absent.** | ✅ **TRANCHÉ par Alain le 16/09** (§9) : Arts de vivre = Lifestyle. **Ne pas réintroduire** au footer ; le footer propose Lifestyle & Koi. Le code actuel est **conforme** ; CLAUDE.md O-1 mis à jour | — | — |
| A2 | **Vidéo produit : une ou plusieurs** | `kh-product-media.php` — 1 meta `_khpm_video_id` par produit, 1 poster | **Une seule** au MVP. CLAUDE.md « HORS MVP : vidéos massives ». Aucune vidéo fournie à ce jour | Galerie multi-vidéos (phase 2, nécessite meta tableau + UI admin) | Nul : aucune vidéo n'existe. Choix technique réversible, pas produit |
| A3 | **Formats vidéo / transcodage** | MP4 + WebM acceptés (`video/mp4`, `video/webm`), pas de transcodage, pas d'autoplay, pas de boucle | **Conserver.** MP4 H.264 suffit seul en pratique (support universel) ; WebM optionnel. Transcodage = hors MVP, Alain fournit un MP4 prêt | Ajouter un service externe (Cloudflare Stream, Bunny) — phase 2 | Nul tant qu'aucune vidéo n'est fournie. Si vidéo lourde fournie : poids page, à traiter au cas par cas |
| A4 | **Affiche vidéo** | Poster facultatif ; repli automatique sur l'image principale (`get_image_id()`) | **Conserver le repli.** Zéro charge éditoriale supplémentaire | Poster dédié obligatoire (bloque la publication d'une fiche sans affiche) | Nul. Choix technique |
| A5 | **Formulaires : pays libre** (IDs 7/8) | Conversion `select_country → input_text` faite **en base du clone** par `tools/recovery/prepare_enquiry_fields.php:35-36` ; `inc/enquiries.php:84-93` ne fait qu'ajouter l'autocomplete `country-name` et vider le placeholder devenu inutile | **Conserver.** Seule solution 100 % conforme à « la Chine n'est jamais citée » sans maintenir une liste ISO expurgée. Doctrine Alain 08/09 | Liste déroulante custom sans la Chine (maintenance, risque de réapparition à chaque mise à jour Fluent Forms) | **Doctrinal.** Si le staging source conserve `select_country`, « China » apparaît publiquement dans la liste → violation au lancement. À porter du clone vers le staging |
| A6 | **Formulaires : quantité min 1** | `prepare_enquiry_fields.php:43-51` — `min=1`, message FR/EN | **Conserver.** Évite les demandes vides | Aucun minimum | Nul. Technique |
| A7 | **Formulaires : date facultative, stricte si saisie** | `enquiries.php:58-73` — validation `createFromFormat`, message FR/EN | **Conserver** | Date obligatoire | Nul. Technique |
| A8 | **Formulaires : honeypot natif** | `enquiries.php:46-56` — honeypot Fluent Forms activé sur IDs 5-10, message FR/EN | **Conserver au lancement**, observer le spam 30 j | reCAPTCHA / Turnstile (ajoute un tiers + bandeau cookies) | Faible. Si spam massif post-lancement : ajouter Turnstile en 1 h |
| A9 | **Emails : délai « 1 à 2 jours ouvrés » + textes FR/EN** | **Clone privé** : 12 modèles (6 équipe + 6 accusés, `enquiry_notification_drafts.php:35,41`), **tous désactivés**, état vérifié. **Staging source** : notifications documentées le 07/09 mais **non recettées**, état actuel **incertain**. Aucun import ni activation ne doit écraser le staging avant inventaire, sauvegarde et comparaison clone/staging | **Alain valide les 12 textes** (lecture ~10 min) puis activation. Le délai engage la marque : 1-2 j ouvrés tenable en solo ? Sinon « sous 3 jours ouvrés » | Activer sans relecture (risque wording) ; ou accusé sans délai chiffré | **Bloquant fonctionnel** : formulaires en ligne sans notification = leads perdus silencieusement. À trancher avant le 30/09 |
| A10 | **Menus : vrais menus 5 entrées ou fallback** | `header.php:13,30,55-57` — emplacement `kh_header` enregistré, fallback calculé par le code ; menus 144/145 (7 entrées) intacts en base, non affectés | **Créer les vrais menus FR/EN 5 entrées** dans WP et les affecter à `kh_header` avant déploiement staging. Le fallback masque toute erreur de menu et n'est pas éditable par Alain | Garder le fallback au lancement (fonctionne, mais dépend du code) | Moyen : header fonctionne via fallback, mais Alain ne peut pas modifier la nav sans développeur. Les menus 144/145 à 7 entrées restent affectés aux anciens emplacements Kadence (à vérifier qu'ils ne s'affichent pas ailleurs) |

## 6. Classement des décisions

### 6.1 Validées par Alain (tracées)

- Header 5 rubriques, sans Accueil ni Arts de vivre (13/09, `d7c6d2f`).
- Logo textuel `Koinobori` / `House` séparés, sous-titre `BY BCDG` en fondu.
- Menu par icônes (série A, Contact de B, Professionnels de C), label au survol, infobulle unique.
- Animation d'ouverture à la première arrivée seulement, puis survol.
- Installation FluentSMTP 2.4.0 sur le clone privé ; autorisation des envois de test.
- Publication jusqu'à `12662b4` sur `origin/codex/kh2027-reprise` (PR 14).
- Chine jamais citée, exception B2G levée (08/09).

### 6.2 Choix techniques réversibles, sans arbitrage produit requis

- Clone privé assaini, garde MU, WP-Cron / REST / achats / réseau bloqués, 6 plugins exécutés.
- Gabarit `kh-home.php` forcé sur 318/319 à l'exécution (prévisualisation).
- Correction `polylang.redirect_lang = true` (à porter sur staging).
- 1 vidéo / produit, MP4/WebM, pas d'autoplay ni boucle, poster facultatif avec repli (A2-A4).
- Quantité min 1, date stricte si saisie, honeypot natif (A6-A8).
- Identifiants SMTP hors base et hors dépôt, Brevo 587/TLS, expéditeur `contact@koinoborihouse.com`.
- 12 notifications désactivées après tests.
- `sessionStorage` pour l'animation unique par onglet.

### 6.3 Décisions produit / UX encore ouvertes (arbitrage Alain)

- ~~**A1** Footer~~ — tranché le 16/09 (§9).
- **A11** (nouveau, découle de §9) Pages `arts-de-vivre` 282/291 : composition du mouvement homepage consolidé ; mécanisme de redirection (`.htaccess` / mu-plugin / SEOPress Pro — CLAUDE.md note « gestionnaire de redirections = SEOPress Pro/.htaccess si besoin, non requis MVP » : ce besoin apparaît) ; création ou non d'une catégorie d'articles « Arts de vivre » dans Lifestyle.
- **A5** Pays en texte libre : entériner comme règle doctrinale (et porter sur staging).
- **A9** Textes des 12 emails + délai annoncé ; activation des notifications.
- **A10** Création et affectation des vrais menus 5 entrées.
- Footer « La maison » : libellé du groupe (CLAUDE.md dit « Navigation »).
- Conformité D. 221-5 des formulaires de rétractation 11/12 (non démontrée).
- Rétention WooCommerce (champs vides vs politique « 3 ans »).

## 7. Prochaines étapes proposées (aucune exécutée)

1. Alain tranche A1, A5, A9, A10 (les autres = confirmation tacite acceptable).
2. Autorisation explicite de pousser `recon/kh2027-social-agent` (ou de pousser les 12 commits sur `codex/kh2027-reprise` puis rebaser recon dessus).
3. Ouverture / mise à jour de la PR vers `main` avec `/code-review` avant merge (exigence Alain).
4. Décision sur les deux fichiers d'origine incertaine (patch plugin 1.1.0, `anonymize_clone.php`) : adopter, ou écarter.
5. Sauvegarde staging source puis portage clone → staging (Polylang, formulaires, thème, menus).

## 8. Corrections demandées par Codex au point de contrôle 1 (2026-09-16)

Commit documentaire unique, aucun code fonctionnel touché, rien poussé ni mergé :

1. §2 : `2b0f4d1` réintégré par cherry-pick sous `88b8d1e` = réintégration de contenu, pas fusion topologique ; le commit original reste hors ascendance.
2. §2 : tableau corrigé — `d47b8ec` = HEAD avant rapport, `c456a73` = rapport, HEAD actuel = le commit `docs(handoff): corrections Codex, point de contrôle 1` (SHA communiqué dans la passation).
3. CLAUDE.md §Catalogue MVP : « une entrée de navigation » remplacé par « page éditoriale accessible depuis la homepage et, sous réserve de l'arbitrage A1, depuis le footer ; absente du header ».
4. A5 : conversion `select_country → input_text` attribuée à `tools/recovery/prepare_enquiry_fields.php:35-36` ; `inc/enquiries.php` = autocomplete + placeholder seulement.
5. A9 : clone privé (12 modèles désactivés, vérifié) distingué du staging source (documenté 07/09, non recetté, état incertain) ; interdiction d'import/activation écrasant le staging avant inventaire, sauvegarde et comparaison.

## 9. Arbitrage Alain du 16/09 — « Arts de vivre » = « Lifestyle »

Texte de la décision (verbatim, transmis par Alain le 2026-09-16) :

> « Arts de vivre » et « Lifestyle » ne sont pas deux rubriques distinctes. Lifestyle est l'espace éditorial qui accueillera mes futurs articles, notamment ceux relevant de l'art de vivre. En conséquence : le header conserve uniquement Lifestyle ; Arts de vivre ne doit pas être réintroduit dans le footer ; le footer doit proposer Lifestyle & Koi ; la destination canonique est `/fr/lifestyle/` et `/en/lifestyle-koi/` ; les anciennes pages `/fr/arts-de-vivre/` et `/en/art-de-vivre/` doivent être auditées avant toute action ; leur contenu utile doit être transféré vers Lifestyle ou une autre page pertinente ; elles devront ensuite rediriger vers les pages Lifestyle correspondantes ; aucune suppression sans sauvegarde ; les mouvements distincts « Lifestyle & Koi » et « Arts de vivre » de la homepage doivent être consolidés ; « Arts de vivre » pourra devenir un thème ou une catégorie d'articles, mais pas une rubrique principale autonome. Cet arbitrage remplace C6 et les anciennes prescriptions qui imposaient Arts de vivre comme page et lien séparés.

Intégration dans CLAUDE.md (ce commit) : paragraphe d'arbitrage en tête de fichier ; C6 barré et renvoyé ; ligne « PÉRIMÉ » nav 7 entrées ; footer O-1 (Arts de vivre retiré) ; homepage 11 mouvements (6 et 7 à consolider, composition non tranchée) ; §Catalogue MVP (thème/catégorie d'articles, jamais collection WooCommerce). Le code footer actuel devient **conforme** sans modification.

Suites à planifier, **aucune exécutée** (points de contrôle 2 et 4) :

| Étape | Environnement | Prérequis |
|---|---|---|
| Audit des pages 282 (FR) et 291 (EN) : contenu, méta SEO, liens entrants internes, appariement Polylang, présence dans `kh-home.php` et docs `lot3` | staging source | accès lecture |
| Transfert du contenu utile vers 284/295 (Lifestyle) ou autre page | staging source | sauvegarde, validation éditoriale Alain |
| Redirection 301 282→284, 291→295 | staging puis prod | choix du mécanisme (A11) ; jamais de redirection `/fr/`↔`/en/` (doctrine i18n : ici même langue, conforme) |
| Passage des pages en brouillon ou corbeille, jamais suppression définitive sans export | staging source | sauvegarde vérifiée |
| Consolidation mouvements 6/7 dans `kh-home.php` et `docs/lot3/homepage-v2-11-mouvements-FR-EN.md` | code + doc | composition tranchée par Alain |
| Mise à jour `docs/ux-architecture.md` §3.1 / §8, `docs/lot3/pages-professionnels-arts-de-vivre-FR-EN.md` | doc | — |

## 10. Graphe Git proposé — S2 modifiée (Codex, point de contrôle 1) — état historique, exécuté au §12

Aucune commande exécutée. État visé après autorisations Alain :

```
main        d3073da ─────────────────────── M15 (merge PR #15 = 2b0f4d1)
              │                                       │
              │                                       │  (4) git merge main
              ▼                                       ▼
codex/      d3073da ─ … ─ 12662b4 ─ 3e2d3ef ─ … ─ 45c2adc
kh2027-               (origin, PR #14)  (12 locaux, push FF en B)   \
reprise                                                              \
                                                                      ▼
integ/      (3) créée depuis 45c2adc ──────────────────────────── M (merge main) ── R (docs réconciliation rejoués)
kh2027-main

recon/      45c2adc ─ 88b8d1e ─ d47b8ec ─ c456a73 ─ e8148d9 ─ <SHA §8>   → gelée, supprimée après vérification (6)
kh2027-
social-agent
```

Séquence (numéros = message Codex) :

1. Alain merge **PR #15** dans `main` (docs seules) → `M15`.
2. `codex/kh2027-reprise` : historique inchangé.
3. `git worktree add ../integ -b integ/kh2027-main 45c2adc`.
4. `git merge main` dans `integ` → commit de fusion `M`. Conflit attendu : **aucun** (démontré par l'auto-merge du cherry-pick sur les mêmes zones).
5. Rejouer les docs de réconciliation : `git cherry-pick d47b8ec c456a73 e8148d9 <SHA §8>` **ou** un seul commit squashé `docs: réconciliation CLAUDE.md et rapport 16/09` — préférence Claude : **squash en un commit R**, historique plus lisible ; Codex tranche.
6. Vérifications, résultats exigés :
   - `git merge-base --is-ancestor 2b0f4d1 integ/kh2027-main` → exit 0.
   - `git log --format=%H origin/codex/kh2027-reprise..45c2adc` identique avant/après → 12 SHA inchangés ; `git log --format=%H d3073da..12662b4` → 20 SHA inchangés.
   - `git log --oneline integ/kh2027-main | grep 88b8d1e` → vide.
   - `git diff main integ/kh2027-main -- docs/ecosystem` → vide (aucune duplication).
7. `integ/kh2027-main` = base de la suite.
8. `codex/kh2027-reprise` gelée (tag `archive/codex-kh2027-reprise-2026-09-16` proposé).

Publication scindée, conforme au message Codex :

- **A** PR #15 (Alain).
- **B** `git push origin codex/kh2027-reprise` (fast-forward `12662b4`→`45c2adc`, 12 commits, après autorisation Alain + revue Codex, **jamais `--force`**).
- **C** push `integ/kh2027-main`, ouverture d'une PR dédiée « docs: réconciliation 16/09 ». Proposition d'ordre de merge : #15 → #14 (squash, après `/code-review`) → PR docs ; ainsi la PR docs ne porte que R.

## 11. Revue ligne à ligne des deux fichiers d'origine incertaine (Claude, 16/09)

Contre-revue Codex attendue. Aucun des deux n'est adopté ni supprimé ; copies dans `C:\dev\_koinobori-safety-2026-09-16\`.

### 11.1 `kh-single-variation-display.php` 1.0.0 → 1.1.0 (patch 45+/19-)

Fonctionnel :
- Restreint le composant à l'attribut Taille (`pa_taille` / `taille` via `sanitize_title`). Un autre attribut mono-valeur garde le select natif. Cohérent avec la doctrine catalogue (attribut global `Taille`, seul attribut variable).
- Wrapper `.kh-size-control` ajouté ; JS refactoré en `applyForm()` : si WooCommerce retire l'option du DOM, le select natif est réaffiché et le libellé masqué (protection contre un état incohérent).
- **Corrige un bug réel** : le lien « Effacer » (`reset_data`) vidait le select masqué → bouton « Ajouter au panier » désactivé sans moyen visible de le réactiver. Le handler resélectionne après reset (`setTimeout 0`).
- `.kh-all-attributes-single` remplace `.kh-single-size` : le lien Effacer n'est masqué que si **tous** les attributs sont mono-valeur.

Sûreté :
- Aucune nouvelle sortie non échappée (`esc_html` conservé). Pas d'écriture base, pas de requête, pas de nouveau hook serveur. Pas de boucle : `trigger('change')` déclenche `check_variations`, pas `reset_data`.
- Risque résiduel : dépend de jQuery et du markup WooCommerce (`.variations select`, `reset_variations`) — identique à 1.0.0.

Avis Claude : **utile et sûr**. Recommandation : adopter dans un commit dédié `fix(plugin): restreint l'affichage taille unique à Taille et gère le reset (1.1.0)` avec mention « origine non tracée, relu Claude + Codex », après test sur staging : produit mono-taille (Sakura 75 cm) — affichage texte, panier actif, clic Effacer, panier toujours actif ; produit bi-taille (Vague Bleue 50/75) — select natif intact.

### 11.2 `tools/recovery/anonymize_clone.php` (17 649 octets, non suivi)

Nature : script WP-CLI `eval-file` d'anonymisation d'un clone (users, usermeta, comments, options, commandes HPOS et legacy, tokens de paiement, clés API, webhooks, sessions, journaux, Fluent Forms, Wordfence).

Sûreté :
- **Ligne 19 : `throw new RuntimeException('Unreviewed anonymisation draft: execution disabled.')`** — tout le reste est inatteignable. Auto-déclaré brouillon.
- Gardes en aval (si réactivé) : `KH2027_SANDBOX`, `wp_get_environment_type() === 'local'`, hôte se terminant par `.invalid`, nom de base explicite par env `KH2027_ANONYMIZE_DB`, sonde réseau bloquée, mail bloqué, confirmation `delete-personal-data-from-test-clone`, InnoDB obligatoire, transaction + rollback, vérification post-exécution. Conception défensive correcte.
- **Défaut réel** (signalé par le commentaire l. 17-18) : `$order_meta_keys = REGEXP 'billing|shipping|email|phone|…'` appliqué à **tout `postmeta`** — effacerait aussi des métas produits ou plugins contenant ces sous-chaînes. Trop large pour un usage réel.
- Données sensibles : **aucune**. Pas de mot de passe, clé, nom de base, chemin serveur ni email réel (`example.invalid` uniquement). Contraste avec `sanitize_clone_db.php` (committé) qui embarque `KH_DB = 'sc3heal3867_kh2027drill'` et `KH_ROOT = '/home3/sc3heal3867/kh2027-private'` — identifiants d'infrastructure, pas des secrets, mais déjà publics sur PR #14 ; à noter.
- Redondance : `sanitize_clone_db.php` (mysqli, sans bootstrap WP, plan/rehearse/apply) couvre le même besoin par une autre voie. Deux outils parallèles = confusion.

Avis Claude : **sûr en l'état (désactivé), sans donnée sensible, non utile** tant que `sanitize_clone_db.php` existe. Recommandation : **ne pas adopter** dans `tools/recovery/` ; soit archiver comme pièce dans `docs/audit/…/evidence/` avec suffixe `.draft.php.txt`, soit écarter (copie de sûreté déjà faite). Décision Alain.

## 12. Clôture du point de contrôle 1 (2026-09-16, soir)

Décisions Alain : PR #15 **GO** · push FF **GO** · `anonymize_clone.php` conservé en archive locale puis écarté · accès staging = SSH/WP-CLI limité au staging, à défaut session cPanel ouverte par Alain.

Actions exécutées, dans l'ordre, environnement **Git / GitHub uniquement** (aucun WordPress touché) :

| # | Action | Résultat |
|---|---|---|
| 1 | Tag annoté local `archive/codex-kh2027-reprise-2026-09-16` → `45c2adc` | posé, **non poussé** |
| 2 | `git push origin codex/kh2027-reprise` | fast-forward `12662b4..45c2adc`, 12 commits, sans `--force` ; PR #14 mise à jour |
| 3 | `anonymize_clone.php` : SHA-256 recontrôlé identique à l'archive (`2d2e56a7…f43824`), puis retiré du worktree `kh2027-reprise` | fait ; le patch plugin 1.1.0 reste non committé dans ce worktree, en attente des tests §11.1 |
| 4 | `/code-review low 15` (exigence Alain avant tout merge) | 0 finding, docs seules |
| 5 | Merge **PR #15** en squash (convention CLAUDE.md) | `main` = `23fc05b` |
| 6 | Worktree `.worktrees/integ-kh2027-main`, branche `integ/kh2027-main` depuis `45c2adc` | créée |
| 7 | `git merge --no-ff main` | `2120dfb`, auto-merge CLAUDE.md, 0 conflit, `git diff main HEAD -- docs/ecosystem` vide |
| 8 | Commit **R** unique : CLAUDE.md final + ce rapport (état documentaire final, aucun commit intermédiaire rejoué) | SHA dans la passation |

Conséquence de la convention squash : `2b0f4d1` n'est **pas** dans l'ascendance de `main` ; c'est `23fc05b` qui porte son contenu. La vérification (6) du §10 est donc adaptée : `git merge-base --is-ancestor 23fc05b integ/kh2027-main` et `git diff main integ/kh2027-main -- docs/ecosystem` vide. `88b8d1e` n'est pas repris ; `recon/kh2027-social-agent` et son worktree sont supprimés après vérification (le tag §12.1 et le bundle conservent l'historique de `codex/kh2027-reprise`, pas celui de `recon`, qui n'était qu'un brouillon de ce commit R).

Nettoyage CLAUDE.md demandé par Codex et appliqué dans R : plus de texte barré ; nav 7 entrées mentionnée comme abrogée avec renvoi Git/rapport ; footer normatif = Boutique · L'Atelier · Lifestyle & Koi ; homepage annoncée à **10 mouvements**, composition du mouvement consolidé **à valider** ; palette `--kh-indigo` « Lifestyle » au lieu de « Arts de vivre » ; « Newsletter au mouvement 9 ».

Point d'infrastructure à trancher avant l'accès staging : d'après KH-100/100b, staging et production vivent dans le **même compte cPanel o2switch** (`heal3867`, DB `wp354` prod / `wp320` staging, 46 domaines, isolation off). Un compte SSH « limité au staging » n'existe donc pas nativement : tout SSH cPanel voit la prod. Proposition Claude : SSH par clé (clé publique fournie par Claude, jamais de mot de passe) + **garde procédurale** — chaque script versionné dans `tools/` refuse d'agir si `DB_NAME`/chemin ≠ staging, journal des commandes horodaté commité dans `docs/audit/`. Contre-avis Codex attendu au point de contrôle 2.

## 13. Documents et code restant à réconcilier (inventaire, aucune modification)

| Cible | Occurrences à traiter | Nature |
|---|---|---|
| `docs/ux-architecture.md` | l.15 A-1 « Nav v2.0 à plat, 7 entrées » ; l.32 A-8 « 11 mouvements » ; l.60-62 §3.1 liste 7 entrées avec Accueil et Arts de vivre ; l.96 §4 « 11 mouvements » ; l.223 O-10 « 7 entrées » ; §8 footer (Arts de vivre) | prescriptions actives → mettre à jour (5 entrées, 10 mouvements, footer sans Arts de vivre) sans réécrire les journaux d'arbitrage |
| `docs/lot3/homepage-v2-11-mouvements-FR-EN.md` | titre + mouvement 7 | contenu actif → consolidation à rédiger après validation Alain de la composition |
| `docs/lot3/pages-professionnels-arts-de-vivre-FR-EN.md` | textes FR/EN de la page Arts de vivre | contenu à transférer vers Lifestyle (§9) |
| `docs/lot3/REPRISE-EDITORIALE-2026-09-12.md`, `staging-inventaire-2026-09-07.md`, `homepage-v1-FR-EN.md` | mentions | journaux historiques → **conserver tels quels** |
| `docs/development/header-et-medias.md` | mention | vérifier, ajuster si prescriptif |
| `wp/themes/koinobori-child/page-templates/kh-home.php` l.83-87 | section « Arts de vivre / Bientôt » + lien `$page_url('arts-de-vivre')` | code → consolidation dans la section Lifestyle & Koi (l.74-81) après validation ; le lien vers 282/291 disparaît |
| `wp/themes/koinobori-child/inc/footer.php` | — | **conforme**, rien à faire |
| A11 redirection 282→284, 291→295 | à développer **après** audit et transfert du contenu | mu-plugin versionné, `wp_safe_redirect( …, 301 )`, actif même page en brouillon, FR et EN séparés, garde anti-boucle, testé avec et sans barre finale (préférence Codex, partagée) |
