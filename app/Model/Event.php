<?php
App::uses('AppModel', 'Model');
App::uses('CakeTime', 'Utility');

class Event extends AppModel {
	
	public function record($event){
		
		$this->create();
		$datas = array('Event'=>array(
										'name'=>$event['name'],
										'coordinate_x'=>$event['coordinate_x'],
										'coordinate_y'=>$event['coordinate_y'],
										'date'=>CakeTime::i18nFormat(time(),null,false,new DateTimezone('Europe/Paris'))
										)
						);
		$this->save($datas);
	}
	
}

?>