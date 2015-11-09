<?php
    $this->Html->meta('description','Vision',array('inline' => false));
?>
<script type="text/javascript">
    var mapLimitX = "<?php echo MAPLIMITX ?>";
    var mapLimitY = "<?php echo MAPLIMITY ?>";

    var fName = "<?php echo $fighterToSight['Fighter']['name'] ?>";
    var fCoordX = "<?php echo $fighterToSight['Fighter']['coordinate_x'] ?>";
    var fCoordY = "<?php echo $fighterToSight['Fighter']['coordinate_y'] ?>";
    var flevel = "<?php echo $fighterToSight['Fighter']['level'] ?>";
    var fXp = "<?php echo $fighterToSight['Fighter']['xp'] ?>";
    var fSight = "<?php echo $fighterToSight['Fighter']['skill_sight'] ?>";
    var fStrength = "<?php echo $fighterToSight['Fighter']['skill_strength'] ?>";
    var fHealMax = "<?php echo $fighterToSight['Fighter']['skill_health'] ?>";
    var fHealth = "<?php echo $fighterToSight['Fighter']['current_health'] ?>";
    
    console.log(fName + " // X : " +fCoordX + " // Y : " +fCoordY + " // LVL : " +flevel + " // XP : " +fXp + " // SIGHT : " +fSight + " // STR : " +fStrength + " // MaxHP : " +fHealMax + " // CurHealth : " +fHealth);
    var lastClicked;
    
    
    function inSight(s,cx,cy) {
    var resultat = Array();
    for ( var x=0; x<=s ;x++) {
            var y = s - x;
            for (y; y>=0 ;y--) {
                //console.log(x-(-cx)+""+y-(-cy));
                //console.log(x-(-cx));
                //console.log(y-(-cy));
                var t1 = String(x-(-cx));
                var test1 = t1 + String(y-(-cy));
               // var test1 = String(x-(-cx)+""+y-(-cy));
                
                var t1 = String(-x-(-cx));
                var test2 = t1 + String(-y-(-cy));
                //var test2 = String(-x-(-cx)+""+-y-(-cy));
                
                var t1 = String(y-(-cx));
                var test3 = t1 + String(x-(-cy));
                //var test3 = String(y-(-cx)+""+x-(-cy));
                
                var t1 = String(-y-(-cx));
                var test4 = t1 + String(-x-(-cy));
                //var test4 = String(-y-(-cx)+""+-x-(-cy));
                //console.log(t2);

                resultat[test1] = Array(x-(-cx),y-(-cy));
                resultat[test2] = Array(-x-(-cx),-y-(-cy));
                resultat[test3] = Array(y-(-cx),x-(-cy));
                resultat[test4] = Array(-y-(-cx),-x-(-cy));
                
                //resultat.push((""+x-(-cx)+""+y-(-cy)+""),  Array(x-(-cx),y-(-cy)));
                //resultat.push((""+-x-(-cx)+""+-y-(-cy)+""), Array(-x-(-cx),-y-(-cy)));
                //resultat.push((""+y-(-cx)+""+x-(-cy)+""),   Array(y-(-cx),x-(-cy)));
                //resultat.push((""+-y-(-cx)+""+-x-(-cy)+""), Array(-y-(-cx),-x-(-cy)));
            }
        }
    //var res = jQuery.unique(resultat);
    return resultat;
        
}
    var champVision = Array();
    champVision = inSight(fSight,fCoordX,fCoordY);
    
var grid = clickableGrid(mapLimitY,mapLimitX,function(el,row,col,i){
    console.log("You clicked on element:",el);
    console.log("You clicked on row:",row);
    console.log("You clicked on col:",col);
    console.log("You clicked on item #:",i);
    console.log(champVision);
    
    //el.innerHTML = fName;
    el.className='clicked';
    if (lastClicked) lastClicked.className='';
    lastClicked = el;
});

function drawGrid () {
    if(document.getElementById('gridContainer')) document.getElementById('gridContainer').appendChild(grid);
}
     
function clickableGrid( rows, cols, callback ){
    var i=0;
    var grid = document.createElement('table');
    grid.className = 'grid';
    for (var r=0;r<rows;++r){
        var tr = grid.appendChild(document.createElement('tr'));
        for (var c=0;c<cols;++c){
            var cell = tr.appendChild(document.createElement('td'));
            //cell.innerHTML = ++i;
            if (fCoordX != "") if(c == fCoordX && r == fCoordY) cell.innerHTML = 'P';
            if (fCoordX != "") if(c == fCoordX && r == fCoordY-1) cell.innerHTML = 'N';
            if (fCoordX != "") if(c == fCoordX && r == fCoordY-(-1)) cell.innerHTML = 'S';
            if (fCoordX != "") if(c == fCoordX-(-1) && r == fCoordY) cell.innerHTML = 'E';
            if (fCoordX != "") if(c == fCoordX-1 && r == fCoordY) cell.innerHTML = 'W';
            
            var title = String(r + c); 
            if(champVision[title]) cell.className = 'cellInSight';
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
echo $this->Form->create('FighterChoose');
echo $this->Form->input('Combattant',array('options'=>$fighters));
echo $this->Form->end('Choose');
        
if ($fighterToSight != "") {
    echo '<div id="gridContainer"></div>
<div class="gridManipulator">';
    
echo $this->Form->create('FighterAction');
echo $this->Form->input('Combattant',array('options'=> array($fighters)));
echo $this->Form->input('Action',array('options'=>array('move'=>'move','attack'=>'attack')));
echo $this->Form->input('Direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('GO!');


/*echo $this->Form->create('FighterAttack');
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
<?php
    }
?>
    </div>

<script type="text/javascript">
   drawGrid();
</script>
