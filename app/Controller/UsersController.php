<?php
define('FACEBOOK_SDK_V4_SRC_DIR', '../../vendor/fr/src/Facebook/');
// Facebook PHP SDK v4.0.8
// path of these files have changes
require_once('../../vendor/fb/src/Facebook/HttpClients/FacebookHttpable.php');
require_once('../../vendor/fb/src/Facebook/HttpClients/FacebookCurl.php');
require_once('../../vendor/fb/src/Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('../../vendor/fb/src/Facebook/Entities/AccessToken.php');
require_once('../../vendor/fb/src/Facebook/Entities/SignedRequest.php');
// other files remain the same
require_once('../../vendor/fb/src/Facebook/FacebookSession.php');
require_once('../../vendor/fb/src/Facebook/FacebookRedirectLoginHelper.php');
require_once('../../vendor/fb/src/Facebook/FacebookRequest.php');
require_once('../../vendor/fb/src/Facebook/FacebookResponse.php');
require_once('../../vendor/fb/src/Facebook/FacebookSDKException.php');
require_once('../../vendor/fb/src/Facebook/FacebookRequestException.php');
require_once('../../vendor/fb/src/Facebook/FacebookOtherException.php');
require_once('../../vendor/fb/src/Facebook/FacebookAuthorizationException.php');
require_once('../../vendor/fb/src/Facebook/GraphObject.php');
require_once('../../vendor/fb/src/Facebook/GraphSessionInfo.php');
require_once('../../vendor/fb/src/Facebook/GraphUser.php');

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

class UsersController extends AppController
{


    public function index()
    {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function forgotten()
    {

    }

    public function view($id = null)
    {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Joueur invalide'));
        }
        $this->set('user', $this->User->findById($id));
    }

    /*
     *Méthode de création d'un nouvel utilisateur
     */
    public function register()
    {
        if ($this->request->is('post')) {
            if ($this->isValid($_POST['g-recaptcha-response']) == true) {
                if ($this->request->data['User']['pass1'] == $this->request->data['User']['pass2']) {
                    $this->request->data['User']['password'] = $this->request->data['User']['pass1'];
                    $this->User->create();
                    if ($this->User->save($this->request->data)) {
                        $this->Flash->success(__('Le joueur a été sauvegardé'));
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
                $this->Session->setFlash('Les mots de passe ne correspondent pas, merci de réessayer', 'default', array('class' => 'alert alert-danger'));
            }
            $this->Session->setFlash('Le catcha n\'a pas été coché, merci de réessayer', 'default', array('class' => 'alert alert-danger'));
        }
    }

    public function edit()
    {
        if (!empty($this->data)) {
            if ($this->request->data['User']['pass1'] == $this->request->data['User']['pass2']) {
                $this->request->data['Users']['password'] = $this->request->data['User']['pass1'];

                $email = $this->Auth->user('email');
                $id = $this->Auth->user('id');
                $datas = array('User' => array(
                    'id' => $id,
                    'email' => $email,
                    'password' => $this->request->data['Users']['password'])
                );
                //pr($datas);
                if ($this->User->save($datas)) {
                    $this->Session->setFlash('Le mot de passe a été changé.', 'default', array('class' => 'alert alert-success'));
                    return $this->redirect(array('action' => '../Arenas/index'));
                } else {
                    $this->Session->setFlash('Le mot de passe n\'a pas été changé.', 'default', array('class' => 'alert alert-danger'));
                }
            } else {
                $this->data = $this->User->findById($this->Auth->user('id'));
            }
        }
    }

    /*
     *Méthode de suppresion d'un utilisateur
     */
    public function delete($id = null)
    {

        // $id = $this->Auth->user('id'));

        if ($this->request->is('get')) {
            $this->request->allowMethod('get');

            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__('Joueur invalide'));
            }
            if ($this->User->delete()) {
                $this->logout();
                $this->Flash->success(__('Joueur supprimé'));
                return $this->redirect($this->Auth->logout());
            }
            $this->Flash->error(__('Le joueur n\'a pas été supprimé'));
            return $this->redirect('../Arenas/index');
        }
    }

    /*
     *Méthode d'authentification
     */
    public function login()
    {
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
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    /*
     * Google ReCaptcha
     */
    public function isValid($code)
    {
        if (empty($code)) {
            return false;
        }
        $params = [
            'secret' => '6Les8BATAAAAAKXW1xHGIIfGGm7u2M3WeRvG53m0',
            'response' => $code
        ];
        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($params);
        $response = file_get_contents($url);

        if (empty($response) || is_null($response)) {
            return false;
        }

        $json = json_decode($response);
        return $json->success;
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


        if (isset($_SESSION['token'])) {
            $session = new FacebookSession($_SESSION['token']);
            try {
                $session->validate(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);
            } catch (FacebookAuthorizationException $e) {
                //echo $e->getMessage();
                $session = '';
            }
        }

        $data = array('email' => '');
        //$data = array();
        $fb_data = array();

        if (isset($session)) {
            $_SESSION['token'] = $session->getToken();
            /*// SessionInfo
            $info = $session->getSessionInfo();
            // getAppId
            echo "Appid: " . $info->getAppId() . "<br />";
            // session expire data
            $expireDate = $info->getExpiresAt()->format('Y-m-d H:i:s');
            echo 'Session expire time: ' . $expireDate . "<br />";
            // session token
            echo 'Session Token: ' . $session->getToken() . "<br />";*/
            $request = new FacebookRequest($session, 'GET', '/me');
            $response = $request->execute();
            $graph = $response->getGraphObject();

            $fb_data = $graph->asArray();
            var_dump($graph);
            $id = $graph->getId();
            $image = "https://graph.facebook.com/" . $id . "/picture?width=100";

            if (!empty($fb_data)) {
                $result = $this->User->findByEmail($fb_data['email']);
                if (!empty($result)) {
                    if ($this->Auth->login($result['User'])) {
                        $this->Session->setFlash(FACEBOOK_LOGIN_SUCCESS, 'default', array('class' => 'message success'), 'success');
                        $this->redirect(BASE_PATH);
                    } else {
                        $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array('class' => 'message error'), 'error');
                        $this->redirect(BASE_PATH . 'login');
                    }

                } else {
                    $data['email'] = $fb_data['email'];
                    /* $data['first_name'] = $fb_data['first_name'];
                     $data['social_id'] = $fb_data['id'];
                     $data['picture'] = $image;
                     $data['uuid'] = String::uuid ();*/
                    $this->User->save($data);
                    if ($this->User->save($data)) {
                        $data['id'] = $this->User->getLastInsertID();
                        if ($this->Auth->login($data)) {
                            $this->Session->setFlash(FACEBOOK_LOGIN_SUCCESS, 'default', array('class' => 'message success'), 'success');
                            $this->redirect(BASE_PATH);
                        } else {
                            $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array('class' => 'message error'), 'error');
                            $this->redirect(BASE_PATH . 'index');
                        }

                    } else {
                        $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array('class' => 'message error'), 'error');
                        $this->redirect(BASE_PATH . 'index');
                    }
                }


            } else {
                $this->Session->setFlash(FACEBOOK_LOGIN_FAILURE, 'default', array('class' => 'message error'), 'error');
                $this->redirect(BASE_PATH . 'index');
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