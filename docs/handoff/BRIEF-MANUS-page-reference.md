# Brief à transmettre à Manus — une page de référence en code

Texte à copier tel quel. Il est écrit pour être lu par Manus sans contexte supplémentaire.

---

## Demande

Bonjour,

La charte v2.0 « Ma, L'Intervalle enchanté » est adoptée et va être implémentée sur WordPress. Pour que le résultat soit fidèle à votre intention, j'ai besoin d'**une seule chose** de votre part.

**Une page de référence, livrée en HTML et CSS.** La page d'accueil, footer compris.

Pas de slides supplémentaires. Une page de code.

---

## Pourquoi ce format, et pas des maquettes

La spécification v2.0 que vous avez livrée contient, après comptage : **1 bloc CSS, 6 valeurs hexadécimales et 11 valeurs numériques en px ou rem**, réparties sur 32 sections. Le reste est de la direction en prose et 20 images de référence.

La direction artistique est limpide. Mais **l'échelle typographique et le rythme d'espacement n'y figurent nulle part.** Quiconque implémente doit donc les inventer ou les mesurer à l'œil sur des PNG — et c'est exactement là que votre univers visuel se perd.

Ces nombres sont votre signature. Personne ne peut les deviner à votre place.

Une page en code les contient tous, écrits noir sur blanc. À partir d'elle, le système entier se déduit et s'applique à toutes les autres pages.

---

## Ce que la page doit exposer

L'essentiel n'est pas qu'elle soit complète, mais qu'elle **révèle le système**.

1. **L'échelle typographique**, déclarée en variables CSS et non en valeurs dispersées : tailles, graisses, interlignages, interlettrages, pour chaque niveau — hero, titres de section, chapô, corps, légende, interface.
2. **Le rythme d'espacement** : l'échelle de base et les respirations entre mouvements. C'est le « Ma » de la charte, et c'est ce qui ne se devine pas.
3. **Les 11 mouvements** de la page d'accueil, au moins dans leur structure et leurs proportions.
4. **Le footer « ligne d'horizon »** : 72 à 96 px, une seule ligne sur desktop, trois zones.
5. **Une carte produit**, avec ses proportions et ses rembourrages.
6. **Les points de rupture** : le comportement à 1280, 768 et 360 px.

---

## Ce qui existe déjà et qu'il faut réutiliser tel quel

Le thème enfant contient déjà les fondations. **Ne les recréez pas, appuyez-vous dessus.**

Les 4 familles sont auto-hébergées en woff2, sous-ensembles latin et latin-ext, unicode-ranges corrects :
Cormorant Garamond 300/400/600 normal et italique · Lora 400 normal et italique · DM Sans 300/400 · Noto Serif JP variable · Shippori Mincho 400/500.

Les jetons existent sous ces noms exacts, à conserver :

```css
--kh-font-display   /* Cormorant Garamond */
--kh-font-body      /* Lora */
--kh-font-ui        /* DM Sans */
--kh-font-kanji     /* Noto Serif JP */
--kh-washi  --kh-sumi  --kh-vermillon  --kh-or  --kh-indigo
```

⚠️ Trois valeurs déployées sont encore celles de la v1 et doivent passer en v2.0 dans votre livraison :
`--kh-washi` `#F7F3EC` → **`#F8F4EE`** · `--kh-or` `#C9A96E` → **`#B8860B`** · `--kh-indigo` `#1B2B5E` → **`#2B3A6B`**.
Ajouter `--kh-white` **`#FFFDFC`** pour les cartes. `--kh-sumi` `#1A1410` et `--kh-vermillon` `#C8311A` sont déjà conformes.

---

## Contraintes techniques

- **HTML et CSS purs.** Aucun framework, aucune étape de compilation, aucun appel à un CDN externe.
- Les polices sont **déjà auto-hébergées** : référencez-les par leur nom de famille, ne rechargez pas depuis Google Fonts.
- Le site tourne sur **WordPress avec le thème Kadence**. Votre CSS devra cohabiter avec le sien. Écrivez des sélecteurs sur vos propres classes préfixées `kh-` plutôt que sur des balises nues — la reprise de la cascade est mon travail, pas le vôtre, mais des classes propres me la rendent possible.
- **Une page HTML autonome + une feuille CSS.** Si vous préférez un seul fichier avec le CSS dans un `<style>`, cela convient aussi.

---

## Décisions verrouillées, à ne pas rouvrir

Elles ont été arbitrées par Alain et certaines l'ont été récemment.

- **Navigation à 7 entrées** : Accueil · Boutique · Arts de vivre · Lifestyle · L'Atelier · Professionnels · Contact. Pas de mega-panel, pas de label « Collections », pas d'entrée Kaïro.
- **Footer en 2 étages** : une bande services en groupes courts (Navigation · Informations · Professionnels) **au-dessus** de la ligne d'horizon stricte. La newsletter reste au mouvement 10, jamais dans le footer.
- **Boutons à angles droits**, fond vermillon.
- **L'or est réservé aux détails précieux et aux citations.** Jamais pour un bouton, un panier, un lien ou un aplat massif.
- **Le vermillon reste rare** : il porte l'action prioritaire, jamais la décoration.
- **Header Shoji V2 verrouillé.** Il est déjà réalisé et n'est pas rouvert. Un seul changement : « Mon compte » passe du corail `#E05A5A` au vermillon `#C8311A`.
- Le 2ᵉ mouvement éditorial de la fiche produit s'intitule **« Détails et matières »**.

---

## Doctrine éditoriale, impérative

Si vous écrivez du texte d'exemple dans la page :

- ❌ Jamais de mention d'atelier, de production, de fabrication ou d'origine.
- ❌ Jamais de tiret cadratin dans un contenu produit. La signature s'écrit `- by BCDG`, avec un trait d'union simple.
- ❌ Jamais « bestsellers ».
- ✅ Utilisez de vrais textes du catalogue plutôt que du faux-texte, ils sont plus révélateurs des longueurs réelles.

---

## Critères d'acceptation

Ceux de votre propre §14, plus l'accessibilité de votre §13 : navigation au clavier, focus visibles, `prefers-reduced-motion` respecté, lisible à 360 px.

---

## Ce dont je n'ai pas besoin

Pour ne pas vous faire travailler pour rien :

- Pas de nouvelles slides ni de nouvelles maquettes.
- Pas de JavaScript, sauf si une interaction est indissociable du design.
- Pas de pages secondaires. **La page d'accueil suffit** — les autres se déduisent du système qu'elle expose.
- Pas de nouvelles images : le pack de 103 fichiers livré le 25/07 est complet et en place.

---

Merci.
