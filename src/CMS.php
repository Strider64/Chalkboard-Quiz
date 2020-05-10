<?php

namespace Library;

use PDO;
use Library\Database as DB;


class CMS {

    protected $query = \NULL;
    public $stmt = \NULL;
    protected $password = \NULL;
    public $page_name = \NULL;
    public $journal = \NULL;
    public $result = \NULL;
    public $count = \NULL;
    public $myURL = \NULL;

    public function __construct() {
        
    }

    public function create(array $data) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = 'INSERT INTO journal( user_id, author, page_name, image_path, rating, thumb_path, Model, ExposureTime, Aperture, ISO, FocalLength, heading, content, date_updated, date_added) VALUES ( :user_id, :author, :page_name, :image_path, :rating, :thumb_path, :Model, :ExposureTime, :Aperture, :ISO, :FocalLength, :heading, :content, NOW(), NOW())';
        $this->stmt = $pdo->prepare($this->query);
        $this->result = $this->stmt->execute([':user_id' => $data['user_id'], 'author' => $data['author'], ':page_name' => $data['page_name'],':image_path' => $data['image_path'], ':rating' => $data['rating'], ':thumb_path' => $data['thumb_path'], ':Model' => $data['Model'], ':ExposureTime' => $data['ExposureTime'], ':Aperture' => $data['Aperture'], ':ISO' => $data['ISO'], ':FocalLength' => $data['FocalLength'], ':heading' => $data['heading'], ':content' => trim($data['content'])]);
        return $data['page_name'];
    }

    public function read() {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = 'SELECT id, user_id, page_name, image_path, thumb_path, heading, content, DATE_FORMAT(date_added, "%W, %M %e, %Y") as date_added FROM journal';

        $this->stmt = $pdo->prepare($this->query); // Prepare the query:
        $this->stmt->execute(); // Execute the query with the supplied user's parameter(s):

        return $this->stmt;
    }

    public function readId($id) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = 'SELECT id, user_id, page_name, image_path, thumb_path, heading, content, DATE_FORMAT(date_added, "%W %M %e, %Y") as date_added  FROM journal WHERE id=:id';
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute([':id' => $id]);
        $this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
        return $this->result;
    }
    
    protected function pageName($id) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->stmt = $pdo->prepare('SELECT page_name FROM journal WHERE id=:id');
        $this->stmt->execute([':id' => $id]);
        $this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
        return $this->result->page_name;
    }

    public function update(array $data) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = 'UPDATE journal SET rating=:rating, heading=:heading, content=:content, date_updated=NOW() WHERE id =:id';
        $this->stmt = $pdo->prepare($this->query);
        $this->result = $this->stmt->execute([':rating' => $data['rating'], ':heading' => $data['heading'], ':content' => $data['content'], ':id' => $data['id']]);
        $this->page_name = $this->pageName($data['id']);
        return $this->page_name;
    }

    public function readImagePath($id = NULL) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT image_path FROM journal WHERE id=:id";
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute([':id' => $id]);
        $this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
        return $this->result->image_path;
    }
    
        public function readThumbPath($id = NULL) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT thumb_path FROM journal WHERE id=:id";
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute([':id' => $id]);
        $this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
        return $this->result->thumb_path;
    }

    public function deleteRecord(int $id = NULL) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "DELETE FROM journal WHERE id=:id";
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute([':id' => $id]);
        return \TRUE;
    }

    public function delete($id = NULL) {
        unset($id);
        unset($this->user);
        unset($_SESSION['user']);
        $_SESSION['user'] = NULL;
        session_destroy();
        return TRUE;
    }

}
