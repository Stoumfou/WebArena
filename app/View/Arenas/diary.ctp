<?php
$this->Html->meta('description','Journal', array('inline' => false));
?>
<a href="index">Accueil</a>
<?php 	
	echo $this->Form->create('FighterChoose');
	echo $this->Form->input('Combattant',array('options'=>$fighters));
	echo $this->Form->end('Voir');
	pr($raw); ?>