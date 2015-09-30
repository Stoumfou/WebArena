<?php 

App::uses('AppController', 'Controller');

/**
 * Main controller of our small application
 *
 * @author ...
 */
class ArenasController extends AppController
{
	public $uses = array('User', 'Fighter', 'Event');
    /**
     * index method : first page
     *
     * @return void
     */
    public function index()
    {	
        if($this->Auth->loggedIn())$this->set('myname', strtok($this->User->findById($this->Auth->user('id'))['User']['email'],'@'));
		else $this->set('myname', "toi petit troll");
    }
	
	
	public function fighter(){
		pr($this->Auth->user('id'));
        $this->set('raw',$this->Fighter->find('first',array('condition'=>array('player_id'=>$this->Auth->user('id')))));
	}
	
	public function sight(){
		
		$this->set('fighters',$this->Fighter->choose($this->Auth->user('id')));
		
		if ($this->request->is('post')){
			if(array_key_exists('FighterMove',$this->request->data))
				$this->Fighter->doMove(
										$this->Fighter->getFighterByUserId($this->Auth->user('id'))['Fighter']['id'],
										$this->request->data['FighterMove']['direction']
									);
			else if(array_key_exists('FighterAttack',$this->request->data))
					$this->Fighter->doAttack(
												$this->Fighter->getFighterByUserId($this->Auth->user('id'))['Fighter']['id'],
												$this->request->data['FighterAttack']['direction']
											);
			pr($this->request->data);
		}
		$this->set('name',$this->Fighter->getFighterByUserId($this->Auth->user('id'))['Fighter']['name']);
		pr($this->Auth->user('id'));
	}
	
	public function diary(){
		$this->set('raw',$this->Event->find());
	}
}
?>
