<?php
$this->Html->meta('description','Combattant', array('inline' => false));

?>


<h2>Gestion des combattants</h2>
    <div class="row top-buffer">
        <div class="col-xs-12 col-md-12 col-lg-12 ">
            <?php if(count($fighters) == 0) {?>
            <div class="col-md-offset-1 col-lg-offset-1 col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <?php } else  {?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <?php }?>
<?php

echo $this->Form->create('FighterCreate', array(
                            'enctype'=>'multipart/form-data',
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'inputDefaults' => array(
                                'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                                'div' => array('class' => 'form-group'),
                                'class' => array('form-control'),
                                'label' => array('class' => 'col-xs-2 col-md-2 col-lg-2 control-label'),
                                'between' => '<div class="col-xs-12 col-md-10 col-lg-10">',
                                'after' => '</div>',
                                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
                            ))); ?>

    <fieldset>
        <legend><?php echo __('Veuillez entrer le nom de votre combattant.'); ?></legend>
        <?php echo $this->Form->input('Nom'); 
        echo $this->Form->input('Avatar', array('type'=>'file'));?>
        <?php echo $this->Form->end(array(
            'label'=>__('Créer'),
            'class'=>'btn btn-primary col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-12 col-md-10 col-lg-10',
            'div'=>'form-actions'));
        ?>
    </fieldset>
    
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<?php
if (count($fighters) != 0) {
    echo $this->Form->create('FighterChoose', array(
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'inputDefaults' => array(
                                'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                                'div' => array('class' => 'form-group'),
                                'class' => array('form-control'),
                                'label' => array('class' => 'col-xs-2 col-md-2 col-lg-2 control-label'),
                                'between' => '<div class="col-xs-12 col-md-10 col-lg-10">',
                                'after' => '</div>',
                                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
                            ))); ?>

    <fieldset>
        <legend><?php echo __('Choisissez un combattant à afficher.'); ?></legend>
        <?php echo $this->Form->input('Combattant',array('options'=>$fighters)); 
         echo $this->Form->end(array(
            'label'=>__('Voir'),
            'class'=>'btn btn-primary col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-12 col-md-10 col-lg-10',
            'div'=>'form-actions'));
        ?>
    </fieldset>

</div>
    </div>
        </div>
</div>
<?php
    
if($canLevelUp){
	echo $this->Form->create('FighterLevelUp');
	echo $this->Form->input('Combattant',array('default'=>$fighter['Fighter']['name'],'type'=>'hidden'));
	echo $this->Form->input('Skill',array('options'=>array('health'=>'health','sight'=>'sight','strength'=>'strength')));
	echo $this->Form->end('Monter un niveau');
}
}
if ($fighter) {
?>
<hr/>
<div id="fighterDisplay" class="row top-buffer">
    <div class="col-xs-12 col-md-3 col-lg-3">
<?php
if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.jpeg'))echo $this->Html->image($fighter['Fighter']['id'].'.jpeg', array('alt' => 'Fighter','class'=>'imgResize'));
else if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.png'))echo $this->Html->image($fighter['Fighter']['id'].'.png', array('alt' => 'Fighter','class'=>'imgResize'));
else if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.jpg'))echo $this->Html->image($fighter['Fighter']['id'].'.jpg', array('alt' => 'Fighter','class'=>'imgResize'));
else if(file_exists(WWW_ROOT.'img/'.$fighter['Fighter']['id'].'.gif'))echo $this->Html->image($fighter['Fighter']['id'].'.gif', array('alt' => 'Fighter','class'=>'imgResize'));
else echo $this->Html->image('fighter.jpg', array('alt' => 'Fighter', 'class'=>'imgResize'));
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
?>
    
    <div class="row top-buffer">
    <div class="col-xs-12 col-md-8 col-lg-8">
    <?php
    echo $this->Form->create('FighterKill');
    echo $this->Form->input('Combattant',array('default'=>$fighter['Fighter']["name"], 'type'=>'hidden'));
    echo $this->Form->end(array(
            'label'=>__('Supprimer'),
            'class'=>'btn btn-danger col-md-offset-12  col-lg-offset-12 col-xs-12 col-sm-12 col-md-6 col-lg-6',
            'before'=>'<hr/>',
            'div'=>'form-actions'));
        
    
    ?></div></div>
</div></div>
<?php }?>