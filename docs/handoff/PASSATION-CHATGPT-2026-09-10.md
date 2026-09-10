# Passation à ChatGPT — 2026-09-10

Document unique de reprise. Écrit pour être lisible sans aucun contexte de session.

- **Projet** : Koinobori House, e-commerce bilingue FR+EN, WordPress + WooCommerce sur o2switch
- **Lancement visé** : **30 septembre 2026**. Il reste **20 jours**.
- **Doctrine faisant autorité** : `CLAUDE.md` à la racine du dépôt. Elle prime sur tout autre document, y compris celui-ci.

---

## 0. À lire en premier, trois avertissements

**1. Ne pas travailler depuis le mauvais répertoire.** Entre le 27 juillet et le 7 septembre, une session ChatGPT a travaillé depuis `SAS MIN&MAI - DEVCODE` au lieu de `C:\dev\Koinobori`. Conséquences constatées : un commit a supprimé deux fichiers de marque (`logo-color-gold-III-cart.png`, `favicon-512.png`, depuis restaurés), et deux corps étrangers se sont retrouvés dans le dépôt, dont un CSV d'avis clients contenant des adresses email. **Toujours vérifier le répertoire courant avant d'écrire.**

**2. `main` n'est pas à jour.** Tout le travail des 7 au 9 septembre vit dans la **PR #13**, non mergée, 19 commits. Cloner `main` fait perdre quatre jours de travail. Voir §1.

**3. Le dépôt ne contient pas le site.** Le dépôt porte la documentation, la doctrine et le peu de code maison (`wp/`). Les pages, produits, menus, formulaires et réglages vivent **uniquement dans la base de données de staging**. Rien n'est rejouable en production à ce jour, voir §6.

---

## 1. État du dépôt

Vérifié le 2026-09-10.

| | |
|---|---|
| Dépôt | `Ahlaoban/koinobori-house`, **privé** |
| Local ↔ distant | synchronisés, arbre de travail propre |
| `main` | `a0c6072` — s'arrête à la PR #12 |
| PR #12 | **mergée** le 09/09 (référence visuelle Manus v3.0 dans le thème enfant) |
| PR #13 | **ouverte**, 19 commits, sans conflit, revue de code passée et corrections appliquées |
| Protection de branche | **impossible** (dépôt privé en plan gratuit). « Jamais de push direct sur main » est une convention, pas un garde-fou |
| CI | **aucune** |

### Recommandation

**Merger la PR #13 avant toute reprise.** Elle est propre, sans conflit, et sa revue de code a été passée puis ses constats corrigés. Tant qu'elle n'est pas mergée, `main` ment sur l'état du projet.

Si le merge n'est pas souhaité, travailler impérativement sur la branche `docs/reprise-2026-09-07`.

---

## 2. Où vit quoi

| Environnement | État au 2026-09-10 |
|---|---|
| **Production** `koinoborihouse.com` | WordPress quasi nu. **Non lancé.** Pas de Polylang, pas de WooCommerce, 0 page. Le thème enfant déployé est un vieux zip |
| **Staging** `staging.koinoborihouse.com` | Le vrai travail. Protégé par authentification HTTP en plus de wp-admin |
| **Dépôt** | Documentation, doctrine, `wp/mu-plugins/`, `wp/plugins/`, `wp/themes/koinobori-child/`, `catalog/`, `brand/` |

⚠️ **L'authentification HTTP de staging tombe régulièrement en cours de session.** Symptôme : le corps des pages revient vide alors que le serveur répond bien un 401. Il faut la ressaisir.

⚠️ **Staging n'est plus un environnement jetable.** `KH-102` le disait, c'est faux depuis le 7 septembre : il détient l'unique copie de 26 pages, 8 formulaires, 2 menus et de tous les appariements Polylang. Le clonage **production vers staging**, documenté comme la procédure normale et exécuté deux fois en juin, **détruirait tout**.

✅ **Staging est sauvegardé**, contrairement à ce que dit encore une partie de la documentation : UpdraftPlus envoie la base **quotidiennement** vers Google Drive, plus des sauvegardes complètes régulières. Une sauvegarde complète du **09/09 à 17h57** est marquée en rétention manuelle : c'est le point de restauration de référence.

---

## 3. Ce qui a été fait du 7 au 9 septembre

### Contenus posés sur staging

12 pages créées, appariées Polylang, hreflang vérifié dans les deux sens :

| Page | FR | EN |
|---|---|---|
| Accueil | 318 `/fr/accueil/` | 319 `/en/home/` |
| Professionnels (aiguillage, sans formulaire) | 279 | 285 `/en/professionals/` |
| Entreprises (B2B, Fluent Forms 7/8) | 280 | 287 `/en/business/` |
| Collectivités (B2G, Fluent Forms 9/10) | 281 | 289 `/en/institutions/` |
| Arts de vivre | 282 | 291 |
| L'Atelier / The House | 283 | 293 `/en/the-house/` |
| Lifestyle & Koi | 284 | 295 `/en/lifestyle-koi/` |

S'y ajoutent les 16 pages posées le 07/09 (légales, confiance, WooCommerce), dont **CGV 238 FR** et **276 EN**.

**Menus 144 (FR) et 145 (EN)**, 7 entrées chacun, affectés aux emplacements Principal et Mobile de leur langue. Rendu public vérifié.

**12 jeux de métadonnées SEOPress** écrits et vérifiés dans le rendu.

**Image Sakura** posée sur les produits 111 et 113. Restent **18 fiches sans image** = 9 produits (Mer 6, Motifs 3) dans les deux langues.

### Corpus juridique

Les CGV FR et EN ont été réécrites le 07/09 puis corrigées le 09/09 après revue. Corrections notables : exclusion de remboursement des droits de douane restaurée, article 14.1 sur le bon à tirer créé, délais de livraison chiffrés rétablis avec renvoi à L. 216-1, hiérarchie de titres corrigée, renumérotation propagée dans les documents qui indexaient les CGV.

**Vérifié contre Légifrance** et correct : encadré D. 211-2, numérotation post-2021 des garanties, délais de rétractation, coordonnées CM2C, et le fait qu'aucun des deux documents ne renvoie vers la plateforme européenne RLL, fermée en juillet 2025.

### Code

- `wp/mu-plugins/koino-bcdg-hyphen.php` v1.1.0 — rétablit le trait d'union de la signature BCDG que `wptexturize` transforme en tiret typographique. **Non déployé.**
- `wp/mu-plugins/koino-diag-frontpage.php` — outil de diagnostic temporaire, voir §5.
- `catalog/master.csv` balayé : 14 blocs « atelier partenaire » supprimés, signatures et cadratins corrigés.

---

## 4. Arbitrages rendus, à ne pas rouvrir

| Date | Décision |
|---|---|
| 07/09 | Lancement au **30 septembre 2026** |
| 07/09 | **BD Kaïro = automne, hors lancement.** Clauses parquées dans `10-clauses-contenu-numerique-BD.md` |
| 07/09 | **USA = « nous contacter »** au lancement |
| 08/09 | 🔴 **La Chine n'est JAMAIS citée, dans aucun contexte, sans aucune exception.** L'exception bornée « B2G culturel » du 28/05 est **levée**. Référent culturel unique : le Japon |
| 08/09 | Menu : **option a**, les 7 entrées câblées tout de suite, L'Atelier et Lifestyle publiées en version minimale mais réelle |
| 09/09 | **Aucun téléphone publié.** `contact@koinoborihouse.com` est le seul canal, dans les CGV comme dans les mentions légales |

---

## 5. Ce qui est ouvert, par ordre de priorité

### 5.1 🔴 La page d'accueil ne s'affiche pas

`/fr/` et `/en/` rendent l'index de blog au lieu de la page d'accueil, alors que `?page_id=318` rend la page avec tout son contenu.

**Diagnostic établi** : les deux options de page statique sont ignorées **ensemble** — test fait en posant `page_for_posts`, qui n'a pas été honoré non plus et n'a même pas persisté. Cela n'arrive que si `show_on_front` ne vaut pas `page` au moment où `WP_Query` décide. **Quelque chose filtre `option_show_on_front` sur le front.** Polylang le fait dans `PLL_Static_Pages` quand il n'arrive pas à résoudre la page d'accueil pour la langue courante.

**Un outil est livré pour trancher** : `wp/mu-plugins/koino-diag-frontpage.php`. Le déposer dans `wp-content/mu-plugins/`, ouvrir `https://staging.koinoborihouse.com/fr/?koino_diag=1` en étant connecté administrateur, lire le bloc affiché, **puis supprimer le fichier**. Il donne la liste nommée des callbacks accrochés à `option_show_on_front`, ce qui désignera le coupable sans ambiguïté.

### 5.2 🔴 Contraste de l'or, arbitrage Manus

L'or v2.0 `#B8860B` mesure **2,97:1 sur washi** et **3,36:1 sur indigo** — échec WCAG AA. La migration v1 vers v2.0 a **dégradé** cette dernière paire, qui mesurait 6,04:1.

Contrainte arithmétique : pour 4,5:1 sur washi il faut une luminance ≤ 0,163, pour 4,5:1 sur indigo il faut ≥ 0,382. **Fenêtres disjointes : aucune couleur unique ne peut porter du texte courant sur les deux fonds.** Or la charte assigne à l'or les « citations », qui sont du texte courant.

Le brief est rédigé et prêt à envoyer : `docs/handoff/BRIEF-MANUS-contraste-or.md`. Sa dernière section est interne et ne doit pas être transmise.

### 5.3 🔴 Conformité du formulaire de rétractation

L'article 10.3 des CGV affirme **au présent** qu'une fonctionnalité de rétractation en ligne est disponible. Les formulaires Fluent Forms 11 et 12 existent, mais leur conformité à l'**article D. 221-5** n'a jamais été vérifiée. Six exigences précises sont listées comme sous-cases dans `09-donnees-manquantes-et-checklist.md` §D. Tant qu'elles ne sont pas vérifiées, les CGV affirment publiquement une conformité non établie.

### 5.4 « Chine » rendu sur deux pages publiques

`/fr/entreprises/` et `/en/business/` contiennent le mot dans la **liste déroulante ISO des pays** du formulaire B2B. ⚠️ **Ne pas résoudre en excluant les balises `<option>` du grep d'audit KH-707** : cela aveuglerait le contrôle sur toute fuite future dans un select. La seule option conforme est de restreindre la liste des pays du formulaire.

### 5.5 Divergence de footer, non tranchée

Manus livre une bande de réassurance en **4 colonnes** ; l'arbitrage O-1 prévoit **3 groupes de liens** (Navigation, Informations, Professionnels). Les deux ne se superposent pas. À trancher avant de câbler quoi que ce soit.

### 5.6 Identité de BCDG, contradiction publique

Les mentions légales disent « BCDG, **l'entreprise créative** créée par Alain Herbinière » ; les CGV article 1 disent « BCDG **ne constitue pas une entité juridique distincte** du vendeur ». Le manifeste de la page d'accueil a été aligné sur la seconde formulation, les mentions légales **pas** : la checklist KH-017 les qualifie de « formulation obligatoire », donc l'arbitrage appartient à Alain.

### 5.7 Le CSS de la charte ne s'applique à rien

La PR #12 livre 1448 lignes de CSS entièrement préfixé `.kh-*`, mais **aucun pattern ni gabarit n'émet ces classes**. Sur toute la feuille, seuls `:root`, le reset `*` et `html` peuvent matcher une page WordPress. Merger la PR ne rend donc pas la page d'accueil conforme à Manus : il faudra construire les 11 mouvements, à la main dans Kadence ou en `register_block_pattern()`.

Autre conséquence : `--kh-white #FFFDFC` est utilisé 15 fois dans le CSS mais absent de `theme.json`, donc impossible à choisir dans l'éditeur alors que la charte le prescrit pour les cartes produits.

### 5.8 Ce qui dépend d'Alain seul

Stripe en mode test · clé Brevo dans FluentSMTP · licence Wordfence · **9 photos produits** · relecture des textes.

---

## 6. Rien n'est rejouable en production

Une sauvegarde protège staging ; elle ne transporte rien vers la production. Manquent, et ce n'est pas fait :

- un export WXR des pages et des menus, ou un script de recréation ;
- l'export JSON des 8 formulaires Fluent Forms, dont les définitions de champs n'existent nulle part dans le dépôt ;
- une procédure de déploiement de `wp/mu-plugins/` et `wp/themes/`, aujourd'hui éclatée dans deux tickets du Lot 1 et jamais généralisée ;
- un relevé de ce qui est réellement déployé où : personne ne peut dire aujourd'hui si le mu-plugin KH-107 tourne en production.

**C'est le risque de calendrier le plus sérieux.** À 20 jours du lancement, reproduire à la main 26 pages, leurs appariements, 2 menus et 14 jeux de métadonnées représente plusieurs jours de travail au clic.

---

## 7. Pièges connus, à ne pas refaire

| Piège | Ce qu'il faut savoir |
|---|---|
| Unicité des slugs | WordPress force l'unicité **entre langues** sous Polylang Free. `/en/contact/` et `/en/lifestyle/` sont impossibles, d'où `contact-us` et `lifestyle-koi`. ⚠️ Ce n'est **pas** une limitation de licence : Polylang Pro ne la lèverait pas, sa traduction de slugs porte sur les **bases** d'URL, pas sur les slugs de pages |
| Page d'accueil, fausse piste | Le mu-plugin KH-107 **n'est pas** en cause : il n'enregistre aucun hook et sort dès que le chemin diffère de la racine. Vérifié |
| Page d'accueil, pistes épuisées | Cache de pages purgé, cache des langues Polylang nettoyé par **trois** chemins, aucun drop-in de cache objet sur ce site, filtre de langue de l'admin remis à neutre, réglages d'URL Polylang conformes. Tout cela est sans effet, ne pas recommencer |
| Polylang for WooCommerce | **N'expose aucun lien de désactivation**, contrairement à toutes les autres extensions. Impossible à tester par l'interface |
| Éditeur de fichiers de thème | Désactivé (`DISALLOW_FILE_EDIT`). Impossible de lister les fichiers du thème déployé depuis wp-admin |
| Médiathèque | Refuse l'upload de `.txt`. Le transport de contenu passe par l'API REST |
| Filtre de langue de l'admin | S'il est sur English, le formulaire des réglages de lecture affiche la page **anglaise** comme page d'accueil. Vérifier avant tout enregistrement |
| Titres produits | La signature `- by BCDG` est **propre en base** ; c'est `wptexturize` qui l'abîme à l'affichage. Ne pas « corriger » les données |

---

## 8. Documents de référence, par ordre d'utilité

| Fichier | Contenu |
|---|---|
| `CLAUDE.md` | **La doctrine.** Prime sur tout |
| `docs/lot3/staging-inventaire-2026-09-07.md` | Journal d'état de staging : identifiants de pages, formulaires, menus, ce qui est vérifié et comment |
| `docs/lot3/pages-professionnels-arts-de-vivre-FR-EN.md` | Textes FR+EN de 6 pages, métadonnées SEO, spec du menu et du footer |
| `docs/lot3/homepage-v2-11-mouvements-FR-EN.md` | Textes FR+EN de la page d'accueil, mouvement par mouvement |
| `docs/lot3/pages-confiance-FR-EN.md` | Textes des pages Livraison, Retours, Contact, Sur-mesure |
| `docs/lot0/KH-017-documents-legaux/` | Les 9 documents légaux FR+EN, plus la checklist `09-...` qui est le registre de conformité |
| `docs/handoff/BRIEF-MANUS-contraste-or.md` | Question prête à envoyer à Manus |
| `docs/handoff/REPARTITION-MANUS-CLAUDE.md` | Qui possède quel fichier. **Manus possède la présentation, jamais l'inverse** |
| `docs/charte-graphique/reference-v3/` | La référence visuelle de Manus : `index.html`, `styles.css`, `INTEGRATION_KADENCE.md` |
| `docs/ux-architecture.md` | Navigation, homepage, footer, arbitrages UX |

---

## 9. Ce que je ferais dans les 20 jours qui restent

1. **Merger la PR #13** pour que `main` cesse de mentir.
2. **Débloquer la page d'accueil** avec l'outil de diagnostic livré. C'est le chemin critique : sans elle, il n'y a pas de site.
3. **Produire les exports** WXR et Fluent Forms, et écrire la procédure de déploiement vers la production. C'est le seul risque qui peut coûter une semaine.
4. **Envoyer le brief à Manus** sur l'or, en parallèle, puisque sa réponse conditionne la couche visuelle.
5. **Vérifier le formulaire de rétractation** contre D. 221-5, puisque les CGV affirment déjà sa conformité.
6. Construire les 11 mouvements de la page d'accueil avec les classes de Manus, une fois son arbitrage rendu.
7. Le reste — photos, Stripe, Brevo, Wordfence — dépend d'Alain.

---

*Rédigé le 2026-09-10. Les affirmations de ce document ont été vérifiées sur le dépôt et sur staging, sauf mention contraire explicite. En cas de contradiction avec `CLAUDE.md`, c'est `CLAUDE.md` qui fait foi.*
