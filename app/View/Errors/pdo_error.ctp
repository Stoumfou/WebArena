<?php $this->Html->meta('description', 'Error email already used', array('inline' => false));
?>
<div class="container">
    <div class="row">
        <div class="text-center">
            <h1>Cet e-mail est déjà utilisé</h1>
            <?php echo $this->Html->image("forgotten.jpg", array(
                "alt" => "Forgotten")
            ); ?>
        </div>
    </div>
</div>