# KH Social Agent — APIs par plateforme, limites, risques

- **Date de vérification** : 2026-09-11 et 2026-09-12, sur pages officielles (developers.facebook.com, developers.tiktok.com, developers.pinterest.com, developers.google.com, learn.microsoft.com/linkedin, sites des fournisseurs). Les pages consultées sont listées en fin de chaque section.
- **Légende** : ✅ possible pour un compte propre sans review · 🟠 possible après review / audit / approbation · ❌ impossible via API officielle · **UNVERIFIED** = non confirmé sur une page officielle lisible ce jour.
- **Contexte** : outil interne mono-marque ; les comptes appartiennent à Koinobori House ; aucun usage pour des tiers.
- **Instruction d'Alain** : ne pas fabriquer les connecteurs à l'aveugle ; distinguer ce qui est possible aujourd'hui, ce qui exige une revue d'application, ce qui n'est pas automatisable proprement.

---

## 1. Synthèse

### 1.1 Matrice capacités × plateforme (connecteurs directs, compte propre)

| Capacité | Instagram | TikTok | Pinterest | YouTube (Shorts) | LinkedIn profil | LinkedIn page |
|---|---|---|---|---|---|---|
| Publier image | ✅ | 🟠 photo post ≤ 35 images, `PULL_FROM_URL` + domaine vérifié ; privé sans audit | 🟠 Standard (Trial = pin privé) | n/a | ✅ | 🟠 |
| Publier carrousel | ✅ (jusqu'à 10) | 🟠 (idem) | 🟠 (2-5 images) | n/a | UNVERIFIED (multi-images, §6) | 🟠 |
| Publier vidéo courte | ✅ Reels | 🟠 **`SELF_ONLY` forcé sans audit** ; audit « several days to two weeks » + UX imposée | 🟠 (couverture obligatoire) | 🟠 public seulement après audit YouTube ; privé sinon | ✅ | 🟠 |
| Stories | ✅ (compte Business) | ❌ | ❌ | ❌ | ❌ | ❌ |
| Planifier via API | ❌ (côté agent) | ❌ | ❌ | ✅ `publishAt` (vidéo privée) | ❌ | ❌ |
| Insights par publication | ✅ | compteurs (Display API, scopes pré-approuvés) ; insights profonds = **Accounts API** (2ᵉ dossier d'accès) | ✅ (90 jours de lookback, latence ≤ 48 h) | ✅ (Analytics API, latence 48-72 h) | 🟠 (Community Management) | 🟠 |
| Lire ses commentaires | ✅ (polling ; webhooks = Advanced Access) | 🟠 **Accounts API seulement** (`/business/comment/*`, webhook `comment.update`) | ❌ (compteurs lifetime seulement) | ✅ | ❌ (permission fermée) | 🟠 |
| Répondre / masquer | ✅ | 🟠 Accounts API | ❌ | ✅ répondre, modérer | UNVERIFIED | 🟠 |
| Liker / suivre / reposter des tiers | ❌ (repost) ; like = Facebook Login + permission dédiée, **exclu par choix** | ❌ (hors liker ses propres commentaires) ; ToS interdisent l'interaction automatisée | repin et follow possibles, **exclus par choix** | techniquement possible, **interdit sans action explicite de l'utilisateur** (Developer Policies III.I.2) | 🟠, **interdit en automatisé** (User Agreement §8.2.13) | idem |
| Champ « contenu IA » | ✅ `is_ai_generated` | ✅ `is_aigc` (v2) / `is_ai_generated` (Business), irréversible | ✅ `ai_disclosures` (`AI_MODIFIED`, `SYNTHETIC_PERFORMER`) | ✅ `containsSyntheticMedia` | ❌ (C2PA seulement) | ❌ |
| Lien cliquable | bio seulement (sticker Stories non exposé par l'API) | bio seulement | ✅ `link` par pin | **non cliquable dans les Shorts** (description et commentaires) ; lien de chaîne | ✅ dans le post | ✅ |
| Review requise pour un compte propre | **Non** (Standard Access) | **Oui** : audit Content Posting (public) + formulaire Accounts API (commentaires, insights) ; scopes à pré-approuver | **Oui** (Standard access, vidéo de démonstration, « a few days ») | **Oui** (audit YouTube pour sortir du privé) + passage « In production » OAuth | Non (Share on LinkedIn) mais ré-auth tous les 60 j | Oui (Community Management) |

### 1.2 Conclusions pour l'architecture

1. **Un connecteur Instagram direct est réalisable sans App Review** pour le compte de la marque (Standard Access, utilisateurs = rôles sur l'app), mais **sans webhooks commentaires** (Advanced Access + Business Verification) : lecture par polling, budget d'appels proportionnel aux impressions.
2. **YouTube et TikTok directs exigent un audit** avant toute vidéo publique via API (YouTube : verrou privé, délai non publié, projet OAuth « In production » sinon tokens de 7 jours ; TikTok : `SELF_ONLY` forcé, audit « several days to two weeks » avec UX imposée, plus un second dossier Accounts API pour les commentaires et les insights). **Pinterest** exige l'accès Standard avec vidéo de démonstration même pour un usage propre.
3. **LinkedIn profil** : publication self-serve, **zéro lecture** (ni stats ni commentaires), ré-authentification humaine tous les 60 jours. La page entreprise passe par Community Management ou par un agrégateur.
4. **Les agrégateurs** (§8) portent les reviews à la place de la marque et couvrent les 5 plateformes pour ~18-25 €/mois ; c'est la voie du MVP ([04-decisions.md](04-decisions.md) D1), derrière une interface `Publisher` qui garde la porte ouverte aux connecteurs directs.
5. **Engagement sortant automatisé** (likes, follows, reposts) : exclu par choix et par les politiques des plateformes. L'agent lit et répond, l'humain like.
6. **Étiquetage IA** : Instagram, TikTok et YouTube exposent un champ natif ; Pinterest et LinkedIn s'appuient sur les métadonnées C2PA/IPTC. Le Media Engine doit conserver les métadonnées et le texte de divulgation (§9).
7. **Liens** : seuls Pinterest, LinkedIn et YouTube (vidéos longues) offrent un lien cliquable par publication. Instagram, TikTok et les Shorts imposent la bio → lien de bio traçable + page « lien en bio » (§7).

---

## 2. Instagram (Meta) et Threads

Version Graph API courante : v26.0 (juillet 2026). Deux voies : **Instagram API with Instagram Login** (sans Page Facebook, hôte `graph.instagram.com`) et **Instagram API with Facebook Login** (Page Facebook liée, `graph.facebook.com`).

### 2.1 Publication

| Format | Statut | Spécifications et limites |
|---|---|---|
| Image | ✅ | JPEG uniquement, ≤ 8 MB, ratio 4:5 à 1.91:1, largeur 320-1440 px, `alt_text` ≤ 1000 car. Hébergement : URL publique obligatoire au moment de la publication |
| Carrousel | ✅ | jusqu'à 10 éléments image/vidéo mixés, compte pour 1 publication, pas de Reels dedans, pas d'`is_ai_generated` sur les enfants |
| Reels | ✅ | MOV/MP4, H.264/HEVC, AAC, 3 s à 15 min, ≤ 300 MB, ≤ 1920 px, 23-60 fps, `cover_url` / `thumb_offset`, `share_to_feed`, caption ≤ 2200 car., ≤ 30 hashtags, ≤ 20 @ ; upload resumable disponible |
| Stories | ✅ compte **Business** | image ou vidéo 3-60 s ≤ 100 MB ; pas de caption, pas de collaborators ; Creator via Instagram Login **UNVERIFIED** |
| Stickers (lien, sondage, lieu), musique licenciée, filtres | ❌ | « Publishing stickers (i.e., link, poll, location) is not supported » |
| `collaborators`, `user_tags`, `location_id` | ✅ Facebook Login ; Instagram Login UNVERIFIED | |
| `product_tags` | Facebook Login + catalogue ; ❌ Instagram Login | |
| Suppression d'un média | ✅ (Facebook Login, `instagram_manage_contents`, depuis 12/2025) | Instagram Login UNVERIFIED |

Flux : `POST /<IG_ID>/media` (container) → interroger `status_code` (`IN_PROGRESS`, `FINISHED`, `ERROR`, `EXPIRED`) une fois par minute pendant 5 min max → `POST /<IG_ID>/media_publish`. Containers expirés après 24 h, **400 containers max / 24 h**. Champ `copyright_check_status`.

**Quota de publication** : les pages officielles divergent (100 publications API / 24 h glissantes dans le guide Content Publishing, 50 dans les références `content_publishing_limit` et `media_publish`). **Le plafond de 25 n'est plus documenté.** Lire la valeur effective via `GET /<IG_ID>/content_publishing_limit?fields=quota_usage,config`. Stories comptées dans ce quota : UNVERIFIED.

**Planification** : aucune. L'agent planifie et publie au slot.

### 2.2 Accès, review, tokens

- **Standard Access suffit pour un compte propre** : « If your app only serves your Instagram professional account or an account you manage, Standard Access is all your app needs ». App Review et Business Verification ne sont pas requis quand tous les utilisateurs ont un rôle sur l'app. App en mode Développement, type **Business**, testeurs avec rôle sur l'app **et** sur le compte Instagram professionnel.
- Permissions (`instagram_business_basic`, `_content_publish`, `_manage_comments`, `_manage_insights`, `_manage_messages`) : même règle ; Advanced Access (review + vérification d'entreprise) dès que l'app sert des comptes non possédés.
- **Exception pratique** : les **webhooks `comments` / `live_comments` exigent Advanced Access** ; `mentions` et `messages` exigent une app Live (Advanced non exigé explicitement, UNVERIFIED). Sans webhooks : polling.
- Tokens Instagram Login : court ≈ 1 h → long 60 jours (`ig_exchange_token`, côté serveur) → refresh (`ig_refresh_token`) possible si le token a ≥ 24 h et n'est pas expiré. Planifier le refresh avant J+60. Permission inutilisée 90 jours → nouvelle autorisation. Facebook Login : Page tokens sans expiration.

### 2.3 Insights

- Médias FEED : `comments, follows, likes, profile_activity, profile_visits, reach, reposts, saved, shares, total_interactions, views` ; REELS + `ig_reels_avg_watch_time, ig_reels_video_view_total_time, reels_skip_rate, crossposted_views` ; STORY : `reach, views, replies, navigation, profile_activity, profile_visits, follows, shares, reposts, link_clicks (06/2026), total_interactions`. Pas d'insights sur les enfants de carrousel. Erreur si < 5 vues.
- **Dépréciés** : `impressions`, `plays`, `clips_replays_count`, `ig_reels_aggregated_all_plays_count` (supprimés 21/04/2025), `video_views` (01/2025). Le Social Agent normalise sur `views` et `reach`.
- Compte : `accounts_engaged, comments, likes, reach, replies, reposts, saves, shares, total_interactions, views, profile_links_taps, follows_and_unfollows, follower_demographics, engaged_audience_demographics` ; `profile_views`, `website_clicks` et les clics de contact ont été supprimés (01/2025). Démographie et `follower_count` exigent ≥ 100 abonnés.
- Rétention : médias jusqu'à 2 ans, compte jusqu'à 90 jours, délai ≤ 48 h, stories 24 h. D'où les snapshots stockés côté agent.

### 2.4 Commentaires, mentions, engagement

- ✅ Lire (`GET /<MEDIA>/comments`, 50 par page), répondre (`POST /<COMMENT>/replies`, pas de réponse à une réponse), masquer, supprimer, désactiver les commentaires.
- Liker un média ou un commentaire : Facebook Login + `instagram_manage_engagement` (04/2026) ; page de référence en erreur ce jour, portée UNVERIFIED. **Exclu par choix.**
- Commenter les posts d'autres comptes : seulement si la marque est @mentionnée (`/mentions`). Reposter : ❌.
- BUC rate limit Instagram : appels / 24 h = 4800 × nombre d'impressions du compte sur 24 h. **Un compte à faible portée a peu d'appels** ; le polling des commentaires doit rester parcimonieux (toutes les 1-2 h, sur les 20 derniers médias).

### 2.5 Étiquetage IA

Paramètre `is_ai_generated` (depuis 22/06/2026) : « self-disclosure of AI usage in the post », applique le label « AI info ». Politique Meta : divulgation **obligatoire** pour vidéo photoréaliste ou audio réaliste créés ou modifiés numériquement, sanctions possibles ; labels automatiques via C2PA / IPTC.

### 2.6 Threads

Publication texte (≤ 500 car.), image, vidéo (≤ 5 min), carrousel 2-20, réponses, reposts, insights média et compte, quotas 250 posts / 1000 réponses / 24 h, tokens 1 h → 60 j → refresh. App Review seulement pour des utilisateurs sans rôle. **Hors MVP** (D18).

### 2.7 Pièges

Refresh impossible avant 24 h ou après expiration ; quota 50 ou 100 à lire sur l'endpoint ; 400 containers / 24 h ; vidéo `ERROR` / `EXPIRED`, contrôle copyright, moov atom en tête, URL joignable jusqu'au `PUBLISHED` ; versions maintenues ≥ 2 ans (v22.0 → 05/2027) ; compte public requis pour les webhooks ; Stories = Business.

### 2.8 Sources (consultées le 2026-09-11)

- https://developers.facebook.com/docs/instagram-platform/overview — Overview (access levels, App Review, BV, rate limits)
- https://developers.facebook.com/docs/instagram-platform/instagram-api-with-instagram-login — Instagram API with Instagram Login
- https://developers.facebook.com/docs/instagram-platform/instagram-api-with-facebook-login — Instagram API with Facebook Login
- https://developers.facebook.com/docs/instagram-platform/content-publishing — Content Publishing
- https://developers.facebook.com/docs/instagram-platform/instagram-graph-api/reference/ig-user/media — IG User Media (containers, specs)
- https://developers.facebook.com/docs/instagram-platform/instagram-graph-api/reference/ig-user/content_publishing_limit — Content Publishing Limit
- https://developers.facebook.com/docs/instagram-platform/instagram-graph-api/reference/ig-media/insights — IG Media Insights
- https://developers.facebook.com/docs/instagram-platform/instagram-graph-api/reference/ig-user/insights — IG User Insights
- https://developers.facebook.com/docs/instagram-platform/comment-moderation — Comment Moderation
- https://developers.facebook.com/docs/instagram-platform/webhooks — Webhooks
- https://developers.facebook.com/docs/instagram-platform/changelog — Instagram Platform Changelog
- https://developers.facebook.com/docs/instagram-platform/reference/refresh_access_token — Refresh Access Token
- https://developers.facebook.com/docs/graph-api/overview/rate-limiting — Rate Limiting (BUC)
- https://developers.facebook.com/docs/graph-api/overview/access-levels — Access Levels
- https://developers.facebook.com/docs/development/release/business-verification — Business Verification
- https://developers.facebook.com/docs/threads — Threads API
- https://about.fb.com/news/2024/04/metas-approach-to-labeling-ai-generated-content-and-manipulated-media/ — Meta's Approach to Labeling AI Content

---

## 3. TikTok

Deux familles d'API : **TikTok for Developers** (Content Posting API v2 + Display API, developers.tiktok.com) et **TikTok for Business – Accounts API** (Organic API v1.3, business-api.tiktok.com). La seconde est la seule à exposer les commentaires et les insights profonds.

### 3.1 Content Posting API v2

| Capacité | Statut | Détail |
|---|---|---|
| Direct Post vidéo public | 🟠 **audit requis** | « All content posted by unaudited clients will be restricted to private viewing mode » : client non audité = `privacy_level` forcé **`SELF_ONLY`**, 5 utilisateurs / 24 h. Erreur `unaudited_client_can_only_post_to_private_accounts` |
| Direct Post vidéo privée | ✅ (test) | utile pour les tests d'intégration ; repasser en public dans l'app : UNVERIFIED |
| Upload vers l'inbox (brouillon) | ✅ (scope `video.upload` à pré-approuver) | l'utilisateur termine le post dans l'app ; ≤ 5 brouillons en attente / 24 h |
| Photo / carrousel (≤ 35 images) | mêmes règles que la vidéo | `POST /v2/post/publish/content/init/`, `PULL_FROM_URL` **seulement**, domaine vérifié, JPEG / WebP ≤ 20 MB, `title` ≤ 90, `description` ≤ 4000 |
| Sources vidéo | | `FILE_UPLOAD` (chunks 5-64 MB, URL d'upload valable 1 h) ou `PULL_FROM_URL` (https, **vérification de domaine ou de préfixe d'URL obligatoire**) ; MP4 / WebM / MOV, ≤ 4 GB, 23-60 fps, durée max par créateur via `creator_info` (3 min pour tous) |
| Champs `post_info` | | `title` (≤ 2200), `privacy_level` (requis, parmi `privacy_level_options` du créateur), `disable_duet / stitch / comment`, `video_cover_timestamp_ms`, `brand_content_toggle` (partenariat payant tiers), `brand_organic_toggle` (sa propre marque), **`is_aigc`** (« Set to true if the video is AI generated content » → label « Creator labeled as AI-generated ») |
| Statut | | `/v2/post/publish/status/fetch/` : `PROCESSING_*`, `SEND_TO_USER_INBOX`, `PUBLISH_COMPLETE`, `FAILED` ; modération « usually within one minute », parfois des heures |
| Planification | ❌ | aucun champ |
| Webhooks | partiels | `video.publish.completed`, `video.upload.failed`, `authorization.removed` ; pas d'événement commentaire |

**Audit** : revue de l'app avec ≤ 5 vidéos de démonstration du flux complet, site + politique de confidentialité + CGU visibles, « several days to two weeks » ; apps incomplètes, de test ou en développement refusées. **UX imposée** au client : afficher le pseudo du créateur, sélecteur de confidentialité sans valeur par défaut, cases commentaire / duet / stitch non cochées, toggles de divulgation commerciale off par défaut avec libellés exacts, mention « By posting, you agree to TikTok's Music Usage Confirmation », prévisualisation, avertissement de délai. Plafond « 24-hour active creator cap » par client selon le formulaire, ~15 posts / jour / créateur (`spam_risk_too_many_posts`). Sandbox : sans revue, mais « does not offer access to Content Posting API for public videos ». Scopes au-delà de `user.info.basic` (profile, stats, `video.list`, `video.upload`, `video.publish`) : **pré-approbation** dans la configuration de l'app.

### 3.2 Display API

`/v2/user/info/` (profil, `follower_count`, `following_count`, `likes_count`, `video_count`), `/v2/video/list/` (vidéos **publiques**, ≤ 20 / page), `/v2/video/query/`. Objet vidéo : `like_count`, `comment_count`, `share_count`, `view_count`, `duration`, `cover_image_url` (TTL 6 h), `embed_link`, `is_aigc`. **Aucun endpoint commentaires** dans Display ni Content Posting.

### 3.3 Accounts API (TikTok for Business, v1.3)

- **Insights** `/business/get/` : `followers_count, video_views, unique_video_views, profile_views, likes, comments, shares`, nouveaux / perdus abonnés, `engaged_audience`, audience (âges, genres, pays, villes, activité ; ≥ 100 abonnés), clics bio ; **look-back 60 jours, délai 24-48 h**. `/business/video/list/` : `likes, comments, shares, favorites, reach, video_views, total / average_time_watched, full_video_watched_rate, new_followers, retention, impression_sources` ; données figées 365 jours après publication.
- **Commentaires** : `/business/comment/list/` (publics et masqués), `/reply/list/`, `/create/`, `/reply/create/`, `/like/`, `/hide/`, `/delete/`, webhook `comment.update` (≤ 5 min).
- **Publication** : `/business/video/publish/` (URL vérifiée, ≤ 1 GB, 3-600 s, `is_brand_organic` / `is_branded_content` requis, `is_ai_generated` irréversible, `upload_to_draft`) et `/business/photo/publish/` ; **6 posts / min, 15 / jour par compte**.
- **Accès** : compte TikTok for Business → app développeur → permission « TikTok Accounts » → **formulaire « Accounts API Access Application » obligatoire depuis le 20/03/2026** avant soumission ; autorisation par le titulaire du compte ; token 1 jour, refresh 1 an ; 40 requêtes / min par compte et endpoint. Délai d'approbation : **UNVERIFIED**. Clause explicite « usage propre » : UNVERIFIED.

### 3.4 Rate limits et tokens (v2)

Init vidéo / photo : 6 requêtes / min / token ; `creator_info` 20 / min ; statut 30 / min ; Display 600 / min par endpoint. Access token **24 h**, refresh token **365 jours** (rotation possible).

### 3.5 Research API, Commercial Content API

Hors périmètre : Research API réservée aux institutions académiques et non lucratives ; Commercial Content API = bibliothèque de transparence publicitaire (DSA art. 39), lecture seule.

### 3.6 Étiquetage IA

Politique : label **obligatoire** pour le contenu généré par IA « that contains realistic images, audio or video » ; lecture des Content Credentials C2PA et auto-label depuis 05/2024 ; filigrane invisible depuis 11/2025. Côté API : `is_aigc` (v2) / `is_ai_generated` (Business) → label « Creator labeled as AI-generated », **irréversible**, « won't affect the distribution » si les règles communautaires sont respectées.

### 3.7 Pièges

Audit refusé si app de test, site / politique / CGU absents, démo incomplète, UX non conforme ; `PULL_FROM_URL` et photos exigent un domaine vérifié (donc un domaine que le Social Agent contrôle, ex. un sous-domaine média) ; Developer ToS (12/2025) : interdiction des moyens automatisés pour « collect information from or otherwise interact », usage « excessive or abusive », spam ; formulation exacte anti-faux-engagement des guidelines : UNVERIFIED ; agrégateurs : ré-autorisation TikTok annuelle, direct = public seulement, 15 vidéos / jour.

### 3.8 Conséquence pour le Social Agent

Un connecteur TikTok direct **ne publie qu'en privé** tant que l'audit n'est pas obtenu, et n'a **ni commentaires ni insights profonds** sans l'Accounts API (second dossier d'accès). Au MVP : agrégateur (§8). Si connecteur direct plus tard : dossier Content Posting (audit, UX imposée) + dossier Accounts API.

### 3.9 Sources (consultées le 2026-09-12)

- https://developers.tiktok.com/doc/content-posting-api-get-started — Get Started Direct Post
- https://developers.tiktok.com/doc/content-posting-api-reference-direct-post — Direct Post reference (`post_info`, `is_aigc`)
- https://developers.tiktok.com/doc/content-posting-api-reference-upload-video — Upload reference
- https://developers.tiktok.com/doc/content-posting-api-reference-photo-post — Photo Post reference
- https://developers.tiktok.com/doc/content-posting-api-media-transfer-guide — Media Transfer Guide (domaine vérifié)
- https://developers.tiktok.com/doc/content-posting-api-reference-get-video-status — Get Post Status
- https://developers.tiktok.com/docs/en/content-sharing-guidelines — Content Sharing Guidelines (UX imposée, clients non audités)
- https://developers.tiktok.com/docs/en/app-review-guidelines — App Review Guidelines
- https://developers.tiktok.com/docs/en/getting-started-faq — App Review FAQ (délais)
- https://developers.tiktok.com/docs/en/add-a-sandbox — Add a Sandbox
- https://developers.tiktok.com/doc/tiktok-api-scopes — Scopes
- https://developers.tiktok.com/doc/oauth-user-access-token-management — Token Management
- https://developers.tiktok.com/doc/tiktok-api-v2-rate-limit — Rate Limits
- https://developers.tiktok.com/doc/display-api-overview — Display API Overview
- https://developers.tiktok.com/doc/tiktok-api-v2-video-object — Video Object
- https://developers.tiktok.com/doc/webhooks-events — Webhooks Events
- https://developers.tiktok.com/products/research-api/ — Research Tools eligibility
- https://developers.tiktok.com/products/commercial-content-api — Commercial Content API
- https://www.tiktok.com/legal/page/global/tik-tok-developer-terms-of-service/en — Developer Terms of Service
- https://business-api.tiktok.com/portal/docs/organic-api/v1.3 — About Organic API
- https://business-api.tiktok.com/portal/docs/get-started/v1.3 — Get started with Accounts API (formulaire 20/03/2026)
- https://business-api.tiktok.com/portal/docs/accounts-api-rate-limits/v1.3 — Accounts API rate limits
- https://business-api.tiktok.com/portal/docs/get-profile-data-of-a-tiktok-account/v1.3 — `/business/get/`
- https://business-api.tiktok.com/portal/docs/get-post-data-of-a-tiktok-account/v1.3 — `/business/video/list/`
- https://business-api.tiktok.com/portal/docs/get-comments-on-an-owned-video/v1.3 — `/business/comment/list/`
- https://business-api.tiktok.com/portal/docs/publish-a-public-video-post-to-an-owned-account/v1.3 — `/business/video/publish/`
- https://newsroom.tiktok.com/new-labels-for-disclosing-ai-generated-content?lang=en — New labels for AIGC (2023)
- https://newsroom.tiktok.com/en-us/partnering-with-our-industry-to-advance-ai-transparency-and-literacy — C2PA (2024)
- https://newsroom.tiktok.com/helping-people-spot-and-understand-aigc-on-tiktok?lang=en-150 — AIGC update (07/2026)

---

## 4. Pinterest (API v5, spec OpenAPI 5.28.0)

### 4.1 Accès : Trial puis Standard, avec vidéo de démonstration

- Toute app approuvée reçoit l'accès **Trial** : 1000 requêtes / jour par app (`org_write` 300 / jour) et surtout **« all Pins and Boards created with Trial access are only visible to their creator as Sandbox entities »**. Un pin créé en Trial n'est pas public.
- **Standard** : formulaire « Upgrade » avec cas d'usage, politique de confidentialité et **vidéo de démonstration du flux OAuth et de l'intégration** ; « If you are the only intended user of the Pinterest API, we will still require a video recording of the OAuth flow » ; « Please allow a few days ». Il existe donc une revue vidéo comparable à celle de Meta, **même pour un usage mono-marque**.
- Grant `client_credentials` (2FA obligatoire) utilisable sur pins, boards, analytics, catalogues pour un compte propre ; ne dispense pas de la revue Standard.
- Tokens : access 30 jours, refresh 60 jours renouvelable indéfiniment (à rafraîchir avant J+60) ; l'ancien refresh 365 jours ne vaut que pour les apps créées avant le 25/09/2025.

### 4.2 Création de pins

| Format | Statut | Détail |
|---|---|---|
| Image | 🟠 Standard (Trial = privé) | `image_url` ou `image_base64` (JPEG / PNG) ; `title` ≤ 100, `description` ≤ 800, `alt_text` ≤ 500, **`link` ≤ 2048** (UTM non documentés, non interdits : UNVERIFIED), `board_id`, `board_section_id`, `dominant_color`, `ai_disclosures` |
| Carrousel | 🟠 | `multiple_image_urls` / `_base64`, 2-5 images, `title` / `description` / `link` par image |
| Vidéo | 🟠 (pas en Sandbox) | `POST /media {video}` → upload multipart (.mp4 / .mov / .m4v) → `GET /media/{id}` jusqu'à `succeeded` → `POST /pins` avec `video_id` **et une image de couverture obligatoire** ; specs produit : H.264 / H.265, ≤ 2 Go, 4 s à 15 min, ratios 1:1, 2:3, 4:5, 9:16 |
| Idea Pins | ❌ | plus créables via API (lecture seule) |
| Planification | ❌ | aucun champ `publish_time` ; le planificateur natif est UI seulement (30 jours, 10 pins) ; Developer Guidelines : « the end user must choose each Pin to be published » |
| Modifier un pin | Beta | `PATCH /pins/{id}` « not available to all apps » |
| Product tags (≤ 24) | 🟠 | `POST /pins/{pin_id}/product_tags`, domaine revendiqué |

Champ `note` supprimé en 5.20.0 (12/2025).

### 4.3 Analytics

- `GET /pins/{id}/analytics` : fenêtre **≤ 90 jours en arrière**, `daily_metrics` + `summary_metrics` + `lifetime_metrics`, `data_status` (`PROCESSING`, `ESTIMATE`…). Métriques : `IMPRESSION, OUTBOUND_CLICK, PIN_CLICK, SAVE, SAVE_RATE, TOTAL_COMMENTS, TOTAL_REACTIONS, USER_FOLLOW, PROFILE_VISIT, VIDEO_MRC_VIEW, VIDEO_10S_VIEW, QUARTILE_95_PERCENT_VIEW, VIDEO_V50_WATCH_TIME, VIDEO_START, VIDEO_AVG_WATCH_TIME` (commentaires et réactions en lifetime seulement).
- `GET /user_account/analytics` : `ENGAGEMENT(_RATE), IMPRESSION, OUTBOUND_CLICK(_RATE), PIN_CLICK(_RATE), SAVE(_RATE)`, filtres `from_claimed_content`, `pin_format`, `content_type`. Top pins ≤ 50 sur 30 jours. Latence jusqu'à 48 h.
- Analytics multi-pins (≤ 100 ids) : beta. Audience insights : compte publicitaire seulement.

### 4.4 Commentaires et engagement

- ❌ **Aucun endpoint** pour lire ou répondre aux commentaires, liker ou réagir. Seuls les compteurs lifetime `TOTAL_COMMENTS` / `TOTAL_REACTIONS` existent. Les commentaires Pinterest se traitent **dans l'application**.
- Repin d'un pin public (`POST /pins/{pin_id}/save`) et follow (`POST /user_account/following/{username}`) : possibles, **exclus par choix** (brief §6).
- Webhooks : Lead ads uniquement.

### 4.5 Rate limits

Standard : 100 requêtes / s / utilisateur / app ; par catégorie et par minute : `org_write` 100, `org_read` 1000, `org_analytics` 60. En-têtes `x-ratelimit-limit`, `-remaining`, `-reset` ; 429. « Subject to change without notice ».

### 4.6 Shopping : Catalogs API et plugin Pinterest for WooCommerce

Catalogs API : compte Business + **domaine revendiqué** + Pinterest tag + merchant guidelines. Plugin officiel **Pinterest for WooCommerce 1.5.0** (09/2026) : flux catalogue (ingestion toutes les 24 h), tag + Conversions API, Rich Pins produits / articles, bouton Save ; exclut produits groupés et abonnements ; « one integration with your account » ; requiert « an ads account for the target country » ; disponibilité FR / US **UNVERIFIED** (liste non lisible). Pertinent **sans code** pour les product pins ; les épingles éditoriales du Social Agent restent hors plugin. À évaluer côté site après le lancement (charge supplémentaire sur o2switch, note utilisateurs 2,3 / 5).

### 4.7 Étiquetage IA

Champ `ai_disclosures.values[]` ∈ {`AI_MODIFIED`, `SYNTHETIC_PERFORMER`} (spec 5.28.0) ; catalogue : `ai_content_disclosure` par asset. Label « AI modified » via métadonnées IPTC + classifieurs + auto-déclaration (C2PA non mentionné : UNVERIFIED). Depuis le 16/10/2025, les utilisateurs peuvent réduire les pins IA générative dans plusieurs catégories dont **home décor** ; effet sur la distribution non documenté. → Un avatar des fondateurs se déclare `SYNTHETIC_PERFORMER` ; une image produit non modifiée ne se déclare pas.

### 4.8 Pièges

Pins Trial invisibles ; vidéo de démonstration pour Standard ; couverture obligatoire ; site revendiqué (un seul compte par site) nécessaire pour catalogues, attribution profil et `from_claimed_content` ; Developer Guidelines : « call the API each time », pas de stockage des données de compte, pas d'entraînement IA sur les données ; lookback analytics 90 jours → snapshots côté agent.

### 4.9 Sources (consultées le 2026-09-12)

- https://developers.pinterest.com/docs/key-concepts/access-tiers/ — Understanding our access tiers
- https://developers.pinterest.com/docs/getting-started/set-up-app/ — Connect app (revue, vidéo)
- https://developers.pinterest.com/docs/reference/rate-limits/ — Rate limits
- https://developers.pinterest.com/docs/getting-started/set-up-authentication-and-authorization/ — Authentication and authorization (tokens, client_credentials)
- https://developers.pinterest.com/docs/work-with-organic-content-and-users/create-boards-and-pins/ — Create boards and Pins
- https://developers.pinterest.com/docs/analytics-and-reports/organic-reporting/ — Organic reporting
- https://developers.pinterest.com/docs/developer-tools/sandbox/ — Sandbox
- https://developers.pinterest.com/docs/key-concepts/using-beta-and-restricted-features/ — Beta and restricted features
- https://github.com/pinterest/api-description — spec OpenAPI officielle (releases v5.15.0 → v5.28.0)
- https://policy.pinterest.com/en/developer-guidelines — Developer guidelines
- https://help.pinterest.com/en/business/article/schedule-pins — Schedule Pins (UI)
- https://help.pinterest.com/en/business/article/pinterest-product-specs — Product specs
- https://help.pinterest.com/en/business/article/claim-your-website — Claim your website
- https://help.pinterest.com/en/article/gen-ai-labels — Gen AI labels
- https://newsroom.pinterest.com/news/pinterest-rolls-out-new-tools-to-give-users-more-control-over-gen-ai-content/ — GenAI controls (16/10/2025)
- https://woocommerce.com/document/pinterest-for-woocommerce/ — Pinterest for WooCommerce
- https://wordpress.org/plugins/pinterest-for-woocommerce/ — plugin 1.5.0

---

## 5. YouTube (Data API v3, Analytics API, OAuth)

### 5.1 Publication

- `videos.insert` : coût **1 unité dans un bucket dédié « Video Uploads », 100 appels / jour par défaut** (quota granulaire depuis le 01/06/2026 ; l'ancien coût de 1600 unités est périmé). MIME `video/*`, ≤ 256 GB, upload resumable (chunks multiples de 256 KB).
- Champs : `snippet.title` (≤ 100 car.), `description` (≤ 5000 octets), `tags` (≤ 500 car.), `categoryId`, `defaultLanguage` ; `status.privacyStatus`, **`publishAt`** (planification native, exige `private`), `selfDeclaredMadeForKids`, **`containsSyntheticMedia`** (divulgation contenu altéré / synthétique, depuis 10/2024), `license`, `embeddable` ; `notifySubscribers`.
- **Shorts** : classification automatique, vidéo carrée ou verticale ≤ 3 min (depuis le 15/10/2024). Aucun champ API ; `#Shorts` non exigé. Miniatures personnalisées des Shorts : YouTube Studio seulement ; effet de `thumbnails.set` sur un Short UNVERIFIED.

### 5.2 Restriction des projets non audités, vérifications

Texte officiel : « All videos uploaded via the videos.insert endpoint from unverified API projects created after 28 July 2020 will be restricted to private viewing mode. To lift this restriction, each API project must undergo an audit to verify compliance with the Terms of Service. »

Trois procédures distinctes :

| Procédure | Objet | Exigences | Délai |
|---|---|---|---|
| **Audit YouTube API Services** (formulaire « Audit and Quota Extension ») | lever le verrou privé, obtenir du quota | raison sociale, site, politique de confidentialité, CGU, compte et URL de démo, captures OAuth / upload, cas d'usage, volumes | **non publié** (UNVERIFIED) ; audit valable 12 mois pour les extensions |
| **Vérification OAuth** (scopes sensibles `youtube.upload`, `youtube`, `youtube.force-ssl`) | écran de consentement sans avertissement « unverified », plus de plafond 100 utilisateurs | domaine vérifié, page d'accueil, politique de confidentialité, vidéo démo | « typically 3-5 business days » ; **facultative pour un usage propre** (< 100 utilisateurs) |
| **Statut de publication « Testing » vs « In production »** | durée des tokens | en Testing, « Authorizations by a test user will expire seven days from the time of consent » → automatisation cassée chaque semaine | passer **In production** (possible sans vérification pour un usage propre) |

Conclusion : un connecteur YouTube direct publie **en privé** tant que l'audit n'est pas obtenu. `publishAt` sur une vidéo privée d'un projet non audité : effet public réel UNVERIFIED.

### 5.3 Commentaires et engagement

- ✅ `commentThreads.list` (1 unité, filtre `videoId`, `moderationStatus` pour le propriétaire), `comments.list`, `comments.insert` (réponse, 50 unités, `force-ssl`), `commentThreads.insert`, `comments.setModerationStatus` (`heldForReview` / `published` / `rejected`, `banAuthor`).
- ❌ `comments.markAsSpam` (déprécié 09/2023) ; liker, épingler ou « cœur » un commentaire : aucune méthode.
- `videos.rate` et `subscriptions.insert` existent, mais les Developer Policies III.I.2 interdisent d'automatiser vues, uploads, commentaires, likes « without the user's prior specific and express consent ». **Exclu.**
- Politique III.E.4.c : données non analytiques conservées ≤ 30 jours ; III.D.1.c : un seul projet API par client.

### 5.4 Analytics

- Analytics API `reports.query` : dimensions `video, day, country, insightTrafficSourceType (SHORTS, YT_SEARCH, EXT_URL, …), creatorContentType (SHORTS / VIDEO_ON_DEMAND / …), deviceType, subscribedStatus…` ; métriques `views, engagedViews (03/2025), estimatedMinutesWatched, averageViewDuration, averageViewPercentage, likes, comments, shares, subscribersGained / Lost, audienceWatchRatio…`. **Latence 48-72 h.** 1 unité par requête.
- Data API `videos.list part=statistics` (`viewCount, likeCount, commentCount`) et `videos.batchGetStats` (06/2026, bucket propre 10 000 / jour) pour le temps réel.
- Comptage des vues modifié : Shorts depuis 03/2025, tous formats depuis le 24/08/2026 (« the moment a video begins to play ») → comparer avec `engagedViews`.

### 5.5 Étiquetage IA

Divulgation **obligatoire** si contenu réaliste : personne réelle faisant ou disant ce qu'elle n'a pas fait, voix synthétique, scène réaliste inventée, musique générée. Non requis pour « Cloning one's own voice to create voice overs or dubs ». Champ `status.containsSyntheticMedia` ; label dans la description étendue, sur le lecteur pour le photoréaliste ; « won't limit a video's audience ». Un avatar des fondateurs, consentants, ne pose pas de problème de vie privée YouTube mais **doit être déclaré**.

### 5.6 Liens

« URLs placed in Shorts comments & Shorts descriptions » = **non cliquables** (mentions et hashtags cliquables). Vidéos longues : cliquables avec « advanced features ». « Related video » d'un Short : Studio seulement. → Pour les Shorts, le CTA passe par le **lien de chaîne** (bannière / À propos) et par un lien de bio traçable `/go/yt`.

### 5.7 Quotas et pièges

10 000 unités / jour hors uploads ; écritures 50 unités ; reset minuit heure du Pacifique. Made for kids coupe commentaires et notifications ; Shorts > 1 min avec claim Content ID bloqués ; audio : bibliothèque libre de droits.

### 5.8 Sources (consultées le 2026-09-11)

- https://developers.google.com/youtube/v3/docs/videos/insert — Videos: insert (verrou privé, quota)
- https://developers.google.com/youtube/v3/docs/videos — Videos resource (`publishAt`, `containsSyntheticMedia`)
- https://developers.google.com/youtube/v3/determine_quota_cost — Quota Calculator
- https://developers.google.com/youtube/v3/guides/quota_and_compliance_audits — Quota and Compliance Audits
- https://support.google.com/youtube/contact/yt_api_form — Audit and Quota Extension Form
- https://developers.google.com/youtube/v3/revision_history — Data API revision history (uploads bucket 06/2026, batchGetStats)
- https://developers.google.com/youtube/v3/docs/commentThreads/list — CommentThreads: list
- https://developers.google.com/youtube/v3/docs/comments/insert — Comments: insert
- https://developers.google.com/youtube/v3/docs/comments/setModerationStatus — Comments: setModerationStatus
- https://developers.google.com/youtube/v3/docs/comments/markAsSpam — Comments: markAsSpam (déprécié)
- https://developers.google.com/youtube/terms/developer-policies — Developer Policies (III.I.2, III.E.4.c, III.D.1.c)
- https://developers.google.com/youtube/analytics/reference/reports/query — Analytics reports.query
- https://developers.google.com/youtube/analytics/metrics — Analytics metrics
- https://developers.google.com/youtube/analytics/dimensions — Analytics dimensions
- https://developers.google.com/identity/protocols/oauth2 — OAuth 2.0 (expiration des tokens en Testing)
- https://developers.google.com/identity/protocols/oauth2/production-readiness/sensitive-scope-verification — Sensitive scope verification
- https://support.google.com/cloud/answer/15549945 — Manage App Audience (Testing / In production)
- https://support.google.com/youtube/answer/15424877 — Three-minute YouTube Shorts
- https://support.google.com/youtube/answer/14328491 — Disclosing use of altered or synthetic content
- https://support.google.com/youtube/answer/13748639 — Sharing links with your audiences (Shorts non cliquables)
- https://support.google.com/youtube/answer/72431 — Add video thumbnails (Shorts)

---

## 6. LinkedIn

### 6.1 Profil personnel (« Share on LinkedIn », self-serve)

- Permission ouverte, ajoutée sans review : `w_member_social` (« Post, comment and like posts on behalf of an authenticated member »). Identité via OpenID Connect (`sub` → `urn:li:person:{sub}`).
- ✅ Post texte (≤ 3000 car.), article-lien, image, vidéo (3 s à 30 min, ≤ 500 MB, upload par parts) ; documents PDF et multi-images (2-20) documentés côté Posts API versionnée, accès pour une app Share seule **UNVERIFIED**.
- ❌ **Aucune lecture** : ni statistiques du post (`r_member_postAnalytics` est une permission Community Management), ni liste de ses posts (`r_member_social` « closed permission »), ni commentaires reçus (`r_member_social_feed` « select developers only »).
- ❌ Planification (`lifecycleState` = PUBLISHED seul accepté). ❌ Champ « contenu IA ».
- Quotas publiés : **150 requêtes / jour / membre**, 100 000 / jour / app.
- **Tokens : 60 jours, pas de refresh programmatique** hors partenaires approuvés → ré-authentification humaine dans le navigateur au plus tous les 60 jours ; changement de scopes = ré-auth.
- Doc consumer encore sur `/v2/ugcPosts` (legacy) ; `/rest/posts` exige l'en-tête `Linkedin-Version: YYYYMM` (versions supportées ≥ 1 an, sunsets mensuels).

### 6.2 Page entreprise (Community Management API)

- Publier en tant que Page : `w_organization_social` + rôle administrateur. Analytics : `organizationalEntityShareStatistics` (impressions, clics, likes, commentaires, partages ; **12 mois glissants**), `organizationalEntityFollowerStatistics`. Commentaires et réactions : `socialActions`, `/rest/reactions`, `socialMetadata`.
- Accès : produit « vetted » avec tiers Development (500 appels / app / jour, 100 / membre, pas de webhooks, à finaliser sous 12 mois) puis Standard (formulaire + screencast). Conditions : organisation légale, e-mail professionnel, site, politique de confidentialité, Page vérifiée par un super admin, app dédiée (CM seul produit), questionnaire sous 21 jours. **Délai non publié** (UNVERIFIED). Refus = nouvelle app.
- Restrictions : pas de « social feed » sur un site, données d'activité des membres ≤ 48 h, aucun usage CRM ou prospection.

### 6.3 Automatisation et étiquetage IA

- User Agreement §8.2.13 : interdiction des bots qui « create, comment on, like, share, or re-share posts, or otherwise drive inauthentic engagement » ; §8.2.2 scraping ; Professional Community Policies : pas de pods. Publier via API avec consentement OAuth du membre ou de la Page = autorisé. API ToU §3.1(26) cite « automate posting » dans les usages interdits ; portée face à une publication initiée par le membre via `w_member_social` : **UNVERIFIED**, à lire dans le texte intégral avant la Phase 3.
- Étiquetage : icône C2PA « Content credentials » sur les médias signés ; aucun champ API ; conservation du manifeste C2PA à l'upload UNVERIFIED. Politiques : média synthétique montrant une personne ⇒ divulgation.

### 6.4 Conséquence pour le Social Agent

Profil d'Alain : **publication manuelle** (export de l'agent) tant que la portée de la clause « automate posting » des API Terms of Use n'est pas lue en texte intégral ; aucune métrique API ; rappel de ré-auth à J+55 si un jour automatisé. Page entreprise : via l'agrégateur (analytics + commentaires) dès qu'elle existe ([04](04-decisions.md) D7). Pas de dossier Community Management au MVP.

### 6.5 Sources (consultées le 2026-09-11)

- https://learn.microsoft.com/en-us/linkedin/consumer/integrations/self-serve/share-on-linkedin — Share on LinkedIn
- https://learn.microsoft.com/en-us/linkedin/shared/authentication/getting-access — Getting Access to LinkedIn APIs
- https://learn.microsoft.com/en-us/linkedin/shared/authentication/programmatic-refresh-tokens — Refresh Tokens (partenaires)
- https://learn.microsoft.com/en-us/linkedin/shared/api-guide/concepts/rate-limits — Rate Limiting
- https://learn.microsoft.com/en-us/linkedin/marketing/community-management/shares/posts-api — Posts API
- https://learn.microsoft.com/en-us/linkedin/marketing/community-management/shares/videos-api — Videos API
- https://learn.microsoft.com/en-us/linkedin/marketing/community-management/members/post-statistics — Member Post Statistics
- https://learn.microsoft.com/en-us/linkedin/marketing/community-management/organizations/share-statistics — Organization Share Statistics
- https://learn.microsoft.com/en-us/linkedin/marketing/community-management/community-management-overview — Community Management Overview
- https://learn.microsoft.com/en-us/linkedin/marketing/community-management-app-review — Community Management App Review
- https://learn.microsoft.com/en-us/linkedin/marketing/versioning — Versioning
- https://learn.microsoft.com/en-us/linkedin/marketing/restricted-use-cases — Restricted Uses
- https://www.linkedin.com/legal/user-agreement — User Agreement (§8.2)
- https://www.linkedin.com/legal/l/api-terms-of-use — API Terms of Use
- https://www.linkedin.com/help/linkedin/answer/a6282984 — Content credentials (C2PA)

---

## 7. Liens traçables et attribution (kh.com)

### 7.1 Ce qui se capture, où

| Donnée | Système | Clé / endpoint | Limite |
|---|---|---|---|
| Type de source, `utm_*` (+ `utm_id`, `source_platform`, `creative_format`, `marketing_tactic`), referrer, page d'entrée, pages vues, appareil | **WooCommerce Order Attribution** (natif depuis 8.5, activé par défaut) | métadonnées `_wc_order_attribution_*` sur la commande, exposées dans `meta_data` de `GET /wp-json/wc/v3/orders` | **last-click** (sourcebuster.js, session 30 min) ; seule la visite courante est stockée ; « Unknown » si JS ou consentement absents |
| Visites, pages, événements, revenu par source / UTM | **Plausible** | `POST https://plausible.io/api/v2/query`, dimensions `visit:source`, `visit:utm_*`, `event:goal`, `event:props:*` | **Stats API, revenu, propriétés et entonnoirs = plan Business** (≈ 19 $/mois, 10k pages vues) ; UTM sensibles à la casse ; source fixée à l'ouverture de session ; `utm_id` ignoré |
| Événements boutique (`Add to Cart`, `Remove from Cart`, `Start Checkout`, `Complete Purchase` + revenu, 15 propriétés) | plugin WordPress Plausible 2.6.1, option « Ecommerce revenue » (WooCommerce depuis 2.1.0) | même API | proxy anti-bloqueurs intégré ; suivi de formulaires testé CF7 / WPForms / Ninja / Elementor, **Fluent Forms non listé (UNVERIFIED)** |
| Lead + UTM | **Fluent Forms Free** : champ caché, valeur par défaut « Populate by GET Param », smart code `{get.utm_source}` (aussi `{cookie.nom}`, `{http_referer}`) | export CSV / JSON ; `GET /wp-json/fluentform/v1/submissions` (auth `X-WP-Nonce`, session admin ; Application Password d'un utilisateur dédié à tester) | **webhook = Pro** ; l'UTM doit être dans l'URL de la page du formulaire ; un cookie 1ʳᵉ partie `kh_utm_*` (lu par `{cookie.kh_utm_source}`) prolongerait l'UTM jusqu'au formulaire, **mais c'est un cookie marketing : à poser uniquement sous consentement via WP Consent API**, sinon repli sur `{get.utm_*}` |
| Clic sur lien court | Redirection / Pretty Links / **mu-plugin `/go/<code>`** (table de codes = fichier versionné déployé avec le site, cibles restreintes à `koinoborihouse.com`, journal) | 302 + UTM, `Cache-Control: no-store` | exclure `/go/` dans LiteSpeed « Do Not Cache URIs » ; comptage brut (bots) ; aucun endpoint d'écriture exposé |
| Consentement | Complianz + plugin **WP Consent API** | filtre `wc_order_attribution_allow_tracking`, catégorie `marketing` hard-codée | **sans le plugin WP Consent API, WooCommerce pose les cookies d'attribution sans consentement** : point de conformité du site **à traiter avant le 30/09**, indépendamment de l'agent (D14, lot 1) ; avec opt-in UE, part « Unknown » élevée (issue WooCommerce #62508 ouverte) |

### 7.2 Contraintes de lien par plateforme

| Plateforme | Lien cliquable | Conséquence |
|---|---|---|
| Instagram | bio ; sticker lien en Stories (non exposé par l'API) | `/go/ig` en bio → page « lien en bio » ; stickers posés à la main si Stories manuelles |
| TikTok | bio (conditions de compte : voir §3) | `/go/tt` en bio |
| Pinterest | `link` de l'épingle | lien direct produit / collection / page Kaïro avec UTM par variante |
| YouTube Shorts | **non cliquable** en description et commentaires | lien de chaîne `/go/yt` ; mention orale et incrustée |
| LinkedIn | URL dans le post (aperçu OG) | lien direct avec UTM |

### 7.3 Taxonomie UTM retenue

Minuscules, tirets, jamais d'espace. `utm_source` = `instagram | tiktok | pinterest | youtube | linkedin` ; `utm_medium` = `social | social_bio | social_video | social_story | social_reply` ; `utm_campaign` = `campaign.code` (ex. `2026-10-kairo-teaser`) ; `utm_content` = identifiant court de variante + format (ex. `v8f3-reel`) ; `utm_term` = type de hook. Pièges : l'UTM écrase la source referrer (jamais d'UTM sur des liens internes) ; WooCommerce = last-click « current », Plausible = source de la session courante ; les deux peuvent diverger sur une commande passée dans une session directe ultérieure.

### 7.4 Leads sans clic (commentaires)

1. Journal côté agent : `plateforme:post_id:auteur:date` + hash du message + code campagne.
2. Réponse publique avec le code lead (`LEAD-IG-014`, format unique de l'écosystème) et, si utile, un lien `/go/` dédié.
3. Formulaire : champ visible optionnel « Code » + champ caché `{get.utm_content}` ; code promo WooCommerce par campagne pour l'attribution à l'achat.
4. Rapprochement hebdomadaire export Fluent Forms × `meta_data` des commandes × journal.

### 7.5 Limites annoncées dans les rapports

Last-click seul (aucun multi-touch ni chez WooCommerce ni chez Plausible) ; pas de cross-device (Plausible : hash quotidien, zéro cookie) ; navigateurs in-app Instagram / TikTok sans referrer → « Direct » sans UTM ; opt-in marketing → « Unknown » côté WooCommerce ; bloqueurs de pub (proxy). Le rapport hebdo sépare toujours « attribuable » et « non attribuable ».

### 7.6 Sources (consultées le 2026-09-11)

- https://woocommerce.com/document/order-attribution-tracking/ — Order Attribution Tracking
- https://developer.woocommerce.com/2024/01/09/woocommerce-8-5-0-released/ — WooCommerce 8.5.0 Released
- https://raw.githubusercontent.com/woocommerce/woocommerce/trunk/plugins/woocommerce/src/Internal/Traits/OrderAttributionMeta.php — source : champs et préfixe
- https://raw.githubusercontent.com/woocommerce/woocommerce/trunk/plugins/woocommerce/src/Internal/Integrations/WPConsentAPI.php — source : intégration WP Consent API
- https://github.com/woocommerce/woocommerce/issues/59611 — catégorie de consentement (closed, not planned)
- https://github.com/woocommerce/woocommerce/issues/62508 — source « Unknown » malgré UTM (ouverte)
- https://raw.githubusercontent.com/woocommerce/woocommerce-rest-api-docs/trunk/source/includes/wp-api-v3/_orders.md — REST API v3 Orders
- https://wordpress.org/plugins/wp-consent-api/ — WP Consent API
- https://complianz.io/wp-consent-api/ — WP Consent API and Complianz
- https://plausible.io/docs/stats-api — Stats API v2
- https://plausible.io/docs/ecommerce-revenue-tracking — Ecommerce revenue tracking
- https://plausible.io/docs/top-referrers — Traffic sources and campaigns
- https://plausible.io/docs/manual-link-tagging — UTM
- https://plausible.io/docs/proxy/guides/wordpress — Proxy via le plugin WordPress
- https://wordpress.org/plugins/plausible-analytics/ — Plugin Plausible Analytics (2.6.1)
- https://plausible.io/ — Pricing
- https://fluentforms.com/docs/form-editor-smart-codes/ — Smart Codes (`{get.*}`, `{cookie.*}`)
- https://fluentforms.com/docs/set-default-form-value-from-url-parameters/ — Default value from URL parameters
- https://fluentforms.com/docs/how-to-integrate-webhook-with-fluent-forms/ — Webhook (Premium)
- https://developers.fluentforms.com/api/endpoints/submissions/ — Submissions endpoints
- https://support.google.com/youtube/answer/13748639 — Sharing links (Shorts)
- https://developers.pinterest.com/docs/work-with-organic-content-and-users/create-boards-and-pins/ — Create boards and Pins (`link`)
- https://docs.litespeedtech.com/lscache/lscwp/cache/ — LSCWP Cache settings (Do Not Cache URIs)
- https://bitly.com/pages/pricing — Bitly pricing (écarté)

---

## 8. Agrégateurs de publication

Objectif : publier / planifier, lire métriques et commentaires sur 5 plateformes **sans porter soi-même les reviews** Meta, TikTok, Pinterest, Google, LinkedIn. Budget cible < 100 €/mois.

### 8.1 Comparatif (1 marque, 5 plateformes)

| Service | Prix / mois | IG feed / carrousel / Reels / Stories | TikTok vidéo / photo | Pinterest image / vidéo + lien | Shorts | LinkedIn perso / page | Analytics post / compte | Commentaires | Webhooks | Credentials propres ? | Société / données |
|---|---|---|---|---|---|---|---|---|---|---|---|
| **Zernio** (ex-Late) | **~18 $** (2 comptes gratuits, puis 6 $/compte ; 24 $ avec page LinkedIn) | ✔ / ✔ (10) / ✔ / ✔ | ✔ / ✔ (≤ 35 img) ; direct = public seulement, 15 vidéos + 15 photos / jour | ✔ / ✔ / `link` HTTPS ; 25 pins / jour | ✔ auto (≤ 3 min vertical) | ✔ / ✔ ; perso = analytics limitées, **pas de commentaires** | ✔ / ✔ (démographie, best time) | ✔ list / reply / delete / hide IG · TikTok · YouTube · LinkedIn page ; Pinterest ✖ | ✔ `post.*`, **`comment.received`**, `analytics.synced` | Non (« no app reviews ») | Zernio Software SL (Espagne) ; média R2 UE, logs Axiom US ; SOC 2 + GDPR ; DPA sous NDA |
| **Upload-Post** | **~19 €** mensuel (13 € annuel), 5 profils, uploads illimités | ✔ / ✔ / ✔ `REELS` / ✔ `STORIES` | ✔ / ✔ (≤ 35 img) | ✔ / ✔ / `pinterest_link` ; board obligatoire | annoncés, **sans paramètre dédié** | ✔ / ✔ | ✔ / ✔ (11 plateformes) | ✔ list / create / delete IG · TikTok · YouTube · LinkedIn ; Pinterest ✖ | `upload_completed`, connexions ; **pas d'événement commentaire** (polling) | Non | TONVI TECH SL (Málaga) ; Hetzner Allemagne + GCS/R2 UE ; **DPA public** ; GDPR |
| **Ayrshare** Premium | **149 $** (1 profil, ≤ 14 comptes) | ✔ / ✔ / ✔ / ✔ (Business) | ✔ / ✔ | ✔ / ✔ / lien | ✔ `shorts:true` | ✔ l'un ou l'autre | ✔ / ✔ | ✔ IG · TikTok · LinkedIn · YouTube ; Pinterest ✖ | Premium (ambigu) | Non (partenaire officiel) | Neverminds Solution LLC (Delaware) ; **données UE stockées aux USA** (SCC) ; DPA public ; SOC 2 |
| **Postiz** cloud | 29 $ (5 canaux) | ✔ post / story ; reel = vidéo | UNVERIFIED | UNVERIFIED | UNVERIFIED | ✔ / ✔ | ✔ post / plateforme (Beta) | **absents de l'API** | 1 événement (post publié) | Non | Gitroom LLC ; localisation UNVERIFIED |
| **Postiz** self-hosted | 0 $ + serveur (AGPL-3.0) | idem | TikTok privé avant audit | idem | idem | idem | idem | idem | idem | **Oui : 5 reviews à passer soi-même** | chez vous |
| Metricool, Publer, Buffer, Planable | 25-54 € | API partielles (export métriques, posts) ; commentaires non couverts ou UNVERIFIED | | | | | | | | Non | |
| Hootsuite, SocialBee, Later | 29-399 $ | Hootsuite : **Instagram Business non supporté par l'API** ; SocialBee et Later : pas d'API | | | | | | | | | |

### 8.2 Recommandation

- **Plan A : Zernio** (~18-24 $/mois). Seul service sous 25 $ couvrant les 5 plateformes avec Stories, TikTok photo, Pinterest vidéo + lien, Shorts, page LinkedIn, analytics et commentaires par API, **webhook `comment.received`**, société UE, SOC 2. Conditions : compte Instagram Business relié à une Page Facebook ; LinkedIn via la page entreprise ; Pinterest = publication + analytics seulement. Réserves : DPA sous NDA, logs aux USA, jeune société (rebranding Late → Zernio en 2026).
- **Plan B : Upload-Post** (~19 €/mois). Équivalent fonctionnel hors webhook commentaires (polling) ; **entité et hébergement UE, DPA public** : argument RGPD fort. Bascule en quelques jours si l'agrégateur est encapsulé derrière l'interface `Publisher`.
- **Écartés** : Ayrshare (hors budget, données aux USA) ; Postiz cloud (pas de commentaires) ; Postiz self-hosted (5 reviews) ; Hootsuite (pas d'Instagram) ; les autres (outils de gestion, API partielle).
- **Risques structurels** : dépendance fournisseur (mitigation : abstraction, source de vérité chez nous, export régulier, plan B testé) ; ToS plateformes (ré-autorisation TikTok annuelle, quotas quotidiens, retrait de permissions possible) ; arrêt de service (publication manuelle de secours) ; **commentaires = données personnelles de tiers transitant par un sous-traitant** → DPA, registre, politique de confidentialité ([04](04-decisions.md) D13).

### 8.3 Sources (consultées le 2026-09-11)

- https://zernio.com/rebrand — Late is now Zernio · https://docs.zernio.com/pricing — Pricing · https://docs.zernio.com/platforms — Platforms Overview · https://docs.zernio.com/webhooks — Webhooks · https://docs.zernio.com/guides/rate-limits — Rate Limits · https://trust.zernio.com/ — Trust Center
- https://www.upload-post.com/llms-full.txt — Pricing & capabilities · https://docs.upload-post.com/api/upload-video/ — Upload Video · https://docs.upload-post.com/api/get-analytics/ — Analytics · https://docs.upload-post.com/api/comments/ — Comments · https://docs.upload-post.com/api/webhooks/ — Webhooks · https://www.upload-post.com/data-and-privacy-policy/ — Privacy Policy v2.4
- https://www.ayrshare.com/pricing/ — Pricing · https://www.ayrshare.com/docs/apis/post/social-networks/instagram — Instagram · https://www.ayrshare.com/docs/rest-api/endpoints/comments — Comments · https://www.ayrshare.com/data-processing-agreement/ — DPA
- https://postiz.com/pricing — Pricing · https://docs.postiz.com/public-api — Public API · https://docs.postiz.com/providers/tiktok — TikTok (self-host) · https://github.com/gitroomhq/postiz-app — README (AGPL-3.0)
- https://help.metricool.com/api-access-export-your-metricool-data-to-other-tools-and-automate-tasks-x8ln5 — Metricool API · https://publer.com/docs — Publer API · https://buffer.com/resources/buffer-api-is-here/ — Buffer API GA (27/05/2026) · https://developer.hootsuite.com/docs/faq — Hootsuite FAQ · https://planable.io/guides/planable-public-api/ — Planable API

---

## 9. Avatars vidéo et étiquetage des contenus IA

Phase 7 du brief. Rien n'est engagé avant la décision D8 ([04-decisions.md](04-decisions.md)) et le consentement écrit de Catherine et d'Alain.

### 9.1 Fournisseurs (avatar d'une personne réelle, pilotable par API)

| Service | Jumeau d'une personne réelle via API | Consentement | 9:16 | Voix FR / EN | Prix indicatif | Données | Note |
|---|---|---|---|---|---|---|---|
| **HeyGen** (API v3) | **Oui** : `POST /v3/avatars` type `digital_twin` (footage 2-5 min, ≥ 1080p, une prise, 16:9 ou 9:16) ; clone la voix du footage ; `type: photo` = Avatar IV | **Obligatoire** : vidéo de consentement < 30 s, même personne, texte imposé, statut `consent_status` lisible par API | ✅ `aspect_ratio 9:16`, SRT fourni, burn-in optionnel | voix plateforme FR + EN ; clone inclus ; cross-lingue UNVERIFIED ; renvoi vers ElevenLabs si l'accent est mal capté | pay-as-you-go **1 $/min** standard, **4 $/min Avatar IV 1080p** ; slots de twin : 1 gratuit, 5 avec Creator 29 $/mois ; plus de crédits API gratuits depuis 02/2026 | US (AWS), DPF + SCC, SOC 2 ; résidence UE Enterprise UNVERIFIED | rendu ≈ 10 min par minute, file jusqu'à 24 h ; plan Free = non commercial |
| **Synthesia** | Oui, API dès **Creator** (89 $/mois, 30 min/mois, 5 avatars personnels) | vidéo de consentement **enregistrée en direct** (upload refusé) ; appariement face / voix = biométrie | ✅ `aspectRatio 9:16` | clone créé à la soumission ; FR / EN pour le clone UNVERIFIED | ≈ 3 $/min inclus ; avatar studio Express-1 = add-on 1000 $/an | AWS, suppression sur demande, région UNVERIFIED | avatar sous ~1 jour ouvré ; compatibilité API des avatars photo UNVERIFIED |
| **D-ID** | Oui : Express Avatar (`source_url` 1-2 min + `consent_id`) | `Create a consent` : 3 mots aléatoires lus à la webcam, reconnaissance faciale + voix | UNVERIFIED | clone : 1 (Launch) / 3 (Scale) | Build 14,4 $/mois (watermark, perso) ; **Launch 35 $/mois annuel** (45 min, licence commerciale, 3 avatars) ; Scale 138,6 $ | UNVERIFIED | 4-10 min de traitement par minute |
| **Tavus** | Oui : `create face` (60 s de footage) | champ consentement **déprécié** ; AUP exige consentement explicite + divulgation IA | UNVERIFIED | voix du footage ; langues UNVERIFIED | Starter 59 $/mois (10 min), 1 $/min au-delà | UNVERIFIED | orienté conversationnel |
| **Argil** | avatar depuis photo | **aucune procédure documentée** | ✅ | pro voices | ~39 $/mois UNVERIFIED | UNVERIFIED | écarté : consentement non vérifié |
| **Captions (Mirage)** | AI Twin depuis ~10 s ; API UNVERIFIED (pages 404) | non décrit | ✅ | UNVERIFIED | 24,99-69,99 $/mois | US | écarté au pilote |
| **Hedra** | partiel : portrait animé + audio, pas de twin | aucune vérification ; AUP interdit les médias d'autrui | ✅ | non (audio externe) | 3,75 $/min 1080p | UNVERIFIED | écarté |
| **ElevenLabs** (voix) | PVC : ≥ 30 min d'audio, captcha vocal lu en direct, « only your own voice », 39 langues dont FR / EN | vérification de propriété obligatoire | n/a | 1 slot PVC Creator (22 $/mois) → **2 comptes** pour 2 fondateurs, ou Scale 299 $ | TTS 0,10 $/1k car. ; Scribe 0,22 $/h (sous-titres) | UNVERIFIED | `audio_url` accepté par HeyGen |
| Open source local (LatentSync 1.6, MuseTalk 1.5) | lip-sync sur vraie vidéo tournée | à organiser soi-même | selon footage | non | GPU 16-24 Go | local | faisable en solo, coût d'ingénierie, pas de C2PA |

### 9.2 Recommandation

**Principal : HeyGen API v3** (jumeau par API, consentement intégré et traçable, 9:16 natif, SRT, prix à la minute sans abonnement ; voix ElevenLabs PVC via `audio_url` si le clone FR déçoit). **Plan B : Synthesia Creator** (89 $/mois, consentement en direct, minutes plafonnées). **Low-cost : D-ID Launch** (35 $/mois annuel).

Coûts : pilote 5 vidéos ≤ 60 s en FR + EN ≈ **60-90 $** ; industrialisation 8-12 vidéos / mois × 2 langues ≈ 12-18 min ≈ **100-145 $/mois** (HeyGen 48-72 $ + Creator 29 $ + ElevenLabs 22-44 $), Synthesia ≈ 90-110 $, D-ID ≈ 35-60 $. À intégrer au plafond D11.

### 9.3 Checklist réglementaire et éthique

1. **AI Act art. 50(4)** (Règlement (UE) 2024/1689, applicable depuis le **2 août 2026**) : le déployeur d'un système produisant un *deep fake* « shall disclose that the content has been artificially generated or manipulated ». Définition art. 3(60) : contenu ressemblant à des personnes existantes et paraissant authentique. **Le consentement des fondateurs ne lève pas l'obligation.** L'exception « œuvre manifestement artistique, créative, satirique » ne couvre pas clairement une vidéo marketing → étiqueter systématiquement.
2. **Art. 50(5)** : information « clear and distinguishable » **dès la première exposition** → label visible **à l'écran dès la première seconde**, pas seulement en description. Lignes directrices de la Commission (20/07/2026) : le marquage machine du fournisseur (50(2)) ne suffit pas au déployeur.
3. **Sanctions art. 99(4)** : jusqu'à 15 M€ ou 3 % du CA mondial, proportionnées pour les PME.
4. **Code de bonne conduite** (10/06/2026, section 2 déployeurs) : signature volontaire ; **icônes UE** « fully AI-generated » / « partially AI-modified » disponibles, à afficher dès la première exposition.
5. **RGPD** : image et voix = données personnelles ; l'appariement face / voix des vidéos de consentement = biométrie (art. 4(14)) → **consentement explicite** art. 9(2)(a), retirable (art. 7(3)) ; la société reste responsable de traitement même pour ses fondateurs : consentement écrit individuel, finalités, durée, sous-traitants US (DPF + SCC), registre ; AIPD recommandée (obligation UNVERIFIED).
6. **Droit à l'image et à la voix** : licence écrite de chaque fondateur au profit de la société (plateformes, langues, pays, durée, validation de chaque script, clause de sortie).
7. **Code pénal art. 226-8** (loi SREN 2024) : diffusion d'un contenu généré « par un traitement algorithmique » sans consentement et sans mention expresse : 1 an / 15 000 €, 2 ans / 45 000 € en ligne. Avec consentement écrit : inapplicable ; la mention expresse reste une seconde protection.
8. **Plateformes** : Meta auto-déclaration exigée (`is_ai_generated`) ; TikTok label obligatoire (`is_aigc`, irréversible, « n'affecte pas la diffusion ») ; YouTube `containsSyntheticMedia` (non-divulgation répétée → label forcé, retrait, exclusion du programme partenaire ; le clone de sa propre voix seul est exempté, l'avatar photoréaliste ne l'est pas) ; Pinterest label automatique « AI modified » + `ai_disclosures` ; LinkedIn icône C2PA sans déclaration obligatoire. Aucune plateforme ne documente d'effet de portée du label.
9. **Mentions type** — FR : « Vidéo générée par IA : avatars numériques de Catherine et Alain, créés avec leur accord. » — EN : "AI-generated video: digital avatars of Catherine and Alain, created with their consent." À l'écran dès la première seconde + description + icône UE.
10. **Conservation** : footage et vidéos de consentement hors plateforme, chiffrés, durée bornée ; suppression chez le fournisseur en fin d'usage ; registre des publications avec champ IA renseigné (`variant.is_ai_generated`, `ai_disclosure_text`).
11. **Kill switch** : retrait de consentement d'un fondateur → suppression de l'avatar et de la voix chez le fournisseur, révocation des clés, dépublication ou archivage, journal. À tester pendant le pilote.
12. **Provenance C2PA** : conserver les métadonnées si le fournisseur en produit (HeyGen : aucune mention, UNVERIFIED).
13. **Gate éditorial** : validation humaine de chaque script avant rendu ; jamais de propos non validés prêtés aux fondateurs (HITL forcé, [01](01-architecture.md) §5.2).

### 9.4 Sources (consultées le 2026-09-12)

- https://www.heygen.com/pricing — HeyGen Pricing · https://help.heygen.com/en/articles/10060327-heygen-api-pricing-explained — API Pricing · https://developers.heygen.com/docs/avatar-consent — Avatar Consent · https://developers.heygen.com/docs/create-avatar — Create Avatar · https://developers.heygen.com/reference/create-video — Create Video · https://help.heygen.com/en/articles/12092609-recording-your-consent-video — Consent Video · https://www.heygen.com/security — Security · https://www.heygen.com/terms — Terms
- https://www.synthesia.io/pricing — Synthesia Pricing · https://docs.synthesia.io/docs/personal-avatars.md — Personal Avatars · https://help.synthesia.io/en/articles/9453224-how-do-i-create-my-personal-avatar-from-a-video — Personal avatar (consentement live) · https://help.synthesia.io/en/articles/8272053-variable-video-aspect-ratio — Aspect ratios
- https://www.d-id.com/pricing/api/ — D-ID API Pricing · https://docs.d-id.com/reference/create-express-avatar — Create an Express Avatar · https://docs.d-id.com/reference/create — Create a consent
- https://www.tavus.io/pricing — Tavus Pricing · https://docs.tavus.io/api-reference/phoenix-replica-model/create-replica — Create Face · https://www.tavus.io/acceptable-use-policy — AUP
- https://docs.argil.ai/resources/api-pricings — Argil API Pricing · https://captions.ai/solutions/ai-twin — Captions AI Twin · https://www.hedra.com/acceptable-use — Hedra AUP
- https://elevenlabs.io/docs/product-guides/voices/voice-cloning/professional-voice-cloning — Professional Voice Cloning · https://elevenlabs.io/pricing — ElevenLabs Pricing
- https://github.com/bytedance/LatentSync — LatentSync · https://github.com/TMElyralab/MuseTalk — MuseTalk
- https://eur-lex.europa.eu/legal-content/EN/TXT/PDF/?uri=OJ:L_202401689 — Règlement (UE) 2024/1689 (art. 3(60), 50, 99, 113)
- https://digital-strategy.ec.europa.eu/en/faqs/transparency-obligations-under-article-50-ai-act — FAQ Article 50 · https://digital-strategy.ec.europa.eu/en/policies/guidelines-transparency-ai-generated-content — Guidelines (20/07/2026) · https://digital-strategy.ec.europa.eu/en/policies/code-practice-ai-generated-content — Code of Practice (10/06/2026) · https://digital-strategy.ec.europa.eu/en/policies/eu-icons-labelling-ai-generated-content — EU Icons
- https://www.cnil.fr/fr/reglement-europeen-protection-donnees/chapitre2 — RGPD art. 7 et 9 · https://www.service-public.gouv.fr/particuliers/vosdroits/F32103 — Droit à l'image · https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000049571542 — Code pénal art. 226-8
- https://about.fb.com/news/2024/04/metas-approach-to-labeling-ai-generated-content-and-manipulated-media/ — Meta labeling · https://www.tiktok.com/tns-inapp/pages/ai-generated-content — TikTok AIGC · https://support.google.com/youtube/answer/14328491 — YouTube disclosure · https://help.pinterest.com/en/article/gen-ai-labels — Pinterest Gen AI labels · https://www.linkedin.com/help/linkedin/answer/a6282984 — LinkedIn Content Credentials

---

## 10. Ce qui n'est pas automatisable proprement (liste consolidée)

| Action | Pourquoi | Ce que fait l'agent à la place |
|---|---|---|
| Liker, suivre, reposter des comptes tiers | non exposé (Instagram repost, Pinterest) ou interdit sans action humaine explicite (YouTube III.I.2, LinkedIn §8.2.13) ; contraire au brief §6 | rien ; l'humain like |
| Stickers Instagram (lien, sondage), musique licenciée | non exposés par l'API | Stories « riches » posées à la main ; Stories API = image / vidéo simples |
| Lien cliquable dans une caption Instagram / TikTok ou un Short | plateformes | lien de bio traçable, page « lien en bio », mention incrustée |
| Webhooks commentaires Instagram sans Advanced Access | Meta | polling parcimonieux ou agrégateur |
| Publication YouTube publique sans audit | Google | agrégateur au MVP ; audit si connecteur direct plus tard |
| Publication TikTok publique sans audit ; commentaires et insights TikTok sans Accounts API | TikTok | agrégateur au MVP ; deux dossiers si connecteur direct plus tard |
| Photos et `PULL_FROM_URL` TikTok sans domaine vérifié | TikTok | sous-domaine média contrôlé par l'agent, vérifié une fois |
| Lecture des stats et commentaires d'un profil LinkedIn personnel | permissions fermées | page entreprise via agrégateur |
| Refresh de token LinkedIn | partenaires seulement | rappel de ré-authentification à J+55 |
| Commentaires Pinterest | aucun endpoint dans l'API v5 (confirmé §4.4), donc aucun agrégateur ne les expose | Pinterest = publication + analytics ; commentaires traités dans l'application |
| Pins publics en accès Trial | Pinterest : entités Sandbox visibles du seul créateur | accès Standard (vidéo de démonstration) ou agrégateur |
| Miniature personnalisée d'un Short | YouTube Studio seulement | première image = cover choisie au montage |
| Planification native Instagram / LinkedIn | absente | planification côté agent |
| DM | hors MVP (D12) | — |

---

## 11. Démarches administratives et délais (checklist)

| Démarche | Nécessaire au MVP (agrégateur) ? | Nécessaire pour un connecteur direct ? | Délai indicatif | Qui |
|---|---|---|---|---|
| Comptes professionnels : Instagram Business + Page Facebook, TikTok Business, Pinterest Business + site revendiqué, chaîne YouTube, page LinkedIn | **Oui** | Oui | 1-2 jours (revendication Pinterest par balise / fichier) | Alain |
| App Meta type Business en mode développement, rôles | Non | Oui (Instagram) | 1 jour | Claude + Alain |
| Advanced Access + Business Verification Meta | Non | Seulement pour les webhooks commentaires | semaines, documents d'entreprise | Alain |
| Audit YouTube API Services + OAuth « In production » | Non | Oui | non publié | Alain |
| Audit TikTok Content Posting (vidéos démo, UX imposée) + formulaire Accounts API (commentaires, insights) | Non | Oui (deux dossiers) | « several days to two weeks » pour l'audit ; Accounts API non publié | Alain + Claude |
| Pinterest Standard access (vidéo du flux OAuth exigée même pour un usage propre) | Non | Oui | « a few days » | Alain + Claude |
| LinkedIn Community Management | Non | Oui (page) | non publié | Alain |
| DPA agrégateur, fiche registre RGPD, amendement politique de confidentialité | **Oui** | Oui | 1 jour | Alain + Claude |
| Plausible plan Business, plugin WP Consent API, `/go/`, champs UTM | **Oui** (mesure) | Oui | ½ journée après le 30/09 | Claude + Alain |
