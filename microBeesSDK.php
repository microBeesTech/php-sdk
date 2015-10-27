<?php 
class microBees {
    public function __construct(array $arguments = array()) {
        if (!empty($arguments)) {
            foreach ($arguments as $property => $argument) {
                $this->{$property} = $argument;
            }
        }
    }

    public function __call($method, $arguments) {
        $arguments = array_merge(array("microBees" => $this), $arguments); // Note: method argument 0 will always referred to the main class ($this).
        if (isset($this->{$method}) && is_callable($this->{$method})) {
            return call_user_func_array($this->{$method}, $arguments);
        } else {
            throw new Exception("Fatal error: Call to undefined method microBees::{$method}()");
        }
    }
	private function json_validate($string){
		$result = json_decode($string);
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred
				break;
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
				break;
			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
				break;
			default:
				$error = 'Unknown JSON error occured.';
				break;
		}

		if ($error !== '') {
			if($this->DEBUG)
				echo "<br/><hr/>Fatal error: ".$error;
			else throw new Exception("Fatal error: ".$error);
		}

		return $result;
	}
	private $token = "";
	private $DEBUG = false;
	public function getAccessToken($username,$password,$clientID,$clientSecret){
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
		$response = $this->json_validate($json_response, true);
		if(!isset($response->{"error"}))
			$token = $json_response->access_token;
		if($this->DEBUG)
			print_r($json_response);
	}
	
	public function doRequest($url,$params){
		if(preg_match('/[^,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t]/',preg_replace('/"(\\.|[^"\\\\])*"/', '', $params))){
			$content = $this->json_validate($params);
		}
		$content = $params;
		$url="https://dev.microbees.com/".$url;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-type: application/json; ',  
			'charset: UTF-8',
			'Content-Length: ' . strlen($params),
			'Authorization: Bearer '.$this->token
			)                                                                       
		); 
		$json_response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($json_response, true);
		if($this->DEBUG){
			echo "<br/><hr/>";
			print_r($json_response);
		}
		return $json_response;
	}
}
?> 