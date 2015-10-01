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
    /*
     *Page d'accueil
	 *
	 *Permet l'accès à la connexion et l'inscription hors connexion
     * 
	 *Permet la déconnexion et la navigation vers les autres pages en temps que connecté
     */
    public function index(){	
	
        if($this->Auth->loggedIn())$this->set('myname', strtok($this->User->findById($this->Auth->user('id'))['User']['email'],'@'));
		else $this->set('myname', "toi petit troll");
    }
	
	/*
	 *Page Combattant
	 *
	 *Permet de créer de nouveau combattant et de voir les caractéristiques des combattants liés au compte
	 *
	 */
	
	public function fighter(){
		
		//Récupération de la liste des noms des Fighter du User connecté
		$this->set('fighters',$this->Fighter->getFighterNameByUser($this->Auth->user('id')));
		$this->set('raw','Séléctioner un Combattant.');
		if ($this->request->is('post')){
			//Affichage des charactéristique du Fighter Séléctioné
			if(array_key_exists('FighterChoose',$this->request->data))$this->set('raw',$this->Fighter->getFighterByUserAndName($this->Auth->user('id'),$this->request->data['FighterChoose']['Combattant']));
			//Création d'un nouveau Fighter avec un nom fournis par le User
			else if (array_key_exists('FighterCreate',$this->request->data)){
				//Création de l'Event d'arrivée dans l'arène
				$event = $this->Fighter->spawn($this->Auth->user('id'),$this->request->data['FighterCreate']['Nom']);
				//Message si l'arène est pleine et le Fighter n'a pas été créé
				if($event != null)$this->Event->record($event);
				else echo('Désolé, l\'arène est pleine ! Vous ne pouvez pas créer de nouveau combattant.');
			}
		}	
	}
	
	/*
	 *Page Vue
	 *
	 *Permet d'effectuer des actions avec les combattants liés au compte
	 *
	 * Déplacement - Attaque
	 *
	 */
	public function sight(){
		//Récupération de la liste des noms des Fighter du User connecté
		$this->set('fighters',$this->Fighter->getFighterNameByUser($this->Auth->user('id')));
		
		if ($this->request->is('post')){
			if(array_key_exists('FighterMove',$this->request->data))
				//Action de déplacement, création de l'Event correspondant
				$this->Event->record($this->Fighter->doMove(
										$this->Fighter->getFighterByUserAndName($this->Auth->user('id'),$this->request->data['FighterMove']['Combattant'])['Fighter']['id'],
										$this->request->data['FighterMove']['direction'])
									);
			else if(array_key_exists('FighterAttack',$this->request->data))
				//Action d'attaque, création de l'Event correspondant
					$this->Event->record($this->Fighter->doAttack(
												$this->Fighter->getFighterByUserAndName($this->Auth->user('id'),$this->request->data['FighterAttack']['Combattant'])['Fighter']['id'],
												$this->request->data['FighterAttack']['direction'])
											);
			pr($this->request->data);
		}
		 // $this->set('raw',$this->Fighter->getFightersByUser($this->Auth->user('id')));
		 // pr($this->Fighter->getFightersByUser($this->Auth->user('id')));
	}
	
	/*
	 *Page de Journal
	 *
	 *Permet de voir les événements passés dans l'arène
	 *
	 */
	public function diary(){
		$this->set('raw',$this->Event->find());
	}
}
?>
