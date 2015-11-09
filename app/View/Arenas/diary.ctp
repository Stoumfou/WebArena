<?php
$this->Html->meta('description', 'Journal', array('inline' => false));
?>

<?php //pr($raw); ?>

<?php
if ($fighters != null) {
    echo '<div class="col-lg-12 text-center">';
	echo $this->Form->create('FighterChoose', array(
        'class' => 'form-inline',
        'inputDefaults' => array(
            'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
            'div' => array('class' => 'form-group col-lg-2'),
            'label' => array('class' => 'control-label'),
            'error' => array('attributes' => array(
                'wrap' => 'span', 'class' => 'help-inline'
            )),
        )));
    echo '<div class="dropdown">';
	echo $this->Form->input('Combattant',array('class'=>'form-control','options'=>$fighters));
    echo '</div>';
    $options = array('label' => 'Voir', 'class' => 'btn btn-primary', 'div'=>false);
	echo $this->Form->end($options);
    echo '</div>';
	//pr($raw);


	if(sizeof($raw) != 0){
        echo '<table id="listEvent" class="table table-bordered table-striped table-responsive table-hover">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>date</th>
            <th>coordinate_x</th>
            <th>coordinate_y</th>
        </tr>
        </thead><tbody>';
        foreach ($raw as $event) {
            echo '<tr>';
            echo '<td>' . $event['Event']['id'] . '</td>';
            echo '<td>' . $event['Event']['name'] . '</td>';
            echo '<td>' . $event['Event']['date'] . '</td>';
            echo '<td>' . $event['Event']['coordinate_x'] . '</td>';
            echo '<td>' . $event['Event']['coordinate_y'] . '</td>';
            echo '</tr>';
        }
    }echo '</tbody></table>';
}else {
    ?>
<div class="jumbotron">
    <h1>Journal</h1>

    <p>Bonjour <span id="PlayerName"><?php echo $myname; ?></span>.</p>

    <p> Il semblerait que tu n'ais pas de combattants ! Va en créer un en cliquant sur le bouton ci-dessous !</p>

    <div class="row">
		<?php echo $this->Html->link('Créer mon combattant !', array('controller' => 'Arenas', 'action' => 'fighter'), array('class'=>'btn btn-lg btn-primary')) ?>
    </div>
</div>
<?php
    }
?>
<script>$(document).ready(function() {
        $('#listEvent').DataTable();
    } );</script>
