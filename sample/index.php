<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../microBeesSDK.php");
$obj = new microBees(array("DEBUG"=>true));
$obj->getAccessToken("example@microbees.com","Example12345","myClientID","myClientSecret");
$obj->doRequest("api/refreshTranslations","{'foo','example'}");
?> 