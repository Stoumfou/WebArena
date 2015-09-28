<?php
    $this->Html->meta('description','Vision',array('inline' => false));
?>
    <a href="index">Accueil</a>
<?php
echo $this->Form->create('FighterMove');
echo $this->Form->input('direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('Move');
?>

<?php
echo $this->Form->create('FighterAttack');
echo $this->Form->input('direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('Attack');
?>

<?php var_dump($raw); ?>