<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','register', 'login','forgotten');
        if($this->Auth->loggedIn())$this->set('myname', strtok($this->User->findById($this->Auth->user('id'))['User']['email'],'@'));
		else $this->set('myname', "futur grand guerrier");
    }

	public $components = array(
		'Session',
        'Flash',
        'Auth' => array(
			'controller' => 'users',
			'userModel' => 'User',
            'action' => 'login',
			'plugin' => 'users',
			'loginAction' => array(
				'controller' => 'Users', 
				'action' => 'login'),
			'loginRedirect' => array(
				'controller' => 'Arenas',
				'action' => 'index'),
			'logoutRedirect' => array(
				'controller' => 'Users',
				'action' => 'login'),
			
			'authenticate' => array(
				'Form' => array(
					'fields' => array(
						'username' => 'email', // 'username' par défaut
						'password' => 'password'  // 'password' par défaut
					),
					'passwordHasher' => 'Blowfish'
				)
			)
        )
    );
}
?>