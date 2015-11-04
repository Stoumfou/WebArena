<?php
$this->Html->meta('description', 'WebArena le site de jeu de combat d\'arène multijoueurs', array('inline' => false));
?>

<div class="jumbotron">
    <h1>WebArenas</h1>

    <p>Bienvenu dans WebArenas <span id="PlayerName"><?php echo $myname; ?></span></p>

    <p> Es-tu prêt à entrer dans l'arène ?</p>

    <div class="row">
        <a href="fighter" class="btn btn-lg btn-primary">Go !</a>
    </div>
</div>

<h2>Principe du jeux</h2>
<p>
    WebArena est un site de jeux multi-joueur de combat sur une arène.<br/>
    Tu vas pouvoir créer ton combattant et explorer l'arène en quête d'autre joueur à affronter, mais attention ton
    parcours pourras être semer d'embuche !</p>
<p>
    Tu pourras déplacer ton combattant case par case dans l'arène sous forme d'un damier (pas de déplacement en diagonale).<br/>
    Ton combattant à 3 caractéristiques : la force (pour faire des dégâts), la vie (si elle tombe à 0 ton personnage est mort), la vue (pour voir les évènements autours de toi)<br/>
    Les attaques se font vers une direction (droite, gauche, haut, bas).<br/>
    Tu gagnes de l'expérience en tuant les autres joueurs et pourras monter de tous les 4 points d'expérience et ainsi choisir une caractéristiques à améliorer.<br/>
    3 types de piège sont présent dans l'arène : le monstre, il te tue en un coup sur tu marches sur sa case. Des indications te seront fournis si tu es prêt de lui. Les pièges te feront des dégât si tu marche dessus, les colonnes te bloqueront le passage.


</p>
