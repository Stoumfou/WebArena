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
	
}

?>