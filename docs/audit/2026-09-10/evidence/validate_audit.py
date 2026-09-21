"""Validation du dossier documentaire et de la préservation des fichiers initiaux."""
from pathlib import Path
from datetime import datetime, timezone
import hashlib,json,re,subprocess
out=Path(__file__).resolve().parent.parent
root=out.parents[2]
inv=json.loads((out/'evidence/tracked-inventory.json').read_text('utf-8'))
changed=[]
for item in inv:
    path=root/item['path']
    if not path.exists() or hashlib.sha256(path.read_bytes()).hexdigest()!=item['sha256']:
        changed.append(item['path'])
matrix=json.loads((out/'MATRICE-REPRISE.json').read_text('utf-8'))['composants']
req=json.loads((out/'evidence/local-checks.json').read_text('utf-8'))['requirements']
unmapped=[q for q in req if not any(q in r['exigences'] for r in matrix)]
broken=[]
for doc in [out/'RAPPORT-AUDIT-KH2027.md',out/'MATRICE-REPRISE.md',out/'COUVERTURE-EXIGENCES.md',out/'RESTAURATION-G0.md',out/'DECISIONS-JALON.md',out/'PILOTAGE-REPRISE.md']:
    for link in re.findall(r'\]\(([^)]+)\)',doc.read_text('utf-8')):
        if link.startswith(('https://','http://','#')):continue
        if not (doc.parent/link.split('#')[0]).exists():broken.append([doc.name,link])
head=subprocess.run(['git','rev-parse','HEAD'],cwd=root,capture_output=True,text=True).stdout.strip()
status=subprocess.run(['git','status','--short','--untracked-files=normal'],cwd=root,capture_output=True,text=True).stdout.strip()
diff=subprocess.run(['git','diff','--exit-code','HEAD'],cwd=root,capture_output=True,text=True)
result={'at_utc':datetime.now(timezone.utc).isoformat(),'baseline_files':len(inv),'changed_baseline_files':changed,'head':head,'git_status':status,'tracked_diff_empty':diff.returncode==0,'components':len(matrix),'requirements':len(req),'unmapped_requirements':unmapped,'broken_document_links':broken,'server_checks':'UI and server read-only inventory, SELECT aggregates, normalized hashes, PHP lint and core checksums; no transaction, restoration or deliberate application/configuration mutation'}
(out/'evidence/final-validation.json').write_text(json.dumps(result,ensure_ascii=False,indent=2),'utf-8')
print(json.dumps(result,ensure_ascii=False,indent=2))
assert not changed and not unmapped and not broken and diff.returncode==0
