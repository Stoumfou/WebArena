<?php
$this->Html->meta('description','Combattant', array('inline' => false));

?>


<?php
echo '<h2>Gestion des combattants</h2>';
//echo $this->Form->create('Combattant',array('enctype'=>'multipart/form-data'));
echo $this->Form->create('FighterCreate',array('enctype'=>'multipart/form-data'));
echo $this->Form->input('Nom');
echo $this->Form->input('Avatar', array('type'=>'file'));
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
if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.jpeg'))echo $this->Html->image($fighter['Fighter']['id'].'.jpeg', array('alt' => 'Fighter'));
else if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.png'))echo $this->Html->image($fighter['Fighter']['id'].'.png', array('alt' => 'Fighter'));
else if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.jpg'))echo $this->Html->image($fighter['Fighter']['id'].'.jpg', array('alt' => 'Fighter'));
else if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.gif'))echo $this->Html->image($fighter['Fighter']['id'].'.gif', array('alt' => 'Fighter'));
else echo $this->Html->image('fighter.jpg', array('alt' => 'Fighter'));
?></div>
<div class="col-xs-12 col-md-8 col-lg-8 jumbotron">
    <h1>
        <?php
echo $fighter['Fighter']['name'].' LvL : '.$fighter['Fighter']['level'];
    ?>
        </h1>
    <?php
echo '
<div class="col-xs-2 col-md-2 col-lg-2">HP ('.$fighter['Fighter']['current_health'].'/'.$fighter['Fighter']['skill_health'].')</div><div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$fighter['Fighter']['current_health'].'" aria-valuemin="0" aria-valuemax="'.$fighter['Fighter']['skill_health'].'" style="width: '.((($fighter['Fighter']['current_health'])/($fighter['Fighter']['skill_health']))*100).'%">
    <span class="sr-only">'.((($fighter['Fighter']['current_health'])/($fighter['Fighter']['skill_health']))*100).'% Complete</span>
  </div>
</div>
<div class="col-xs-2 col-md-2 col-lg-2">XP ('.$fighter['Fighter']['xp'].'/4)</div><div class="progress">
  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'. $fighter['Fighter']['xp'] .'" aria-valuemin="0" aria-valuemax="4" style="width: '. (($fighter['Fighter']['xp'])/4)*100 .'%">
    <span class="sr-only">100% Complete (warning)</span>
  </div>
</div>
<i class="col-xs-3 col-md-3 col-lg-3 fa fa-gavel fa-3x" id="gavelIcon">'.$fighter['Fighter']['skill_strength'].'</i>
<i class="col-xs-3 col-md-3 col-lg-3 fa fa-eye fa-3x" id="eyeIcon">'.$fighter['Fighter']['skill_sight'].'</i>
<i class="col-xs-3 col-md-3 col-lg-3 fa fa-arrows-h fa-3x" id="arrowIcon">'.$fighter['Fighter']['coordinate_x'].'</i>
    <i class="col-xs-3 col-md-3 col-lg-3 fa fa-arrows-v fa-3x" id="arrowIcon">'.$fighter['Fighter']['coordinate_y'].'</i>';
?></div>
</div>
<?php }?>