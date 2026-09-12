from pathlib import Path
p=Path(__file__).with_name('build_matrix.py')
s=p.read_text('utf-8')
s=s.replace("'version':'0.2'", "'version':'0.3'").replace('— v0.2','— v0.3').replace('S01–S16 et F01–F13','S01–S17 et F01–F14')
lines=s.splitlines()
extra={'M08':' ; V S17 : panier anglais vide partiellement français', 'M09':' ; V S17 : trois paires légales/USA présentes sans liaison affichée', 'M28':' ; V S17 : formulaires rendus FR/EN avec confirmation, aucun envoi', 'M29':' ; V S17 : liaisons mentions et cookies manquantes'}
for i,line in enumerate(lines):
    ident=line.split('|')[0]
    if ident in extra:
        parts=line.split('|');parts[4]+=extra[ident];lines[i]='|'.join(parts)
p.write_text('\n'.join(lines)+'\n','utf-8')
