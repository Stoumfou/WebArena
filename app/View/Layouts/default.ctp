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
		//Décommenter et supprimer cake.generic pour passez au CSS Bootstrap
		echo $this->Html->css(array('bootstrap.min', 'font-awesome.min'));
        //echo $this->Html->css('cake.generic');
        echo $this->Html->script('jquery-2.1.4');
        echo $this->Html->script(array('grid'));
        echo $this->Html->script(array('jquery-1.11.3.min','bootstrap.min'));
		echo $this->fetch('css');
        echo $this->fetch('javascript');
        echo $this->Html->css(array('footer','grid'));
	?>
</head>
<body  >

<script>
    //Script pour Google Analytics
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-61968583-2', 'auto');
    ga('send', 'pageview');

</script>
<script>
    //Script pour facebookConnect

    // This is called with the results from from FB.getLoginStatus().
    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            testAPI();
        } else if (response.status === 'not_authorized') {
            // The person is logged into Facebook, but not your app.
            document.getElementById('status').innerHTML = 'Please log ' +
                'into this app.';
        } else {
            // The person is not logged into Facebook, so we're not sure if
            // they are logged into this app or not.
            document.getElementById('status').innerHTML = 'Please log ' +
                'into Facebook.';
        }
    }

    // This function is called when someone finishes with the Login
    // Button.  See the onlogin handler attached to it in the sample
    // code below.
    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1720702151482399',
            cookie     : true,  // enable cookies to allow the server to access
                                // the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.2' // use version 2.2
        });

        // Now that we've initialized the JavaScript SDK, we call
        // FB.getLoginStatus().  This function gets the state of the
        // person visiting this page and can return one of three states to
        // the callback you provide.  They can be:
        //
        // 1. Logged into your app ('connected')
        // 2. Logged into Facebook, but not your app ('not_authorized')
        // 3. Not logged into Facebook and can't tell if they are logged into
        //    your app or not.
        //
        // These three cases are handled in the callback function.

        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });

    };

    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Here we run a very simple test of the Graph API after login is
    // successful.  See statusChangeCallback() for when this call is made.
    function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me',{fields: 'id,name,email'}, function(response) {
            console.log('Successful login for: ' + response.name);
            document.getElementById('status').innerHTML =
                'Thanks for logging in, ' + response.name + '!';
            var name = response.name;
            var email = response.email;
           // alert(email);
        });
    }

</script>


<!--<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>-->

<!--<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5&appId=1720702151482399";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>-->

<div id="status">
</div>
    <div id="mainContainer">
        
		<div id="content">
			<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="../Arenas/index">WebArenas</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
          <?php 	if ($myname == 'toi petit troll') {}
		else echo '
<li><a href="fighter">Combattant</a></li>
<li><a href="sight">Vision</a></li>
<li><a href="diary">Journal</a></li>';?>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <?php 	if ($myname == 'toi petit troll') {echo ('<li><a href="../Users/login">Connexion</a></li><li><a href="../Users/register">Inscription</a></li>'); /*echo '<li class="fb-login-button" data-max-rows="2" data-size="medium" data-show-faces="false" data-auto-logout-link="true"></li>';*/}
else echo '<li><a href="../Users/logout">Déconnexion</a></li>';?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
            <div class="container-fluid">
            <?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
</div>
            
		</div>
        <div class="footer">
        <p id="footer" class="footer">Nicolas BOUVET / Alexis PAMBOURG / Paul CABELLAN</p>
    </div>
	</div>
	<!--<?php echo $this->element('sql_dump'); ?>
    <?php echo $this->fetch('script'); ?>-->
</body>
</html>