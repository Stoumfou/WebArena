<?php
    $this->Html->meta('description','Vision',array('inline' => false));
?>
<script type="text/javascript">
    var mapLimitX = "<?php echo MAPLIMITX ?>";
    var mapLimitY = "<?php echo MAPLIMITY ?>";
    
    var lastClassClicked;
    var lastClicked;

    var nbPilar = (mapLimitX*mapLimitY)/10;
    var arPilars = <?php echo json_encode($manyWalls) ?>;
    var arEvents = <?php echo json_encode($manyEvents) ?>;
    var arEnnemies = <?php echo json_encode($manyEnnemies) ?>;

    var manyPillars = Array();
    for(var i=0;i<nbPilar;i++){
        manyPillars.push(String(arPilars[i]["Surroundings"]["coordinate_x"])+";"+String(arPilars[i]["Surroundings"]["coordinate_y"]));
    }
    
    
    var manyEvents = Array();
    var testEvents = Array();
    
    if(arEvents != "") {
        for(var i=0;i<arEvents.length;i++){
            var coordString = String(arEvents[i]['Event']['coordinate_x'])+";"+String(arEvents[i]['Event']['coordinate_y']);
            manyEvents.push(coordString);
            testEvents[coordString] = String(arEvents[i]['Event']['name']);
        }
    }
    
    var manyEnnemies = Array();
    for(var i=0;i<arEnnemies.length;i++){
        manyEnnemies.push(String(arEnnemies[i]['coordinate_x'])+";"+String(arEnnemies[i]['coordinate_y']));
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
    
    function legendOnClick(el){
        document.getElementById('legendDisplayTitle').innerHTML = el;
    }
    
var grid = clickableGrid(mapLimitY,mapLimitX,function(el,row,col,i){
    var tableEntry = document.createElement('table');
    var headTableEntry = document.createElement('thead'); 
    var headTableEntryRow = document.createElement('tr'); 
    var headNameTable = document.createElement('th');
    var headDateTable = document.createElement('th');
    var bodyTableEntry = document.createElement('tbody');
    
    tableEntry.className ='table table-bordered table-striped table-responsive table-hover';
    tableEntry.setAttribute("id", "tableEntry");
    
    headNameTable.innerHTML = "Entrée";
    headDateTable.innerHTML = "Date";
    
    headTableEntryRow.appendChild(headDateTable);
    headTableEntryRow.appendChild(headNameTable);
    headTableEntry.appendChild(headTableEntryRow);
    
    tableEntry.appendChild(headTableEntry);
    
    for(var i = 0; i<arEvents.length;i++) {
        var coordCompare = (String(arEvents[i]['Event']['coordinate_x'])+";"+String(arEvents[i]['Event']['coordinate_y']));
        if ((coordCompare)==(col+';'+row)) {
            var rowTable = document.createElement('tr'); 
            var entryDate = document.createElement('td');
            var entryName = document.createElement('td');
            
            entryName.innerHTML = String(arEvents[i]['Event']['name']);
            entryDate.innerHTML = String(arEvents[i]['Event']['date']);
            rowTable.appendChild(entryDate);
            rowTable.appendChild(entryName);
            
            bodyTableEntry.appendChild(rowTable);
        }
    }
    tableEntry.appendChild(bodyTableEntry);
    
    var disEvent = document.getElementById('displayEvent');
    var entryEvent = "Il n'y a pas d'entrée.";
    if (testEvents[col+';'+row] != undefined) entryEvent = testEvents[col+';'+row];
    disEvent.className = 'jumbotron showDisplayEvent';
    disEvent.innerHTML = '<h2>événements de la case ' + col +";"+ row + '</h2>';
    disEvent.appendChild(tableEntry);

    if (lastClicked) lastClicked.className = lastClassClicked;
    lastClassClicked = el.className;
    el.className = el.className + ' clicked';
    lastClicked = el;
    
    $('#tableEntry').DataTable();
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
            var ennemy = document.createElement('i');
            ennemy.innerHTML = '<i class="fa fa-dot-circle-o fa-2x"></i>';
            
            if (fCoordX != "") {
                if(c == fCoordX && r == fCoordY-1) cell.innerHTML = 'N';
                if(c == fCoordX && r == fCoordY-(-1)) cell.innerHTML = 'S';
                if(c == fCoordX-(-1) && r == fCoordY) cell.innerHTML = 'E';
                if(c == fCoordX-1 && r == fCoordY) cell.innerHTML = 'W';
                if(c == fCoordX && r == fCoordY) cell.appendChild(player);
                               }
        
                var title = String(c) +";"+ String(r); 
            if(($.inArray( title, manyEnnemies)>= 0) && title != (fCoordX+";"+fCoordY)) cell.appendChild(ennemy);
            if($.inArray( title, manyPillars)>=0)cell.className = ' pilar';
            else if($.inArray( title, manyEvents )>=0)cell.className = cell.className + ' events';
            else if($.inArray( title, champVision )>=0)cell.className = cell.className + ' cellInSight';
            else cell.className = 'cellNoSight';
            
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
                                'after' => '</div><br />',
                                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
                            ))); ?>

    <fieldset>
        <legend><?php echo __('Choisissez un combattant à afficher.'); ?></legend>
        <?php echo $this->Form->input('Combattant',array('options'=>$fighters, 'default'=>$fighterToSight['Fighter']["name"])); 
         echo $this->Form->end(array(
            'label'=>__('Voir'),
            'class'=>'btn btn-primary col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-12 col-md-10 col-lg-10',
            'div'=>'form-actions',
            'after' => '<br />',
         ));
        ?>
    </fieldset>
            <?php 
        
        if ($fighterToSight != "") {
        echo $this->Form->create('FighterMove',array(
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
            echo $this->Form->input('Action',array('default'=>'move', 'type'=>'hidden'));
            echo $this->Form->input('Direction',array('options' => array('north'=>'Nord','east'=>'Est','south'=>'Sud','west'=>'Ouest'), 'default' => 'north'));
            echo $this->Form->end(array(
            'label'=>__('Marchons !'),
            'class'=>'btn btn-success col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-12 col-md-10 col-lg-10',
            'div'=>'form-actions'));
            
            echo $this->Form->create('FighterAttack',array(
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'inputDefaults' => array(
                                'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                                'div' => array('class' => 'form-group'),
                                'before' => '<hr/>',
                                'class' => array('form-control'),
                                'label' => array('class' => 'col-xs-2 col-md-2 col-lg-2 control-label'),
                                'between' => '<div class="col-xs-12 col-md-10 col-lg-10">',
                                'after' => '</div>',
                                'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
                            )));
            ?>
                <?php
            echo $this->Form->input('Combattant',array('default'=>$fighterToSight['Fighter']["name"], 'type'=>'hidden'));
            echo $this->Form->input('Action',array('default'=>'attack', 'type'=>'hidden'));
            echo $this->Form->input('Direction',array('options' => array('north'=>'Nord','east'=>'Est','south'=>'Sud','west'=>'Ouest'), 'default' => 'north'));
            echo $this->Form->end(array(
            'label'=>__('A l\'attaque !'),
            'class'=>'btn btn-danger col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-12 col-md-10 col-lg-10',
            'div'=>'form-actions'));
        ?>

</div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<?php
    echo '<div id="gridContainer">
        <div> Légende (clique ci-dessous) : <span id="legendDisplayTitle"></span>
        
    <table id="tableLegends"  class="grid"><tr>
        <td id="legUser" onclick="legendOnClick(\'Votre personnage\')" class="cellInSight"><i class="fa fa-user fa-2x"></i></td>
        <td id="legInSight" onclick="legendOnClick(\'Dans le champ de vision\')" class="cellInSight"></td>
        <td id="legNoSight" onclick="legendOnClick(\'En dehors du champ de vision\')" class="cellNoSight"></td>
        <td id="legPilar" onclick="legendOnClick(\'Colonne (obstacle)\')" class="pilar"></td>
        <td id="legEvent" onclick="legendOnClick(\'Evénement\')" class="events"></td>
        <td id="legEnnemy" onclick="legendOnClick(\'Autre joueur\')" class="cellInSight"><i class="fa fa-dot-circle-o fa-2x"></i></td>
    </tr>
    </table>
    </div>
    </div>';
?>
            </div>
<script type="text/javascript">
   drawGrid();
</script>
            
<?php }
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



<?php
    }
?>
            
        </div>
    </div>
    
                <div id="displayEvent" class="jumbotron hideDisplayEvent">
                
                </div>  
            

    

