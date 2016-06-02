<?php

namespace Sprint;

use \PDO;

class TodoCommand extends Command {
    private $todos = array();

    public function excute($message){
        $str = explode(" ", $message);

        if($str[2] === "add"){
            $this->add($message);
            return;
        }else if($str[2] === "delete"){
            $this->delete($message);
            return;
        }else if($str[2] === "list"){
            $this->getList($message); // list は予約語である
            return;
        }else{
            $this->result = "そんなコマンドは無い";
            return;
        }
    }
    
    private function add($message){
        $str = explode(" ", $message);
        
        if(!isset($str[3])){
            return;
        }

        $sql = 'INSERT INTO todos(title, body) values(:title, :body)';
        $stmt = Dbh::get()->prepare($sql);
        $stmt->bindValue(':title', $str[3], PDO::PARAM_STR);
        if(isset($str[4])){
            $stmt->bindValue(':body', $str[4], PDO::PARAM_STR);
        }else{
            $stmt->bindValue(':body', "", PDO::PARAM_STR);
        }
        $stmt->execute();
            
        $this->result = "todo added";
    }

    private function delete($message){
        $str = explode(" ", $message);
        
        if(!$str[3]){
            return;
            $this->result = "何を削除しろと";
        }
        
        $sql = 'DELETE FROM todos WHERE title = :title';
        $stmt = Dbh::get()->prepare($sql);
        $stmt->bindValue(':title', $str[3], PDO::PARAM_STR);
        $stmt->execute();
        
        $this->result = "todo deleted";
        
        return;
    }

    private function getList($message){
        $res = "";
        $rowsnum = 0;
        
        $sql = 'SELECT title, body FROM todos';
        $stmt = Dbh::get()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowsnum = count($rows);
        
        if($rowsnum === 0){
            $this->result = "todo empty";

            return;
        }

        for($i=0; $i< $rowsnum; $i++){
            $res .= $rows[$i]["title"];
            if(isset($rows[$i]["body"])){
                $res .= " ". $rows[$i]["body"];
            }
            if($i !== count($rows) -1){
                $res .= "\n";
            }
        }

        $this->result = $res;
        return;

    }

}