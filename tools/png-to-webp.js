// PNG -> WebP (récursif). Usage : node tools/png-to-webp.js <dossier> [qualité 1-100] [--delete]
// Ex : node tools/png-to-webp.js koinobori-house-images 82
// Convertit chaque .png en .webp (même chemin). Garde le .png sauf si --delete.
const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const root = process.argv[2] || 'koinobori-house-images';
const quality = parseInt(process.argv[3] || '82', 10);
const del = process.argv.includes('--delete');

if (!fs.existsSync(root)) { console.error('Dossier introuvable: ' + root); process.exit(1); }

const pngs = [];
(function walk(d) {
  for (const e of fs.readdirSync(d, { withFileTypes: true })) {
    const p = path.join(d, e.name);
    if (e.isDirectory()) walk(p);
    else if (/\.png$/i.test(e.name)) pngs.push(p);
  }
})(root);

(async () => {
  let totIn = 0, totOut = 0, ok = 0;
  for (const png of pngs) {
    const webp = png.replace(/\.png$/i, '.webp');
    const inSz = fs.statSync(png).size;
    try {
      await sharp(png).webp({ quality }).toFile(webp);
      const outSz = fs.statSync(webp).size;
      totIn += inSz; totOut += outSz; ok++;
      console.log(`OK  ${path.relative(process.cwd(), webp)}  ${(inSz/1024).toFixed(0)} Ko -> ${(outSz/1024).toFixed(0)} Ko (-${Math.round(100 - outSz/inSz*100)}%)`);
      if (del) fs.unlinkSync(png);
    } catch (e) {
      console.log(`ERR ${png} : ${e.message}`);
    }
  }
  console.log(`\n${ok}/${pngs.length} converti(s) | total ${(totIn/1024).toFixed(0)} Ko -> ${(totOut/1024).toFixed(0)} Ko${del ? ' | PNG supprimés' : ''}`);
})();
