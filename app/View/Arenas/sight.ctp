<?php
    $this->Html->meta('description','Vision',array('inline' => false));
?>
<script type="text/javascript">
    var mapLimit = "<?php echo MAPLIMIT ?>"; 
    var fName = "<?php echo $fighterToSight['Fighter']['name'] ?>";
    var fCoordX = "<?php echo $fighterToSight['Fighter']['coordinate_x'] ?>";
    var fCoordY = "<?php echo $fighterToSight['Fighter']['coordinate_x'] ?>";
    var flevel = "<?php echo $fighterToSight['Fighter']['level'] ?>";
    var fXp = "<?php echo $fighterToSight['Fighter']['xp'] ?>";
    var fSight = "<?php echo $fighterToSight['Fighter']['skill_sight'] ?>";
    var fStrength = "<?php echo $fighterToSight['Fighter']['skill_strength'] ?>";
    var fHealMax = "<?php echo $fighterToSight['Fighter']['skill_health'] ?>";
    var fHealth = "<?php echo $fighterToSight['Fighter']['current_health'] ?>";
    
    console.log(fName + " " +fCoordX + " " +fCoordY + " " +flevel + " " +fXp + " " +fSight + " " +fStrength + " " +fHealMax + " " +fHealth);
    
    var lastClicked;
    
var grid = clickableGrid(mapLimit,mapLimit,function(el,row,col,i){
    console.log("You clicked on element:",el);
    console.log("You clicked on row:",row);
    console.log("You clicked on col:",col);
    console.log("You clicked on item #:",i);
    
    el.innerHTML = fName;
    el.className='clicked';
    if (lastClicked) lastClicked.className='';
    lastClicked = el;
});

function drawGrid () {
    document.getElementById('gridContainer').appendChild(grid);
    if (fCoordX != "") {
        window.alert(fCoordX);
    } else {
        window.alert("null");
    }
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
            cell.addEventListener('click',(function(el,r,c,i){
                return function(){
                    callback(el,r,c,i);
                }
            })(cell,r,c,i),false);
        }
    }
    return grid;
}</script>


<div id="gridContainer"></div>
<div class="gridManipulator">
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
    </div>

<script type="text/javascript">
   drawGrid();
</script>