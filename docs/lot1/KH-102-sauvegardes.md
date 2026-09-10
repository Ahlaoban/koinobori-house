# KH-102 — Sauvegardes UpdraftPlus + Google Drive (Lot 1)

- **Ticket** : KH-102
- **Lot** : 1
- **Date exécution** : 2026-06-12
- **Statut** : ✅ **FERMÉ** — drill de restauration complet réussi
- **Environnement** : production `koinoborihouse.com`

## 1. Architecture de sauvegarde (3 niveaux)

| Niveau | Outil | Quoi | Fréquence | Rétention | Destination |
|---|---|---|---|---|---|
| 1 | **UpdraftPlus Free 1.26.5** | Base de données | Quotidienne | 7 | Google Drive (externe) |
| 1 | UpdraftPlus | Fichiers (plugins/thèmes/uploads/autres) | Hebdomadaire | 4 | Google Drive (externe) |
| 2 | **JetBackup 5** (o2switch, automatique) | Compte complet (home + bases) | Quotidien (géré hébergeur) | ~30 j | Infrastructure o2switch |
| 3 | Manuel ponctuel | Dump SQL phpMyAdmin + `wp-config.php.bak` | Avant opérations risquées | — | Poste local Alain (hors repo) |

## 2. Configuration UpdraftPlus

- Plugin : UpdraftPlus Free (TeamUpdraft/DavidAnderson), installé 2026-06-12 sur la prod.
- Stockage distant : **Google Drive**, compte dédié projet **`jabarlehunt.alain@gmail.com`** (15 Go libres ; usage 0 % à l'init).
- Authentification OAuth réussie (1ʳᵉ tentative échouée `googledrive_insufficient_permissions` — case d'autorisation Drive non cochée sur l'écran granulaire Google ; résolu en refaisant le flux avec la case cochée).
- Rapport email basique : actif vers l'email admin.
- Planification posée le 2026-06-12 ~19h26 → sauvegardes nocturnes/matinales (~09h26 fichiers hebdo, base quotidienne).

## 3. Drill de restauration complète (2026-06-12)

**Contexte** : drill exécuté sur la **production**, validé par Alain **uniquement parce que le site est encore vide + noindex** (fenêtre sans risque, avant import catalogue). Ne pas reproduire tel quel sur un site vivant sans arbitrage.

**Protocole** :
1. Vérification JetBackup (67 sauvegardes compte ; ⚠️ base `heal3867_wp354` créée la veille **pas encore couverte** par le passage nocturne → filet manuel créé : dump SQL phpMyAdmin téléchargé en local).
2. Sauvegarde complète UpdraftPlus 19h31 (db + plugins + thèmes + uploads + autres), envoyée Google Drive, icône Drive confirmée sur la ligne de sauvegarde.
3. **Marqueur témoin** : page brouillon `TEST-RESTORE-MARKER` créée **après** la sauvegarde.
4. Restauration complète (tous composants cochés) → **« Restore successful! »**.
5. Vérifications post-restauration :
   - ✅ Marqueur `TEST-RESTORE-MARKER` **disparu** (preuve du retour à l'état 19h31)
   - ✅ Front `https://koinoborihouse.com` fonctionnel, HTTPS OK
   - ✅ Wordfence intact (réglages KH-101 préservés)
   - ✅ Santé du site « Bien »
   - ✅ Anciens répertoires de restauration nettoyés

**Verdict : restauration complète prouvée. Sauvegardes UpdraftPlus = restaurables.**

## 4. Procédure de restauration d'urgence (référence future)

1. `wp-admin` accessible → UpdraftPlus → Sauvegardes existantes → Restaurer → cocher tous les composants → suivre l'assistant.
2. `wp-admin` inaccessible → réinstaller WP propre (Softaculous) → installer UpdraftPlus → Réglages → reconnecter Google Drive (compte `jabarlehunt.alain@gmail.com`) → « Scanner l'espace de stockage distant » → restaurer.
3. Alternative niveau 2 : cPanel → JetBackup 5 → restaurer Home Directory + base `heal3867_wp354`.
4. Toujours vérifier après restauration : front, login admin + 2FA, Wordfence, Santé du site.

## 5. Points de vigilance

- ⚠️ **2FA sur le compte Google `jabarlehunt.alain@gmail.com`** : à confirmer/activer (le compte détient des copies complètes du site, base incluse). **Point ouvert au moment de la fermeture du ticket.**
- JetBackup couvre `wp354` à partir de son premier passage nocturne post-création (vérifier sous quelques jours que la base apparaît dans Restore → Databases).
- Quota Drive : 15 Go ≫ besoin actuel ; le rapport email UpdraftPlus alertera en cas d'échec d'envoi.
- Avec l'arrivée du catalogue (Lot 2), le volume des archives `uploads` grossira — rétention 4 hebdo à réévaluer si besoin.
- ~~Staging (`staging.koinoborihouse.com`) : **non couvert** par UpdraftPlus (environnement jetable, re-clonable depuis la prod via Softaculous). Choix assumé.~~

  🔄 **Périmé, corrigé le 2026-09-09 après vérification sur le site.** Cette ligne datait du 2026-06-12, quand staging était vide. Elle est fausse aujourd'hui, et sur les deux plans :

  - **Staging *est* couvert.** L'écran UpdraftPlus de staging montre 9 sauvegardes existantes, toutes envoyées vers Google Drive : base de données **quotidienne** les 04, 05, 06, 07, 08 et 09 septembre à 9h30, plus des sauvegardes complètes (base, extensions, thèmes, téléversements, mu-plugins) les 29/08, 03/09 et 05/09. Planification active : base le jeudi, fichiers le samedi.
  - **Staging n'est plus jetable.** Il détient l'unique copie des 26 pages, 8 formulaires, 2 menus, métadonnées SEO et appariements Polylang du Lot 3. Le clonage prod vers staging, exécuté deux fois en juin, **détruirait tout** s'il était relancé par réflexe. Ne plus le faire sans sauvegarde préalable et sans décision explicite.

  Une sauvegarde complète manuelle a été déclenchée le 2026-09-09 à 17h57, avec l'option de **rétention manuelle** pour qu'elle échappe à la rotation : c'est le point de restauration de référence de l'état Lot 3.

  ⚠️ La revue de code du 2026-09-09 avait classé l'absence de sauvegarde de staging en risque le plus grave de la PR. Ce classement reposait sur cette ligne de documentation, pas sur le site. Le risque réel était documentaire : quelqu'un lisant ce ticket aurait pu conclure qu'un re-clonage était sans conséquence.

## 6. Liens

- KH-100/100b/101 : fondation WP + staging + sécurité (notes de session, hors repo)
- CLAUDE.md §Stack technique verrouillée (UpdraftPlus + Google Drive)
