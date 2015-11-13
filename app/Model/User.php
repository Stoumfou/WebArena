<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel
{

    public $useTable = 'players';

    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => 'email',
                'allowEmpty' => false,
                'message' => 'Un e-mail est requis'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => "Ce mail est déjà pris"
            )

        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Un mot de passe est requis'
            )
        )
    );

    public $hasMany = array(
        'Fighter' => array(
            'className' => 'Fighter',
            'foreignKey' => 'player_id'
        )
    );

    /*
    *Méthode de Hashage du mot de passe avant enregistrement en DB
    */
    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return true;
    }
}

?>