# KH Social Agent — Points nécessitant une décision humaine

- **Date** : 2026-09-12 (révisé après revue adversariale)
- **Statut** : 🟡 aucune décision rendue. Chaque ligne propose une recommandation ; **Alain tranche**, Catherine pour ce qui la concerne.
- **Convention** : `D<n>` référencé depuis [01-architecture.md](01-architecture.md), [02-apis-plateformes.md](02-apis-plateformes.md), [03-mvp-backlog.md](03-mvp-backlog.md).

Les décisions D1, D2, D3 conditionnent le démarrage. D14 contient un point de **conformité du site** indépendant de l'agent, à traiter avant le 30/09.

---

## Bloquantes pour démarrer

### D1 — Publier via un agrégateur ou via des connecteurs directs ?

| Option | Pour | Contre |
|---|---|---|
| **A. Agrégateur au MVP** (Zernio ~18 $/mois ou Upload-Post ~19 €/mois, cf. [02](02-apis-plateformes.md) §8) | aucun audit à porter ; 5 plateformes couvertes dont Stories, TikTok photo, Pinterest vidéo, Shorts, page LinkedIn ; analytics et commentaires par API ; opérationnel en quelques jours | dépendance à une petite société ; DPA à obtenir ; commentaires de tiers transitant par un sous-traitant ; Pinterest sans commentaires (chez tous, et dans l'API directe) ; profil LinkedIn perso sans commentaires |
| **B. Connecteurs directs** | contrôle total, aucun intermédiaire de données | Instagram réalisable sans review pour un compte propre, mais sans webhooks commentaires ; **TikTok** publie en privé sans audit et n'a ni commentaires ni insights sans un second dossier Accounts API ; **YouTube** verrouille en privé sans audit ; **Pinterest** exige l'accès Standard avec vidéo de démonstration ; **LinkedIn** ré-authentification tous les 60 jours. Plusieurs semaines de démarches avant la première publication publique |
| **C. Hybride** | A pour publier vite, B ajouté plateforme par plateforme quand un audit est obtenu | double maintenance |

**Recommandation : A, avec l'interface `Publisher` qui rend C possible sans refonte, et la planification gardée côté agent** (le kill switch ne dépend pas du fournisseur). Choisir le fournisseur sur deux critères : DPA public et hébergement UE (avantage Upload-Post), webhook commentaires (avantage Zernio). Tester les deux une semaine avant d'engager l'annuel.

### D2 — Quand démarrer, par rapport au lancement du site du 30 septembre 2026 ?

Le site n'est pas prêt (Stripe, SMTP, menus, homepage, images produits). La priorité absolue reste le lancement.

**Recommandation** :
- **Avant le 30/09 : zéro heure de développement.** Seulement : réserver les handles (D17, 1 h), obtenir les consentements écrits de Catherine et d'Alain (KHS-003), valider cette architecture. Pas de comptes professionnels, pas de Content Brain, pas de repo, pas de tournage dédié avant le lancement.
- **Semaine du 5 octobre** : Phase 0 (comptes, agrégateur, Content Brain v0, repo, VPS, tournage initial) puis Phase 2 (Content Engine) ; les captions produites sont publiées **à la main** via l'agrégateur dès début novembre.
- **Mi-novembre à début janvier** : Phase 3 (validation + publication automatique), puis **point GO / STOP**.
- **Janvier-février 2027** : Phases 4 et 5. Phases 6 et 7 au printemps 2027 selon D8.

La capacité réelle est de 10-15 h / semaine **tout compris** (développement, exploitation, production de contenu) : le calendrier de [03](03-mvp-backlog.md) §2 en tient compte. Si MaïJinn reprend le focus principal en octobre, décaler d'un mois mais garder la publication manuelle assistée.

### D3 — Comptes, handles, politique de langue et de destinations

Questions : un compte par plateforme ou deux (FR / EN) ? Quelle langue par plateforme ? Quel handle ? Où envoyer un visiteur américain tant que la zone USA est en « nous contacter » ?

**Recommandation** :
- **Un seul compte par plateforme**, handle `koinoborihouse` partout (fallback `koinobori.house`). Deux audiences séparées tôt = deux croissances lentes.
- Instagram, TikTok, YouTube : **captions bilingues** (FR puis EN court, ou l'inverse selon le sujet ; sous-titres vidéo dans les deux langues) ; hashtags mixtes.
- Pinterest : **tableaux par langue**, épingles EN majoritaires (décoration, mariage, lifestyle), boards FR pour mariage et décoration.
- LinkedIn : profil d'Alain en FR, page entreprise bilingue quand elle existe.
- **Destinations géographiques** (`account.geo_policy`) : tant que la zone USA n'est pas active, les liens en anglais pointent vers la page Livraison ou des destinations UE, **jamais vers une fiche produit non achetable depuis les États-Unis** (trafic non qualifié, exposition inutile face à Etsy). Le rapport hebdo mesure la part US ; l'ouverture des liens produit EN suit l'activation de la zone USA (KH-015).
- `account.language_policy` porte le choix de langue ; révisable par plateforme quand les données parlent.

---

## À trancher avant la Phase 3 (publication)

### D4 — Qui valide quoi ?

Contrainte : rien ne se publie sur Catherine ou en son nom sans sa validation (O-4, charte §7.3).

**Recommandation** : Alain = approbateur des contenus sans Catherine. **Catherine approuve elle-même** tout contenu où elle apparaît (image, avatar, voix) ou qui porte son persona (`pillar_person = CATHERINE`), depuis une file dédiée dans l'UI ; à défaut, une **délégation écrite, bornée et datée** à Alain, enregistrée dans `personas/catherine.md` (ex. « captions de mon persona sans image de moi, jusqu'au 31/12/2026 »). Le persona lui-même est validé par elle avant KHS-103. Règle d'intégrité correspondante dans [01](01-architecture.md) §4.3.

### D5 — Niveaux d'autonomie initiaux

**Recommandation** : `SEMI_AUTO` sur tous les comptes et territoires ; `HITL` forcé par la sensibilité (partenariat, juridique, crise, annonce, prix, personne tierce hors liste blanche). **Aucune réponse automatique aux commentaires au MVP** ; réévaluer après 4 semaines de précision et de rappel mesurés, sur la catégorie `COMPLIMENT` uniquement. Les niveaux se règlent depuis l'UI, jamais par une PR de l'agent.

### D6 — Frontière doctrine du territoire Making-of

Le brief cite « prototypes », « réception d'échantillons », « évolution dessin → produit fini ». La doctrine interdit toute mention d'atelier ou de production.

**Recommandation** : Making-of = **geste créatif seulement** (croquis, choix de couleurs, motifs, mises en couleur, essais de composition, prototype montré comme objet de design). Jamais : réception d'échantillons, déballage, matières premières, lieu, partenaire, délai, « avant / après fabrication ». Les mots `prototype` et `échantillon` passent en `FLAG` (relecture humaine), pas en `BLOCK` ; les plans de déballage / réception sont interdits, et la **passe visuelle** vérifie que les images ne montrent ni étiquettes, ni cartons, ni documents ([01](01-architecture.md) §7.5). À confirmer ou durcir.

### D7 — LinkedIn : profil d'Alain seulement, ou page entreprise dès le début ?

Faits vérifiés ([02](02-apis-plateformes.md) §6) : le profil personnel se publie en self-serve mais **sans analytics ni lecture des commentaires** par API, avec **ré-authentification tous les 60 jours** ; la portée de la clause « automate posting » des API Terms of Use reste **non vérifiée** ; la page entreprise exige l'accès Community Management (délais non publiés) **ou** passe par l'agrégateur.

**Recommandation** : profil d'Alain en **publication manuelle** (export KHS-105) tant que la clause n'est pas lue en texte intégral, d'autant que ce compte sert aussi à MaïJinn et JDIC ; **créer la page entreprise** dès la Phase 0 et la brancher sur l'agrégateur (analytics + commentaires). Pas de demande Community Management au MVP.

### D8 — Avatars : fournisseur, pilote, consentement de Catherine, texte de divulgation

Trois sous-décisions :
1. **Consentement écrit de Catherine et d'Alain** pour la création d'un jumeau vidéo et d'un clone de voix (biométrie au sens RGPD), avec droit de retrait, durée et licence d'image / voix au profit de la société. Sans le consentement de Catherine, seul l'avatar d'Alain est envisageable, et le persona Catherine reste en vraies vidéos.
2. **Fournisseur** : HeyGen API v3 en principal, Synthesia Creator en plan B, D-ID Launch en low-cost ([02](02-apis-plateformes.md) §9.2). Pilote de 5 vidéos ≈ 60-90 $ avant tout abonnement ; industrialisation 8-12 vidéos / mois ≈ 100-145 $/mois.
3. **Divulgation** : l'AI Act art. 50(4)-(5) (applicable depuis le 2 août 2026) impose une mention **visible dès la première seconde** de la vidéo, pas seulement en description, **même avec le consentement des fondateurs**. Texte type : « Vidéo générée par IA : avatars numériques de Catherine et Alain, créés avec leur accord. » / "AI-generated video: digital avatars of Catherine and Alain, created with their consent." + champs natifs (`is_ai_generated` Instagram, `is_aigc` TikTok, `containsSyntheticMedia` YouTube, `ai_disclosures` Pinterest) + icône UE optionnelle. Checklist complète en [02](02-apis-plateformes.md) §9.3.

**Recommandation** : décider 1 maintenant, 2 et 3 en Phase 7. Pas d'avatar avant le printemps 2027. Le label permanent à l'écran est une contrainte créative forte : à peser contre de vraies vidéos tournées (D9).

### D9 — Part de vraies vidéos humaines

Le brief exige que les avatars prolongent la présence sans la remplacer.

**Recommandation** : sur les trois premiers mois d'avatars, **au moins une vidéo sur deux** avec de vraies images (visage, mains, table de dessin, installations). Ratio suivi par le Learning Engine (`asset.origin`), révisable sur données. Corollaire : la production de contenu réel (4-6 h / semaine) est budgétée dans la capacité, pas « en plus ».

### D10 — Dépôt et hébergement

**Recommandation** : nouveau repo privé `Ahlaoban/kh-social-agent` (protection de branche, CODEOWNERS sur `prompts/`, `strategy/`, `content-brain/doctrine.md`) ; VPS Docker Compose chez un hébergeur UE (OVH ou Hetzner, ~10 €/mois) + stockage objet UE (~2-5 €/mois) ; docs d'architecture maintenues ici dans `docs/ecosystem/`. o2switch mutualisé écarté pour le worker.

### D11 — Plafond budgétaire mensuel d'outillage

Le brief ne fixe pas de budget ; la cible « < 100 € » vient du cadrage agrégateur. Estimation révisée :

| Poste | Estimation | Note |
|---|---|---|
| Agrégateur | 18-25 € | Zernio ou Upload-Post |
| VPS + stockage objet | 12-15 € | |
| Claude API | **40-120 €** | 20-30 contenus + gate + commentaires + analyse hebdo ; le thinking adaptatif d'Opus est facturé en sortie ; Sonnet 5 en `effort` bas pour le pré-tri et l'extraction ; **plafond dur dans la console** + alerte à 80 % |
| Plausible Business | ≈ 19 $ | **optionnel au MVP** (attribution WooCommerce + journal `/go/` suffisent) ; coût nouveau, Plausible n'étant pas encore installé sur le site |
| Avatars (Phase 7) | 100-145 $ | hors enveloppe MVP, décision séparée D8 |

**Recommandation** : plafond **≤ 150 €/mois** pour le MVP sans Plausible ni avatars, avec revue mensuelle des coûts réels après trois mois ; les avatars font l'objet d'une enveloppe distincte.

### D12 — Messages privés (DM)

**Recommandation** : **hors MVP**. Les DM sont un canal de service client et de prospection 1-to-1 (Sales Agent), avec des permissions et un traitement RGPD distincts. Réévaluer avec le KH Sales / Follow-up Agent.

### D13 — RGPD : registre, DPA, conservation, politique de confidentialité

Faits : les commentaires sont des données personnelles de tiers ; l'agrégateur est un sous-traitant (DPA public chez Upload-Post, sous NDA chez Zernio, stockage US sous SCC chez Ayrshare) ; les aperçus envoyés par Telegram ou e-mail contiennent ces commentaires ; YouTube impose ≤ 30 jours de conservation des données non analytiques ; la politique de confidentialité du site (KH-017) ne couvre pas ce traitement.

**Recommandation** : fiche de traitement « animation des réseaux sociaux » au registre, avec l'agrégateur **et** Telegram / e-mail comme sous-traitants ; DPA signé ; **rétention par plateforme** (YouTube 30 jours, autres 90 jours) pour les interactions non commerciales, leads réduits aux champs extraits après cette durée ; amendement de la politique de confidentialité **après le lancement** ; aucune donnée d'auteur dans les logs applicatifs.

### D14 — Changements côté site

Deux lots distincts :

1. **Conformité du site, indépendante de l'agent, à traiter avant le 30/09** : sans le plugin **WP Consent API**, WooCommerce pose ses cookies d'attribution `sbjs_*` **sans consentement**, malgré Complianz ([02](02-apis-plateformes.md) §7.1). Deux options de 5 minutes : installer WP Consent API (Complianz le supporte), ou désactiver « Order Attribution » dans WooCommerce › Réglages › Avancé › Fonctionnalités jusqu'à l'installation. Ce point relève de la checklist de lancement, pas du Social Agent.
2. **Après le 30/09, pour l'agent** : page « lien en bio » (`noindex`, manuelle), mu-plugin `/go/<code>` (table de codes versionnée, cibles restreintes au domaine, `no-store`, journal, exclusion LiteSpeed), champs cachés UTM sur les formulaires Fluent Forms 5-12 (cookie de conservation des UTM **uniquement sous consentement `marketing`**), utilisateur WordPress dédié à capacité minimale avec Application Password et liste blanche IP pour lire les soumissions, Plausible plan Business en option.

**Recommandation** : lot 1 immédiatement (Alain, 5 min) ; lot 2 groupé sur une demi-journée en octobre.

Option distincte, à évaluer plus tard : le plugin officiel **Pinterest for WooCommerce** (catalogue produits → product pins, tag, Rich Pins) fait des épingles produit sans code mais exige un site revendiqué et un compte publicitaire ; note utilisateurs médiocre, charge supplémentaire sur o2switch ([02](02-apis-plateformes.md) §4.6). Hors MVP.

---

## À trancher plus tard

### D15 — Calendrier Kaïro et vocabulaire autorisé

Tant que la date de la BD n'est pas arrêtée : **aucune date, aucune saison** (« cet automne » compris), aucune promesse de sortie pour la BD ni pour les épisodes 5-15 (B-4). Formules autorisées : « un nouveau chapitre se prépare », « l'histoire continue ». Le Content Brain ne contient que les épisodes 1-4 et les **titres** des chapitres suivants, sans synopsis (le gate bloque les dates, pas les spoilers). Décider le plan de lancement social de la BD quand la date existe (campagne dédiée).

### D16 — Droits UGC (World Gallery, photos clients)

**Recommandation** : formulaire de consentement type (FR/EN) envoyé par DM ou e-mail avant toute republication ; mention du compte auteur ; stockage du consentement dans `asset.rights` ; passe visuelle comme pour tout média. Sans consentement écrit, la photo reste dans la galerie privée.

### D17 — Réserver les handles maintenant

Coût nul, risque de squat réel une fois le site public. **Recommandation : réserver dès cette semaine**, comptes en privé, sans lien depuis le site (O-5 maintenu). C'est la seule action recommandée avant le 30/09 avec les consentements.

### D18 — Page Facebook et Threads

Une **Page Facebook** est requise pour lier un compte Instagram Business (Stories via API, exigence des agrégateurs). **Recommandation** : créer la Page en Phase 0, la laisser non publiée ou minimale ; aucune publication Facebook au MVP. **Threads** : API simple et audience adjacente à Instagram ; hors MVP, à activer en Phase 6 si Instagram performe.

### D19 — Auto-approbation par l'agent

**Hors MVP.** Ne se pose qu'en Phase 6, après 8 semaines de mesure du taux d'édition humaine par territoire. Critère proposé : < 10 % de caractères modifiés et zéro `FLAG` non détecté sur la période. Si elle est un jour activée, elle exige un nouveau `decided_by = AGENT` explicitement autorisé dans `autonomy_policy`, territoire par territoire.

### D20 — Contrat `lead.detected` avec le KH Sales / Follow-up Agent

Le brief frère proposé par Alain doit consommer `lead.detected` v1 ([01](01-architecture.md) §9.2). À figer ensemble avant la Phase 5 : champs, transport (webhook signé), accusé de réception, statut retour (`lead.status_changed`), rétention partagée.
