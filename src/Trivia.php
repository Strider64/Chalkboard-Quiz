<?php

namespace Library;


use PDO;
use DateTime;
use DateTimeZone;

class Trivia {

    public $result = \NULL;
    public $data = [];
    public $quiz = [];
    public $answered = [];
    public $currentQuestion = [];
    public $records = [];
    protected $pdo = \NULL;
    protected $q_num = \NULL;
    protected $query = \NULL;
    protected $stmt = \NULL;
    protected $today = \NULL;
    protected $categories = [];
    public $count = \NULL;

    public function __construct() {
        
    }

    public function create(array $data) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = 'INSERT INTO trivia_questions (user_id, hidden, question, answer1, answer2, answer3, answer4, correct, category, play_date) VALUES (:user_id, :hidden, :question, :answer1, :answer2, :answer3, :answer4, :correct, :category, NOW())';

        $this->stmt = $pdo->prepare($this->query);
        $this->result = $this->stmt->execute([
            ':user_id' => 1,
            ':hidden' => 'no',
            ':question' => $data['question'],
            ':answer1' => $data['answer1'],
            ':answer2' => $data['answer2'],
            ':answer3' => $data['answer3'],
            ':answer4' => $data['answer4'],
            ':correct' => $data['correct'],
            ':category' => $data['category']
        ]);
        return TRUE;
    }

    public function categories($category, $hidden = 'no') {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT count(1) FROM trivia_questions WHERE category=:category AND hidden=:hidden";
        $this->stmt = $pdo->prepare($this->query);

        $this->stmt->execute([':category' => $category, ':hidden' => $hidden]);
        $this->count = $this->stmt->fetchColumn();
        $this->records = ['status' => 'edit', 'category' => $category, 'total' => $this->count];

        return $this->records;
    }

    public function read($category) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT id, question, answer1, answer2, answer3, answer4, category FROM trivia_questions WHERE category=:category";
        $this->stmt = $pdo->prepare($this->query);


        $this->stmt->execute([':category' => $category]);

        return $this->stmt->fetchALL(PDO::FETCH_ASSOC);
        
    }
    
    public function check(int $id) {
         $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT id, correct FROM trivia_questions WHERE id=:id";
        $this->stmt = $pdo->prepare($this->query);


        $this->stmt->execute([':id' => $id]);

        $this->data = $this->stmt->fetch(PDO::FETCH_ASSOC);    
        
        return $this->data;
    }

    public function readAll($start = 0, $end = 0, $category = "movie") {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT id, hidden, question, answer1, answer2, answer3, answer4, correct, category, play_date "
                . "FROM trivia_questions "
                . "WHERE category=:category "
                . "ORDER BY id DESC LIMIT :start, :end";
        $this->stmt = $pdo->prepare($this->query);

        $this->stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $this->stmt->bindValue(':start', (int) $start, PDO::PARAM_INT);
        $this->stmt->bindValue(':end', (int) $end, PDO::PARAM_INT);


        $this->stmt->execute();

        $this->quiz = $this->stmt->fetchAll(PDO::FETCH_OBJ);

        return $this->quiz;
    }
    
    public function fetchData () {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "SELECT * FROM trivia_questions ORDER BY id ASC";
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute();
        
        $this->data = $this->stmt->fetchAll(\PDO::FETCH_OBJ);
        return $this->data;
    }

    public function update(array $data) {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        $this->query = "UPDATE trivia_questions "
                . "SET "
                . "user_id = :user_id, "
                . "question = :question, "
                . "answer1 = :answer1, "
                . "answer2 = :answer2, "
                . "answer3 = :answer3, "
                . "answer4 = :answer4, "
                . "correct = :correct, "
                . 'category = :category '
                . "WHERE id = :id";
        $this->stmt = $pdo->prepare($this->query);
        $this->stmt->execute([
            ':user_id' => $data['user_id'],
            ':question' => $data['question'],
            ':answer1' => $data['answer1'],
            ':answer2' => $data['answer2'],
            ':answer3' => $data['answer3'],
            ':answer4' => $data['answer4'],
            ':correct' => $data['correct'],
            ':category' => $data['category'],
            ':id' => $data['id']
        ]);
        return \TRUE;
    }

    public function loadQuestion($pointer = 0) {
        return $this->quiz[$pointer];
    }

    public function readQA($q_num) {
        $play_date = new DateTime("Now", new DateTimeZone("America/Detroit"));
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
        $this->query = "SELECT id, q_num, question, answer1, answer2, answer3, answer4, correct, play_date FROM trivia_questions WHERE q_num=:q_num AND play_date=:play_date";
        $this->stmt = $this->pdo->prepare($this->query);
        $this->stmt->execute([':q_num' => $q_num, ':play_date' => $play_date->format("Y-m-d")]);
        $this->data = $this->stmt->fetchAll(PDO::FETCH_OBJ);
        if ($this->data) {
            return $this->data;
        } else {
            return NULL;
        }
    }

    public function checkDailyTen($q_num, $answer) {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
        $query = "SELECT  id, q_num, correct, play_date FROM the_daily_ten WHERE q_num=:q_num";
        $this->stmt = $this->pdo->prepare($query);
        $this->stmt->execute([':q_num' => $q_num]);
        $this->row = $this->stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->row) {
            if ($this->row['correct'] === (int) $answer) {
                $this->data['correct'] = TRUE;
                $this->data['right_answer'] = $this->row['correct'];
                $this->data['user_answer'] = (int) $answer;
            } else {
                $this->data['correct'] = FALSE;
                $this->data['right_answer'] = $this->row['correct'];
                $this->data['user_answer'] = (int) $answer;
            }
            return $this->data;
        } else {
            return NULL;
        }
    }

}
