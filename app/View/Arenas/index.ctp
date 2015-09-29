<?php
    $this->Html->meta('description','WebArena le site de jeu de combat d\'arène multijoueurs', array('inline' => false));
?>

<h1>WebArenas</h1>
<p>Bienvenu dans WebArena, <?php echo $myname;?></p>

<?php 	if ($myname == 'toi petit troll') echo ('<ul><li><a href="../Users/login">Connexion/Inscription</a></li>');
		else echo '<ul><li><a href="../Users/logout">Déconnexion</a></li>
<li><a href="fighter">Combattant</a></li>
<li><a href="sight">Vision</a></li>
<li><a href="diary">Journal</a></li></ul>';?>