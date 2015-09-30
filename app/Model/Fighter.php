<?php

define('MAPLIMIT',15);

App::uses('AppModel', 'Model');

class Fighter extends AppModel {

    public $displayField = 'name';

    public $belongsTo = array(

        'User' => array(

            'className' => 'User',

            'foreignKey' => 'player_id',

        ),

   );
   
   public function getFighterByUserId($user_id){
		return $this->findByPlayer_id('first',array('condition'=>array('player_id'=>$user_id)));
   }
   
   public function choose($user_id){
	   $result = array();
	   $fighters = $this->find('all',array('condition'=>array('player_id'=>$user_id)));
	   
	   foreach($fighters as $fighter){
		   $result = array_merge($result,array($fighter['Fighter']['name']=>$fighter['Fighter']['name']));
	   }
	   return $result;
   }
   
   public function vector($direction){
		$vector = array('x'=>0,'y'=>0);
		
		switch($direction){
			case "north":	$vector['y']++;
							break;
			case "south":	$vector['y']--;
							break;
			case "east":	$vector['x']++;
							break;
			case "west":	$vector['x']--;
							break;
            default: ;
		}
		
		return $vector;
	}
	
   
   public function isThere($fighterId, $vector){
		$player = $this->find('first', array('condition'=>array('id'=>$fighterId)));
		$target = array();
		$result = -1;
		
		if((($player['Fighter']['coordinate_x']+$vector['x'])>0)&&
		(($player['Fighter']['coordinate_x']+$vector['x'])<=MAPLIMIT)&&
		(($player['Fighter']['coordinate_y']+$vector['y'])>0)&&
		(($player['Fighter']['coordinate_y']+$vector['y'])<=MAPLIMIT)){
			$target = $this->find('all',array('conditions'=>array('coordinate_x'=>($player['Fighter']['coordinate_x']+$vector['x']),'coordinate_y'=>($player['Fighter']['coordinate_y']+$vector['y']))));
		}else $result = -2;
		
		if(count($target) == 0)$result++;
		else $result= $target[0];
	   
	   //var_dump($result);
	   return $result;
   }
   
   public function doMove($fighterId, $direction){
		$player = $this->find('first', array('condition'=>array('id'=>$fighterId)));
		$vector = $this->vector($direction);
		
		$result = false;
		
		if($this->isThere($fighterId, $vector) == 0){
			$datas = array('Fighter'=>array('id'=>$fighterId,'coordinate_y'=>$player['Fighter']['coordinate_y'] + $vector['y'],'coordinate_x'=>$player['Fighter']['coordinate_x'] + $vector['x']));
			$this->save($datas);
			$result = true;
		}
		
		return $result;
	}
	
	public function doAttack($fighterId, $direction){
		$result = -1;
		
		$player = $this->find('first', array('condition'=>array('id'=>$fighterId)));
		$vector = $this->vector($direction);
		$defenser = $this->isThere($fighterId, $vector);
		var_dump($defenser);
		
		if(is_array($defenser)){
			$result = true;
			$rand = rand(1,20);
			echo $rand;
			
			if($rand>(10+$defenser['Fighter']['level']-$player['Fighter']['level'])){
				$datas = array('Fighter'=>array('id'=>$defenser['Fighter']['id'],'current_health'=>($defenser['Fighter']['current_health'] - $player['Fighter']['skill_strength'])));
				$this->save($datas);
				$result = 1;
			}else $result = 0;
		}
		return $result;
	}
}
?>