# Home éditable (thème enfant 1.3.0)

Cap Alain du 2026-09-21 : tout texte public se modifie dans l'admin WordPress. La home n'est plus écrite dans le PHP du thème.

## Où se modifie quoi

| Élément | Où |
|---|---|
| Tous les textes de la home (titres, chapôs, libellés des boutons et des liens, les cinq mondes, Kaïro, L'Atelier, Lifestyle & Koi, manifeste, Professionnels) | Pages → « Accueil » (FR, 318) et « Home » (EN, 319), éditeur de blocs |
| Image du hero et son texte alternatif | Même page, bloc Image en haut à droite |
| Liens des cinq mondes et des boutons | Même page, lien du titre ou du paragraphe concerné |
| Les 4 produits affichés | Automatique (ordre du catalogue). Bloc « Code court » `[kh_creations]` : le déplacer ou le supprimer, ne pas le modifier |
| Les 3 derniers articles | Automatique. Bloc « Code court » `[kh_journal]` : rien ne s'affiche tant qu'aucun article n'est publié |

Chaque enregistrement crée une révision : un retour en arrière se fait depuis l'éditeur (Révisions).

## Règles pour ne pas casser la mise en page

- Ne pas supprimer les groupes (Groupe / Section) : ils portent les classes de la charte (`kh-movement`, `kh-container`, `kh-home-…`). Modifier le texte **à l'intérieur**.
- Pour ajouter un paragraphe, dupliquer un paragraphe existant du même groupe : il garde la bonne classe.
- Une flèche s'ajoute toute seule après les liens (`→`) et après le nom des cinq mondes (`↗`) : ne pas la taper.
- FR et EN sont deux pages distinctes : toute modification de fond se fait dans les deux.

## Doctrine éditoriale (rappel pour toute relecture)

Jamais de mention de la Chine, dans aucun contexte. Jamais « fabrication », « fabriqué en France », ni mention d'atelier ou de production sur les fiches produits. Référent culturel : le Japon. Pas de tiret cadratin dans les fiches produits ; la signature s'écrit `- by BCDG`. Aucun volume global de stock. Détail : `CLAUDE.md` § Doctrine éditoriale impérative.

## Technique

- `page-templates/kh-home.php` : cadre seul (`the_content()`), plus aucun texte.
- `inc/home-blocks.php` : shortcodes `kh_creations` et `kh_journal`, et contenu initial `koinobori_child_home_seed()` (lu une seule fois par le script de remplissage, jamais à l'affichage).
- `tools/staging/pc6/home-blocks-seed.php` : remplit 318 / 319 après sauvegarde (`restore.php` du PC4 pour revenir en arrière), refuse de repasser sur une page déjà remplie.
- Blocs natifs uniquement (groupe, titre, paragraphe, boutons, image, code court) : aucun plugin ajouté.
