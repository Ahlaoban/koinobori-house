#!/usr/bin/env python3
# PR1 fonts : Latin via Google CSS2 (latin+latin-ext), JP via sous-set fontTools.
import os, re, sys, urllib.request, subprocess

UA = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36"
OUT = r"C:\dev\Koinobori\wp\themes\koinobori-child\assets\fonts"
os.makedirs(OUT, exist_ok=True)

def get(url, ua=False):
    req = urllib.request.Request(url, headers={"User-Agent": UA} if ua else {})
    return urllib.request.urlopen(req, timeout=30).read()

LATIN = {
    "Cormorant Garamond": ("cormorant-garamond", "family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400;1,600"),
    "Lora": ("lora", "family=Lora:ital,wght@0,400;1,400"),
    "DM Sans": ("dm-sans", "family=DM+Sans:wght@300;400"),
}
KEEP = {"latin", "latin-ext"}
faces = []

# ---- Latin families ----
for fam, (slug, q) in LATIN.items():
    css = get(f"https://fonts.googleapis.com/css2?{q}&display=swap", ua=True).decode("utf-8")
    blocks = re.split(r"/\*\s*([\w-]+)\s*\*/", css)
    # blocks = ['', subset1, cssblock1, subset2, cssblock2, ...]
    for i in range(1, len(blocks), 2):
        subset = blocks[i].strip()
        body = blocks[i+1]
        if subset not in KEEP:
            continue
        style = re.search(r"font-style:\s*(\w+)", body).group(1)
        weight = re.search(r"font-weight:\s*(\d+)", body).group(1)
        urange = re.search(r"unicode-range:\s*([^;]+);", body).group(1).strip()
        url = re.search(r"url\((https://[^)]+\.woff2)\)", body).group(1)
        fn = f"{slug}-{weight}-{style}-{subset}.woff2"
        open(os.path.join(OUT, fn), "wb").write(get(url))
        faces.append(f'@font-face{{font-family:"{fam}";font-style:{style};font-weight:{weight};font-display:swap;src:url("../fonts/{fn}") format("woff2");unicode-range:{urange};}}')
        print("LATIN", fn)

# ---- JP families (sous-set) ----
JP_TEXT = "海花風夢間回廊見文様地域五つの世界鯉のぼり美風物詩"
JP_UNICODES = "U+3000-303F,U+3040-309F,U+30A0-30FF,U+FF00-FFEF,U+0020-007E"
def subset(src_url, out_fn, fam, weight, urange):
    src = os.path.join(OUT, "_src_" + out_fn.replace(".woff2", ".ttf"))
    open(src, "wb").write(get(src_url))
    cmd = [sys.executable, "-m", "fontTools.subset", src,
           f"--text={JP_TEXT}", f"--unicodes={JP_UNICODES}",
           "--flavor=woff2", f"--output-file={os.path.join(OUT, out_fn)}",
           "--no-hinting", "--desubroutinize", "--name-IDs=", "--notdef-outline"]
    subprocess.run(cmd, check=True)
    os.remove(src)
    sz = os.path.getsize(os.path.join(OUT, out_fn))
    faces.append(f'@font-face{{font-family:"{fam}";font-style:normal;font-weight:{weight};font-display:swap;src:url("../fonts/{out_fn}") format("woff2");unicode-range:{urange};}}')
    print("JP", out_fn, f"{sz//1024}Ko")

JP_RANGE = "U+3000-303F,U+3040-309F,U+30A0-30FF,U+4E00-9FFF,U+FF00-FFEF"
subset("https://raw.githubusercontent.com/google/fonts/main/ofl/shipporimincho/ShipporiMincho-Regular.ttf",
       "shippori-mincho-400-jp.woff2", "Shippori Mincho", "400", JP_RANGE)
subset("https://raw.githubusercontent.com/google/fonts/main/ofl/shipporimincho/ShipporiMincho-Medium.ttf",
       "shippori-mincho-500-jp.woff2", "Shippori Mincho", "500", JP_RANGE)
# Noto Serif JP variable -> un seul woff2, plage de poids 400..500
subset("https://raw.githubusercontent.com/google/fonts/main/ofl/notoserifjp/NotoSerifJP%5Bwght%5D.ttf",
       "noto-serif-jp-var-jp.woff2", "Noto Serif JP", "400 500", JP_RANGE)

open(os.path.join(os.path.dirname(__file__), "fontface.css"), "w", encoding="utf-8").write("\n".join(faces) + "\n")
print(f"\nDONE {len(faces)} @font-face -> fontface.css | woff2 in {OUT}")
