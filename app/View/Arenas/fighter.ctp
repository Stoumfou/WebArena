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
if (count($fighters) != 0) {
echo $this->Form->create('FighterChoose');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->end('Voir');
    
echo $this->Form->create('FighterKill');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->end('Supprimer');

if($canLevelUp){
	echo $this->Form->create('FighterLevelUp');
	echo $this->Form->input('Combattant',array('default'=>$fighter['Fighter']['name'],'type'=>'hidden'));
	echo $this->Form->input('Skill',array('options'=>array('health'=>'health','sight'=>'sight','strength'=>'strength')));
	echo $this->Form->end('Monter un niveau');
}
}
if ($fighter) {
?>
<div id="fighterDisplay" class="">
    <div class="col-xs-12 col-md-3 col-lg-3">
<?php
echo $this->Html->image('fighter.jpg', array('alt' => 'Fighter'));
?></div>
<div class="col-xs-12 col-md-8 col-lg-8 jumbotron">
    <h1>
        <?php
echo $fighter['Fighter']['name'];
    ?>
        </h1>
    <?php
echo '
<div class="progress ">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
    <span class="sr-only">40% Complete (success)</span>
  </div>
</div>
<div class="col-xs-2 col-md-2 col-lg-2">Vie</div><div class="progress">
  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
    <span class="sr-only">20% Complete</span>
  </div>
</div>
<div class="col-xs-2 col-md-2 col-lg-2">Vie</div><div class="progress">
  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'. $fighter['Fighter']['xp'] .'" aria-valuemin="0" aria-valuemax="4" style="width: '. (($fighter['Fighter']['xp'])/4)*100 .'%">
    <span class="sr-only">100% Complete (warning)</span>
  </div>
</div>
<div class="col-xs-2 col-md-2 col-lg-2">Vie</div><div class="progress col-xs-9 col-md-9 col-lg-9">
  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
    <span class="sr-only">80% Complete (danger)</span>
  </div>
</div>';
?></div>
</div>
<?php }?>