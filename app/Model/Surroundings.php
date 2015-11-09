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
			$coord['coordinate_x'] = rand(0,MAPLIMITX-1);
			$coord['coordinate_y'] = rand(0,MAPLIMITY-1);

			//Si la case (x,y) n'a pas été testée
			if(array_search($coord,$tried) == false){
				//Si aucun Surroundings n'est positionné sur la case (x,y), la case est marquée comme libre
				if(count($this->find('all',array('conditions'=>array('coordinate_x'=>$coord['coordinate_x'],'coordinate_y'=>$coord['coordinate_y'])))) == 0)$freeSpot = true;
				//Sinon la case est marquée comme testée
				else array_push($tried,$coord);
			}
			//Si toute les cases ont été testées
			if(count($tried) == (MAPLIMITX * MAPLIMITY)){
				//L'Event est annulé et la boucle est terminée
				break;
			}
		}

		if($freeSpot){
			$mob['Surroundings']['coordinate_x'] = $coord['coordinate_x'];
			$mob['Surroundings']['coordinate_y'] = $coord['coordinate_y'];

			$datas = array('Surroundings'=>array('id'=>$mob['Surroundings']['id'],'coordinate_x'=>$mob['Surroundings']['coordinate_x'],'coordinate_y'=>$mob['Surroundings']['coordinate_y']));
			$this->save($datas);

			foreach($warnings as $warning)$this->delete($warning['Surroundings']['id']);

			$this->genWarnings('mob',$coord);
		}
	}

	public function generate($type){

		//Initialisation des coordonnées de spawn
		$coord = array('coordinate_x'=>0,'coordinate_y'=>0);
		$tried = array();
		$freeSpot = false;

		while(!$freeSpot){
			//Choix d'un couple (x,y) de coordonnée aléatoire dans l'arène
			$coord['coordinate_x'] = rand(0,MAPLIMITX-1);
			$coord['coordinate_y'] = rand(0,MAPLIMITY-1);

			//Si la case (x,y) n'a pas été testée
			if(array_search($coord,$tried) == false){
				//Si aucun Surroundings n'est positionné sur la case (x,y), la case est marquée comme libre
				if(count($this->find('all',array('conditions'=>array('coordinate_x'=>$coord['coordinate_x'],'coordinate_y'=>$coord['coordinate_y'])))) == 0)$freeSpot = true;
				//Sinon la case est marquée comme testée
				else array_push($tried,$coord);
			}

			//Si toute les cases ont été testées
			if(count($tried) == (MAPLIMITX * MAPLIMITY))break;
		}
		//Si la dernière case testée est marquée libre
		if($freeSpot){
			switch($type){
				case 'wall':	//Enregistrement du nouveau Surroundings
					$datas = array('Surroundings'=>array('coordinate_x'=>$coord['coordinate_x'],
							'coordinate_y'=>$coord['coordinate_y'],
							'type'=>$type
					)
					);
					$this->create();
					$this->save($datas);
					break;

				case 'trap':
				case 'mob' :	//Enregistrement du nouveau Surroundings
					$datas = array('Surroundings'=>array('coordinate_x'=>$coord['coordinate_x'],
							'coordinate_y'=>$coord['coordinate_y'],
							'type'=>$type
					)
					);
					$this->create();
					$this->save($datas);
					$this->genWarnings($type,$coord);
					break;
				default : ;
			}
		}
	}

	public function genWarnings($type,$coord){

		$warning = array('Surroundings'=>array('coordinate_x'=>0,'coordinate_y'=>0,'type'=>'warning_'.$type));
		$i = 0;
		$j = 0;

		for($k=0;$k<4;$k++){

			switch($k){
				case 0: $i=-1;
					$j=0;
					break;
				case 1: $i=0;
					$j=1;
					break;
				case 2: $i=1;
					$j=0;
					break;
				case 3: $i=0;
					$j=-1;
					break;
				default: ;
			}
			if(($coord['coordinate_x']+$i>=0)&&($coord['coordinate_x']+$i<MAPLIMITX)&&($coord['coordinate_y']+$j>=0)&&($coord['coordinate_y']+$j<MAPLIMITY)){
				$warning['Surroundings']['coordinate_x'] = $coord['coordinate_x']+$i;
				$warning['Surroundings']['coordinate_y'] = $coord['coordinate_y']+$j;

				$datas = array('Surroundings'=>array('type'=>$warning['Surroundings']['type'],
						'coordinate_x'=>$warning['Surroundings']['coordinate_x'],
						'coordinate_y'=>$warning['Surroundings']['coordinate_y']
				)
				);
				$this->create();
				$this->save($datas);
			}
			$i++;
		}
	}

	public function genMap(){
		$ratio = MAPLIMITX*MAPLIMITY/10;

		for($i=0;$i<$ratio;$i++){
			$this->generate('trap');
			$this->generate('wall');
		}

		$this->generate('mob');

	}

}
?>