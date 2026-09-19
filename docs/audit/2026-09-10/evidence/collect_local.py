"""Relevé local non destructif ; écrit uniquement ses propres preuves d'audit."""
import collections, csv, datetime, difflib, hashlib, json, re, subprocess
from pathlib import Path
ROOT=Path(__file__).resolve().parents[4]
OUT=Path(__file__).resolve().parent
def git(*args):
    r=subprocess.run(['git',*args],cwd=ROOT,capture_output=True,encoding='utf-8',errors='replace')
    return {'command':['git',*args],'exit_code':r.returncode,'stdout':r.stdout,'stderr':r.stderr}
def save(name,data):
    (OUT/name).write_text(json.dumps(data,ensure_ascii=False,indent=2),encoding='utf-8')
tracked=git('ls-files','-z')['stdout'].strip('\0').split('\0')
inventory=[]
for name in tracked:
    p=ROOT/name
    if p.is_file(): inventory.append({'path':name,'bytes':p.stat().st_size,'sha256':hashlib.sha256(p.read_bytes()).hexdigest()})
save('tracked-inventory.json',inventory)
save('git-baseline.json',{'utc':datetime.datetime.now(datetime.timezone.utc).isoformat(),'cwd':str(ROOT),'commands':[git(*args) for args in [('rev-parse','HEAD'),('status','--porcelain=v2','--untracked-files=all'),('diff','--stat'),('diff','--cached','--stat'),('branch','-avv'),('worktree','list','--porcelain'),('log','--all','--date=iso','--format=%h %ad %s'),('log','--all','--format=%h %s','--name-status','--','wp','brand','catalog')]]})
ignored=git('ls-files','--others','--ignored','--exclude-standard','-z')['stdout'].strip('\0').split('\0')
save('ignored-inventory.json',[{'path':s,'bytes':(ROOT/s).stat().st_size} for s in ignored if s and (ROOT/s).is_file()])
counts=collections.Counter(p['path'].split('/')[0] for p in inventory)
code=[p['path'] for p in inventory if p['path'].startswith('wp/') and p['path'].endswith('.php')]
hooks={f:[{'line':i,'text':l.strip()} for i,l in enumerate((ROOT/f).read_text('utf-8').splitlines(),1) if re.search(r'add_(?:action|filter)\s*\(|register_(?:rest_route|post_type|taxonomy|block_pattern)|wp_schedule',l)] for f in code}
save('hooks.json',hooks)
secret_patterns={
    'private-key':r'-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----',
    'stripe-key':r'\b[sr]k_(?:live|test)_[A-Za-z0-9]{16,}',
    'github-token':r'\bgh[pousr]_[A-Za-z0-9]{30,}',
    'aws-key':r'\bAKIA[A-Z0-9]{16}\b',
    'brevo-key':r'\bxkeysib-[A-Za-z0-9-]{25,}',
}
hits=[]; scanned=[]
for entry in inventory:
    p=ROOT/entry['path']
    if p.suffix.lower() not in ('.php','.js','.py','.css','.json','.csv','.md','.html','.txt','.sh','.ps1'):
        continue
    scanned.append(entry['path'])
    for line,text in enumerate(p.read_text('utf-8',errors='replace').splitlines(),1):
        for kind,pattern in secret_patterns.items():
            if re.search(pattern,text): hits.append({'path':entry['path'],'line':line,'type':kind})
save('secret-pattern-scan.json',{'scope':'Current tracked text files only; no binary, ignored files or historical blob scan. No secret values emitted. Not an exhaustive secret scanner.','scanned_count':len(scanned),'patterns':list(secret_patterns),'findings':hits})
csvs={}
for f in [x['path'] for x in inventory if x['path'].endswith('.csv') and (x['path'].startswith('catalog/') or x['path'].startswith('docs/lot2/'))]:
    with (ROOT/f).open(encoding='utf-8-sig',newline='') as stream:
        reader=csv.DictReader(stream); rows=list(reader); fields=reader.fieldnames
    csvs[f]={'rows':len(rows),'fields':fields,'ragged_rows':[i+2 for i,r in enumerate(rows) if None in r]}
    if f=='catalog/master.csv':
        csvs[f]['collections']=dict(collections.Counter(r.get('collection','') for r in rows))
        csvs[f]['types']=dict(collections.Counter(r.get('product_type','') for r in rows))
        csvs[f]['export_flags']=dict(collections.Counter(r.get('export_to_wc','') for r in rows))
        sku=collections.Counter(r.get('sku') for r in rows)
        csvs[f]['duplicate_skus']=[k for k,v in sku.items() if v>1]
        csvs[f]['image_path_base']='catalog/ (relative to master.csv)'
        csvs[f]['missing_image_paths']=[{'line':i+2,'sku':r.get('sku'),'path':r.get('main_image_path')} for i,r in enumerate(rows) if r.get('main_image_path') and not (ROOT/'catalog'/r['main_image_path']).is_file()]
        csvs[f]['empty_fields']={k:sum(not r.get(k,'').strip() for r in rows) for k in fields if k in ('main_image_path','weight_kg','weight_g','model_name_en','short_desc_en','long_desc_en','design_id','family')}
        csvs[f]['export_missing_required']=[{'line':i+2,'sku':r.get('sku'),'missing':[k for k in ('product_type','sku','model_name_fr','short_desc_fr','long_desc_fr','main_image_path','status') if not r.get(k)]} for i,r in enumerate(rows) if r.get('export_to_wc')=='yes' and any(not r.get(k) for k in ('product_type','sku','model_name_fr','short_desc_fr','long_desc_fr','main_image_path','status'))]
save('catalog-checks.json',csvs)
def lum(h):
    c=[int(h[i:i+2],16)/255 for i in (1,3,5)]
    c=[v/12.92 if v<=.04045 else ((v+.055)/1.055)**2.4 for v in c]
    return sum(a*b for a,b in zip(c,(.2126,.7152,.0722)))
contrast={}
for bg in ['#F8F4EE','#2B3A6B','#FFFDFC','#E8E4DC']:
    a,b=sorted([lum('#B8860B'),lum(bg)])
    contrast[bg]=round((b+.05)/(a+.05),4)
css=ROOT/'wp/themes/koinobori-child/assets/css/kh-charte-v3.css'
ref=ROOT/'docs/charte-graphique/reference-v3/styles.css'
norm=lambda s:re.sub(r'url\([^)]*\)','url(ASSET)',s)
missing_assets=[]
for f in [x['path'] for x in inventory if x['path'].endswith('.css') and x['path'].startswith('wp/')]:
    for link in re.findall(r'url\([\"\']?([^\)\"\']+)',(ROOT/f).read_text('utf-8')):
        if not link.startswith(('data:','http','/')) and not (ROOT/f).parent.joinpath(link).exists(): missing_assets.append({'file':f,'url':link})
passage=Path(r'C:\Users\herbi\Downloads\PASSATIONCHATGPT20260910.md').read_text('utf-8-sig')
rep=(ROOT/'docs/handoff/PASSATION-CHATGPT-2026-09-10.md').read_text('utf-8-sig')
reference=(OUT/'referentiel-extrait.txt').read_text('utf-8')
requirements=sorted(set(re.findall(r'\b[A-Z]{2,4}-\d{2}\b',reference)))
save('local-checks.json',{'utc':datetime.datetime.now(datetime.timezone.utc).isoformat(),'tracked_count':len(inventory),'by_root':dict(counts),'php_files':code,'font_files':len([x for x in inventory if x['path'].endswith('.woff2')]),'font_bytes':sum(x['bytes'] for x in inventory if x['path'].endswith('.woff2')),'theme_images':[x for x in inventory if '/assets/images/' in x['path']],'gold_contrast':contrast,'css_equal_ignoring_url':norm(css.read_text('utf-8'))==norm(ref.read_text('utf-8')),'missing_css_assets':missing_assets,'passation_equal_normalized':passage.strip()==rep.strip(),'requirements':requirements,'theme_palette_slugs':[x['slug'] for x in json.loads((ROOT/'wp/themes/koinobori-child/theme.json').read_text('utf-8'))['settings']['color']['palette']]})
(OUT/'passation-diff.txt').write_text('\n'.join(difflib.unified_diff(rep.splitlines(),passage.splitlines(),fromfile='repo',tofile='attachment')),encoding='utf-8')
print(json.dumps({'tracked_count':len(inventory),'roots':dict(counts),'php_files':code,'gold_contrast':contrast,'requirements':requirements,'catalog':csvs['catalog/master.csv'],'passation_equal_normalized':passage.strip()==rep.strip()},ensure_ascii=False,indent=2))
