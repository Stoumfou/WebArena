<?php
/**
 * Created by PhpStorm.
 * User: Alexis
 * Date: 25/10/2015
 * Time: 14:17
 */

define ('FACEBOOK_SDK_V4_SRC_DIR', '../../vendor/facebook/php-sdk-v4/src/Facebook');
require_once("../../vendor/autoload.php");

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;

?>