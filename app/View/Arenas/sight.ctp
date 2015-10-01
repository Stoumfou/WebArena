<?php
    $this->Html->meta('description','Vision',array('inline' => false));
?>
    <a href="index">Accueil</a>
	<a href="fighter">Combattant</a>
<?php

echo $this->Form->create('FighterMove');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->input('direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('Move');


echo $this->Form->create('FighterAttack');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->input('direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('Attack');
?>


