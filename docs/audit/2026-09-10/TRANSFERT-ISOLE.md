# Transfert privé — autorisation reçue et préparation

**Autorisation reçue le 10/09/2026 : « Permission accordée », en réponse à la demande explicite de clé SSH temporaire restreinte sur sc3heal3867 et de fermeture Web de ce sous-compte. Ne pas redemander cet accord pour les mêmes actions.** L’historique du refus ci-dessous reste conservé pour expliquer le jalon.

Après confirmation « activation faite » d’Alain, cPanel montre sc3heal3867 actif, gratuit ; trois lunes actives et cinq restantes. Le sous-compte est accessible depuis la session principale. Aucun nouveau mot de passe lu ou collecté.

## Précontrôles vérifiés

- Compte cible sc3heal3867, UID1473, répertoire `/home3/sc3heal3867` ; compte source heal3867, UID1067.
- Racine Web cible `/home3/sc3heal3867/public_html`, contenant seulement cgi-bin lors du contrôle. Aucune base existante affichée dans cPanel.
- Lecture de `/home3/heal3867/staging.koinoborihouse.com/wp-config.php` impossible depuis le compte cible selon os.access. Ce test ponctuel ne constitue pas un audit exhaustif du cloisonnement.
- Aucune ligne cron active relevée. Outils wp, uapi, php, ssh, ssh-keygen et curl présents.
- cPanel affiche disque156KB sans quota fini, mémoire48GB, I/O48MB/s et IOPS1024. Ce sont des limites affichées, pas une mesure de performance garantie.

## Opération concrète proposée

1. Dans le seul compte cible, créer le dossier privé kh2027-private/incoming en0700 et installer le récepteur tools/recovery/receive_snapshot.py. Celui-ci écrit uniquement transfer.tar avec création exclusive, maximum256MiB, délai180s ; il ne restaure rien, ne lit pas les sources et n’exécute aucun argument du client.
2. Autoriser temporairement une clé publique Ed25519 de transfert dans le fichier .ssh/authorized_keys de **sc3heal3867 uniquement**, limitée à la provenance127.0.0.1 et à la commande forcée du récepteur, sans shell interactif, PTY ni forwarding. Préserver les autres lignes. La clé privée correspondante reste sur le compte source dans kh2027-private/transfer-sc3-20260910 ; elle n’est ni affichée ni exportée. Sa création est réalisée ; aucun accès distant n’a été autorisé avec elle.
3. Bloquer temporairement tout accès HTTP à la racine Web cible avec un nouveau .htaccess `Require all denied`, puis vérifier le refus. Production et staging ne sont pas concernés. Aucune archive ni configuration privée n’est placée dans le Web.
4. Transmettre par SSH local chiffré les archives déjà vérifiées et leurs manifestes depuis le compte source vers le récepteur fixe. Vérifier l’identité SSH du serveur et les empreintes reçues avant toute extraction. Arrêter en cas d’erreur, conserver les preuves et ne pas écraser une réception existante.
5. Retirer uniquement la ligne de clé temporaire ajoutée après le transfert, ou en cas d’abandon de la tentative. Garder le Web cible fermé jusqu’à la mise en place et au test des protections du clone.

**Risque borné :** pendant sa présence, la clé permet de déposer un flux limité dans un seul fichier privé du nouveau compte, depuis le serveur lui-même. Le blocage HTTP rend la nouvelle adresse de test indisponible jusqu’à sa réouverture contrôlée. Aucune lecture des bases sources n’est accordée au nouveau compte, aucun droit de shell n’est accordé à cette clé et aucun fichier existant n’est écrasé par le récepteur.

## Refus automatique et état réel

Le contrôle automatique d’autorisation avait refusé la commande regroupant la préparation du récepteur, l’ajout de la clé SSH et le blocage HTTP, au motif que les accès persistants et leurs destinataires précis n’étaient pas explicitement autorisés. **Cette commande initiale n’a pas été exécutée.** L’autorisation explicite d’Alain a ensuite été reçue pour l’ajout temporaire de cette clé sur sc3heal3867 et le blocage Web de ce seul sous-compte, comme indiqué en tête de document. Ce refus historique n’est plus une demande d’autorisation en attente.

Les précontrôles en lecture seule et la préparation locale restent autorisés. Le script récepteur est testé sur fixtures : copie exacte, refus d’écrasement d’un fichier et refus d’un flux trop grand. La restauration, les sorties du clone et le retour ne sont pas encore testés. La sauvegarde source demeure privée et inchangée.

## Exécution après autorisation

L’ancien terminal n’était plus attaché au navigateur. Une ouverture de l’ancienne URL source a répondu « Jeton de sécurité non valide » dans le contexte sc3heal3867. Une recherche d’historique limitée au serveur cow.o2switch.net:2083, entre10:00 et12:21 UTC le10/09, a retrouvé une URL récente du terminal du sous-compte. L’outil a mis environ96 minutes à retourner le résultat ; aucune opération serveur n’a été exécutée pendant cette attente. L’URL de session reste hors des fichiers versionnés.

Le terminal retrouvé confirme UID1473 et /home3/sc3heal3867. Précontrôle : kh2027-private, .ssh et public_html/.htaccess absents. Les actions suivantes ont ensuite terminé avec succès :

- Création de kh2027-private et incoming en0700, sans écrasement.
- Écriture d’une version compacte du récepteur dans kh2027-private/receive_snapshot.py, fichier privé ; contrôle py_compile réussi.
- Création de public_html/.htaccess en0644 avec `Require all denied` et `Options -Indexes`.
- Aucun authorized_keys créé et aucune clé SSH activée à ce stade : attendre le rétablissement de l’accès source avant d’ouvrir cette autorisation temporaire.

Le compte principal heal3867 doit être rouvert pour accéder aux archives sources. Une demande d’adresse actuelle de son onglet a été envoyée à Alain, sans demande de mot de passe. L’autorisation de transfert est acquise ; il s’agit uniquement d’un accès de session manquant. Aucune archive transférée ou restaurée à ce stade.

## Contrôles Web et TLS du sous-compte

Preuves observées le 10/09/2026 dans le terminal cPanel cible et les interfaces SSL/TLS Status et Let's Encrypt du même compte :

- Une requête HEAD HTTP vers la racine de sc3heal3867.universe.wf retourne `HTTP/1.1 403 Forbidden`, date serveur 14:04:56 GMT. Le refus est ainsi vérifié depuis le serveur ; ce contrôle seul ne couvre pas tous les chemins ni un accès depuis un réseau extérieur.
- La requête HEAD HTTPS échoue avec le code curl 60, certificat autosigné. Aucun contournement de validation TLS n'a été utilisé. L'interface SSL/TLS Status confirme un certificat autosigné sur les dix noms affichés, expirant le 10/09/2027, sans renouvellement AutoSSL pour ce certificat.
- L'interface Let's Encrypt n'affiche aucun certificat existant et propose une génération gratuite. Les alias mail et www ont été décochés conformément à l'avertissement de l'interface sur les hôtes alternatifs universe.wf ; aucun wildcard ni sous-domaine de service cPanel ajouté.
- Une seule **simulation**, méthode http-01, pour le seul domaine sc3heal3867.universe.wf, échoue auprès du service ACME de test : `SERVFAIL looking up CAA for universe.wf`. Ce message constitue une erreur DNS rapportée par l'autorité de test, pas un diagnostic indépendant des serveurs DNS. Aucun certificat de production demandé ou installé.

Le certificat valide reste une exigence avant ouverture de la version de test. Cet échec ne bloque pas le transfert SSH privé ni la restauration hors Web lorsque l'accès source sera disponible. La protection Web reste en place ; aucun changement DNS, aucune clé SSH autorisée, aucune archive transférée pendant ces contrôles.

## Reprise après réinitialisation — 10 septembre 2026

Le checkout `codex/kh2027-reprise` et les fichiers non commités de préparation
ont été retrouvés et conservés. L'ouverture ciblée de `cow.o2switch.net:2083`
affiche le formulaire « Identifiant cPanel » : la session principale n'est pas
accessible dans ce navigateur. La page a été laissée ouverte pour reconnexion
par Alain ; aucun secret demandé dans le chat. Aucune opération serveur effectuée
pendant cette reprise. Les constats serveur ci-dessus restent historiques, sans
nouvelle vérification de leur état.

Contrôles locaux : **18 tests réussis**, avec Python fourni par l'environnement
Codex. L'extraction privée vérifie désormais aussi la décompression intégrale du
SQL et son plafond de taille. Sept tests supplémentaires éprouvent le transfert
complet sur fixtures et ses échecs : empreinte de transport, destination existante,
gzip tronqué, manifestes divergents, archive altérée et membre inattendu.

Prochaine action : après reconnexion du compte principal, vérifier les deux
identités et les fichiers déjà présents, reprendre le transfert autorisé, retirer
la clé temporaire, puis extraire en privé. Ne pas déclarer la restauration SQL,
la neutralisation des sorties, la recette WordPress ou G0 réussis sur la seule
base des tests locaux.

## État courant — S21

La séquence précédente est historique. Le transfert privé a depuis été reçu et
son SHA-256 vérifié ; la clé temporaire a été retirée et aucune ligne ne reste
dans `authorized_keys`. L'extraction complète, l'import et le retour SQL sur la
base isolée, puis le démarrage contrôlé de WordPress et du socle commercial ont
réussi. La racine Web du sous-compte reste fermée. Les résultats, limites et
preuves chiffrées sont centralisés dans
[S21 — restauration](evidence/reprise-restauration.md).
