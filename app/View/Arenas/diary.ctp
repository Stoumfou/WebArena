<?php
$this->Html->meta('description','Journal', array('inline' => false));
?>

<?php //pr($raw); ?>

<?php 	
if ($fighters != null) {
	echo $this->Form->create('FighterChoose');
	echo $this->Form->input('Combattant',array('options'=>$fighters));
	echo $this->Form->end('Voir');
	pr($raw); 

}   else {
    ?>
<div class="jumbotron">
    <h1>Journal</h1>

    <p>Bonjour <span id="PlayerName"><?php echo $myname; ?></span>.</p>

    <p> Il semblerait que tu n'ais pas de combattants ! Va en créer un en cliquant sur le bouton ci-dessous !</p>

    <div class="row">
        <a href="fighter" class="btn btn-lg btn-primary">Créer mon combattant !</a>
    </div>
</div>
<?php
    }
?>
