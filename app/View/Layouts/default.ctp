<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'WebArena le site de jeu de combat d\'arène multijoueurs');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php

    echo $this->Html->charset();
    echo $this->fetch('meta');
    echo $this->Html->meta('icon');?>
    <meta name="author" content="Alexis Pambourg Nicolas Bouvet Paul Cabellan">
    <title>
        <?php
            echo $cakeDescription;
            echo $this->fetch('title'); ?>
    </title>
    <?php
		/*Décommenter et supprimer cake.generic pour passez au CSS Bootstrap
		 * echo $this->Html->css(array('bootstrap.min', 'font-awesome.min'));*/
        echo $this->Html->css('cake.generic');
        echo $this->Html->script(array('bootstrap.min','jquery-1.11.3.min'));
		echo $this->fetch('css');

	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php echo $cakeDescription; ?></h1>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false, 'id' => 'cake-powered')
				);
			?>
			<p>
				<?php echo $cakeVersion; ?>
			</p>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
    <?php echo $this->fetch('script');?>
</body>
</html>
