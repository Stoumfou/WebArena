<?php
    $this->Html->meta('description','Vision',array('inline' => false));
?>
<script type="text/javascript">
    var mapLimitX = "<?php echo MAPLIMITX ?>";
    var mapLimitY = "<?php echo MAPLIMITY ?>";

    var nbPilar = (mapLimitX*mapLimitY)/10;
    var arPilars = <?php echo json_encode($manyWalls) ?>;
    var arEvents = <?php echo json_encode($manyEvents) ?>;

    var manyPillars = Array();
    for(var i=0;i<nbPilar;i++){
        manyPillars.push(String(arPilars[i]["Surroundings"]["coordinate_x"])+";"+String(arPilars[i]["Surroundings"]["coordinate_y"]));
    }
    
    
    var manyEvents = Array();
    if(arEvents != "") {
        //console.log(arEvents);
        for(var i=0;i<arEvents.length;i++){
            manyEvents.push(String(arEvents[i]['Event']['coordinate_x'])+";"+String(arEvents[i]['Event']['coordinate_y']));
        }
    }
    

    var fName = "<?php echo $fighterToSight['Fighter']['name'] ?>";
    var fCoordX = "<?php echo $fighterToSight['Fighter']['coordinate_x'] ?>";
    var fCoordY = "<?php echo $fighterToSight['Fighter']['coordinate_y'] ?>";
    var flevel = "<?php echo $fighterToSight['Fighter']['level'] ?>";
    var fXp = "<?php echo $fighterToSight['Fighter']['xp'] ?>";
    var fSight = "<?php echo $fighterToSight['Fighter']['skill_sight'] ?>";
    var fStrength = "<?php echo $fighterToSight['Fighter']['skill_strength'] ?>";
    var fHealMax = "<?php echo $fighterToSight['Fighter']['skill_health'] ?>";
    var fHealth = "<?php echo $fighterToSight['Fighter']['current_health'] ?>";
    
    
    function inSight(s,cx,cy) {
        var resultat = Array();
        for ( var x=0; x<=s ;x++) {
            var y = s - x;
            for (y; y>=0 ;y--) {
                var test1 = String(x-(-cx)) +";"+ String(y-(-cy));
                var test2 = String(x-(-cx)) +";"+ String(-y-(-cy));
                var test3 = String(-y-(-cx)) +";"+ String(x-(-cy));
                var test4 = String(-y-(-cx)) +";"+ String(-x-(-cy));
                
                resultat.push(test1);
                resultat.push(test2);
                resultat.push(test3);
                resultat.push(test4);
                
            }
        }
        var res = jQuery.unique(resultat);
        return res;
        
    }
    var champVision = Array();
    champVision = inSight(fSight,fCoordX,fCoordY);
    
var grid = clickableGrid(mapLimitY,mapLimitX,function(el,row,col,i){
    console.log("You clicked on element:",el);
    console.log("You clicked on row:",row);
    console.log("You clicked on col:",col);
    console.log("You clicked on item #:",i);
    
    //el.innerHTML = fName;
    //lastClassClicked = el.className;
    //el.className = el.className + ' clicked';
    //if (lastClicked) lastClicked.className = lastClassClicked;
    //lastClicked = el;
});

function drawGrid () {
    if(document.getElementById('gridContainer')) document.getElementById('gridContainer').appendChild(grid);
}
     
function clickableGrid( rows, cols, callback ){
    var i=0;
    var grid = document.createElement('table');
    var player = document.createElement('i');
    player.innerHTML = '<i class="fa fa-user fa-2x"></i>';
    grid.className = 'grid';
    for (var r=0;r<rows;++r){
        var tr = grid.appendChild(document.createElement('tr'));
        for (var c=0;c<cols;++c){
            var cell = tr.appendChild(document.createElement('td'));
            //cell.innerHTML = ++i;
            if (fCoordX != "") if(c == fCoordX && r == fCoordY) cell.appendChild(player);
            if (fCoordX != "") if(c == fCoordX && r == fCoordY-1) cell.innerHTML = 'N';
            if (fCoordX != "") if(c == fCoordX && r == fCoordY-(-1)) cell.innerHTML = 'S';
            if (fCoordX != "") if(c == fCoordX-(-1) && r == fCoordY) cell.innerHTML = 'E';
            if (fCoordX != "") if(c == fCoordX-1 && r == fCoordY) cell.innerHTML = 'W';
            
            var title = String(c) +";"+ String(r); 
            if($.inArray( title, manyPillars)>=0)cell.className = ' pilar';
            else if($.inArray( title, manyEvents )>=0)cell.className = cell.className + ' events';
            else if($.inArray( title, champVision )>=0)cell.className = cell.className + ' cellInSight';
            else cell.className = 'cellNoSight';
            
            //else cell.className = 'cellNoSight';
            cell.addEventListener('click',(function(el,r,c,i){
                return function(){
                    callback(el,r,c,i);
                }
            })(cell,r,c,i),false);
        }
    }
    return grid;
}

</script>

<?php
if ($fighters != null) {
?>        
<div class="panel panel-info">
<div class="panel-heading"><div class="panel-title">
    <h2>Gestion des actions</h2></div></div>
    <div class="panel-body">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<?php
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
            <?php 
        
        if ($fighterToSight != "") {
        echo $this->Form->create('FighterAction',array(
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
                            )));
            ?>
            <fieldset>
        <legend><?php echo __('Choisissez une action.'); ?></legend>
                <?php
            echo $this->Form->input('Combattant',array('default'=>$fighterToSight['Fighter']["name"], 'type'=>'hidden'));
            echo $this->Form->radio('Action',array('attack'=>'Attack'));
            echo $this->Form->radio('Action',array('move'=>'Move'));
            echo $this->Form->input('Direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
            echo $this->Form->end(array(
            'label'=>__('GO!'),
            'class'=>'btn btn-success col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-12 col-md-10 col-lg-10',
            'div'=>'form-actions'));
        ?>

</div>
<?php
    echo '<div id="gridContainer"></div>
<div class="gridManipulator">';
?>
<script type="text/javascript">
   drawGrid();
</script>
<?php /*echo $this->Form->create('FighterAttack');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->input('direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('Attack');*/
    }
} else {
    ?>
<div class="jumbotron">
    <h1>Vision</h1>

    <p>Bonjour <span id="PlayerName"><?php echo $myname; ?></span>.</p>

    <p> Il semblerait que tu n'ais pas de combattants ! Va en créer un en cliquant sur le bouton ci-dessous !</p>

    <div class="row">
        <?php echo $this->Html->link('Créer mon combattant !', array('controller' => 'Arenas', 'action' => 'fighter'), array('class'=>'btn btn-lg btn-primary')) ?>
    </div>
</div>

    </div>
</div>
</div>

<?php
    }
?>

