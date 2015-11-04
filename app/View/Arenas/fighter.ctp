<?php
$this->Html->meta('description','Combattant', array('inline' => false));
?>


<?php
echo '<h2>Gestion des combattants</h2>';

echo $this->Form->create('FighterCreate');
echo $this->Form->input('Nom');
echo $this->Form->end('Entrer dans l\'arène !');
?>

<?php
echo $this->Form->create('FighterChoose');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->end('Voir');
pr ($raw);

if($canLevelUp){
	echo $this->Form->create('FighterLevelUp');
	echo $this->Form->input('Combattant',array('default'=>$fighter['Fighter']['name'],'type'=>'hidden'));
	echo $this->Form->input('Skill',array('options'=>array('health'=>'health','sight'=>'sight','strength'=>'strength')));
	echo $this->Form->end('Monter un niveau');
}
?>
