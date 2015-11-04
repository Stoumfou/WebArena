<?php
    $this->Html->meta('description','Connexion/Inscription', array('inline' => false));
?>
<h1>Connexion/Inscription</h1>
<a href="../Arenas/index">Accueil</a>
<?php var_dump($_SESSION) ;?>

<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>
            <?php echo __('Please enter your username and password'); ?>
        </legend>
        <?php echo $this->Form->input('email');
        echo $this->Form->input('password');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>

<a class="btn btn-default facebook" href="<?php echo BASE_PATH.'fblogin'; ?>"> <i class="fa fa-facebook modal-icons"></i> Signin with Facebook</a>

<!--<div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="true" data-auto-logout-link="true"></div>-->

<a href="forgotten">Mot de passe oublié</a>

<a href="register">Pas encore inscrit ?</a>




