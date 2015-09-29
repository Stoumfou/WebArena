<?php
App::uses('AppModel', 'Model');

class User extends AppModel {

    public $useTable = 'players';
	
    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Un e-mail est requis'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Un mot de passe est requis'
            )
        )
    );
}
?>