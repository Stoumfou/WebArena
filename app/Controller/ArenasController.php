<?php 

App::uses('AppController', 'Controller');

/**
 * Main controller of our small application
 *
 * @author ...
 */
class ArenasController extends AppController
{
	public $uses = array('Player', 'Fighter', 'Event');
    /**
     * index method : first page
     *
     * @return void
     */
    public function index()
    {
        $this->set('myname', "Toi petit troll");
    }
	
	public function login(){
		
        $this->set('myname', "Toi petit troll");
	}
	
	public function fighter(){
		
        $this->set('raw',$this->Fighter->findById(1));
	}
	
	public function sight(){
		
		if ($this->request->is('post')){
			if(array_key_exists('FighterMove',$this->request->data))$this->Fighter->doMove(1, $this->request->data['FighterMove']['direction']);
			else if(array_key_exists('FighterAttack',$this->request->data))$this->Fighter->doAttack(1, $this->request->data['FighterAttack']['direction']);
			pr($this->request->data);
		}
		$this->set('raw',$this->Fighter->find('all'));
	}
	
	public function diary(){
		$this->set('raw',$this->Event->find());
	}
}
?>
