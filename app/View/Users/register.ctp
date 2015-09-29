<a href="../Users/login">Connexion</a>
<?php echo $this->Form->create('User');?>
    <fieldset>
        <legend><?php echo __('Inscription'); ?></legend>
        <?php echo $this->Form->input('email');
        echo $this->Form->input('password');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Ajouter'));?>