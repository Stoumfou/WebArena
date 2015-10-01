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
   
   /*
    *Méthode de récupération de tout les combattant d'un joueur
    *Reçoit un id de User en string et retourne un array contenant tout ses Fighter en array
    */
   public function getFightersByUser($user_id){
		return $this->find('all',array('conditions'=>array('player_id'=>$user_id)));
   }
   
   /*
    *Méthode de récupération d'un combattant par son nom et l'id de son joueur
    *Reçoit un nom de Fighter et un id de User en string et retourne un combattant en array
    */
   public function getFighterByUserAndName($user_id,$name){
	   return $this->find('first',array('conditions'=>array('player_id'=>$user_id,'Fighter.name'=>$name)));
   }
   
   /*
    *Méthode de récupération d'un combattant par son nom
    *Reçoit un nom de Fighter en string et retourne un combattant
    */
   public function getFighterByName($name){
	   return $this->findByName($name);
   }
   
   /*
    *Méthode de récupération de la liste des noms des combattants d'un joueur
    *Reçoit un id de User en string et retourne un array listant les noms de ses combattans
    */
   public function getFighterNameByUser($user_id){
	   $result = array();
	   $fighters = $this->find('all',array('conditions'=>array('player_id'=>$user_id)));
	   
	   foreach($fighters as $fighter){
		   $result = array_merge($result,array($fighter['Fighter']['name']=>$fighter['Fighter']['name']));
	   }
	   return $result;
   }
   /*
    *Methode de création de vecteur en fonction d'une direction
    *Reçoit une direction en string et retourne un array contenant deux entiers x et y
    */
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
	
   /*
    *Méthode déterminant si un combattant est sur une case ciblé par un autre
    *Reçoit l'id du Fighter en action et la direction de celle-ci en string
    *Retourne -1 si la case cible est hors de l'arène, 0 si la case est vide et retourne le combattant sur la case si elle est occupée
    */
   public function isThere($fighterId, $vector){
		$player = $this->find('first', array('conditions'=>array('Fighter.id'=>$fighterId)));
		$target = array();
		$result = -1;
		
		//Vérification que la case cible est dans l'arène
		if((($player['Fighter']['coordinate_x']+$vector['x'])>0)&&
		(($player['Fighter']['coordinate_x']+$vector['x'])<=MAPLIMIT)&&
		(($player['Fighter']['coordinate_y']+$vector['y'])>0)&&
		(($player['Fighter']['coordinate_y']+$vector['y'])<=MAPLIMIT)){
			//Vérification de la présence d'un combattant sur la case cible
			$target = $this->find('all',array('conditions'=>array('coordinate_x'=>($player['Fighter']['coordinate_x']+$vector['x']),'coordinate_y'=>($player['Fighter']['coordinate_y']+$vector['y']))));
		}else $result = -2;
		
		//Vérification du résultat de l'appel $this->find
		if(count($target) == 0)$result++;
		else $result= $target[0];
	   
	   return $result;
   }
   
   /*
    *Méthode action de déplacement d'un combattant
    *Reçoit un id de Fighter et une direction en string
    *Retourne un Event en array avec des valeurs nom, coordinate_x et coordinate_y initialisées
    */
   public function doMove($fighterId, $direction){
		//Initialisation de l'Event
		$event = array('name'=>'','coordinate_x'=>0,'coordinate_y'=>0);
	   
		//Récupération du Fighter en action
		$player = $this->find('first', array('conditions'=>array('Fighter.id'=>$fighterId)));
		//Mention du Fighter en action dans l'Event
		$event['name'] .= $player['Fighter']['name']." se deplace ";
		//Détermination du vecteur mouvement à partir de la direction choisi par le joueur
		$vector = $this->vector($direction);
		//Ajout de la case cible dans l'Event
		$event['coordinate_x'] = $player['Fighter']['coordinate_x']+$vector['x'];
		$event['coordinate_y'] = $player['Fighter']['coordinate_y']+$vector['y'];
		
		//Vérification de la présence d'un autre Fighter à la destination
		if($this->isThere($fighterId, $vector) == 0){
			//Enregistrement du mouvement en DB
			$datas = array('Fighter'=>array('id'=>$fighterId,'coordinate_y'=>$player['Fighter']['coordinate_y'] + $vector['y'],'coordinate_x'=>$player['Fighter']['coordinate_x'] + $vector['x']));
			$this->save($datas);
		//Mention de l'échec de l'action dans l'Event
		}else $event['name'] .= 'mais est bloque';
		
		return $event;
	}
	
	/*
    *Méthode action d'attaque d'un combattant
    *Reçoit un id de Fighter et une direction en string
    *Retourne un Event en array avec des valeurs nom, coordinate_x et coordinate_y initialisées
    */
	public function doAttack($fighterId, $direction){
		//Initialisation de l'Event
		$event = array('name'=>'','coordinate_x'=>0,'coordinate_y'=>0);
		
		//Récupération du Fighter en action
		$player = $this->find('first', array('conditions'=>array('Fighter.id'=>$fighterId)));
		//Mention du Fighter en action dans l'Event
		$event['name'] .= $player['Fighter']['name']." attaque ";
		//Détermination du vecteur d'attaque à partir de la direction choisi par le joueur
		$vector = $this->vector($direction);
		//Ajout de la case cible dans l'Event
		$event['coordinate_x'] = $player['Fighter']['coordinate_x']+$vector['x'];
		$event['coordinate_y'] = $player['Fighter']['coordinate_y']+$vector['y'];
		
		//Vérification de la présence d'un Fighter sur la case cible
		$defenser = $this->isThere($fighterId, $vector);
		
		//Si un Fighter est trouvé comme "Attaqué"
		if(is_array($defenser)){
			$result = true;
			
			//Jet de tentative d'attaque
			$rand = rand(1,20);
			// echo $rand;
			
			//Mention du Fighter attaqué dans l'event
			$event['name'] .= $defenser['Fighter']['name']." et le ";
			
			//Si le jet d'attaque est supérieur à 10 plus la différence de niveau des deux joueurs, l'attaque réussie
			if($rand>(10+$defenser['Fighter']['level']-$player['Fighter']['level'])){
				//Enregistrement de la blessure en DB
				$datas = array('Fighter'=>array('id'=>$defenser['Fighter']['id'],'current_health'=>($defenser['Fighter']['current_health'] - $player['Fighter']['skill_strength'])));
				$this->save($datas);
				
				//Mention de la réussite de l'attaque dans l'Event
				$event['name'] .= "touche";
			//Mention de l'échec de l'attaque dans l'Event
			}else $event['name'] .= "rate";
		}else $event['name'] .= "dans le vide";
		
		return $event;
	}
	
	/*
	 *Méthode de création d'un nouveau combattant
	 *Reçoit un id de User et un nom de Fighter en string
	 *Retourne un Event en array avec des valeurs nom, coordinate_x et coordinate_y initialisées
	 */
	public function spawn($userId,$name){
		//Initialisation de l'Event
		$event = array('name'=>'Entree de ','coordinate_x'=>0,'coordinate_y'=>0);
		
		//Mention du nouveau Fighter dans l'Event
		$event['name'] .= $name;
		
		//Initialisation des coordonnées de spawn
		$coord = array('coordinate_x'=>0,'coordinate_y'=>0);
		$tried = array();
		$freeSpot = false;
		
		//Temps qu'un emplacement libre n'est pas trouvé
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
				$event = null;
				break;
			}
		}
		
		//Si la dernière case testée est marquée libre
		if($freeSpot){
			//Enregistrement du noveau Fighter
			$datas = array('Fighter'=>array('player_id'=>$userId,'name'=>$name,'coordinate_x'=>$coord['coordinate_x'],'coordinate_y'=>$coord['coordinate_y']));
			$this->save($datas);
			//Ajout de la l'emplacement du spawn dans l'Event
			$event['coordinate_x'] = $coord['coordinate_x'];
			$event['coordinate_y'] = $coord['coordinate_y'];
		}
		return $event;
	}
	
}
?>