"""Audit passif : GET publics, sans authentification, mutation ni transaction."""
import datetime, hashlib, json, re, time, urllib.request, urllib.error
from pathlib import Path

OUT = Path(__file__).resolve().parent
URLS = [
    ('prod-root-fr','https://koinoborihouse.com/','fr-FR,fr;q=0.9,en;q=0.5'),
    ('prod-root-en','https://koinoborihouse.com/','en-US,en;q=0.9'),
    ('prod-fr','https://koinoborihouse.com/fr/','fr'),
    ('prod-en','https://koinoborihouse.com/en/','en'),
    ('prod-robots','https://koinoborihouse.com/robots.txt','en'),
    ('prod-rest','https://koinoborihouse.com/wp-json/','en'),
    ('prod-pages','https://koinoborihouse.com/wp-json/wp/v2/pages?per_page=1&_fields=id,slug,status','en'),
    ('prod-theme-style','https://koinoborihouse.com/wp-content/themes/koinobori-child/style.css','en'),
    ('prod-theme-foundations','https://koinoborihouse.com/wp-content/themes/koinobori-child/assets/css/kh-foundations.css','en'),
    ('prod-theme-charte','https://koinoborihouse.com/wp-content/themes/koinobori-child/assets/css/kh-charte-v3.css','en'),
    ('staging-root','https://staging.koinoborihouse.com/','fr'),
    ('staging-fr','https://staging.koinoborihouse.com/fr/','fr'),
    ('staging-en','https://staging.koinoborihouse.com/en/','en'),
]
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None
opener = urllib.request.build_opener(NoRedirect)
results=[]
for key,url,lang in URLS:
    entry={'id':key,'url':url,'method':'GET','accept_language':lang,'utc':datetime.datetime.now(datetime.timezone.utc).isoformat()}
    start=time.perf_counter()
    try:
        req=urllib.request.Request(url,headers={'User-Agent':'KH2027-REF read-only audit','Accept-Language':lang})
        try: response=opener.open(req,timeout=20)
        except urllib.error.HTTPError as error: response=error
        data=response.read(2_000_000)
        entry.update(status=response.code,elapsed_seconds=round(time.perf_counter()-start,3),bytes=len(data),sha256=hashlib.sha256(data).hexdigest())
        entry['headers']={k:v for k,v in response.headers.items() if k.lower() not in ('set-cookie',)}
        entry['sets_cookie']=bool(response.headers.get('Set-Cookie'))
        body=data.decode('utf-8',errors='replace')
        entry['title']=re.findall(r'<title[^>]*>(.*?)</title>',body,re.S)
        entry['generator']=re.findall(r'<meta[^>]*name=[\"\']generator[\"\'][^>]*>',body)
        entry['body_class']=re.findall(r'<body[^>]*class=[\"\']([^\"\']*)',body)
        entry['assets']=sorted(set(re.findall(r'(?:href|src)=[\"\']([^\"\']+\.(?:css|js)(?:\?[^\"\']*)?)',body)))
        entry['seo_tags']=re.findall(r'<(?:meta|link)\b[^>]*(?:canonical|hreflang|robots)[^>]*>',body)
        if key=='prod-rest':
            parsed=json.loads(body)
            entry['namespaces']=parsed.get('namespaces',[])
            entry['route_count']=len(parsed.get('routes',{}))
        # Only public content. No authenticated records or cookie values are saved.
        (OUT/(key+'.txt')).write_text(body,encoding='utf-8')
    except Exception as error: entry['error']=str(error)
    results.append(entry)
(OUT/'http-public.json').write_text(json.dumps(results,ensure_ascii=False,indent=2),encoding='utf-8')
print(json.dumps(results,ensure_ascii=False,indent=2))
