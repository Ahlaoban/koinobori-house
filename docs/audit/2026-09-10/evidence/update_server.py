"""Mise à jour documentaire S19 ; ne touche pas au code applicatif."""
from pathlib import Path
import subprocess, sys
out=Path(__file__).resolve().parent.parent
p=out/'evidence/build_matrix.py'
s=p.read_text('utf-8')
# La classe IMPROVE recommande une intervention sur un composant observé,
# sans attester que ses tests métier sont réussis.
lines=s.splitlines()
add={
'M07':'V S19 : racines/bases identifiées et checksums cœur conformes sur les deux sites ; fichiers error_log supplémentaires à contrôler',
'M08':'V S19 : absent de production ; staging HPOS 4 checkout-draft et 1 completed, nature réelle/test non établie',
'M11':'V S19 : parent production 1.5.0 confirmé par WP-CLI ; conserver le socle et vérifier compatibilité sur clone',
'M12':'V S19 : functions.php différent après normalisation CRLF/LF ; charte v3 absente du staging ; lint PHP réussi',
'M14':'V S19 : theme.json staging diffère réellement du dépôt après normalisation CRLF/LF',
'M19':'V S19 : fichier staging identique au dépôt après normalisation, lint réussi ; absent de production',
'M20':'V S19 : absence du fichier confirmée dans les deux racines',
'M21':'V S19 : absence du fichier confirmée dans les deux racines',
'M22':'V S19 : fichier staging identique après normalisation et lint réussi ; absent de production',
'M31':'V S19 : JetBackup 32 copies par base KH, dernières09/09 ; 0 archive backup_ directe dans updraft staging ; intégrité et restauration NV',
'M35':'V S19 : uploads0777 et wp-config0644 sur les deux sites ; compte système partagé ; protection Basic staging ; exploitation/MFA NV',
'M36':'V S19 : aucun drop-in PHP observé à la racine wp-content staging ; logs supplémentaires dans les cœurs WP, contenu et accès public NV',
'M55':'V S19 : six lunes gratuites disponibles ; quota réel, licences, factures et coûts récurrents restent NV',
}
for i,line in enumerate(lines):
    if not line.startswith('M') or '|' not in line: continue
    v=line.split('|')
    if v[0] in {'M07','M08','M11'}: v[3]='IMPROVE'
    if v[0] in add: v[4]+=' ; '+add[v[0]]
    lines[i]='|'.join(v)
s='\n'.join(lines)+'\n'
s=s.replace("'version':'0.4'","'version':'0.5'").replace('— v0.4','— v0.5').replace('S01–S18 et F01–F15','S01–S19 et F01–F16')
s=s.replace('Lecture/inventaire local, HTTP public ou UI staging selon S16 ; tests transactionnels et restauration non exécutés','Lecture/inventaire local, HTTP public, UI S16–S18 et serveur S19 selon composant ; lint de trois PHP et checksums cœur exécutés ; transactions et restauration non exécutées')
s=s.replace('Production : generator WP7.1 ; staging : état WC WP7.1','Production et staging WP7.1 via WP-CLI ; checksums cœur réussis avec avertissements fichiers supplémentaires S19')
s=s.replace('Production : actifs Kadence1.5.0 ; staging : état WC Kadence1.5.2','Production : Kadence1.5.0 WP-CLI S19 ; staging : Kadence1.5.2 état WC S16')
s=s.replace('(écran authentifié S16, pas de checksum distant)','(écran authentifié S16 ; comparaison distante limitée aux chemins explicités S19)')
p.write_text(s,'utf-8')
subprocess.run([sys.executable,str(p)],check=True)

p=out/'RAPPORT-AUDIT-KH2027.md'; s=p.read_text('utf-8')
s=s.replace('version 0.4','version 0.5').replace('IMPROVE 24','IMPROVE 27').replace('22 décisions non attribuées','19 décisions non attribuées')
s=s.replace('**La comparaison complète base/fichiers entre staging, dépôt et production reste impossible à ce stade.**','**Le complément serveur S19 identifie les deux racines et bases, confirme l’absence de WooCommerce/Polylang en production et compare neuf chemins spécifiques. Il reste un rapprochement complet des fichiers et données à réaliser ; le contrôle partiel ne permet pas de recopier aveuglément le dépôt sur staging.**')
s=s.replace('| S18 |', '| S19 | [Audit cPanel et serveur](evidence/serveur-cpanel.md) | Racines/bases, sauvegardes JetBackup, neuf comparaisons de fichiers, commandes HPOS, permissions, lint et checksums cœur |\n| S18 |',1)
s=s.replace('**NV.** Aucun checksum des fichiers PHP distants ni commit de staging.','**V S19.** Neuf chemins comparés : staging comporte quatre correspondances après normalisation CRLF/LF, deux différences de contenu (functions.php, theme.json), trois absences (charte v3, BCDG, diagnostic). Les trois PHP spécifiques présents passent le lint. Production comporte sept absences et deux différences. **NV.** Commit exact du staging, rapprochement ligne par ligne et inventaire comparatif exhaustif.')
s=s.replace('**NV.** Contenu et intégrité des archives, accès aux objets Drive/JetBackup, restauration, neutralisation des sorties et durée réelle de reprise.','**V S19.** JetBackup liste 32 sauvegardes de chacune des deux bases KH identifiées par WP-CLI, dernières affichées le09/09. Le dossier updraft staging ne contient aucun fichier direct backup_. **NV.** Contenu et intégrité des archives, récupération des objets Drive/JetBackup, restauration, neutralisation des sorties et durée réelle de reprise.')
s=s.replace('Un en-tête ou namespace indique un comportement visible, pas la configuration complète, la licence, la protection effective ou la version binaire installée. L\'absence de namespace WooCommerce est cohérente avec la passation, mais ne suffit pas seule à prouver qu\'aucun plugin WC n\'est installé.','Un en-tête ou namespace ne suffisait pas à inventorier production. **S19 apporte désormais la preuve WP-CLI : seules UpdraftPlus1.26.5 et Wordfence8.2.2 sont installées, sans WooCommerce ni Polylang.** WP7.1 et Kadence1.5.0 sont confirmés directement. Les checksums du cœur réussissent dans les deux environnements, avec avertissements de fichiers error_log supplémentaires. Les racines et bases distinctes restent sous le même compte système.')
s=s.replace('## 7. Écarts de cible', '''### F16 — Isolation, permissions et fichiers de logs à renforcer — P1, P0 avant copie de données privées

**V S19.** Production et staging sont sous le même compte d'hébergement, partagé avec d'autres sites hors périmètre. Les deux dossiers uploads sont en0777 ; wp-config.php en0644. Le staging comporte une protection Basic au niveau .htaccess. Les fichiers du cœur vérifiés correspondent aux checksums officiels, mais des error_log supplémentaires sont signalés. **NV.** Propriétaires effectifs, ACL, cloisonnement hébergeur, exposition HTTP des logs, quota et contrôle des sorties du clone ; aucune compromission démontrée.

**R.** Utiliser un sous-compte indépendant pour la copie de test ; six emplacements gratuits sont disponibles dans cPanel. Vérifier ses droits/base, capacité, accès et neutralisation des sorties avant le premier démarrage de la copie. Tester la réduction des permissions et le stockage privé des logs sur clone avant application aux sources. Ne pas confondre espace libre du système de fichiers avec quota du compte. Aucun sous-compte activé, aucune dépense, aucun chmod ou déplacement réalisé. [Principes WordPress](https://developer.wordpress.org/advanced-administration/server/file-permissions/), [isolation o2switch](https://faq.o2switch.fr/cpanel/o2switch/univers-web-sous-comptes/).

## 7. Écarts de cible''',1)
s=s.replace('**Résultat actuel :** aucune nouvelle sauvegarde serveur ni restauration exécutée, les ensembles Updraft étant seulement listés, sans archive vérifiée ni environnement isolé identifié.','**Résultat actuel :** aucune nouvelle sauvegarde serveur ni restauration exécutée. Updraft et JetBackup sont inventoriés ; aucune archive décompressée ou vérifiée. Un sous-compte gratuit est une destination candidate, pas encore un environnement isolé opérationnel.')
s=s.replace('**Non exécuté :** tests automatisés PHP/WP,','**Non exécuté :** tests fonctionnels automatisés PHP/WP,')
s=s.replace('inventaire authentifié production, comparaison des fichiers PHP distants, audit intégral des réglages et extensions serveur.','rapprochement ligne par ligne des PHP distants et comparaison complète de tous les fichiers/bases, audit intégral des réglages et extensions serveur.')
s=s.replace('**Préservation vérifiée :**','**Complément exécuté S19 :** lecture ciblée cPanel, associations domaine/racine/base, plugins/thèmes production, neuf comparaisons SHA-256 normalisées, lint de trois PHP staging, SELECT agrégés incluant HPOS (quatre brouillons checkout et une commande terminée), permissions de quatre chemins, inventaire Updraft local et JetBackup, sous-comptes disponibles, checksums des deux cœurs avec avertissements logs.\n\n**Préservation vérifiée :**',1)
s=s.replace('Partielle ; versions UI staging et divergences CSS/routage prouvées, fichiers/base non réconciliés','Partielle ; S19 ajoute racines/bases, neuf hashes normalisés et compteurs HPOS ; comparaison exhaustive non réalisée')
s=s.replace('**G0 reste ouvert.** La prochaine preuve nécessaire est l’inventaire des fichiers/configurations serveur et l’intégrité des sauvegardes, puis une restauration isolée avec retour mesuré. L’accès administrateur staging a été obtenu et exploité dans cet audit. Le dossier permet déjà de discuter les décisions de reprise sans modifier ni sacrifier le travail existant.','**G0 reste ouvert.** Les accès staging et serveur ont été obtenus et exploités. Les preuves restantes sont la récupération privée d’un ensemble cohérent, son intégrité, puis une restauration isolée avec retour mesuré et le rapprochement des écarts. La validation du jalon d’architecture ne vaut pas réussite de ces contrôles. Le [dossier des décisions](DECISIONS-JALON.md) précise les seuls arbitrages proposés ; aucune transformation structurelle avant validation utilisateur.')
p.write_text(s,'utf-8')

p=out/'RESTAURATION-G0.md'; s=p.read_text('utf-8')
s=s.replace('| JetBackup | Mention historique de sauvegarde du compte dans KH-102 | Couverture actuelle des bases production ET staging, dates et capacité de restauration à vérifier |','| JetBackup | S19 : 32 copies par base KH ; dernières affichées09/09 ; bases directement associées aux sites | Contenu, cohérence fichiers/base et restauration non vérifiés |')
s=s.replace('| Destination isolée | Aucune identifiée dans le dépôt ou les accès déjà connus | Lire domaines, racines, bases, capacité et outils cPanel |','| Destination isolée | S19 : six sous-comptes gratuits disponibles ; aucun activé | Candidat distinct ; droits, quota, accès privé et contrôle des sorties à établir avant copie |')
s=s.replace('Sources : [S16]','Sources : [S19](evidence/serveur-cpanel.md), [S16]',1)
s=s.replace('## Lecture cPanel à effectuer en premier','## Lecture cPanel — réalisée partiellement dans S19, contrôles restants')
s=s.replace('Les cinq objets shop_order_placehold observés ne permettent pas à eux seuls de compter les commandes HPOS ni de déterminer leur statut commercial. Relever les véritables commandes et leurs relations sans publier de données client.','S19 complète les placeholders par un SELECT HPOS : quatre wc-checkout-draft et une wc-completed. La nature réelle/test de la commande terminée reste inconnue. Préserver chaque enregistrement et rapprocher ses relations au moment du snapshot, sans publier de données client.')
s=s.replace('| Source / snapshot / opérateur | À identifier |','| Source / snapshot / opérateur | Racine staging et base heal3867_wp551 identifiées S19 ; snapshot cohérent à sélectionner |')
s=s.replace('| Résultat archive locale / copie externe | Non vérifié |','| Résultat archive locale / copie externe | 0 fichier direct backup_ dans updraft staging ; copies externes listées, intégrité non vérifiée |')
p.write_text(s,'utf-8')

p=out/'PILOTAGE-REPRISE.md'; s=p.read_text('utf-8').replace('Rapport v0.4','Rapport v0.5').replace('22 décisions encore réservées','19 décisions encore réservées')
s=s.replace('Utilisateur indique l’onglet ouvert ; nom du serveur demandé pour accès ciblé, inspection non encore effectuée','Session ciblée exploitée ; inventaire serveur S19, sans URL de session dans les preuves')
s=s.replace('À présenter après les compléments d’audit nécessaires','Dossier DECISIONS-JALON.md disponible ; validation utilisateur non reçue')
s=s.replace('Accéder uniquement au serveur cPanel indiqué par l’utilisateur, lire domaines/racines/bases/sauvegardes/capacité, puis remplir le protocole de restauration.','Soumettre les orientations du dossier DECISIONS-JALON.md. Après validation, achever les preuves de restauration et de réconciliation requises avant les transformations ; poursuivre ensuite les lots approuvés.')
p.write_text(s,'utf-8')

p=out/'evidence/validate_audit.py'; s=p.read_text('utf-8').replace("out/'RESTAURATION-G0.md']","out/'RESTAURATION-G0.md',out/'DECISIONS-JALON.md',out/'PILOTAGE-REPRISE.md']").replace('UI read-only only; no transaction, restoration or code/configuration mutation','UI and server read-only inventory, SELECT aggregates, normalized hashes, PHP lint and core checksums; no transaction, restoration or deliberate application/configuration mutation')
p.write_text(s,'utf-8')
