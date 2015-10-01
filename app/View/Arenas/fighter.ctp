<?php
$this->Html->meta('description','Combattant', array('inline' => false));
?>
<a href="index">Accueil</a>
<a href="sight">Vision</a>
<?php
echo $this->Form->create('FighterChoose');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->end('Voir');
 pr($raw); 
?>
<h2>Créer un nouveau combattant</h2>
<?php
echo $this->Form->create('FighterCreate');
echo $this->Form->input('Nom');
echo $this->Form->end('Entrer dans l\'arène !');
?>