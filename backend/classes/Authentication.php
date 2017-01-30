<?php

require_once("Connection.php");

class Authentication extends Connection
{
    private $conn;

    private $errors;

    function __construct()
    {
        $this -> conn = $this -> getConnection();
        $this -> errors = array();
    }

    public function userLogin($user) {
        $this -> validateUser($user);
        if(count($this -> errors) == 0) {
            $email = $user["email"];
            $password = $this -> hashPassword($user["password"]);
            try {
                $stmt = $this -> conn -> prepare("SELECT * FROM wor_users_login WHERE user_login_email = :email AND user_login_hash = :hash");
                $stmt -> execute([
                    "email" => $email,
                    "hash" => $password
                ]);
                $result = $stmt -> fetchAll();
                if ($stmt -> rowCount() == 1 ) {
                    if($result[0]["user_login_email_confirm"] == 0) {
                        echo "please confirm your password to login";
                    } elseif ($this -> updateLoginDate($result[0]["user_id"]) ) {
                        $this::set("user_id", $result[0]["user_id"]);
                        header(BASE_URL_REDIRECT);
                    }
                } else {
                    echo "wrong username and/or password";
                }
            } catch (PDOException $e) {
    			$error = new ErrorMaster();
    			$error -> reportError($e);
    		}
        }
    }

    private function validateUser($user) {
        $emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        if(empty($user["email"])) {
            $this -> errors["email"] = "Please provide and email address";
        } elseif (!preg_match($emailPattern, $user["email"])) {
            $this -> errors["email"] = "Incorrect email format";
        }
        if(empty($user["password"])) {
            $this -> errors["password"] = "Please provide a valid password";
        }
    }

    public function getErrors() {
        return $this -> errors;
    }

    private function updateLoginDate($userid) {
        try {
            $stmt = $this -> conn -> prepare("UPDATE wor_users SET user_last_login_date	 = :currentTime WHERE user_id = :id");
            $stmt -> execute([
                "currentTime" => date("Y-m-d H:i:s"),
                "id" => $userid
            ]);
            if($stmt -> rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

    public function isLoggedIn() {
        if($this::get("user_id") == null) {
            return false;
        } else {
            return true;
        }
    }

    public static function startSession() {
        ini_set('session.use_only_cookies', SESSION_USE_ONLY_COOKIES);
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params(  $cookieParams["lifetime"],
                                    $cookieParams["path"],
                                    $cookieParams["domain"],
                                    SESSION_SECURE,
                                    SESSION_HTTP_ONLY
                                );
        session_start();
        session_regenerate_id(SESSION_REGENERATE_ID);
    }

    public static function destroySession() {
        $_SESSION = array();
        $params = session_get_cookie_params();
        setcookie(  session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
        session_destroy();
    }

    private function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	    $output = NULL;
	    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
	        $ip = $_SERVER["REMOTE_ADDR"];
	        if ($deep_detect) {
	            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	    }
	    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	    $continents = array(
	        "AF" => "Africa",
	        "AN" => "Antarctica",
	        "AS" => "Asia",
	        "EU" => "Europe",
	        "OC" => "Australia (Oceania)",
	        "NA" => "North America",
	        "SA" => "South America"
	    );
	    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
	        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
	        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
	            switch ($purpose) {
	                case "location":
	                    $output = array(
	                        "city"           => @$ipdat->geoplugin_city,
	                        "state"          => @$ipdat->geoplugin_regionName,
	                        "country"        => @$ipdat->geoplugin_countryName,
	                        "country_code"   => @$ipdat->geoplugin_countryCode,
	                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
	                        "continent_code" => @$ipdat->geoplugin_continentCode
	                    );
	                    break;
	                case "address":
	                    $address = array($ipdat->geoplugin_countryName);
	                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
	                        $address[] = $ipdat->geoplugin_regionName;
	                    if (@strlen($ipdat->geoplugin_city) >= 1)
	                        $address[] = $ipdat->geoplugin_city;
	                    $output = implode(", ", array_reverse($address));
	                    break;
	                case "city":
	                    $output = @$ipdat->geoplugin_city;
	                    break;
	                case "state":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "region":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "country":
	                    $output = @$ipdat->geoplugin_countryName;
	                    break;
	                case "countrycode":
	                    $output = @$ipdat->geoplugin_countryCode;
	                    break;
	            }
	        }
	    }
        //////////////////////change this back///////////////////////////////
        // return $output;
        return "KE";
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function destroy($key) {
        if ( isset($_SESSION[$key]) )
            unset($_SESSION[$key]);
    }

    public static function get($key, $default = null) {
        if(isset($_SESSION[$key]))
            return $_SESSION[$key];
        else
            return $default;
    }

    public function getUserCountryId() {
        $ip = $this -> getUserIp();
        $countryCode = $this -> ip_info($ip,"countrycode");
        try {

            $stmt = $this -> conn -> prepare("SELECT country_id FROM wor_countries WHERE code = :code");
            $stmt -> execute(["code" => $countryCode]);
            $result = $stmt -> fetch();
            if($stmt -> rowCount() > 0) {
                return $result["country_id"];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

    public function getUserDetails($user_id) {
        $query = "SELECT * FROM wor_users WHERE user_id = :user_id";
        try {
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute(["user_id" => $user_id]);
            $result = $stmt -> fetch();
            if($stmt -> rowCount() > 0) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

    public function updatePersonalInfo($info, $userId) {
        $firstname = ucwords(strtolower($info["firstname"]));
        $lastname = ucwords(strtolower($info["lastname"]));
        $country = ucwords(strtolower($info["country"]));
        $zip = ucwords(strtolower($info["zip"]));
        $address = ucwords(strtolower($info["address"]));
        $street = ucwords(strtolower($info["street"]));
        $town = ucwords(strtolower($info["town"]));

        if(empty($firstname) || empty($lastname) || empty($country) || empty($zip) || empty($address) || empty($street) || empty($town)) {
            $this -> errors["update-info"] = "please fill in all the required feilds";
            return false;
        }

        else {
            $query = "UPDATE wor_users SET
            user_firstname = :user_firstname,
            user_lastname = :user_lastname,
            user_billing_country_id = :user_billing_country_id,
            user_billing_zip = :user_billing_zip,
            user_billing_address = :user_billing_address,
            user_billing_street = :user_billing_street,
            user_billing_town = :user_billing_town
            WHERE user_id = :user_id ";
            try {
                $stmt = $this -> conn -> prepare($query);
                $stmt -> execute([
                    "user_firstname" => $firstname,
                    "user_lastname" => $lastname,
                    "user_billing_country_id" => $country,
                    "user_billing_zip" => $zip,
                    "user_billing_address" => $address,
                    "user_billing_street" => $street,
                    "user_billing_town" => $town,
                    "user_id" => $userId
                ]);
                if($stmt -> rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                $error = new ErrorMaster();
                $error -> reportError($e);
            }
        }
    }

    public function changeEmail($userId, $email) {
        $emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        if (!preg_match($emailPattern, $email)) {
            $this -> errors["change-email"] = "invalid email format";
            return false;
        } elseif ($this -> exists($email)) {
            $this -> errors["change-email"] = "the email exists";
            return false;
        } else {
            echo "2";
            $key = $this -> _generateKey();
            $query = "UPDATE wor_users_login SET
            user_login_email = :user_login_email,
            user_login_email_confirm = :user_login_email_confirm,
            user_confirmation_key = :user_confirmation_key
            WHERE user_id = :user_id";
            try {
                $stmt = $this -> conn -> prepare($query);
                $stmt -> execute([
                    "user_login_email" => $email,
                    "user_login_email_confirm" => 0,
                    "user_confirmation_key" => $key,
                    "user_id" => $userId
                ]);
                $result = $stmt -> fetch();
                if($stmt -> rowCount() == 1) {
                    //update user information table
                    $query = "UPDATE wor_users SET
                    user_email = :user_email
                    WHERE user_id = :user_id";
                    $stmt = $this -> conn -> prepare($query);
                    $stmt -> execute([
                        "user_email" => $email,
                        "user_id" => $userId
                    ]);
                    if($stmt -> rowCount() == 1) {
                        echo BASE_URL . "confirm.php?k=" . $key;
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                $error = new ErrorMaster();
                $error -> reportError($e);
            }
        }
    }

    public function getUserIp() {
        //////////////////////change this back///////////////////////////////
        //return $_SERVER['REMOTE_ADDR'];
        //mock ip address
        return "217.21.114.58";
    }

    public function forgotPassword($email) {
        $emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        if(empty($email)) {
            echo "hit here";
            $this -> errors["email"] = "Please provide and email address";
        } elseif (!preg_match($emailPattern, $email)) {
            echo "hit here 2";
            $this -> errors["email"] = "Incorrect email format";
        } elseif (!($this -> exists($email))) {
            $this -> errors["email"] = "User does not exist";
        }

        if(count($this -> errors) == 0) {
            //too much sauce
            $resetKey = md5($email . $this -> _generateKey());
            $query = "UPDATE wor_users_login SET password_reset_key = :password_reset_key, 	password_reset_timestamp = :password_reset_timestamp WHERE user_login_email = :user_login_email";
            try {
                $stmt = $this -> conn -> prepare($query);
                $stmt -> execute([
                    "password_reset_key" => $resetKey,
                    "password_reset_timestamp" => date("Y-m-d H:i:s"),
                    "user_login_email" => $email
                ]);
                if($stmt -> rowCount() == 1) {
                    echo "Go reset your password you have 60 min to do it: " .BASE_URL."change.php?r={$resetKey}";
                }
            } catch (PDOException $e) {
                $error = new ErrorMaster();
                $error -> reportError($e);
            }
        }
    }

    public function isResetKeyValid($key) {
        $query = "SELECT * FROM wor_users_login WHERE password_reset_key = :password_reset_key";
        try {
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([
                "password_reset_key" => $key,
            ]);
            if($stmt -> rowCount() == 1) {
                $result = $stmt -> fetch();
                $to_time = strtotime(date("Y-m-d H:i:s"));
                $from_time = strtotime($result["password_reset_timestamp"]);
                $difference =  round(abs($to_time - $from_time) / 60,2);
                if($difference > 60) {
                    $this -> errors["reset"] = "Reset Key is not valid";
                    return false;
                } else {
                    return true;
                }
            } else {
                $this -> errors["reset"] = "Reset Key is not valid";
                return false;
            }
        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

    public function resetPassword($newpass, $confirmpass, $key) {
        $passwordPattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/";
        if($newpass != $confirmpass){
            $this -> errors["reset"] = "Passwords do not match";
            return false;
        }
        if(!preg_match($passwordPattern, $newpass)) {
            $this -> errors["reset"] = "Password not strong enough, Must be Minimum 8 characters at least 1 Alphabet, 1 Number and 1 Special Character";
            return false;
        }
        if(!isset($this -> errors["reset"])) {
            try {
                $query = "UPDATE wor_users_login SET user_login_hash = :user_login_hash WHERE password_reset_key = :password_reset_key";
                $stmt = $this -> conn -> prepare($query);
                $stmt -> execute([
                    "user_login_hash" => $this -> hashPassword($newpass),
                    "password_reset_key" => $key
                ]);
                if($stmt -> rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                $error = new ErrorMaster();
                $error -> reportError($e);
            }
        } else {
            return false;
        }
    }

    public function changePassword($oldpass, $newpass, $newpassconfirm, $userId) {

        $passwordPattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/";

        if(empty($oldpass) || empty($newpass) || empty($newpassconfirm)) {
            $this -> errors["changepassword"] = "please fill out all required feilds";
            return false;
        }

        if($newpass != $newpassconfirm) {
            $this -> errors["changepassword"] = "passwords do not match";
            return false;
        }

        if(!preg_match($passwordPattern, $newpass)) {
            $this -> errors["changepassword"] = "password is not strong enough";
            return false;
        }

        if(!$this -> passwordMatchCheck($userId, $this -> hashPassword($oldpass))) {
            $this -> errors["changepassword"] = "incorrect password";
            return false;
        } else {
            $query = "UPDATE wor_users_login SET user_login_hash = :user_login_hash WHERE user_id = :user_id";
            try {
                $stmt = $this -> conn -> prepare($query);
                $stmt -> execute([
                    "user_login_hash" => $this -> hashPassword($newpass),
                    "user_id" => $userId
                ]);
                if($stmt -> rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                $error = new ErrorMaster();
                $error -> reportError($e);
            }
        }






    }

    private function passwordMatchCheck($userId, $password) {
        $query = "SELECT user_login_hash FROM wor_users_login WHERE user_id = :user_id";
        try {
            $stmt = $this -> conn -> prepare($query);
            $stmt -> execute([ "user_id" => $userId]);
            if($stmt -> rowCount() > 0) {
                $result = $stmt -> fetch();
                if($result["user_login_hash"] == $password) {
                    return true;
                } else {
                    echo $result["user_login_hash"] . "<br>";
                    echo $password . "<br>";
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

    private function hashPassword($password) {
        $salt = "$2a$" . PASSWORD_BCRYPT_COST . "$" . PASSWORD_SALT;
        $newPassword = crypt($password, $salt);
        return $newPassword;
    }

    private function _generateKey() {
        return md5(time() . PASSWORD_SALT . time() .uniqid());
    }

    private function exists($email) {
        try {

            $stmt = $this -> conn -> prepare("SELECT * FROM wor_users WHERE user_email = :email");
            $stmt -> execute(["email" => $email]);
            if ($stmt -> rowCount() > 0 ) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }
}

?>
