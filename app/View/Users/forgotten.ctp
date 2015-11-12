<?php
/**
 * Created by PhpStorm.
 * User: Alexis
 * Date: 06/11/2015
 * Time: 13:55
 */
$this->Html->meta('description', 'Mot de passe oublié', array('inline' => false));
?>
<div class="container">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h1>Dommage</h1></div>
                </div>
                <div class="panel-body">
                    <?php echo $this->Html->image("forgotten.jpg", array(
                            "alt" => "Forgotten")
                    ); ?>
                </div>
            </div>
        </div>
    </div>