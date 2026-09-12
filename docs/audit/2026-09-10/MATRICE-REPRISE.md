# Matrice de reprise KH2027 — v0.5

Base : `d3073da`, audit du 10 septembre 2026. [Rapport et preuves](RAPPORT-AUDIT-KH2027.md). [Données complètes structurées](MATRICE-REPRISE.json).

**57 composants couverts. G0 non acquis. Aucune décision exécutée.** Les classes portent sur le périmètre explicitement observé ; une classe vide signifie non attribuée, et non BUILD. Un KEEP d’actif/archive ne certifie ni ses droits ni une intégration serveur. Les BUILD concernent des livrables manquants dans le dépôt, sans présumer des outils externes. REMOVE M21 vise le diagnostic dans le déploiement normal après usage, sans supprimer sa source.

Les six classes sont disponibles : KEEP conserver, IMPROVE améliorer localement, REFACTOR préserver le comportement en corrigeant la structure, REPLACE remplacer après comparaison/migration/retour, REMOVE retrait maîtrisé, BUILD construire après preuve d’absence. **Aucun REPLACE justifié à ce stade.**

Répartition : BUILD 4, IMPROVE 27, KEEP 4, NON ATTRIBUÉE 19, REFACTOR 2, REMOVE 1.

Les fourchettes couvrent les interventions décrites et se chevauchent ; elles ne constituent pas un devis ni une somme de budget. L’ordre est un identifiant de lecture ; la priorité opérationnelle et les dépendances priment (rapport §10). Responsable technique à désigner, Alain approbateur métier : aucune validation supposée.

| ID | Composant | Classe proposée | Priorité / phase | Charge j6h | Preuve / réserve |
|---|---|---|---|---|---|
| [M01](#m01) | Baseline Git et branches | KEEP | P0/F0 | 0–0.25 | V S04/S05 : HEAD distant identique et inventaire des fichiers ; conservation des références |
| [M02](#m02) | Garde-fous et CI | IMPROVE | P0/F0 | 1–2 | V S13 : protected=false et 0 workflow ; convention PR existante |
| [M03](#m03) | Documentation et préséance | IMPROVE | P0/F0 | 0.5–2 | V S02/S03 : dates, stack verrouillée et instructions anciennes en conflit avec cibles |
| [M04](#m04) | Référence créative archivée | KEEP | P1/F1 | 0–0.5 | V S05/S10 : fichiers et empreintes contrôlés ; conservation comme source, pas validation du rendu final |
| [M05](#m05) | Identité de marque | KEEP | P1/F1 | 0–0.5 | V S05 : neuf fichiers présents ; KEEP limité à la préservation binaire, droits NV |
| [M06](#m06) | Masters et fichiers ignorés | KEEP | P0/F0 | 0.5–1 | V S05 : inventoriés ; stockage de sauvegarde externe NV |
| [M07](#m07) | WordPress cœur et base | IMPROVE | P0/F0 | 0.5–1.5 | V S06/S16 : WP7.1 annoncé prod et état WC staging ; PHP8.3.33 MariaDB11.4.13 staging ; checksums/config complets NV ; V S19 : racines/bases identifiées et checksums cœur conformes sur les deux sites ; fichiers error_log supplémentaires à contrôler |
| [M08](#m08) | WooCommerce et autorité commerce | IMPROVE | P0/F1 | 1–3 | V S16 : WC11.1.0, base11.1.0-1, HPOS actif ; blocs panier/checkout ; transactions NV ; V S17 : panier anglais vide partiellement français ; V S19 : absent de production ; staging HPOS 4 checkout-draft et 1 completed, nature réelle/test non établie |
| [M09](#m09) | Polylang et Polylang for WC | IMPROVE | P0/F1 | 0.5–2 | V S16/F05 : PLL3.8.7 et PLLWC2.2.4, racines FR/EN blog vide mais pages longues présentes ; cause NV ; V S17 : trois paires légales/USA présentes sans liaison affichée |
| [M10](#m10) | SEOPress et sitemaps | IMPROVE | P1/F1 | 0.5–1.5 | V S16 : SEOPress10.2, canonical accueil vers racine en désaccord de rendu ; sitemap exhaustif NV |
| [M11](#m11) | Kadence parent / Blocks | IMPROVE | P1/F1 | 0.5–1.5 | V S16 : Kadence1.5.2 staging, classique ; aucun Kadence Blocks dans14 extensions ; licence NV ; V S19 : parent production 1.5.0 confirmé par WP-CLI ; conserver le socle et vérifier compatibilité sur clone |
| [M12](#m12) | Intégration PHP du thème enfant | IMPROVE | P1/F1 | 0.5–1.5 | V S16/F02 : staging1.1.0 mais charte v3 non liée, dépôt la charge ; identité release non fiable ; V S19 : functions.php différent après normalisation CRLF/LF ; charte v3 absente du staging ; lint PHP réussi |
| [M13](#m13) | Feuilles de style dupliquées | REFACTOR | P1/F1 | 1–3 | V S05/F08 : égalité après normalisation URL ; dette démontrée |
| [M14](#m14) | Palette / contraste / liens | IMPROVE | P1/F1 | 0.5–2 | V S05/F09 : or inférieur aux seuils textuels, kh-white absent éditeur ; V S19 : theme.json staging diffère réellement du dépôt après normalisation CRLF/LF |
| [M15](#m15) | Assemblages de pages versionnés | BUILD | P1/F1 | 3–8 | V S05/F08 : aucun assemblage versionné ; classe limitée au dépôt, blocs en base NV |
| [M16](#m16) | Header Shoji et navigation | IMPROVE | P1/F1 | 1–3 | V F07/S16 : prototype liens # ; menu staging sept entrées, Accueil /fr/ différent du lien logo /fr/accueil/ |
| [M17](#m17) | Polices et outil de préparation | IMPROVE | P1/F1 | 0.5–1.5 | V S05/F09 : 21 WOFF2 ; chemin Windows codé, sources main non figées, licences à réunir |
| [M18](#m18) | Médias du thème et produits | IMPROVE | P1/F1 | 1–3 | V S05/F09 : 13 images thème, formats WebP ; poids élevés de certains masters Web |
| [M19](#m19) | Routage racine | IMPROVE | P1/F1 | 0.5–1.5 | V S16 : MU routage1.1.0 seul chargé ; code relu, variantes REST/query/cookies non recettées ; V S19 : fichier staging identique au dépôt après normalisation, lint réussi ; absent de production |
| [M20](#m20) | Correction signature BCDG | IMPROVE | P1/F1 | 0.25–1 | V S16/F10 : absent de la liste MU staging ; correctif1.1.0 local, tests intégration NV ; V S19 : absence du fichier confirmée dans les deux racines |
| [M21](#m21) | Diagnostic temporaire accueil | REMOVE | P1/F0 | 0.25–0.5 | V S16 : diagnostic absent de la liste MU ; retrait recommandé du déploiement normal après tout usage futur, source conservée ; V S19 : absence du fichier confirmée dans les deux racines |
| [M22](#m22) | Affichage taille unique | IMPROVE | P1/F1 | 0.5–2 | V S16/F10 : plugin1.0.0 actif ; défauts potentiels mono-option/multi-attribut relevés ; panier NV ; V S19 : fichier staging identique après normalisation et lint réussi ; absent de production |
| [M23](#m23) | Source catalogue et imports | REFACTOR | P0/F0 | 2–5 | V F04/S09 : sources partielles, anciennes taxonomies et SKU incompatibles |
| [M24](#m24) | Données WC / variantes / taxes | IMPROVE | P0/F1 | 1–3 | V S16/S18 :34 produits publiés ; une zone France forfait5/gratuit55, aucune méthode reste du monde malgré promesse UE/DROM ; taxes désactivées ; checkout NV |
| [M25](#m25) | Paiement Stripe et PayPal | IMPROVE | P0/F1 | 1–3 | V S16/S18 : Stripe10.9.1 test et configuration requise ; PayPal4.1.2 Compte de test ; transactions/webhooks NV |
| [M26](#m26) | SMTP / e-mails commerciaux | IMPROVE | P0/F1 | 0.5–2 | V S16 : FluentSMTP2.3.1 affiche doit être configuré ; transport effectif et délivrabilité NV |
| [M27](#m27) | Formulaires FR/EN / leads | Non attribuée | P0/F1 | 1–3 | V S16 : Fluent Forms6.2.13, huit formulaires5–12 actifs zéro entrée affichée ; définitions/notifications NV |
| [M28](#m28) | Rétractation et accusé durable | IMPROVE | P0/F1 | 0.5–2 | V S16/F12 : formulaires11/12 actifs ; promesse CGV existante, accusé durable et parcours non recettés ; V S17 : formulaires rendus FR/EN avec confirmation, aucun envoi |
| [M29](#m29) | Corpus juridique / identité / origine | IMPROVE | P0/F1-F2 | 1–3 | V F12/S11 : corpus et ambiguïtés BCDG ; écart doctrine cible ; V S17 : liaisons mentions et cookies manquantes |
| [M30](#m30) | Cookies / consentements | Non attribuée | P0/F1 | 0.5–2 | V S16 : Complianz7.5.4 actif ; blocage réel/refus/retrait/traceurs NV |
| [M31](#m31) | Sauvegardes et restauration | IMPROVE | P0/F0 | 1–3 | V S16 : neuf ensembles listés dont09/09 17h57, base daily7/fichiers weekly4, Drive déclaré connecté ; intégrité/restauration NV ; V S19 : JetBackup 32 copies par base KH, dernières09/09 ; 0 archive backup_ directe dans updraft staging ; intégrité et restauration NV |
| [M32](#m32) | Déploiement et exports répétables | BUILD | P0/F0 | 2–5 | V S05/F02 : pas de release ni export rejouable versionné ; service externe NV |
| [M33](#m33) | Scripts de recette existants | IMPROVE | P1/F0 | 0.5–2 | V F11 : scénarios utiles, TLS désactivé, paramètres sensibles |
| [M34](#m34) | Outillage images sharp | IMPROVE | P1/F0 | 0.5–1.5 | V S12 : sharp0.33.5, audit high ; usage serveur non établi |
| [M35](#m35) | Sécurité serveur / MFA / rôles | IMPROVE | P0/F0-F1 | 1–3 | V S16 : Wordfence9.0.1, intégration Login Security WC inactive,6 mises à jour proposées ; MFA/permissions NV ; V S19 : uploads0777 et wp-config0644 sur les deux sites ; compte système partagé ; protection Basic staging ; exploitation/MFA NV |
| [M36](#m36) | Cache / jobs / logs / alertes | IMPROVE | P0/F1 | 1–3 | V S16 : LSCache page indisponible, pas de cache objet externe déclaré ;7 échecs fetch_patterns sans callback ; supervision NV ; V S19 : aucun drop-in PHP observé à la racine wp-content staging ; logs supplémentaires dans les cœurs WP, contenu et accès public NV |
| [M37](#m37) | État des comptes clients | Non attribuée | P0/F1 | 1–3 | V S18 : invité et inscriptions checkout/compte actifs ; récupération, identité et cloisonnement non recettés |
| [M38](#m38) | Organisations et permissions métier | Non attribuée | P0/F2 | 3–7 | V S05 : aucun module local ; NV backend ; orientation BUILD si absence confirmée |
| [M39](#m39) | Designs / familles / droits / versions | Non attribuée | P1/F1-F2 | 2–5 | V F04 : modèle dédié absent du dépôt ; NV base |
| [M40](#m40) | Custom2D et minimums serveur | Non attribuée | P0-P1/F2 | 5–12 | V S05 : pas de code métier ; NV fonctions externes ; orientation BUILD conditionnelle |
| [M41](#m41) | Projects / visualisation / queue | Non attribuée | P0-P1/F2 | 6–15 | V S05 : aucun endpoint/job local ; NV service distant |
| [M42](#m42) | Devis et BAT versionnés | Non attribuée | P0/F2 | 5–12 | V S05 : aucune machine d'états locale ; clauses BAT seules |
| [M43](#m43) | Production / B2G / expéditions | Non attribuée | P0/F2 | 3–8 | NV : aucun workflow numérique identifié ; absence locale seulement |
| [M44](#m44) | Stockage privé / uploads / purge | Non attribuée | P0/F2 | 3–8 | NV service ; aucune implémentation locale ni DPA fournis |
| [M45](#m45) | Suivi commercial / WhatsApp | Non attribuée | P1/F2 | 2–5 | NV service ; aucun journal applicatif local identifié |
| [M46](#m46) | Back-office et attention opérateur | Non attribuée | P0-P1/F2 | 3–7 | NV service ; aucune vue dossier versionnée |
| [M47](#m47) | Contrats API et schéma KH versionnés | BUILD | P0-P1/F0-F2 | 3–7 | V S05 : pas de contrats/migrations KH versionnés ; cible ARC/DAT |
| [M48](#m48) | Kaïro / éditions / QR durables | IMPROVE | P1/F1-F2 | 2–5 | V S15 : récit15 épisodes et quatre produits sources ; éditions/QR NV |
| [M49](#m49) | Analytics et indicateurs | Non attribuée | P1/F1-F2 | 1–3 | V S16 : aucun plugin Plausible dans14 actives ; service/injection externe et événements NV |
| [M50](#m50) | RGPD et cycle de vie | IMPROVE | P0/F1-F2 | 1–3 | V S11/S18 : politique comptes3 ans ; champs de conservation WC vides ; procédure hors écran et purge effective NV |
| [M51](#m51) | Passport / My Collection | Non attribuée | P2/F3 | 2–5 | NV implémentation hors dépôt ; P2 option, pas de chantier engagé |
| [M52](#m52) | World Gallery / modération | Non attribuée | P2 et P0 activation/F3 | 3–7 | NV implémentation hors dépôt ; aucun transfert automatique des photos |
| [M53](#m53) | Extensions et kits DIY | Non attribuée | P2-P3/F3-F4 | 0.5–2 | NV dossiers/essais ; option, pas de fabrication déduite du logiciel |
| [M54](#m54) | App native / AR | Non attribuée | P3/F4 | 0–0 | V S05 : absence de chantier local ; option non engagée, aucune décision de reprise nécessaire |
| [M55](#m55) | Coûts / licences / capacité équipe | Non attribuée | P0/F0 | 0.5–1.5 | NV factures/renouvellements, capacité réelle et budgets ; vieux objectifs non probants ; V S19 : six lunes gratuites disponibles ; quota réel, licences, factures et coûts récurrents restent NV |
| [M56](#m56) | Recette transverse et traçabilité | BUILD | P0/F0-F2 | 1–3 | V S05 : pas de registre exhaustif préalable ; présent dossier amorce la traçabilité, scénarios d'exécution restent à construire |
| [M57](#m57) | Factures PDF et numérotation | Non attribuée | P0/F1 | 0.5–2 | V S16 :5.16.1 actif ; paramétrage fiscal, numérotation, accès factures et émission NV |

## Fiches complètes

Toutes les fiches reprennent les champs AUD-02 p.10. V = vérifié dans cet audit ; H = historique déclaré ; NV = non vérifié. Les identifiants S01–S19 et F01–F16 renvoient au registre et aux constats du rapport. Pour toutes les fiches : date 10/09/2026, base locale d3073da ; les observations HTTP ont leur timestamp dans S06.

### M01

**Baseline Git et branches — KEEP**

- Chemin/service : `.git ; deux worktrees`. Fonction : Baseline Git et branches.
- Version déployée : Git main d3073dafb0b11c9866e23a02ecc357e0b7ca0cdd, pas une version serveur.
- Contribution Claude connue : Historique préservé, PR12/13 fusionnées.
- Exigences : AUD-01, GOV-01. Criticité/phase : P0/F0.
- Constat/preuve et justification : V S04/S05 : HEAD distant identique et inventaire des fichiers ; conservation des références.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Aucune mutation prévue.
- Données affectées / risque : Code et historique ; perte par reset/nettoyage.
- Charge basse/haute : 0–0.25 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 1. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Ne rien supprimer ; retrouver chaque commit et fichier par manifeste.

### M02

**Garde-fous et CI — IMPROVE**

- Chemin/service : `GitHub main ; .github/workflows`. Fonction : Garde-fous et CI.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Convention de revue documentée, CI absente.
- Exigences : GOV-01, REC-01, SEC-04. Criticité/phase : P0/F0.
- Constat/preuve et justification : V S13 : protected=false et 0 workflow ; convention PR existante.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Droits GitHub et checks pertinents.
- Données affectées / risque : Code ; publication non contrôlée.
- Charge basse/haute : 1–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 2. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Désactiver nouveau workflow défaillant, conserver revues et version précédente.

### M03

**Documentation et préséance — IMPROVE**

- Chemin/service : `CLAUDE.md ; README.md ; docs/handoff/`. Fonction : Documentation et préséance.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Passation et doctrine riches mais datées.
- Exigences : AUD-01, AUD-02, GOV-01. Criticité/phase : P0/F0.
- Constat/preuve et justification : V S02/S03 : dates, stack verrouillée et instructions anciennes en conflit avec cibles.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Arbitrage Alain.
- Données affectées / risque : Décisions ; erreur de périmètre ou de manipulation.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 3. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Historique Git des documents ; archiver sans effacer les décisions antérieures.

### M04

**Référence créative archivée — KEEP**

- Chemin/service : `docs/charte-graphique/reference-v3/`. Fonction : Référence créative archivée.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Livraison Manus intégrée et corrections Claude tracées.
- Exigences : CAT-01, UX-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S05/S10 : fichiers et empreintes contrôlés ; conservation comme source, pas validation du rendu final.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Arbitrage créatif séparé.
- Données affectées / risque : Référence créative ; perte d'intention.
- Charge basse/haute : 0–0.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 4. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver source d'origine et tracer les adaptations.

### M05

**Identité de marque — KEEP**

- Chemin/service : `brand/koinoborihouse/`. Fonction : Identité de marque.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Logos et favicon existants conservés.
- Exigences : CAT-01, PRD-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S05 : neuf fichiers présents ; KEEP limité à la préservation binaire, droits NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Validation créative et droits.
- Données affectées / risque : Images publiques ; mauvais logo ou droits incomplets.
- Charge basse/haute : 0–0.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 5. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Restaurer binaire de référence par SHA-256.

### M06

**Masters et fichiers ignorés — KEEP**

- Chemin/service : `Koinobori_House_Assets_Claude/ ; prototypes ; logs`. Fonction : Masters et fichiers ignorés.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Pack Manus et matériaux de travail locaux.
- Exigences : AUD-01, SEC-04. Criticité/phase : P0/F0.
- Constat/preuve et justification : V S05 : inventoriés ; stockage de sauvegarde externe NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Copie privée externe.
- Données affectées / risque : Actifs irremplaçables ; perte disque.
- Charge basse/haute : 0.5–1 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 6. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Aucune suppression ; copie externe et contrôle d'empreinte à vérifier.

### M07

**WordPress cœur et base — IMPROVE**

- Chemin/service : `Production et staging`. Fonction : WordPress cœur et base.
- Version déployée : Production et staging WP7.1 via WP-CLI ; checksums cœur réussis avec avertissements fichiers supplémentaires S19.
- Contribution Claude connue : Installation déclarée, cœur hors Git.
- Exigences : ARC-01, COM-01, SEC-04. Criticité/phase : P0/F0.
- Constat/preuve et justification : V S06/S16 : WP7.1 annoncé prod et état WC staging ; PHP8.3.33 MariaDB11.4.13 staging ; checksums/config complets NV ; V S19 : racines/bases identifiées et checksums cœur conformes sur les deux sites ; fichiers error_log supplémentaires à contrôler.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Accès serveur et clone.
- Données affectées / risque : Identités et contenus ; incompatibilité ou perte.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 7. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Snapshot cohérent et version applicative compatible, drill obligatoire.

### M08

**WooCommerce et autorité commerce — IMPROVE**

- Chemin/service : `Plugin WC ; commandes ; HPOS`. Fonction : WooCommerce et autorité commerce.
- Version déployée : Staging WooCommerce 11.1.0 ; base 11.1.0-1 ; HPOS actif (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Catalogue et synchronisation déclarés.
- Exigences : COM-01, ORD-01, DAT-01. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16 : WC11.1.0, base11.1.0-1, HPOS actif ; blocs panier/checkout ; transactions NV ; V S17 : panier anglais vide partiellement français ; V S19 : absent de production ; staging HPOS 4 checkout-draft et 1 completed, nature réelle/test non établie.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Inventaire plugins et paiements sandbox.
- Données affectées / risque : Commandes/paiements/stock ; double moteur ou perte.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 8. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Retour applicatif compatible ; réconcilier commandes récentes avant tout retour de base.

### M09

**Polylang et Polylang for WC — IMPROVE**

- Chemin/service : `Plugins i18n et tables de liaison`. Fonction : Polylang et Polylang for WC.
- Version déployée : Staging Polylang 3.8.7 et Polylang for WooCommerce 2.2.4 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Paramétrage et essais historiques.
- Exigences : SEO-01, INT-01, COM-01. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16/F05 : PLL3.8.7 et PLLWC2.2.4, racines FR/EN blog vide mais pages longues présentes ; cause NV ; V S17 : trois paires légales/USA présentes sans liaison affichée.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Clone, versions, liaisons et options.
- Données affectées / risque : Liens langues et stock ; duplication ou rupture URLs.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 9. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver IDs et paires ; restaurer réglages précis avec relevé avant/après.

### M10

**SEOPress et sitemaps — IMPROVE**

- Chemin/service : `Plugin SEO ; métadonnées`. Fonction : SEOPress et sitemaps.
- Version déployée : Staging SEOPress 10.2 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Choix et scripts de recette documentés.
- Exigences : SEO-01, SEO-02, INT-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S16 : SEOPress10.2, canonical accueil vers racine en désaccord de rendu ; sitemap exhaustif NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Crawl authentifié et historique URLs.
- Données affectées / risque : Indexation ; canonical ou sitemap erroné.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 10. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Rétablir métadonnées et redirections ; ne pas lever noindex avant recette.

### M11

**Kadence parent / Blocks — IMPROVE**

- Chemin/service : `Thème parent ; plugin de blocs`. Fonction : Kadence parent / Blocks.
- Version déployée : Production : Kadence1.5.0 WP-CLI S19 ; staging : Kadence1.5.2 état WC S16.
- Contribution Claude connue : Socle existant, contenu Gutenberg annoncé.
- Exigences : UX-01, ARC-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S16 : Kadence1.5.2 staging, classique ; aucun Kadence Blocks dans14 extensions ; licence NV ; V S19 : parent production 1.5.0 confirmé par WP-CLI ; conserver le socle et vérifier compatibilité sur clone.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Versions serveur et clone.
- Données affectées / risque : Présentation/contenu ; incompatibilité après mise à jour.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 11. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Version parent précédente et données de blocs préservées.

### M12

**Intégration PHP du thème enfant — IMPROVE**

- Chemin/service : `wp/themes/koinobori-child/functions.php`. Fonction : Intégration PHP du thème enfant.
- Version déployée : Enfant production1.0.1 ; dépôt et staging1.1.0 annoncés, chargements CSS différents.
- Contribution Claude connue : Fondations, preloads et palette avec repli.
- Exigences : UX-01, GOV-02, REC-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S16/F02 : staging1.1.0 mais charte v3 non liée, dépôt la charge ; identité release non fiable ; V S19 : functions.php différent après normalisation CRLF/LF ; charte v3 absente du staging ; lint PHP réussi.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : WP/Kadence et recette checkout.
- Données affectées / risque : Présentation ; dérive éditeur/front et ordre CSS.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 12. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Retour au fichier précédent sans toucher aux commandes.

### M13

**Feuilles de style dupliquées — REFACTOR**

- Chemin/service : `kh-foundations.css ; kh-charte-v3.css ; reference-v3/styles.css`. Fonction : Feuilles de style dupliquées.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Portage Manus et corrections Claude.
- Exigences : UX-01, UX-02, GOV-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S05/F08 : égalité après normalisation URL ; dette démontrée.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Tests visuels et périmètre marchand.
- Données affectées / risque : CSS ; dérive entre source et thème.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 13. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Garder artefact CSS antérieur ; mêmes sélecteurs et rendu contractuel.

### M14

**Palette / contraste / liens — IMPROVE**

- Chemin/service : `theme.json ; CSS ; fallback functions.php`. Fonction : Palette / contraste / liens.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Palette bornée et repli existants.
- Exigences : UX-02. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S05/F09 : or inférieur aux seuils textuels, kh-white absent éditeur ; V S19 : theme.json staging diffère réellement du dépôt après normalisation CRLF/LF.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Arbitrage couleurs et vrais gabarits.
- Données affectées / risque : Lisibilité ; liens indiscernables si kh-page global.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 14. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Revenir aux tokens précédents ; publier seulement après recette accessible.

### M15

**Assemblages de pages versionnés — BUILD**

- Chemin/service : `wp/themes/koinobori-child/ : patterns/templates absents`. Fonction : Assemblages de pages versionnés.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : CSS livré ; assemblage non livré en code.
- Exigences : UX-01, CAT-01, SEO-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S05/F08 : aucun assemblage versionné ; classe limitée au dépôt, blocs en base NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Export des blocs existants et arbitrage navigation 2027.
- Données affectées / risque : Pages/menus ; écrasement des contenus saisis.
- Charge basse/haute : 3–8 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 15. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Désactiver nouveaux patterns ; conserver pages et templates précédents.

### M16

**Header Shoji et navigation — IMPROVE**

- Chemin/service : `header-shoji-koino-v1.src.html ; .html`. Fonction : Header Shoji et navigation.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Prototype existant, présentation à préserver.
- Exigences : UX-01, UX-02, INT-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V F07/S16 : prototype liens # ; menu staging sept entrées, Accueil /fr/ différent du lien logo /fr/accueil/.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Menus serveur, intégration accessible.
- Données affectées / risque : Navigation/compte/panier ; faux liens.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 16. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Revenir au header existant ; conserver menus et identifiants.

### M17

**Polices et outil de préparation — IMPROVE**

- Chemin/service : `assets/fonts/ ; tools/fetch-fonts.py`. Fonction : Polices et outil de préparation.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Self-host et sous-ensembles JP.
- Exigences : UX-01, OPS-01, PRD-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S05/F09 : 21 WOFF2 ; chemin Windows codé, sources main non figées, licences à réunir.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Provenance/version/licences et couverture glyphes.
- Données affectées / risque : Actifs ; non-reproductibilité et droits incomplets.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 17. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver WOFF2 validés et checksums avant régénération.

### M18

**Médias du thème et produits — IMPROVE**

- Chemin/service : `assets/images/ ; catalog/images/`. Fonction : Médias du thème et produits.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Conversions et visuels créatifs existants.
- Exigences : CAT-02, UX-01, OPS-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S05/F09 : 13 images thème, formats WebP ; poids élevés de certains masters Web.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Photos réelles et droits ; mesures de pages.
- Données affectées / risque : Images publiques ; qualité/performance/représentativité.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 18. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver originaux et variantes ; revenir au mapping précédent.

### M19

**Routage racine — IMPROVE**

- Chemin/service : `wp/mu-plugins/koino-lang-redirect.php`. Fonction : Routage racine.
- Version déployée : Staging MU racine 1.1.0 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : KH-107 chargé avant Polylang.
- Exigences : INT-01, SEO-01, ARC-03. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S16 : MU routage1.1.0 seul chargé ; code relu, variantes REST/query/cookies non recettées ; V S19 : fichier staging identique au dépôt après normalisation, lint réussi ; absent de production.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : WP/PLL versions ; tests root/REST/checkout.
- Données affectées / risque : URLs/cookies ; redirection parasite.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 19. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Ancien MU-plugin et config précédents ; ne pas casser accès /fr /en.

### M20

**Correction signature BCDG — IMPROVE**

- Chemin/service : `wp/mu-plugins/koino-bcdg-hyphen.php`. Fonction : Correction signature BCDG.
- Version déployée : Dépôt 1.1.0 ; absent de la liste MU staging (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Correctif et revue du 09/09.
- Exigences : CAT-02, SEO-01, REC-01. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S16/F10 : absent de la liste MU staging ; correctif1.1.0 local, tests intégration NV ; V S19 : absence du fichier confirmée dans les deux racines.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : PHP/WP/SEOPress et exemples FR/EN.
- Données affectées / risque : Rendu texte ; correction appliquée au mauvais contexte.
- Charge basse/haute : 0.25–1 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 20. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Retirer filtres ou revenir au fichier précédent ; aucune réécriture des données.

### M21

**Diagnostic temporaire accueil — REMOVE**

- Chemin/service : `wp/mu-plugins/koino-diag-frontpage.php`. Fonction : Diagnostic temporaire accueil.
- Version déployée : Dépôt 1.0.0 ; absent de la liste MU staging (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Diagnostic v1.0.0 livré, résultat inconnu.
- Exigences : SEC-01, SEC-03, OPS-02. Criticité/phase : P1/F0.
- Constat/preuve et justification : V S16 : diagnostic absent de la liste MU ; retrait recommandé du déploiement normal après tout usage futur, source conservée ; V S19 : absence du fichier confirmée dans les deux racines.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Diagnostic terminé ; présence distante à vérifier.
- Données affectées / risque : État interne PHP ; exposition accidentelle ou oubli.
- Charge basse/haute : 0.25–0.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 21. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Archiver outil et résultat privé ; réactivation contrôlée sur clone seulement.

### M22

**Affichage taille unique — IMPROVE**

- Chemin/service : `wp/plugins/kh-single-variation-display/`. Fonction : Affichage taille unique.
- Version déployée : Staging 1.0.0 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : UX catalogue v1.0.0.
- Exigences : CAT-02, COM-01, UX-02. Criticité/phase : P1/F1.
- Constat/preuve et justification : V S16/F10 : plugin1.0.0 actif ; défauts potentiels mono-option/multi-attribut relevés ; panier NV ; V S19 : fichier staging identique après normalisation et lint réussi ; absent de production.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Tests mono/multi attributs, rupture, FR/EN et mobiles.
- Données affectées / risque : Variation/panier ; impossibilité de réinitialiser ou ajouter.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 22. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Désactiver plugin pour revenir au select natif, conserver produits et variations.

### M23

**Source catalogue et imports — REFACTOR**

- Chemin/service : `catalog/master.csv ; docs/lot2/*.csv`. Fonction : Source catalogue et imports.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Imports FR et traductions documentés.
- Exigences : CAT-01, CAT-02, DAT-01, DAT-02. Criticité/phase : P0/F0.
- Constat/preuve et justification : V F04/S09 : sources partielles, anciennes taxonomies et SKU incompatibles.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Export WC/PLL et source autoritaire arbitrée.
- Données affectées / risque : SKU/IDs/prix/stock par référence ; doublons.
- Charge basse/haute : 2–5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 23. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Mapping réversible, essai à blanc ; jamais de renumérotation automatique des SKU vendus.

### M24

**Données WC / variantes / taxes — IMPROVE**

- Chemin/service : `Base staging/prod ; zones et transport`. Fonction : Données WC / variantes / taxes.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Réglages manuels et doctrine shipping.
- Exigences : COM-01, INT-01, ORD-01. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16/S18 :34 produits publiés ; une zone France forfait5/gratuit55, aucune méthode reste du monde malgré promesse UE/DROM ; taxes désactivées ; checkout NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Accès WC et règles commerciales validées.
- Données affectées / risque : Prix/commandes/stock ; facturation erronée.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 24. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Exporter réglages et variations ; rapprocher chaque commande impactée.

### M25

**Paiement Stripe et PayPal — IMPROVE**

- Chemin/service : `Passerelles officielles et webhooks`. Fonction : Paiement Stripe et PayPal.
- Version déployée : Staging Stripe 10.9.1 et PayPal 4.1.2 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Passerelles installées selon inventaire.
- Exigences : COM-01, ORD-01, SEC-03. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16/S18 : Stripe10.9.1 test et configuration requise ; PayPal4.1.2 Compte de test ; transactions/webhooks NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Comptes sandbox et recette idempotence.
- Données affectées / risque : Paiements ; double commande ou confirmation injustifiée.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 25. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Ancienne version plugin et paramètres sécurisés ; réconciliation prestataire/commande.

### M26

**SMTP / e-mails commerciaux — IMPROVE**

- Chemin/service : `FluentSMTP ; Brevo ; wp_mail`. Fonction : SMTP / e-mails commerciaux.
- Version déployée : Staging FluentSMTP 2.3.1 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : DNS et service déclarés, notifications documentées.
- Exigences : COM-01, ARC-03, OPS-01. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16 : FluentSMTP2.3.1 affiche doit être configuré ; transport effectif et délivrabilité NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : SMTP sandbox/sink, DNS et destinataires autorisés.
- Données affectées / risque : Messages ; absence d'accusés ou envoi réel en test.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 26. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Rétablir transport précédent ; file de messages et relance contrôlée.

### M27

**Formulaires FR/EN / leads — classe non attribuée**

- Chemin/service : `Fluent Forms 5–12 ; pages Lot3`. Fonction : Formulaires FR/EN / leads.
- Version déployée : Staging Fluent Forms 6.2.13 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Contact/B2B/B2G/rétractation saisis selon Claude.
- Exigences : CRM-01, PRV-01, UX-02. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16 : Fluent Forms6.2.13, huit formulaires5–12 actifs zéro entrée affichée ; définitions/notifications NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Export des définitions sans soumissions personnelles.
- Données affectées / risque : Leads et consentements ; perte ou destinataire erroné.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 27. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Réimport version connue avec IDs et pages, préserver les soumissions.

### M28

**Rétractation et accusé durable — IMPROVE**

- Chemin/service : `Formulaires11/12 ; CGV10.3 ; checklist`. Fonction : Rétractation et accusé durable.
- Version déployée : Fluent Forms 6.2.13 ; formulaires 11 et 12 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : CGV et formulaire déclarés.
- Exigences : COM-01, PRV-03, REC-01. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16/F12 : formulaires11/12 actifs ; promesse CGV existante, accusé durable et parcours non recettés ; V S17 : formulaires rendus FR/EN avec confirmation, aucun envoi.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Validation fonctionnelle FR/EN et e-mail.
- Données affectées / risque : Demandes clients ; exercice du droit et preuve.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 28. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver traces et ancienne définition ; correction ne doit pas effacer demandes.

### M29

**Corpus juridique / identité / origine — IMPROVE**

- Chemin/service : `docs/lot0/KH-017-documents-legaux/`. Fonction : Corpus juridique / identité / origine.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Révisions et relecture sourcée déclarées.
- Exigences : PRV-01, PRV-02, CAT-02, VIS-02. Criticité/phase : P0/F1-F2.
- Constat/preuve et justification : V F12/S11 : corpus et ambiguïtés BCDG ; écart doctrine cible ; V S17 : liaisons mentions et cookies manquantes.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Arbitrage Alain et compétences juridiques.
- Données affectées / risque : Engagements publics ; promesse non implémentée.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 29. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Versions datées archivées ; ne pas altérer conditions acceptées.

### M30

**Cookies / consentements — classe non attribuée**

- Chemin/service : `Complianz ; politiques ; newsletter`. Fonction : Cookies / consentements.
- Version déployée : Staging Complianz 7.5.4 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Textes et consentements déclarés.
- Exigences : PRV-01, PRV-04, ANA-01. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16 : Complianz7.5.4 actif ; blocage réel/refus/retrait/traceurs NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Inventaire réseau et finalités.
- Données affectées / risque : Préférences et analytics ; traceurs prématurés.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 30. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver preuve des choix ; revenir à configuration connue sans réactiver traceurs refusés.

### M31

**Sauvegardes et restauration — IMPROVE**

- Chemin/service : `UpdraftPlus ; Drive ; JetBackup`. Fonction : Sauvegardes et restauration.
- Version déployée : Staging UpdraftPlus 1.26.7 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Sauvegardes historiquement configurées.
- Exigences : AUD-01, SEC-04, OPS-01, OPS-02, GOV-02. Criticité/phase : P0/F0.
- Constat/preuve et justification : V S16 : neuf ensembles listés dont09/09 17h57, base daily7/fichiers weekly4, Drive déclaré connecté ; intégrité/restauration NV ; V S19 : JetBackup 32 copies par base KH, dernières09/09 ; 0 archive backup_ directe dans updraft staging ; intégrité et restauration NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Accès archives et clone isolé neutralisé.
- Données affectées / risque : Toutes données ; perte unique copie staging.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 31. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Drill chronométré, réconciliation commandes, aucun clonage prod vers staging.

### M32

**Déploiement et exports répétables — BUILD**

- Chemin/service : `Procédures KH103/KH107 ; exports non suivis`. Fonction : Déploiement et exports répétables.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Procédures manuelles dispersées.
- Exigences : GOV-02, OPS-01, DAT-02. Criticité/phase : P0/F0.
- Constat/preuve et justification : V S05/F02 : pas de release ni export rejouable versionné ; service externe NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : M31 inventaire et mapping IDs.
- Données affectées / risque : Code/config/contenus ; écrasement et dérive.
- Charge basse/haute : 2–5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 32. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Retour applicatif compatible et archive pré-release ; jamais rollback aveugle de base.

### M33

**Scripts de recette existants — IMPROVE**

- Chemin/service : `docs/lot1/*tests.ps1 ; *tests.sh`. Fonction : Scripts de recette existants.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Gates bilingues historiques.
- Exigences : REC-01, SEC-03, SEO-01. Criticité/phase : P1/F0.
- Constat/preuve et justification : V F11 : scénarios utiles, TLS désactivé, paramètres sensibles.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : TLS validé, secrets hors arguments/logs.
- Données affectées / risque : Preuves/logs ; faux PASS sécurité.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 33. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver scénarios et résultats historiques ; nouvelle version revue.

### M34

**Outillage images sharp — IMPROVE**

- Chemin/service : `tools/package.json ; package-lock.json ; png-to-webp.js`. Fonction : Outillage images sharp.
- Version déployée : Outil local sharp0.33.5 ; aucun déploiement Web établi.
- Contribution Claude connue : Convertisseur local et verrou npm.
- Exigences : SEC-04, SEC-02, OPS-01. Criticité/phase : P1/F0.
- Constat/preuve et justification : V S12 : sharp0.33.5, audit high ; usage serveur non établi.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Mise à jour testée sur copies fiables.
- Données affectées / risque : Fichiers locaux ; parsing non fiable, --delete possible.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 34. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver sorties antérieures ; pas de conversion avec --delete ; verrou de dépendances versionné.

### M35

**Sécurité serveur / MFA / rôles — IMPROVE**

- Chemin/service : `Wordfence ; cPanel ; PHP ; permissions`. Fonction : Sécurité serveur / MFA / rôles.
- Version déployée : Staging Wordfence 9.0.1 ; PHP 8.3.33 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Hardening déclaré, pas d'inventaire actuel.
- Exigences : SEC-01, SEC-03, SEC-04, BO-01. Criticité/phase : P0/F0-F1.
- Constat/preuve et justification : V S16 : Wordfence9.0.1, intégration Login Security WC inactive,6 mises à jour proposées ; MFA/permissions NV ; V S19 : uploads0777 et wp-config0644 sur les deux sites ; compte système partagé ; protection Basic staging ; exploitation/MFA NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Accès admin/serveur et liste versions.
- Données affectées / risque : Système et données ; accès excessifs ou faille version.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 35. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Sauvegarder configuration ; maintenir MFA et accès de secours contrôlé.

### M36

**Cache / jobs / logs / alertes — IMPROVE**

- Chemin/service : `LiteSpeed ; WP cron ; Action Scheduler ; hébergeur`. Fonction : Cache / jobs / logs / alertes.
- Version déployée : Staging LiteSpeed Cache 7.9.1 ; Action Scheduler 4.0.0 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Cache et backups déclarés.
- Exigences : OPS-01, OPS-02, ARC-03. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16 : LSCache page indisponible, pas de cache objet externe déclaré ;7 échecs fetch_patterns sans callback ; supervision NV ; V S19 : aucun drop-in PHP observé à la racine wp-content staging ; logs supplémentaires dans les cœurs WP, contenu et accès public NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Audit configuration et test incident.
- Données affectées / risque : Sessions/jobs ; panier caché, tâches perdues.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 36. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Revenir aux exclusions/config validées ; conserver jobs et rapprochements.

### M37

**État des comptes clients — classe non attribuée**

- Chemin/service : `WP users ; Woo My Account`. Fonction : État des comptes clients.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Compte WC prévu ; réalisation actuelle NV.
- Exigences : ACC-01, ACC-03, SEC-01. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S18 : invité et inscriptions checkout/compte actifs ; récupération, identité et cloisonnement non recettés.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Clone anonymisé et deux comptes tests.
- Données affectées / risque : Identités/commandes ; transfert abusif ou verrouillage.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 37. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Préserver IDs clients et commandes ; révoquer sessions de test seulement.

### M38

**Organisations et permissions métier — classe non attribuée**

- Chemin/service : `Module KH éventuel hors dépôt`. Fonction : Organisations et permissions métier.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Hors ancien MVP, aucune livraison locale identifiée.
- Exigences : ACC-02, BO-01, SEC-01. Criticité/phase : P0/F2.
- Constat/preuve et justification : V S05 : aucun module local ; NV backend ; orientation BUILD si absence confirmée.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Inventaire distant, modèle ACC/DAT.
- Données affectées / risque : Organisations ; accès croisé et faux signataire.
- Charge basse/haute : 3–7 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 38. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Fonction désactivable, migrations additives, conserver historique des habilitations.

### M39

**Designs / familles / droits / versions — classe non attribuée**

- Chemin/service : `Catalogue et futur modèle KH`. Fonction : Designs / familles / droits / versions.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Catégories/SKU existants à préserver.
- Exigences : CAT-01, DAT-01, DAT-02, PRD-01. Criticité/phase : P1/F1-F2.
- Constat/preuve et justification : V F04 : modèle dédié absent du dépôt ; NV base.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : M23 et décision de schéma.
- Données affectées / risque : Relations et droits ; duplication des récits.
- Charge basse/haute : 2–5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 39. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Mapping IDs sauvegardé, modèles extensibles sans écraser données initiales.

### M40

**Custom2D et minimums serveur — classe non attribuée**

- Chemin/service : `Module KH / configurateur non trouvé localement`. Fonction : Custom2D et minimums serveur.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Page de contact Custom existante, pas preuve d'un configurateur.
- Exigences : CUS-01, CUS-02, CUS-03, UX-01, UX-02. Criticité/phase : P0-P1/F2.
- Constat/preuve et justification : V S05 : pas de code métier ; NV fonctions externes ; orientation BUILD conditionnelle.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : M38/39/44, API et tests T03/T04.
- Données affectées / risque : Configs/fichiers ; seuil50/150 contournable.
- Charge basse/haute : 5–12 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 40. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Feature flag fermé ; garder briefs, versions et commandes, aucun envoi industriel automatique.

### M41

**Projects / visualisation / queue — classe non attribuée**

- Chemin/service : `Module KH / prestataire images non identifié`. Fonction : Projects / visualisation / queue.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Aucune livraison de module identifiée.
- Exigences : PRJ-01, VIS-01, VIS-02, VIS-03, ARC-03. Criticité/phase : P0-P1/F2.
- Constat/preuve et justification : V S05 : aucun endpoint/job local ; NV service distant.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Stockage privé, quotas, prototype rendu et repli.
- Données affectées / risque : Photos et coûts ; fuite ou génération répétée.
- Charge basse/haute : 6–15 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 41. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Arrêter nouvelles tâches, conserver dossiers, repli manuel sans perdre demandes.

### M42

**Devis et BAT versionnés — classe non attribuée**

- Chemin/service : `Module KH ; approbations`. Fonction : Devis et BAT versionnés.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Clause contractuelle rédigée, module NV.
- Exigences : QUO-01, BAT-01, BAT-02, DAT-02. Criticité/phase : P0/F2.
- Constat/preuve et justification : V S05 : aucune machine d'états locale ; clauses BAT seules.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Identité, droits, fichiers privés, contrôle concurrence.
- Données affectées / risque : Accords immuables ; fabrication sur mauvaise version.
- Charge basse/haute : 5–12 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 42. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Ne jamais effacer accords ; désactiver nouvelle libération, restaurer code compatible.

### M43

**Production / B2G / expéditions — classe non attribuée**

- Chemin/service : `Dossiers fournisseur ; libération KH`. Fonction : Production / B2G / expéditions.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Traitement offline prévu historiquement.
- Exigences : PRO-01, ORD-01, BO-02. Criticité/phase : P0/F2.
- Constat/preuve et justification : NV : aucun workflow numérique identifié ; absence locale seulement.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : M42 et conditions commerciales validées.
- Données affectées / risque : BAT/BC/paiement/lot ; production injustifiée.
- Charge basse/haute : 3–8 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 43. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Suspendre nouveaux ordres sans annuler silencieusement ceux en cours ; journal manuel.

### M44

**Stockage privé / uploads / purge — classe non attribuée**

- Chemin/service : `Serveur/stockage objet non identifié`. Fonction : Stockage privé / uploads / purge.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Uploads explicitement hors ancien MVP.
- Exigences : SEC-02, SEC-03, PRV-02, PRV-03, DAT-02. Criticité/phase : P0/F2.
- Constat/preuve et justification : NV service ; aucune implémentation locale ni DPA fournis.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Architecture stockage, rôles, scanner et sandbox.
- Données affectées / risque : Photos/BAT ; liens publics permanents et conservation excessive.
- Charge basse/haute : 3–8 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 44. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Fermer nouveaux uploads ; garder accès privé aux contrats et réappliquer purges après restauration.

### M45

**Suivi commercial / WhatsApp — classe non attribuée**

- Chemin/service : `Dossiers et canal autorisé`. Fonction : Suivi commercial / WhatsApp.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Formulaires de captation existants déclarés.
- Exigences : CRM-01, CRM-02, PRV-04. Criticité/phase : P1/F2.
- Constat/preuve et justification : NV service ; aucun journal applicatif local identifié.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Consentement distinct et opérateur KH.
- Données affectées / risque : Contacts ; relance non autorisée ou fuite vidéo.
- Charge basse/haute : 2–5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 45. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Arrêter relances, conserver historique/preuves et canal e-mail autorisé.

### M46

**Back-office et attention opérateur — classe non attribuée**

- Chemin/service : `Module KH / capacités métier`. Fonction : Back-office et attention opérateur.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Administration WC prévue, fonctions KH non identifiées.
- Exigences : BO-01, BO-02, BO-03. Criticité/phase : P0-P1/F2.
- Constat/preuve et justification : NV service ; aucune vue dossier versionnée.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : M38–45 et opérateur commercial.
- Données affectées / risque : Dossiers/rôles ; modification non tracée.
- Charge basse/haute : 3–7 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 46. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Feature flags, rôles minimaux et conservation des journaux.

### M47

**Contrats API et schéma KH versionnés — BUILD**

- Chemin/service : `Documentation/code du dépôt`. Fonction : Contrats API et schéma KH versionnés.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Aucun livrable API métier local.
- Exigences : ARC-01, ARC-02, ARC-03, DAT-01, DAT-02. Criticité/phase : P0-P1/F0-F2.
- Constat/preuve et justification : V S05 : pas de contrats/migrations KH versionnés ; cible ARC/DAT.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : ADR, inventaire distant et prototype.
- Données affectées / risque : Interfaces ; confiance prix client et concurrence.
- Charge basse/haute : 3–7 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 47. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Versionner contrats ; compatibilité descendante, migrations additives et contrôles totaux.

### M48

**Kaïro / éditions / QR durables — IMPROVE**

- Chemin/service : `docs/kairo-bd-recit-et-produit.md ; catalogue`. Fonction : Kaïro / éditions / QR durables.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Récit et décisions antérieures documentés.
- Exigences : KAI-01, KAI-02, ANA-02. Criticité/phase : P1/F1-F2.
- Constat/preuve et justification : V S15 : récit15 épisodes et quatre produits sources ; éditions/QR NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Édition FR/EN, droits et distributeur validés.
- Données affectées / risque : URLs imprimées/contenus ; liens périssables.
- Charge basse/haute : 2–5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 48. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver QR et redirections ; ne jamais réattribuer identifiants d'aventure.

### M49

**Analytics et indicateurs — classe non attribuée**

- Chemin/service : `Plausible et commerce`. Fonction : Analytics et indicateurs.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Doctrine WC Orders source de vérité.
- Exigences : ANA-01, ANA-02, PRV-04. Criticité/phase : P1/F1-F2.
- Constat/preuve et justification : V S16 : aucun plugin Plausible dans14 actives ; service/injection externe et événements NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Consentements, commandes test et réconciliation.
- Données affectées / risque : Événements ; double purchase et données personnelles.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 49. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Désactiver collecteur défaillant, garder commerce intact et versions du plan.

### M50

**RGPD et cycle de vie — IMPROVE**

- Chemin/service : `Politiques / registre / sous-traitance`. Fonction : RGPD et cycle de vie.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Corpus FR/EN et checklist.
- Exigences : PRV-01, PRV-02, PRV-03, PRV-04, OPS-02. Criticité/phase : P0/F1-F2.
- Constat/preuve et justification : V S11/S18 : politique comptes3 ans ; champs de conservation WC vides ; procédure hors écran et purge effective NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Prestataires/rôles/finalités et validation compétente.
- Données affectées / risque : Données personnelles ; promesses non appliquées.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 50. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver demandes/archives nécessaires ; documenter purges et exceptions de litige.

### M51

**Passport / My Collection — classe non attribuée**

- Chemin/service : `Option future`. Fonction : Passport / My Collection.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Aucune livraison identifiée.
- Exigences : FID-01. Criticité/phase : P2/F3.
- Constat/preuve et justification : NV implémentation hors dépôt ; P2 option, pas de chantier engagé.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : GO usage/marge, preuve de possession, identité unique.
- Données affectées / risque : Possession et compte ; fausse certification.
- Charge basse/haute : 2–5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 51. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Désactiver option sans affecter comptes/commandes ; export des inscriptions.

### M52

**World Gallery / modération — classe non attribuée**

- Chemin/service : `Option future`. Fonction : World Gallery / modération.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Aucune livraison identifiée.
- Exigences : GAL-01, GAL-02, PRV-04, SEC-02. Criticité/phase : P2 et P0 activation/F3.
- Constat/preuve et justification : NV implémentation hors dépôt ; aucun transfert automatique des photos.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : GO, droits, modération, purge et retrait.
- Données affectées / risque : Photos/GPS/droits ; publication indue.
- Charge basse/haute : 3–7 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 52. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Dépublier médias concernés, révoquer liens et conserver preuve des consentements.

### M53

**Extensions et kits DIY — classe non attribuée**

- Chemin/service : `Dossiers produit non inventoriés`. Fonction : Extensions et kits DIY.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Pistes gamme, aucun essai démontré.
- Exigences : PRD-01, DIY-01, DIY-02. Criticité/phase : P2-P3/F3-F4.
- Constat/preuve et justification : NV dossiers/essais ; option, pas de fabrication déduite du logiciel.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : GO produit, essais physiques et responsabilités.
- Données affectées / risque : Produits/lot ; promesse de pose ou sécurité sans essai.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 53. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Retrait contrôlé de référence, traçabilité commandes et lots ; pas de suppression d'historique.

### M54

**App native / AR — classe non attribuée**

- Chemin/service : `Aucun projet natif dans le dépôt`. Fonction : App native / AR.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Aucune implémentation native identifiée.
- Exigences : ARC-03, UX-01. Criticité/phase : P3/F4.
- Constat/preuve et justification : V S05 : absence de chantier local ; option non engagée, aucune décision de reprise nécessaire.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : GO distinct et preuve de besoin.
- Données affectées / risque : Périmètre/coûts ; infrastructure prématurée.
- Charge basse/haute : 0–0 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 54. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Aucun changement à effectuer ; conserver compte/API communs si option future.

### M55

**Coûts / licences / capacité équipe — classe non attribuée**

- Chemin/service : `Contrats hébergeur/plugins/services`. Fonction : Coûts / licences / capacité équipe.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Stack et échéances déclarées.
- Exigences : OPS-01, ARC-02, PRD-01. Criticité/phase : P0/F0.
- Constat/preuve et justification : NV factures/renouvellements, capacité réelle et budgets ; vieux objectifs non probants ; V S19 : six lunes gratuites disponibles ; quota réel, licences, factures et coûts récurrents restent NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Alain, prestataires et responsable technique.
- Données affectées / risque : Exploitation ; licence expirée et charge sous-estimée.
- Charge basse/haute : 0.5–1.5 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 55. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver factures/licences privées, prévoir export et remplacement seulement après comparaison.

### M56

**Recette transverse et traçabilité — BUILD**

- Chemin/service : `Tests T01–T14 ; registre d'exigences`. Fonction : Recette transverse et traçabilité.
- Version déployée : Non vérifiée sauf précision dans la preuve ; voir rapport §5.
- Contribution Claude connue : Gates i18n partiels réutilisables.
- Exigences : AUD-01, AUD-02, REC-01. Criticité/phase : P0/F0-F2.
- Constat/preuve et justification : V S05 : pas de registre exhaustif préalable ; présent dossier amorce la traçabilité, scénarios d'exécution restent à construire.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Recommandation locale conditionnelle ; non appliquée.
- Dépendances : Environnements neutres et exigences validées.
- Données affectées / risque : Preuves ; faux terminé sans scénario métier.
- Charge basse/haute : 1–3 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 56. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Conserver résultats par version ; ne pas écraser les échecs historiques.

### M57

**Factures PDF et numérotation — classe non attribuée**

- Chemin/service : `PDF Invoices & Packing Slips`. Fonction : Factures PDF et numérotation.
- Version déployée : Staging PDF Invoices & Packing Slips 5.16.1 (écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19).
- Contribution Claude connue : Extension installée, contribution précise non attribuable.
- Exigences : COM-01, ORD-01, PRV-02. Criticité/phase : P0/F1.
- Constat/preuve et justification : V S16 :5.16.1 actif ; paramétrage fiscal, numérotation, accès factures et émission NV.
- Couverture des tests : Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées.
- Décision : Non attribuée : accès/inventaire requis ; inconnue ne signifie pas BUILD.
- Dépendances : Commandes test et validation paramètres/documents.
- Données affectées / risque : Factures et identité ; doublons, mentions ou accès erronés.
- Charge basse/haute : 0.5–2 j6h. Estimation initiale de la prochaine intervention décrite, hors attente externe ; non additive aux autres lignes, à réestimer après F0. Pour options : étude/pilote seulement, aucun engagement.
- Ordre de lecture : 57. Responsable : Responsable technique à désigner ; Alain pour données/décisions métier. Approbateur : Alain (métier) et responsable technique (exploitation), non acquis.
- Retour proposé (non testé) : Préserver numéros et factures émises ; retour plugin compatible sans régénération aveugle.
