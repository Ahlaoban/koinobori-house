from pathlib import Path
out=Path(__file__).resolve().parent.parent
p=out/'RAPPORT-AUDIT-KH2027.md'
s=p.read_text('utf-8').replace('version 0.3','version 0.4')
s=s.replace('IMPROVE 23','IMPROVE 24').replace('23 décisions non attribuées','22 décisions non attribuées')
anchor='| S17 | [Complément staging](evidence/staging-complement.md) | Liaisons Polylang, formulaires publics FR/EN et panier anglais |'
assert anchor in s
s=s.replace(anchor,anchor+'\n| S18 | [Réglages commerce](evidence/staging-commerce.md) | Taxes, zones/tarifs de livraison, comptes, conservation et états des paiements |')
anchor='## 7. Écarts de cible 2026 / KH2027'
section='''### F15 — Livraison internationale annoncée mais non configurée ; conservation à réconcilier — P0/P1 avant ouverture

**V.** Les réglages généraux permettent tous les pays, mais seule la zone France possède des méthodes : forfait5 EUR et gratuité dès55 EUR. La zone Reste du monde n’en a aucune. La page Livraison annonce pourtant un calcul au panier pour UE et DROM-COM. USA, Royaume-Uni et Suisse sont annoncés sur demande : l’absence de méthode ne contredit pas cette procédure manuelle. **R.** Réconcilier les destinations réellement offertes et les grilles de transport validées avant recette ; ne pas inventer de tarifs ni restreindre silencieusement le périmètre international. **NV.** Checkout par adresse, poids, remises et mode relais. [S18]

**V.** Achat invité et inscriptions activés. Tous les champs de durée de conservation WC inspectés sont vides, dont comptes inactifs, tandis que la politique KH prévoit3 ans d’inactivité. **NV.** Procédure humaine ou mécanisme extérieur à cet écran. **R.** Vérifier le traitement effectif et ses exceptions avant une purge ; aucune suppression de compte/commande autorisée par ce constat. [S18]

**V, complément F13.** PayPal affiche Compte de test et Stripe Action requise / Terminer la configuration. Le calcul des taxes est désactivé ; ce paramètre correspond au régime déclaré dans les documents sans en certifier la validité juridique. Aucune transaction ni modification fiscale effectuée. [S18]

'''
assert anchor in s
s=s.replace(anchor,section+anchor)
p.write_text(s,'utf-8')
p=out/'evidence/build_matrix.py';s=p.read_text('utf-8')
lines=s.splitlines()
proofs={
'M24':'V S16/S18 :34 produits publiés ; une zone France forfait5/gratuit55, aucune méthode reste du monde malgré promesse UE/DROM ; taxes désactivées ; checkout NV',
'M25':'V S16/S18 : Stripe10.9.1 test et configuration requise ; PayPal4.1.2 Compte de test ; transactions/webhooks NV',
'M37':'V S18 : invité et inscriptions checkout/compte actifs ; récupération, identité et cloisonnement non recettés',
'M50':'V S11/S18 : politique comptes3 ans ; champs de conservation WC vides ; procédure hors écran et purge effective NV'}
for i,line in enumerate(lines):
    ident=line.split('|')[0]
    if ident in proofs:
        parts=line.split('|');parts[4]=proofs[ident]
        if ident=='M24':parts[3]='IMPROVE'
        lines[i]='|'.join(parts)
s='\n'.join(lines)+'\n'
s=s.replace("'version':'0.3'","'version':'0.4'").replace('— v0.3','— v0.4').replace('S01–S17 et F01–F14','S01–S18 et F01–F15')
p.write_text(s,'utf-8')
p=out/'PILOTAGE-REPRISE.md';s=p.read_text('utf-8').replace('Rapport v0.3','Rapport v0.4').replace('23 décisions encore réservées','22 décisions encore réservées').replace('faits S16/S17','faits S16–S18');p.write_text(s,'utf-8')
print('Constats S18 intégrés ; régénérer matrice puis validation.')
