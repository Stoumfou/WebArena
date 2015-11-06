<?php
define('FACEBOOK_SDK_V4_SRC_DIR','../../vendor/fr/src/Facebook/');
// Facebook PHP SDK v4.0.8
// path of these files have changes
require_once( '../../vendor/fb/src/Facebook/HttpClients/FacebookHttpable.php' );
require_once( '../../vendor/fb/src/Facebook/HttpClients/FacebookCurl.php' );
require_once( '../../vendor/fb/src/Facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( '../../vendor/fb/src/Facebook/Entities/AccessToken.php' );
require_once( '../../vendor/fb/src/Facebook/Entities/SignedRequest.php' );
// other files remain the same
require_once( '../../vendor/fb/src/Facebook/FacebookSession.php' );
require_once( '../../vendor/fb/src/Facebook/FacebookRedirectLoginHelper.php' );
require_once( '../../vendor/fb/src/Facebook/FacebookRequest.php' );
require_once( '../../vendor/fb/src/Facebook/FacebookResponse.php' );
require_once( '../../vendor/fb/src/Facebook/FacebookSDKException.php' );
require_once( '../../vendor/fb/src/Facebook/FacebookRequestException.php' );
require_once( '../../vendor/fb/src/Facebook/FacebookOtherException.php' );
require_once( '../../vendor/fb/src/Facebook/FacebookAuthorizationException.php' );
require_once( '../../vendor/fb/src/Facebook/GraphObject.php' );
require_once( '../../vendor/fb/src/Facebook/GraphSessionInfo.php' );
require_once( '../../vendor/fb/src/Facebook/GraphUser.php' );

// path of these files have changes
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
use Facebook\GraphUser;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

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

    /**
     * Facebook Login
     */

    public function fblogin()
    {
        $this->autoRender = false;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        FacebookSession::setDefaultApplication(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);
        $helper = new FacebookRedirectLoginHelper(FACEBOOK_REDIRECT_URI);
        $url = $helper->getLoginUrl(array('email'));
        $this->redirect($url);
    }

    public function fb_login()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->layout = 'ajax';
        FacebookSession::setDefaultApplication(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);
        $helper = new FacebookRedirectLoginHelper(FACEBOOK_REDIRECT_URI);
        $session = $helper->getSessionFromRedirect();

        if(isset($_SESSION['token'])){
            $session = new FacebookSession($_SESSION['token']);
            try{
                $session->validate(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);
            }catch(FacebookAuthorizationException $e){
                echo $e->getMessage();
            }
        }

        $data = array();
        $fb_data = array();

        if(isset($session)){
            $_SESSION['token'] = $session->getToken();
            $request = new FacebookRequest($session, 'GET', '/me');
            $response = $request->execute();
            $graph = $response->getGraphObject(GraphUser::className());

            $fb_data = $graph->asArray();
            $id = $graph->getId();
            $image = "https://graph.facebook.com/".$id."/picture?width=100";

            if( !empty( $fb_data )){
                $result = $this->User->findByEmail( $fb_data['email'] );
                if(!empty( $result )){
                    if($this->Auth->login($result['User'])){
                        $this->Session->setFlash(FACEBOOK_LOGIN_SUCCESS, 'default', array( 'class' => 'message success'), 'success' );
                        $this->redirect(BASE_PATH);
                    }else{
                        $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array( 'class' => 'message error'), 'error' );
                        $this->redirect(BASE_PATH.'login');
                    }

                }else{
                    $data['email'] = $fb_data['email'];
                    $data['first_name'] = $fb_data['first_name'];
                    $data['social_id'] = $fb_data['id'];
                    $data['picture'] = $image;
                    $data['uuid'] = String::uuid ();
                    $this->User->save( $data );
                    if($this->User->save( $data )){
                        $data['id'] = $this->User->getLastInsertID();
                        if($this->Auth->login($data)){
                            $this->Session->setFlash(FACEBOOK_LOGIN_SUCCESS, 'default', array( 'class' => 'message success'), 'success' );
                            $this->redirect(BASE_PATH);
                        }else{
                            $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array( 'class' => 'message error'), 'error' );
                            $this->redirect(BASE_PATH.'index');
                        }

                    }else{
                        $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array( 'class' => 'message error'), 'error' );
                        $this->redirect(BASE_PATH.'index');
                    }
                }




            }else{
                $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array( 'class' => 'message error'), 'error' );
                $this->redirect(BASE_PATH.'index');
            }


        }
    }
    public function beforeFilter()
    {
        $this->Auth->allow('fblogin', 'fb_login');
        parent::beforeFilter();
    }
}
?>