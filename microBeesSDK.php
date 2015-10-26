<?php 
class microBees { 
	private $token = "";
	public $DEBUG = false;
	function getAccessToken($username,$password,$clientID,$clientSecret){
		$endpoint="https://dev.microbees.com/oauth/token?grant_type=password&username=".$username."&password=".$password."&client_id=".$clientID."&client_secret=".$clientSecret."&redirect_uri=";
		$curl = curl_init($endpoint);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-type: application/json; ',  
			'charset: UTF-8'
			)                                                                       
		); 
		$json_response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($json_response, true);
		$token = $json_response->access_token;
	};
	
} 
?> 