<?php
    $this->Html->meta('description','Connexion/Inscription', array('inline' => false));
?>


<!--
    <fieldset>
        <legend>
            <?php //echo __('Please enter your username and password'); ?>
        </legend>
        <?php echo $this->Form->input('email');
        echo $this->Form->input('password');
    ?>
    </fieldset>-->


<!--<div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="true" data-auto-logout-link="true"></div>-->




<div class="container">
<div class="row">
<div class="col-xs-12 col-md-12 col-lg-12 ">
    
    <div class="main">     
        <div class="row">

 
        <div class="col-xs-6 col-sm-6 col-lg-6">
                    
            <h1>Connexion</h1>
            
            <?php echo $this->Flash->render('auth'); ?>
            <?php echo $this->Form->create('User', array(
'class' => 'form-horizontal', 
'role' => 'form',
'inputDefaults' => array(
    'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
    'div' => array('class' => 'form-group'),
    'class' => array('form-control'),
    'label' => array('class' => 'col-md-5 col-lg-5 control-label'),
    'between' => '<div class="col-md-5 col-lg-5">',
    'after' => '</div>',
    'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
))); ?>
<fieldset>
    <legend><?php echo __('Please enter your username and password'); ?></legend>
    <?php echo $this->Form->input('email'); 
     echo $this->Form->input('password'); ?>
</fieldset>
    <div class="col-md-offset-0 col-md-6"><input  class="btn btn-success btn btn-success" type="submit" value="Login"/><a class="btn btn-default facebook" href="<?php echo BASE_PATH.'fblogin'; ?>"> <i class="fa fa-facebook modal-icons"> Sign in with Facebook</i></a> 
            <a href="forgotten">Mot de passe oublié</a>
            </div>
                </div> 
            
                        
        <div class="col-xs-6 col-sm-6 col-lg-6">
                    
            <h1>Inscription</h1>
            
            <?php echo $this->Flash->render('auth'); ?>
            <?php echo $this->Form->create('RegisterUser', array(
'class' => 'form-horizontal', 
'role' => 'form',
'inputDefaults' => array(
    'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
    'div' => array('class' => 'form-group'),
    'class' => array('form-control'),
    'label' => array('class' => 'col-md-5 col-lg-5 control-label'),
    'between' => '<div class="col-md-5 col-lg-5">',
    'after' => '</div>',
    'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
))); ?>
<fieldset>
    <legend><?php echo __('Please enter your username and password'); ?></legend>
    <?php echo $this->Form->input('email'); ?>
    <?php echo $this->Form->input('password'); ?>
</fieldset>
    <div class="col-md-offset-0 col-md-6"><input  class="btn btn-success btn btn-success" type="submit" value="Register"/><a class="btn btn-default facebook" href="<?php echo BASE_PATH.'fblogin'; ?>"> <i class="fa fa-facebook modal-icons"> Sign in with Facebook</i></a>
            </div>
            </div>
        </div>  
        
        
    </div>
        
    </div>
</div>
</div>