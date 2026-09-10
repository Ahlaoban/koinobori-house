# Brief à transmettre à Manus — contraste de l'or v2.0

- **Date** : 2026-09-09
- **Objet** : une seule question, sur un seul jeton de la palette
- **Ne rouvre pas** : la charte v2.0, le header Shoji V2, la navigation, la structure des 11 mouvements, le footer ligne d'horizon. Rien de tout cela n'est en cause.

Texte à copier tel quel. Il est écrit pour être lu par Manus sans contexte supplémentaire.

---

## Le contexte

Bonjour,

La référence v3.0 que vous avez livrée est intégrée dans le thème enfant, et elle est mergée. La revue de code passée sur cette intégration a validé l'essentiel : boutons à angles droits, or employé deux fois seulement et en détail, jamais sur un bouton ni un lien, navigation à sept entrées, focus visibles, `prefers-reduced-motion` complet, ordre des mouvements conforme. Le système tient.

Un point demande votre arbitrage, et il porte sur la palette elle-même, pas sur son application.

## Ce que la mesure donne

L'or de la charte v2.0 §5.1, `--kh-gold` `#B8860B`, mesuré contre les cinq fonds de la palette :

| Or `#B8860B` sur | Rapport | Seuil AA texte courant | Seuil AA grand texte et non-texte |
|---|---:|:---:|:---:|
| Washi `#F8F4EE` | **2,97:1** | ✗ | ✗ |
| Blanc `#FFFDFC` | 3,21:1 | ✗ | ✓ |
| Brume `#E8E4DC` | **2,57:1** | ✗ | ✗ |
| Indigo `#2B3A6B` | **3,36:1** | ✗ | ✓ |
| Sumi `#1A1410` | 5,60:1 | ✓ | ✓ |

Rappel des seuils WCAG AA : 4,5:1 pour du texte courant, 3:1 pour du grand texte et pour les éléments non textuels.

Deux remarques factuelles, sans reproche.

D'abord, **la migration v1 vers v2.0 a dégradé une paire**. Sur le fond indigo de la section Arts de vivre, l'exergue `.kh-eyebrow--light` emploie l'or à 12 px. Avec les valeurs v1 (`#C9A96E` sur `#1B2B5E`) cette paire mesurait **6,04:1**. Avec les valeurs v2.0 elle mesure **3,36:1**. Éclaircir l'indigo et foncer l'or au même moment a fait basculer le couple sous le seuil.

Ensuite, **le réglage WordPress verrouille la sortie de secours**. La palette est fermée : `custom: false` et `disableCustomColors` retirent le champ hexadécimal de l'éditeur. Un rédacteur qui choisit « Or » pour une citation n'a aucun moyen de corriger le rendu.

## Le point dur, qui n'est pas un réglage mais une contrainte

En cherchant quelle valeur d'or corrigerait le problème, on tombe sur une impossibilité arithmétique.

Pour atteindre 4,5:1 sur washi, un or doit avoir une luminance relative **inférieure ou égale à 0,163**. Pour atteindre 4,5:1 sur indigo, il doit avoir une luminance **supérieure ou égale à 0,382**.

Les deux fenêtres sont disjointes. **Aucune couleur unique ne peut porter du texte courant à la fois sur washi et sur indigo.** Ce n'est pas une question de teinte : c'est vrai de n'importe quelle couleur, or ou non.

En revanche, au seuil de 3:1, une fenêtre existe : luminance entre **0,238 et 0,269**. L'or actuel est à **0,273**, c'est-à-dire à peine au-dessus du plafond. Un assombrissement très léger le ferait entrer dans la fenêtre. À titre d'illustration, `#AD800B` donne 3,27:1 sur washi, 3,06:1 sur indigo et 5,10:1 sur sumi. C'est un repère de calcul, pas une proposition de teinte : la valeur exacte vous appartient.

## La question

La charte v2.0 §5.1 assigne à l'or les « **détails précieux et citations** ». Une citation est du texte courant, donc soumise au seuil de 4,5:1, que l'or ne peut pas atteindre sur washi tout en restant lisible sur indigo.

**Comment voulez-vous résoudre cette tension ?** Quatre directions nous semblent ouvertes, et le choix est le vôtre :

1. **L'or cesse de porter du texte.** Il devient un signe purement graphique : filets, traits, sceau, séparateurs, encadrements. Le seuil de 3:1 suffit alors, et un léger assombrissement le met en règle sur tous les fonds. Les citations passent en sumi, distinguées par la typographie plutôt que par la couleur — italique Cormorant, filet, retrait.
2. **L'or ne porte du texte que sur sumi**, où il mesure déjà 5,60:1. Il reste doré et lisible, mais uniquement dans les zones sombres.
3. **Deux ors**, un pour les fonds clairs et un pour les fonds sombres. C'est la solution qui préserve le mieux l'intention visuelle, mais elle rompt le principe d'un jeton unique et ajoute une entrée à la palette.
4. **Vous assumez le rapport actuel** comme un choix esthétique, et nous l'inscrivons comme une dérogation documentée aux critères d'accessibilité de votre propre §13. Nous ne le recommandons pas, mais c'est une réponse possible et elle a le mérite d'être explicite.

Si vous retenez la direction 1 ou la direction 2, il nous faut de vous **la valeur hexadécimale exacte** de l'or retenu, pour que nous ne l'inventions pas à votre place.

## Deux détails à confirmer dans le même mouvement

Ils ont été corrigés dans la référence, et nous préférons vous le dire plutôt que de les laisser passer en silence.

- **Le cinquième monde.** Le mouvement 03 de `index.html` listait Mer, Kaïro, Hanami, Motifs et Arts de vivre. La taxonomie du catalogue est figée à cinq collections depuis juin, et Arts de vivre n'en est pas une : c'est une page éditoriale, avec son propre mouvement 07. **Territoires** a donc repris la cinquième place. Nous avons choisi le kanji `地` et retiré le modificateur indigo, l'indigo étant réservé à Arts de vivre par la charte. Ces deux choix sont les vôtres, dites-nous s'ils vous conviennent.
- **La ligne d'horizon.** Le lien Instagram a été retiré, aucun réseau social n'étant ouvert au lancement, et les quatre liens légaux ont remplacé le mélange précédent. Le copyright reprend la mention « Créations BCDG ».

Enfin, une divergence reste ouverte et n'appelle pas de correction de notre part : votre bande `.kh-services__grid` est une réassurance en **quatre colonnes**, là où l'arbitrage O-1 prévoit **trois groupes de liens** (Navigation, Informations, Professionnels). Les deux ne se superposent pas. Nous attendons de savoir laquelle des deux structures vous voulez voir vivre avant de câbler le footer.

Merci,

---

## Notes internes, à ne pas transmettre

- Mesures recalculées à la main puis vérifiées par script, formule WCAG 2.x sur la luminance relative sRGB. Reproductibles.
- L'or alimente aussi `--global-palette2` dans `kh-foundations.css`, c'est-à-dire l'accent secondaire de Kadence, qui sert de couleur de survol de liens et de boutons dans le mapping par défaut. À vérifier sur staging : si ce mapping est actif, l'or peut atterrir sur des liens, ce que CLAUDE.md interdit. Ce point est indépendant de la réponse de Manus et relève de notre couche.
- `--kh-white #FFFDFC` est utilisé quinze fois dans `kh-charte-v3.css` mais absent de `theme.json`, donc impossible à choisir dans l'éditeur alors que la charte le prescrit pour les cartes produits. À traiter avec la réponse sur l'or, puisque les deux touchent la palette.
