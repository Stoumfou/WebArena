<?php

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
                $this->Flash->success(__('Le joueur a été sauvegardé'));
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

}
?>