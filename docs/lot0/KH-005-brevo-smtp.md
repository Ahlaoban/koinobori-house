# KH-005 — Brevo SMTP transactionnel

Fiche cadrage Lot 0 pour infrastructure email transactionnelle Koinobori House. Doc-only, pas de code ni intégration WP à ce stade.

- **Ticket** : KH-005
- **Lot** : 0
- **Date ouverture** : 2026-05-29
- **Date fermeture** : 2026-05-29
- **Statut** : ✅ **FERMÉ côté Lot 0**
- **Dépendances aval** : Lot 1 KH-100+ (install WP + plugin FluentSMTP + connexion Brevo)

## ✅ Validation fermeture KH-005 (2026-05-29)

Tous prérequis SMTP transactionnel acquis :

- ✅ Compte Brevo créé, plan Free actif
- ✅ Expéditeur `contact@koinoborihouse.com` vérifié
- ✅ Nom expéditeur `Koinobori House`
- ✅ Domaine `koinoborihouse.com` authentifié dans Brevo
- ✅ DNS posés et validés o2switch : Brevo code + DKIM 1 + DKIM 2 + DMARC
- ✅ Ancien mot de passe SMTP exposé en capture **remplacé**
- ✅ Nouvelle clé SMTP standard générée + stockée gestionnaire mots de passe uniquement
- ✅ Aucune clé / API / credential dans le repo

**Décisions complémentaires** :
- ❌ Blocage IP SMTP **non activé** maintenant — réévaluation Lot 1 quand IP sortante WordPress/o2switch confirmée
- ⏸ Install FluentSMTP différée Lot 1
- ⏸ Intégration WP/WC SMTP différée Lot 1

## 1. Scope KH-005

Préparer l'infrastructure email transactionnelle du futur site Koinobori House avec **Brevo SMTP** (stack verrouillée CLAUDE.md).

**Inclus MVP** :
- Compte Brevo créé / vérifié
- Domaine authentifié côté Brevo (DNS SPF + DKIM + éventuel DMARC)
- Adresse expéditeur configurée
- Nom expéditeur configuré
- Prêt pour connexion plugin FluentSMTP Lot 1

**Hors MVP (phase 2 ou Lot 1+ selon besoin)** :
- Newsletter (Brevo Email Campaigns) — pas MVP sauf besoin avéré
- Templates email avancés
- Automation Brevo
- SMS marketing

## 2. Cas d'usage cibles (Lot 1+)

Emails transactionnels à terme :
- ✅ Confirmations commande WooCommerce
- ✅ Emails compte client (création, reset password, modification)
- ✅ Notifications formulaires contact B2B / B2G (Fluent Forms → email admin)
- ✅ Emails système WordPress (mot de passe perdu admin, etc.)
- ⏸ Newsletter — hors MVP sauf décision Alain

## 3. Compte Brevo

| Item | Décision |
|------|----------|
| Plan Brevo | À choisir : Free (300 emails/jour) suffisant pour démarrage MVP B2C bas volume, ou Lite payant si projection > 9000 emails/mois |
| Compte | À créer ou vérifier si déjà existant (Alain) |
| Email login | À définir Alain (email perso ou pro Alain, pas adresse de marque) |
| 2FA | **Obligatoire** : activer 2FA Brevo via app authentificateur (TOTP, pas SMS) cohérent doctrine sécu KH-004 |

## 4. Identité expéditeur

| Champ | Valeur recommandée |
|-------|--------------------|
| **Adresse expéditeur principale FR** | `contact@koinoborihouse.com` |
| **Adresse expéditeur EN** (optionnelle, plus tard si besoin) | `hello@koinoborihouse.com` |
| **Nom expéditeur** | `Koinobori House` |
| **Reply-To** | `contact@koinoborihouse.com` |

**Adresses techniques additionnelles à prévoir côté boîtes mail o2switch** :
- `noreply@koinoborihouse.com` (alias éventuel, à éviter sauf cas système — mauvais pour deliverability)
- `support@koinoborihouse.com` (optionnel)

⚠️ **Ne pas créer encore les boîtes mail côté o2switch** sauf si Alain veut anticiper. Création peut se faire au moment connexion DNS.

## 5. DNS à configurer (SPF + DKIM + DMARC)

À configurer côté **o2switch zone DNS koinoborihouse.com** une fois domaine acquis et compte Brevo créé.

**Valeurs exactes à récupérer depuis dashboard Brevo** (Account → Senders → Add domain → DNS records). Les valeurs ci-dessous sont **génériques indicatives**, **ne pas appliquer telles quelles**.

### SPF (TXT @ koinoborihouse.com)

Indicatif (à confirmer Brevo dashboard) :
```
v=spf1 include:spf.brevo.com mx ~all
```

Notes :
- `include:spf.brevo.com` ou `include:sendinblue.com` selon la doc Brevo actuelle
- `mx` autorise les serveurs MX du domaine (utile si o2switch envoie aussi via webmail)
- `~all` softfail (recommandé en démarrage, peut passer à `-all` strict plus tard)

### DKIM (TXT mail._domainkey ou autre selector Brevo)

Brevo fournit 1 ou 2 enregistrements DKIM dans le dashboard. Format typique :
```
mail._domainkey.koinoborihouse.com IN TXT "k=rsa; p=MIGfMA0GCSqGSIb3..."
```

⚠️ Le selector (`mail._domainkey` ou autre) **dépend exactement** de ce que Brevo génère pour ce compte. Copier-coller depuis Brevo dashboard.

### DMARC (TXT _dmarc, optionnel mais recommandé)

Démarrage en mode `p=none` (monitoring sans bloquer) :
```
_dmarc.koinoborihouse.com IN TXT "v=DMARC1; p=none; rua=mailto:contact@koinoborihouse.com; pct=100"
```

Évolution recommandée :
- Phase 1 (3-4 semaines) : `p=none` (monitoring)
- Phase 2 (post-validation rapports rua) : `p=quarantine`
- Phase 3 (mature) : `p=reject` (strict, après confirmation 100 % emails légitimes signés)

### Vérification post-config

Outils gratuits à utiliser :
- `mxtoolbox.com/SuperTool.aspx` (SPF lookup + DKIM lookup + DMARC lookup)
- `mail-tester.com` (envoyer email test depuis Brevo, scoring deliverability /10)

## 6. Sécurité — clés API / SMTP

❌ **Jamais stocker en clair dans le repo** :
- Clé API Brevo (`xkeysib-...`)
- SMTP password Brevo (clé SMTP générée dashboard)
- Login Brevo
- Email de récupération Brevo

✅ **Stockage attendu Lot 1** :
- Plugin FluentSMTP stocke credentials chiffrés en base WP (option `_options_chiffrees`)
- Configuration plugin via interface admin WP, jamais commit
- Variables sensibles éventuelles dans `wp-config.php` (hors repo via `.gitignore`)

✅ **`.gitignore` strict obligatoire Lot 1** :
- `.env*`
- `wp-config.php`
- `wp-content/uploads/`
- `*.key`, `*.pem`
- Tout fichier mentionnant `BREVO_API_KEY`, `SMTP_PASSWORD`, etc.

## 7. Intégration WordPress (Lot 1, hors KH-005)

Plugin verrouillé stack CLAUDE.md : **FluentSMTP** (gratuit, robuste, supporte Brevo natif).

Étapes à Lot 1 (KH-100+) :
1. Installer **FluentSMTP** plugin gratuit
2. Settings FluentSMTP → Add Provider → **Brevo** (sélection native)
3. Saisir API key (récupérée Brevo dashboard, jamais commit)
4. Adresse expéditeur : `contact@koinoborihouse.com`
5. Nom expéditeur : `Koinobori House`
6. Mode envoi : **API** (recommandé) ou SMTP standard
7. Test d'envoi via FluentSMTP → Send Test Email
8. Activer Logs FluentSMTP (utile debug)

## 8. Tests d'envoi prévus (post-config FluentSMTP Lot 1)

Liste à exécuter avant publication site (KH-707 audit) :
- [ ] Envoi vers **Gmail** (vérif boîte de réception + spam + promotions)
- [ ] Envoi vers **Outlook / Hotmail** (vérif boîte + spam)
- [ ] Envoi vers **adresse pro Alain** (vérif anti-spam corporate)
- [ ] Envoi vers **Yahoo Mail** (anti-spam strict)
- [ ] Email reçu : vérifier expéditeur affiché `Koinobori House <contact@koinoborihouse.com>`
- [ ] Email reçu : vérifier signature DKIM valide (header `Authentication-Results: dkim=pass`)
- [ ] Email reçu : vérifier SPF pass (header `spf=pass`)
- [ ] Score `mail-tester.com` ≥ 8/10
- [ ] Test simulation parcours commande WC (Lot 4+) : `New order`, `Order completed`, `Customer invoice` reçus correctement

## 9. Checklist actions Alain (HISTORIQUE — toutes résolues 2026-05-29)

> ⚠️ **Section historique.** Toutes les actions ci-dessous ont été **réalisées et validées** (cf §Validation fermeture KH-005 en tête de fiche). Les statuts « ❌ à faire » conservés ci-dessous documentent le cadrage initial et **ne reflètent pas l'état réel** : compte Brevo, 2FA, plan, expéditeur, DNS SPF/DKIM/DMARC et stockage hors-repo de la clé SMTP sont **acquis**.

| # | Action | Owner | Statut |
|---|--------|-------|--------|
| 1 | Créer ou vérifier compte Brevo Business | Alain | ❌ à faire |
| 2 | Activer 2FA Brevo via app authentificateur (TOTP) | Alain | ❌ à faire |
| 3 | Choisir plan Brevo Free (démarrage) ou Lite payant | Alain | ❌ à faire |
| 4 | Confirmer adresse expéditeur : `contact@koinoborihouse.com` | Alain | ❌ à confirmer |
| 5 | Confirmer nom expéditeur : `Koinobori House` | Alain | ❌ à confirmer |
| 6 | Vérifier accès DNS o2switch koinoborihouse.com (cPanel ou interface zone) | Alain | ✅ opérationnel |
| 7 | Récupérer enregistrements SPF + DKIM depuis dashboard Brevo (Account → Senders → Add domain) | Alain | ❌ post-compte Brevo |
| 8 | Ajouter enregistrements SPF + DKIM dans zone DNS o2switch | Alain | ❌ post-récupération valeurs |
| 9 | Ajouter DMARC `p=none` initial (monitoring) | Alain | ❌ optionnel mais recommandé |
| 10 | Vérifier propagation DNS via mxtoolbox.com (24-48 h max) | Alain | ❌ post-config |
| 11 | Validation domaine côté Brevo dashboard (bouton "Verify") | Alain | ❌ post-propagation |
| 12 | Stocker credentials Brevo (login + API key) dans **gestionnaire mots de passe sécurisé hors repo** | Alain | ❌ obligatoire |

## 10. Informations DNS à collecter post-Brevo (HISTORIQUE — collecte effectuée)

> ⚠️ **Section historique.** Les enregistrements SPF + DKIM 1 + DKIM 2 + DMARC ont été **posés et validés** dans la zone DNS o2switch (2026-05-29). Le tableau ci-dessous, avec ses « ❌ à collecter », documente le cadrage initial ; les valeurs réelles vivent côté dashboard Brevo + zone DNS o2switch, **hors repo**.

Pour mémoire (cadrage initial) :

| Record | Type | Hôte / Selector | Valeur Brevo | Statut |
|--------|------|-----------------|--------------|--------|
| SPF | TXT | `@` (apex) | à récupérer Brevo | ❌ à collecter |
| DKIM 1 | TXT | à récupérer Brevo (ex `mail._domainkey`) | à récupérer Brevo | ❌ à collecter |
| DKIM 2 (si fourni) | TXT | à récupérer Brevo | à récupérer Brevo | ❌ à collecter |
| DMARC | TXT | `_dmarc` | `v=DMARC1; p=none; rua=mailto:contact@koinoborihouse.com; pct=100` | ❌ à appliquer |

## 11. Garde-fous doctrine

- ❌ Pas de credentials Brevo dans repo (ni clé API, ni password SMTP, ni login)
- ❌ Pas de coding intégration FluentSMTP au stade KH-005 (Lot 1)
- ❌ Pas d'installation WordPress / FluentSMTP maintenant
- ❌ Pas de bouton / lien email automatisé visible front
- ❌ Pas de campagne newsletter Brevo MVP sauf décision Alain explicite
- ✅ Compte Brevo + 2FA + DNS = prérequis Lot 1, peut se faire dès maintenant
- ✅ Documentation DNS valeurs dans cette fiche post-collecte
- ✅ Captures écran Brevo dashboard (DNS values fournies) horodatées hors-repo
- ✅ Stack technique respectée : Brevo + FluentSMTP (pas autre service ni autre plugin)

## 12. Statut

**Prérequis disponibles** ✅ :
- Domaine `koinoborihouse.com` **acquis**
- Accès o2switch / cPanel **opérationnel** → ajout DNS Brevo possible immédiatement

**Actions Alain — réalisées 2026-05-29** (historique, conservé pour traçabilité ; état réel = §Validation fermeture en tête) :
1. Créer ou vérifier compte Brevo
2. Activer 2FA TOTP
3. Configurer expéditeur `contact@koinoborihouse.com`
4. Configurer nom expéditeur `Koinobori House`
5. Récupérer enregistrements SPF / DKIM exacts depuis Brevo dashboard
6. Ajouter SPF + DKIM + DMARC dans zone DNS o2switch / cPanel
7. Vérifier propagation DNS (mxtoolbox.com 24-48 h)
8. Valider le domaine dans Brevo dashboard
9. **Ne stocker aucun identifiant SMTP / API dans le repo** (gestionnaire mots de passe sécurisé hors repo obligatoire)

**Non bloquants Lot 0 général** : KH-005 avance en parallèle des autres tickets Lot 0.

**À maintenir** :
- Plugin futur WordPress : **FluentSMTP** (Lot 1 seulement)
- Pas d'intégration WordPress maintenant
- Pas d'installation plugin maintenant
- Pas de newsletter Brevo MVP sauf décision explicite Alain

## 13. Liens

- CLAUDE.md §Stack technique verrouillée (SMTP : FluentSMTP + Brevo)
- Note mémoire `project_koinobori_kh004_paypal_done` (mémoire de session Claude Code, hors repo) — modèle 2FA app authentificateur cohérent
