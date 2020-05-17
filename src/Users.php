<?php

namespace Library;

use PDO;
use Library\Database as DB;

//use website_project\database\PDOConnection as PDOConnect;

class Users {

    private $connectPDO;
    private $pdo;
    private $password = \NULL;
    protected $id = NULL;
    protected $query = NULL;
    protected $stmt = NULL;
    protected $result = NULL;
    protected $queryParams = NULL;
    protected $row = NULL;
    protected $loginStatus = false;
    protected $salt = \NULL;
    public $user_id = \NULL;
    public $fullName = \NULL;
    public $user = NULL;
    public $userArray = [];
    public $username = NULL;

    /* Create (Insert) new users information */

    public function __construct() {
        
    }

// End of constructor:
    protected function generateSalt($max = 64) {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
    }

    /* This method also takes an array of data and utilizes the constructor. */

    public function register($data , $status) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();

        if ($data['password'] === $data['repeatPassword']) {
            $pwd = password_hash($data['password'], PASSWORD_DEFAULT);

            $this->query = 'INSERT INTO members (username, status, password, security, email, date_added) VALUES (:username, :status, :password, :security, :email, Now())';
            $this->stmt = $pdo->prepare($this->query);
            $this->result = $this->stmt->execute([':username' => $data['username'], ':status' => $status, ':password' => $pwd, ':security' => 'newuser', ':email' => $data['email']]);
            return true;
        } else {
            return false;
        }
    }
    
    public function activate($activationNumber) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();

        $this->query = 'UPDATE members SET security=:security WHERE status=:status';


        $this->stmt = $pdo->prepare($this->query);
        $this->result = $this->stmt->execute([':security' => 'member', ':status' => $activationNumber]);

        if ($this->result) {
            return \TRUE;
        } else {
            return \FALSE;
        }
        
    }

    public function read($username, $password) {

        $db = DB::getInstance();
        $pdo = $db->getConnection();
        /* Setup the Query for reading in login data from database table */
        $this->query = 'SELECT id, password FROM members WHERE username=:username';


        $this->stmt = $pdo->prepare($this->query); // Prepare the query:
        $this->stmt->execute([':username' => $username]); // Execute the query with the supplied user's emaile:

        $this->result = $this->stmt->fetch(PDO::FETCH_OBJ);

        if ($this->result->password && password_verify($password, $this->result->password)) {

            unset($this->result->password);
            unset($password);
            session_regenerate_id();
            $lifetime = 60 * 60 * 24 * 7;
            setcookie(session_name(), session_id(), time() + $lifetime);
            $_SESSION['id'] = $this->result->id;

            // Save these values in the session, even when checks aren't enabled 
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['last_login'] = time();
            return $this->result->id;
        } else {
            return false;
        }
    }

    public function facebook($username) {
        session_regenerate_id();
        $lifetime = 60 * 60 * 24 * 7;
        setcookie(session_name(), session_id(), time() + $lifetime);
        $_SESSION['username'] = $username;

        // Save these values in the session, even when checks aren't enabled 
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_login'] = time();
    }

    public function secureRead($email, $password) {
        $stored_salt = $this->retrieveSalt($email);
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = 'SELECT id FROM members WHERE email=:email AND password=:password';


        $this->stmt = $pdo->prepare($this->query); // Prepare the query:
        $this->stmt->execute([
            ':email' => $email,
            ':password' => hash('whirlpool', $stored_salt . $password)
        ]); // Execute the query with the supplied user's parameter(s):

        $this->stmt->setFetchMode(PDO::FETCH_OBJ);
        unset($stored_salt, $password);
        if ($this->user_id = $this->stmt->fetchColumn()) {
            return $this->user_id;
        } else {
            return FALSE;
        }
    }

    private function retrieveSalt($email) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT salt FROM members WHERE email=:email";
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute([':email' => $email]);
        $this->stmt->setFetchMode(PDO::FETCH_OBJ);
        return $this->stmt->fetchColumn();
    }

    public function username($id = 0) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT username FROM members WHERE id=:id";
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute([':id' => $id]);
        $this->user = $this->stmt->fetch(PDO::FETCH_OBJ);
        return $this->user->username; // Send back Real Name of User:
    }

    public function checkSecurityCode($confirmation_code) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = 'SELECT security_level FROM users WHERE confirmation_code=:confirmation_code';


        $this->stmt = $pdo->prepare($this->query); // Prepare the query:
        $this->stmt->execute([':confirmation_code' => $confirmation_code]); // Execute the query with the supplied user's parameter(s):

        $this->stmt->setFetchMode(PDO::FETCH_OBJ);
        $this->user = $this->stmt->fetch();

        if ($this->user->security_level === 'public') {
            return \TRUE;
        } else {
            return \FALSE;
        }
    }

    public function update($confirmation_code) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();

        $this->query = 'UPDATE users SET security_level=:security_level WHERE confirmation_code=:confirmation_code';


        $this->stmt = $pdo->prepare($this->query);
        $this->result = $this->stmt->execute([':security_level' => 'member', ':confirmation_code' => $confirmation_code]);

        if ($this->result) {
            return \TRUE;
        } else {
            return \FALSE;
        }
    }

    /* Logoff Current User */

    public function delete($id = NULL) {
        unset($id);
        unset($this->user);
        unset($_SESSION['user']);
        $_SESSION['user'] = NULL;
        session_destroy();
        return TRUE;
    }

}

// End of Users class: