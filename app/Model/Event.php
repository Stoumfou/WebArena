<?php
App::uses('AppModel', 'Model');

class Event extends AppModel {
	
	
	/*
	 *Méthode de création d'un événement
	 *Reçoit un Event en array contenant un nom et des coordonnées initialisés
	 */
	public function record($event){
		
		//Création d'un objet Event
		$this->create();
		//Enregistrement de l'objet en base avec les paramètres reçu en valeurs d'attributs
		$datas = array('Event'=>array(
										'name'=>$event['name'],
										'coordinate_x'=>$event['coordinate_x'],
										'coordinate_y'=>$event['coordinate_y'],
										'date'=>date('Y-m-d H:i:s', strtotime("now"))
										)
						);
		$this->save($datas);
	}
	
	/*
	 *Méthode de récupération d'un Event convertie en string
	 *Reçoit un id d'Event et retourne l'Event convertie en string
	 */
	public function getEventToString($id){
		$result = "";
		
		//Récupération de l'Event par l'id en paramètre
		$event = $this->findById($id);
		
		//Concatenation des informations de l'Event
		$result .= "[".$event['Event']['date']."]:";
		$result .= $event['Event']['name'];
		$result .= " en (".$event['Event']['coordinate_x'].",".$event['Event']['coordinate_y'].")";
		
		return $result;
	}
	
	public function getEventList($coord, $range){
		
		$res = array();
		
		$topleft = array('coord_x'=>$coord['coord_x']-$range,'coord_y'=>$coord['coord_y']+$range);
		for($i=0;$i<(($range*2)+1);$i++){
			for($j=0;$j<(($range*2)+1);$j++){
				$event = $this->find('all',array('conditions'=>array('coordinate_x'=>$topleft['coord_x']+$i,'coordinate_y'=>$topleft['coord_y']-$j)));
				if(count($event) !=0){
					foreach($event as $event){
						if(strtotime("now") - strtotime($event['Event']['date']) <86400)array_push($res,$event);
					}
				}
			}
		}
		
		return $res;
	}
	
}

?>