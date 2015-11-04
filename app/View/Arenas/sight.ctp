<?php
    $this->Html->meta('description','Vision',array('inline' => false));
?>
<script type="text/javascript">
    var mapLimit = "<?php echo MAPLIMIT ?>"; 
    var fighter = "<?php echo $fighterToSight ?>";
    
    var lastClicked;
    
var grid = clickableGrid(mapLimit,mapLimit,function(el,row,col,i){
    console.log("You clicked on element:",el);
    console.log("You clicked on row:",row);
    console.log("You clicked on col:",col);
    console.log("You clicked on item #:",i);
    
    el.innerHTML = fighter;
    
    el.className='clicked';
    if (lastClicked) lastClicked.className='';
    lastClicked = el;
});

function drawGrid () {
    document.getElementById('gridContainer').appendChild(grid);
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