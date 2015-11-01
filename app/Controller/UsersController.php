<?php
/*define ('FACEBOOK_SDK_V4_SRC_DIR', '../../vendor/facebook/php-sdk-v4/src/Facebook');
require_once("../../vendor/autoload.php");

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;*/

class UsersController extends AppController {

	
    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Joueur invalide'));
        }
        $this->set('user', $this->User->findById($id));
    }

	/*
	 *Méthode de création d'un nouvel utilisateur
	 */
    public function register() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                //$this->Flash->success(__('Le joueur a été sauvegardé'));
                $id = $this->User->id;
                $this->request->data['User'] = array_merge(
                    $this->request->data['User'],
                    array('id' => $id)
                );
                unset($this->request->data['User']['password']);
                $this->Auth->login($this->request->data['User']);
                 return $this->redirect(array('action' => '../Arenas/index'));
            } else {
                $this->Flash->error(__('Le joueur n\'a pas été sauvegardé. Merci de réessayer.'));
            }
        }
    }

	/*
	 *Méthode de modification d'un utilisateur
	 */
    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Joueur Invalide'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('Le joueur a été sauvegardé'));
                return $this->redirect(array('action' => '../Arenas/index'));
            } else {
                $this->Flash->error(__('Le joueur n\'a pas été sauvegardé. Merci de réessayer.'));
            }
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
    }

	/*
	 *Méthode de suppresion d'un utilisateur
	 */
    public function delete($id = null) {

        $this->request->allowMethod('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Joueur invalide'));
        }
        if ($this->User->delete()) {
            $this->Flash->success(__('Joueur supprimé'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('Le joueur n\'a pas été supprimé'));
        return $this->redirect(array('action' => 'index'));
    }
	
	/*
	 *Méthode d'authentification
	 */
	public function login(){
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect('../Arenas/index');
			} else {
				$this->Flash->error(__("Nom d'user ou mot de passe invalide"));
			}
		}
	}
	
	/*
	 *Méthode de déconnexion
	 */
	public function logout() {
		return $this->redirect($this->Auth->logout());
	}


    /*
     * Facebook Login
     *         //Fonctionne a peu près


    public function fblogin()
    {

        if (session_status() == PHP_SESSION_NONE){
            session_start();
        }
        $fb = new \Facebook\Facebook([
            'app_id' => '1720702151482399',
            'app_secret' => '498d1d995ef2a182e5f1760734ad57b6',
            'default_graph_version' => 'v2.4',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // optional
        $loginUrl = $helper->getLoginUrl(FACEBOOK_REDIRECT_URI, $permissions);
        $this->redirect($loginUrl);*/


        /*
         *Fonction pas du tout
         *
         *
         *
         * $this->autoRender = false;
        if (session_status() == PHP_SESSION_NONE){
            session_start();
        }
        FacebookSession::setDefaultApplication(FACEBOOK_APP_ID,FACEBOOK_APP_SECRET);
        $helper = new FacebookRedirectLoginHelper(FACEBOOK_REDIRECT_URI);
        $url = $helper->getLoginUrl(array('email'));
        $session = $helper->getSessionFromRedirect();
        $_SESSION['fb_token'] = $session->getToken();
        $request = new FacebookRequest($session,'GET','/me');
        $profile = $request->execute()->getGraphObject();

        $this->redirect($url);*/
        /*
         *
         *
         *
         *
         *
        if(isset($_SESSION) && isset ($_SESSION['fb_token']))
        {
            $session = new FacebookSession($_SESSION['fb_token']);
        }else{
            $session = $helper->getSessionFromRedirect();
        }
        if($session)
        {
            $_SESSION['fb_token'] = $session->getToken();
            $request = new FacebookRequest($session,'GET','/me');
            $profile = $request->execute()->getGraphObject();
            define('TRUC',$profile);

        }else{
            $url = $helper->getLoginUrl());
        }
        $session = $helper->getSessionFromRedirect();
        $_SESSION['fb_token'] = $session->getToken();*/

/*
    }

    public function fb_login()
    {
        $this->layout = 'ajax';
        FacebookSession::setDefaultApplication(FACEBOOK_APP_ID,FACEBOOK_APP_SECRET);
        $helper = new FacebookRedirectLoginHelper(FACEBOOK_REDIRECT_URI);
    }

    public function beforeFilter()
    {
        $this->Auth->allow('fblogin','fb_login');
        parent::beforeFilter();
    }*/
}
?>