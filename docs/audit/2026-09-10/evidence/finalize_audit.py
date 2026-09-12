"""Intègre les observations authentifiées aux seuls livrables d'audit."""
from pathlib import Path
import json, hashlib, subprocess
out=Path(__file__).resolve().parent.parent
p=out/'RAPPORT-AUDIT-KH2027.md'
s=p.read_text('utf-8')
def replace(a,b):
    global s
    assert a in s,a
    s=s.replace(a,b)
replace('version 0.1','version 0.2')
replace('Le staging détient, selon Claude, l\'essentiel du travail métier ; il répond actuellement `401` à nos requêtes non authentifiées.', 'Le staging a ensuite été inspecté dans la session Chrome authentifiée : WordPress 7.1, WooCommerce 11.1.0, 34 produits publiés, huit formulaires actifs et thème enfant 1.1.0 sont confirmés. Le défaut des racines FR/EN est reproduit ; FluentSMTP reste non configuré. Les réponses `401` concernent uniquement les requêtes non authentifiées. [S16]')
replace('| S15 |', '| S16 | [Relevé staging authentifié](evidence/staging-authentifie.md) | Versions, réglages, jobs, sauvegardes, pages, plugins et formulaires lus dans Chrome le 10/09 vers 11:28–11:34 Paris |\n| S15 |')
replace('Déploiement staging actuel et non-régression checkout NV','Fondations chargées sur staging ; charte v3 non liée ; checkout NV')
replace('Non déployé selon passation ; rendu réel NV','Absent de la liste MU actuelle ; rendu produit non recetté')
replace('Saisie serveur déclarée, pas d\'export ni de preuve actuelle','40 pages publiées et huit formulaires confirmés ; définitions/export complets NV')
replace('Production publique               Staging (HTTP Basic 401)\n  WP/Kadence/enfant observables      WP/WC/Polylang et données : déclarés par Claude\n  /fr/ et /en/ : 404                base/fichiers actuels : non consultés\n  ancienne CSS enfant              UpdraftPlus/Drive : sauvegardes déclarées', 'Production publique               Staging authentifié (Basic hors session)\n  WP/Kadence/enfant observables      WP7.1/WC11.1/Polylang vérifiés en UI\n  /fr/ et /en/ : 404                /fr/ et /en/ : blog vide, pages longues présentes\n  enfant1.0.1                      enfant1.1.0, charte v3 non liée\n                                    9 sauvegardes listées, restauration NV')
replace('Version actuelle NV ; mémoire ancienne 7.0','**7.1** dans état WC [S16]')
replace('| Classes et URLs d\'actifs annoncent **1.5.0** | NV |','| Classes et URLs d\'actifs annoncent **1.5.0** | **1.5.2**, thème classique [S16] |')
replace('| `style.css` **1.0.1**, 1 480 octets ; anciennes couleurs/radius 8px | NV |','| `style.css` **1.0.1**, 1 480 octets ; anciennes couleurs/radius 8px | **1.1.0** annoncé, foundations chargée, charte v3 non liée [S16] |')
replace('**11.1 déclaré** le 07/09, à vérifier','**11.1.0**, HPOS actif, synchronisation HPOS désactivée [S16]')
replace('Installation/licence/MFA NV','**9.0.1** active ; intégration Login Security WC inactive ; licence/MFA NV')
replace('| PHP/SQL/cron/cache/HPOS | Non fournis | NV | NV |','| PHP/SQL/cron/cache/HPOS | Non fournis | NV | PHP8.3.33, MariaDB11.4.13, WP-Cron actif, pas de cache objet externe déclaré ; LSCache page indisponible [S16] |')
replace('**NV.** Aucun checksum des fichiers PHP distants ni commit de staging.', '**V.** Staging annonce le thème enfant1.1.0, mais ne lie pas `kh-charte-v3.css` sur l’accueil ; le dépôt la charge explicitement. Le numéro1.1.0 ne suffit donc pas à identifier une release. [S16]\n\n**NV.** Aucun checksum des fichiers PHP distants ni commit de staging.')
replace('**NV.** Archives, intégrité, chiffrement, accès Drive/JetBackup, restauration de cet état, neutralisation paiements/e-mails et durée réelle de reprise non vérifiés.', '**V.** S16 confirme neuf ensembles listés, dont celui du09/09 17h57 ; base quotidienne rétention7, fichiers hebdomadaires rétention4, Google Drive sélectionné et déclaré connecté. La base du10/09 09h30 est listée. Rapport e-mail non coché. Couverture standard wp-content et base, pas wp-config.php ni cœur. **NV.** Contenu et intégrité des archives, accès aux objets Drive/JetBackup, restauration, neutralisation des sorties et durée réelle de reprise.')
replace('Relever la configuration effective plutôt que choisir arbitrairement une phrase.', 'La configuration effective est désormais relevée : base quotidienne ; la phrase « base le jeudi » est périmée. [S16]')
replace('**NV.** Exactitude actuelle de ces nombres et données.', '**V.** La liste actuelle montre34 produits publiés et1 brouillon ; l’état WC compte46 objets variation tous statuts. **NV.** Réconciliation exhaustive des17 paires, données par SKU, médias manquants et synchronisation effective. [S16]')
start=s.index('### F05 —')
end=s.index('**V, code.** KH-107',start)
s=s[:start]+'''### F05 — Accueil Polylang : symptôme reproduit, cause non prouvée — P0 F1

**V.** En session administrateur, `/fr/` et `/en/` rendent le blog vide ; `/fr/accueil/` et `/en/home/` affichent les contenus FR/EN attendus. Réglages lus : `show_on_front=page`, `page_on_front=318`, `page_for_posts=0`. Le menu Accueil pointe `/fr/`, le logo `/fr/accueil/`. Sur cette dernière, canonical pointe `/`, hreflang fr/en pointent les pages longues, x-default `/`. Le dysfonctionnement signalé par Claude est donc reproduit et comporte une incohérence navigation/canonical. Le contexte anonyme avec cache reste à comparer. [S16]

**NV.** Cause exacte dans le cycle de requête, filtres et fichiers réellement chargés. Diagnostic absent de la liste MU ; pas de résultat d’exécution. Un callback ou une classe CSS ne suffit pas à prouver la causalité. Aucun plugin désactivé pour expérimenter.

'''+s[end:]
replace('### F07 —', '**V.** Contrairement à une autre affirmation de S03, la liste actuelle présente un lien Désactiver pour Polylang for WooCommerce ; le plugin de base Polylang est requis par cette extension. S16 confirme aussi que le partage des slugs demande Pro. Aucun achat ni changement effectué.\n\n### F07 —')
replace('**NV :** formulaires 11/12, réception réelle, contenu de l\'accusé.', '**V :** formulaires11/12 actifs dans Fluent Forms, chacun affiche0 entrée. **NV :** définition complète, fonctionnement FR/EN, réception réelle et contenu de l’accusé. [S16]')
replace('## 7. Écarts', '''### F13 — Exploitation staging : configuration inachevée — P0/P1 F1

**V.** FluentSMTP affiche une demande de configuration ; Stripe est en test avec OAuth non connecté et compte vide dans l’état WC ; PayPal est déclaré intégré mais sans réception de webhook attestée par cet écran. Les huit formulaires sont actifs, sans recette des notifications. PDF Invoices5.16.1 est actif, configuration fiscale/numérotation et factures non vérifiées. **R.** Recetter sur environnement neutralisé achat FR/EN, webhooks, remboursement, facture et accusés ; aucune transaction ou émission réelle n’a été faite. [S16]

**V.** Quatorze plugins actifs, aucun inactif, six mises à jour proposées, auto-update désactivé pour les quatorze. Il s’agit d’un retard de versions observé, pas de six vulnérabilités démontrées. Wordfence signale l’intégration Login Security WooCommerce inactive. Prioriser compatibilité HPOS/blocs checkout et tests après sauvegarde, sans mise à jour en masse. [S16]

**V.** Action Scheduler compte sept échecs, tous `fetch_patterns` sans callback enregistré, du3 au9 septembre ; aucun incident de paiement ne s’en déduit. LSCache annonce ses fonctions de cache de pages indisponibles, tandis que le serveur public expose PowerBoost et l’état WC Apache. **R.** Clarifier les couches de cache et la responsabilité des jobs avant tuning ; ne pas purger/supprimer les traces pour masquer les symptômes. WP-Cron actif et698 tâches terminées ne démontrent pas une supervision opérationnelle. [S16]

## 7. Écarts''')
replace('faute d\'accès aux archives/serveurs et d\'environnement isolé identifié.', 'les ensembles Updraft étant seulement listés, sans archive vérifiée ni environnement isolé identifié.')
replace('inspection Chrome de la racine production.', 'inspection Chrome de la racine production et audit authentifié staging : versions/plugins/MU, état WC/HPOS, lecture WP, accueil FR/EN, Polylang, formulaires, sauvegardes/rétention et échecs Action Scheduler/cache. [S16]')
replace('inventaire authentifié staging/production, comparaison des fichiers PHP distants.', 'inventaire authentifié production, comparaison des fichiers PHP distants, audit intégral des réglages et extensions serveur.')
replace('Une demande d\'ouverture de session Chrome a été adressée à l\'utilisateur, sans demande de mot de passe.', 'La session Chrome ouverte par l’utilisateur a ensuite permis les lectures authentifiées S16, sans transfert de mot de passe.')
replace('Partielle ; divergence CSS publique prouvée','Partielle ; versions UI staging et divergences CSS/routage prouvées, fichiers/base non réconciliés')
replace('La prochaine étape nécessaire est l\'accès authentifié en lecture au staging et aux inventaires/sauvegardes serveur, puis la restauration isolée.', 'La prochaine preuve nécessaire est l’inventaire des fichiers/configurations serveur et l’intégrité des sauvegardes, puis une restauration isolée avec retour mesuré. L’accès administrateur staging a été obtenu et exploité dans cet audit.')
p.write_text(s,'utf-8')

p=out/'evidence/build_matrix.py'
s=p.read_text('utf-8')
s=s.replace('M54|App native / AR|Aucun projet natif dans le dépôt|KEEP|V S05 : absence de chantier local ; KEEP du périmètre Web, pas une certification d\'app', 'M54|App native / AR|Aucun projet natif dans le dépôt||V S05 : absence de chantier local ; option non engagée, aucune décision de reprise nécessaire')
proofs={
'M07':'V S06/S16 : WP7.1 annoncé prod et état WC staging ; PHP8.3.33 MariaDB11.4.13 staging ; checksums/config complets NV',
'M08':'V S16 : WC11.1.0, base11.1.0-1, HPOS actif ; blocs panier/checkout ; transactions NV',
'M09':'V S16/F05 : PLL3.8.7 et PLLWC2.2.4, racines FR/EN blog vide mais pages longues présentes ; cause NV',
'M10':'V S16 : SEOPress10.2, canonical accueil vers racine en désaccord de rendu ; sitemap exhaustif NV',
'M11':'V S16 : Kadence1.5.2 staging, classique ; aucun Kadence Blocks dans14 extensions ; licence NV',
'M12':'V S16/F02 : staging1.1.0 mais charte v3 non liée, dépôt la charge ; identité release non fiable',
'M16':'V F07/S16 : prototype liens # ; menu staging sept entrées, Accueil /fr/ différent du lien logo /fr/accueil/',
'M19':'V S16 : MU routage1.1.0 seul chargé ; code relu, variantes REST/query/cookies non recettées',
'M20':'V S16/F10 : absent de la liste MU staging ; correctif1.1.0 local, tests intégration NV',
'M21':'V S16 : diagnostic absent de la liste MU ; retrait recommandé du déploiement normal après tout usage futur, source conservée',
'M22':'V S16/F10 : plugin1.0.0 actif ; défauts potentiels mono-option/multi-attribut relevés ; panier NV',
'M24':'V S16 :34 produits publiés,1 brouillon,46 objets variation tous statuts ; rapprochement taxes/stock/langues NV',
'M25':'V S16 : Stripe10.9.1 test, OAuth non connecté, compte vide ; PayPal4.1.2 intégré déclaré, webhook non attesté ; transactions NV',
'M26':'V S16 : FluentSMTP2.3.1 affiche doit être configuré ; transport effectif et délivrabilité NV',
'M27':'V S16 : Fluent Forms6.2.13, huit formulaires5–12 actifs zéro entrée affichée ; définitions/notifications NV',
'M28':'V S16/F12 : formulaires11/12 actifs ; promesse CGV existante, accusé durable et parcours non recettés',
'M30':'V S16 : Complianz7.5.4 actif ; blocage réel/refus/retrait/traceurs NV',
'M31':'V S16 : neuf ensembles listés dont09/09 17h57, base daily7/fichiers weekly4, Drive déclaré connecté ; intégrité/restauration NV',
'M35':'V S16 : Wordfence9.0.1, intégration Login Security WC inactive,6 mises à jour proposées ; MFA/permissions NV',
'M36':'V S16 : LSCache page indisponible, pas de cache objet externe déclaré ;7 échecs fetch_patterns sans callback ; supervision NV',
'M37':'V S16 : page compte77 et traduction signalées, shortcode my_account ; accès et séparation données non recettés',
'M49':'V S16 : aucun plugin Plausible dans14 actives ; service/injection externe et événements NV',
}
lines=s.splitlines()
for i,line in enumerate(lines):
    ident=line.split('|')[0]
    if ident in proofs:
        parts=line.split('|'); parts[4]=proofs[ident]
        if ident in ('M09','M10','M25','M26','M31','M35','M36'): parts[3]='IMPROVE'
        lines[i]='|'.join(parts)
s='\n'.join(lines)+'\n'
marker="M56|Recette transverse"
idx=s.index("\n'''",s.index(marker))
s=s[:idx]+'''\nM57|Factures PDF et numérotation|PDF Invoices & Packing Slips||V S16 :5.16.1 actif ; paramétrage fiscal, numérotation, accès factures et émission NV|COM-01 ORD-01 PRV-02|P0/F1|0.5-2|Extension installée, contribution précise non attribuable|Factures et identité ; doublons, mentions ou accès erronés|Commandes test et validation paramètres/documents|Préserver numéros et factures émises ; retour plugin compatible sans régénération aveugle'''+s[idx:]
s=s.replace("'version':'0.1'","'version':'0.2'").replace('— v0.1','— v0.2').replace('**56 composants','**57 composants').replace('M01–M56','M01–M57').replace('S01–S15 et F01–F12','S01–S16 et F01–F13').replace('staging authentifié et sandbox requis','sandbox et destinataires de test requis')
s=s.replace("Production : WordPress 7.1 annoncé par generator ; staging NV", "Production : generator WP7.1 ; staging : état WC WP7.1")
s=s.replace("Production : Kadence1.5.0 dans URLs actifs ; staging NV", "Production : actifs Kadence1.5.0 ; staging : état WC Kadence1.5.2")
s=s.replace("Enfant production1.0.1 ; dépôt1.1.0 ; staging NV", "Enfant production1.0.1 ; dépôt et staging1.1.0 annoncés, chargements CSS différents")
s=s.replace('Lecture/inventaire local ou observation publique selon preuve ; tests fonctionnels serveur non exécutés','Lecture/inventaire local, HTTP public ou UI staging selon S16 ; tests transactionnels et restauration non exécutés')
p.write_text(s,'utf-8')
print('Rapport et générateur mis à jour ; exécuter build_matrix.py puis validation.')
