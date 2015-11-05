<?php

App::uses('AppModel', 'Model');

class Surroundings extends AppModel {

    public $displayField = 'name';
	
	public function vector($direction){
		$vector = array('x'=>0,'y'=>0);
		
		switch($direction){
			case "north":	$vector['y']--;
							break;
			case "south":	$vector['y']++;
							break;
			case "east":	$vector['x']++;
							break;
			case "west":	$vector['x']--;
							break;
            default: ;
		}
		
		return $vector;
	}
	
	public function checkSurroundings($fighter, $direction){
		
		$result = array();
	   
		//Récupération du Fighter en action
		$player = $fighter;
		//Détermination du vecteur mouvement à partir de la direction choisi par le joueur
		$vector = $this->vector($direction);
		
		//Vérification de la présence d'un Surroundings à la destination
		$surroundings = $this->find('all',array('conditions'=>array('coordinate_x'=>($player['Fighter']['coordinate_x']+$vector['x']),
																	'coordinate_y'=>($player['Fighter']['coordinate_y']+$vector['y'])
																	),
												'order'=>'type'
												)
									);
		
		if(count($surroundings) != 0){
			foreach($surroundings as $surrounding){
				var_dump($surrounding);
				switch($surrounding['Surroundings']['type']){
					case 'trap': 			array_push($result,1);
											break;
					case 'mob':				array_push($result,2);
											break;
					case 'wall':			array_push($result,3);
											break;
					case 'warning_trap':	array_push($result,4);
											break;
					case 'warning_mob':		array_push($result,5);
											break;
					default:	;
				}
			}
		}else array_push($result,0);
		
		return $result;
	}
	
	public function mobMove(){
		$mob = $this->find('first',array('conditions'=>array('type'=>'mob')));
		$warnings = $this->find('all',array('conditions'=>array('type'=>'mob')));
		
		//Initialisation des coordonnées de spawn
		$coord = array('coordinate_x'=>0,'coordinate_y'=>0);
		$tried = array();
		$freeSpot = false;
		
		while(!$freeSpot){
			//Choix d'un couple (x,y) de coordonnée aléatoire dans l'arène
			$coord['coordinate_x'] = rand(1,MAPLIMIT);
			$coord['coordinate_y'] = rand(1,MAPLIMIT);
			
			//Si la case (x,y) n'a pas été testée
			if(array_search($coord,$tried) == false){
				//Si aucun Fighter n'est positionné sur la case (x,y), la case est marquée comme libre
				if(count($this->find('all',array('conditions'=>array('coordinate_x'=>$coord['coordinate_x'],'coordinate_y'=>$coord['coordinate_y'])))) == 0)$freeSpot = true;
				//Sinon la case est marquée comme testée
				else array_push($tried,$coord);
			}
			//Si toute les cases ont été testées
			if(count($tried) == (MAPLIMIT * MAPLIMIT)){
				//L'Event est annulé et la boucle est terminée
				break;
			}
		}
		
		if($freeSpot){
			$mob['Surroundings']['coordinate_x'] = $coord['coordinate_x'];
			$mob['Surroundings']['coordinate_y'] = $coord['coordinate_y'];
			
			$datas = array('Surroundings'=>array('id'=>$mob['Surroundings']['id'],'coordinate_x'=>$mob['Surroundings']['coordinate_x'],'coordinate_y'=>$mob['Surroundings']['coordinate_y']));
			$this->save($datas);
			
			$i = -1;
			$j = -1;
			
			foreach($warnings as $warning){
				
				if($i==2){
					$i =-1;
					$j++;
				}else if(($i ==0)&&($j==0))$i++;
					
				$warning['Surroundings']['coordinate_x'] = $coord['coordinate_x']+$i;
				$warning['Surroundings']['coordinate_y'] = $coord['coordinate_y']+$j;
				
				$datas = array('Surroundings'=>array('id'=>$warning['Surroundings']['id'],'coordinate_x'=>$warning['Surroundings']['coordinate_x'],'coordinate_y'=>$warning['Surroundings']['coordinate_y']));
				$this->save($datas);
					
				$i++;
			}
		}
	}
	
	public function generate(){
		
		
	}

}
?>