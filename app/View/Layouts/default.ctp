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

$cakeDescription = __d('cake_dev', 'WebArena le site de jeu de combat d\'arène multi-joueurs');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <?php
    echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
    echo $this->Html->meta('fighter.jpg', '/fighter.jpg', array('type' => 'icon'));
    echo $this->Html->meta(array('name' => 'author', 'content' => 'Nicolas Bouvet Alexis Pambourg Paul Cabellan'));
    echo $this->Html->meta(array('name' => 'copyright', 'content' => 'Nicolas Bouvet, Alexis Pambourg, Paul Cabellan'));
    echo $this->Html->meta(array('name' => 'robots', 'content' => 'index, follow'));
    echo $this->Html->meta(array('name' => 'category', 'content' => 'play'));
    echo $this->Html->meta(array('name' => 'distribution', 'content' => 'global'));
    echo $this->Html->meta('keywords', 'WebArena Fighter Combattant Ece ProjetCakePhp');
    echo $this->Html->charset();
    echo $this->fetch('meta');
    ?>
    <title>
        <?php
        echo $cakeDescription;
        echo $this->fetch('title'); ?>
    </title>
    <?php
    //Décommenter cake.generic pour passez au CSS Bootstrap
    //echo $this->Html->css('cake.generic');
    echo $this->Html->css(array('bootstrap.min', 'font-awesome.min', 'grid', 'webarena'));
    echo $this->Html->script(array('jquery-2.1.4', 'FacebookLike', 'GoogleAnalytics', 'grid', 'bootstrap.min'));
    echo $this->fetch('css');
    echo $this->fetch('javascript');
    ?>
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet"
          type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
</head>
<body>
<!--<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>-->
<div id="mainContainer">
    <div id="content">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo $this->Html->image("Logo.png", array(
                        "alt" => "Logo",
                        'url' => array('controller' => 'Arenas', 'action' => 'index', '')
                    )); ?>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php if ($myname == 'futur grand guerrier') {
                        } else {
                            echo '<li>' . $this->Html->link('Combattant', array('controller' => 'Arenas', 'action' => 'fighter')) . '</li>';
                            echo '<li>' . $this->Html->link('Vision', array('controller' => 'Arenas', 'action' => 'sight')) . '</li>';
                            echo '<li>' . $this->Html->link('Journal', array('controller' => 'Arenas', 'action' => 'diary')) . '</li>';
                        }; ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if ($myname == 'futur grand guerrier') {
                            echo '<li>' . $this->Html->link('Connexion', array('controller' => 'Users', 'action' => 'login')) . '</li>';
                            echo '<li>' . $this->Html->link('Inscription', array('controller' => 'Users', 'action' => 'register')) . '</li>';
                        }else{
                            echo '<li>' . $this->Html->link('Suppression', array('controller'=>'Users','action'=>'delete', $idDelete)). '</li>';
                            echo '<li>' . $this->Html->link('Déconnexion', array('controller' => 'Users', 'action' => 'logout')) . '</li>';}
                        ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container-fluid">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>

    </div>
    <div class="preFooter"></div>
    <div class="footer">
        <div class="row-fluid">
            <div class="col-lg-2">
                <div class="btn-group" id="btn-footer">
                    <a class="btn btn-info"
                       href="http://webarena.free-hosting.fr/WebArenaGroupSIA-00-00/Arenas/index"><i
                            class="fa fa-globe"></i></a>
                    <a class="btn btn-default" href="https://github.com/Stoumfou/WebArenaGroupSIA-00-00"><i
                            class="fa fa-github-alt"></i></a>
                    <a class="btn btn-primary" href="https://www.facebook.com/WebArenaEce/?fref=ts"><i
                            class="fa fa-facebook-official"></i></a>
                    <a class="btn btn-danger" href="https://plus.google.com/116930005621093464314/about"><i
                            class="fa fa-google-plus"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row-fluid">
                    <p id="footer"> Copyright &copy; <?php echo $date = date('Y'); ?> Nicolas BOUVET / Alexis PAMBOURG
                        / Paul CABELLAN Gr2-12-DG</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="fb-root"></div>
                <div class="fb-like"></div>
                <div id="status"></div>
            </div>
        </div>
    </div>
</div>
<!--<?php echo $this->element('sql_dump'); ?>
    <?php echo $this->fetch('script'); ?>-->
</body>
</html>