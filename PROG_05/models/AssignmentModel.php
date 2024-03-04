<?php

require_once("Database.php");

class AssignmentModel {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    public function __destruct() {
        if ($this->connection){
            $this->connection->close();
        }
    }


    public function read($page = 0, $recordPerPage = 0, $queryParams = [], $searchKey = "", $select = [] ) {
        // Create SELECT clause based on provided columns
        $selectClause = empty($select) ? '*' : implode(', ', $select);
    
        $keys = array_keys($queryParams);
        $values = array_values($queryParams);

        //$conditions[] = $searchKey ? "description LIKE '%" . $searchKey . "%'"  : "1";
        //$whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $limitClause = "";
        if ($page){
            $start_from = ($page - 1) * $recordPerPage;
            $limitClause .= " LIMIT $start_from, $recordPerPage";             
        }

        $query = "SELECT $selectClause FROM assignments ";

        if (!empty($keys) || $searchKey){
            $query .='WHERE ';
            if (!empty($keys)){
                for ($i=0; $i < count($keys) - 1; $i++){
                    $query .= ($keys[$i] . "=? AND ");
                }
                $query .= ($keys[count($keys) - 1] . "=?");
            }

            if ($searchKey){
                if (!empty($keys)){
                    $query .= " AND ";
                }
                $query .= "fullname LIKE CONCAT('%',?,'%')";
            }
            
        }

        $query .= $limitClause;
        
        $stmt = $this->connection->prepare($query);

        if (!empty($values) || $searchKey){
            $typeOfBind = ""; 

            if (!empty($values)){
                for ($i=0; $i < count($values); $i++){
                    if ($keys[$i] === "teacher_id" && $keys[$i] === "id"){
                        $typeOfBind .= "i";
                    } else {
                        $typeOfBind .= "s";
                    }
                }
            }
            
            if ($searchKey){
                $typeOfBind .= "s";
                $values[] = $searchKey;
            }

            $stmt->bind_param($typeOfBind,...$values);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        

        if ($result->num_rows > 0) {
            $records = [];
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
            return $records;
        } else {
            return null;
        }
        
    }

    public function create($data) {
        $keys = array_keys($data);
        $values = array_values($data);
        
        foreach ($values as $value){
            $value = $this->connection->real_escape_string($value);
            $value = trim($value);
        }

        if (!empty($keys)) {
            
            $query = "INSERT INTO assignments (" . implode(", ", $keys) . ") VALUES (";

            for ($i=0; $i < count($keys) - 1; $i++){
                $query .= "?, ";
            }
            $query .= "?)";

            $stmt = $this->connection->prepare($query);

            if (!empty($values)){
                $typeOfBind = ""; 
                for ($i=0; $i < count($values); $i++){
                    if ($keys[$i] === "teacher_id" && $keys[$i] === "id"){
                        $typeOfBind .= "i";
                    } else {
                        $typeOfBind .= "s";
                    }
                }
            }
            
            
            $stmt->bind_param($typeOfBind,...$values);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        
        
    }
    

    public function update($id, $data) {
        $keys = array_keys($data);
        $values = array_values($data);

        foreach ($values as $value) {
            $value = $this->connection->real_escape_string($value);
            $value = trim($value);
        }


        if (!empty($keys)){
            $query = "UPDATE assignments SET ";

            for ($i=0; $i < count($keys) - 1; $i++){
                $query .= ($keys[$i] . "=? , ");
            }
            $query .= ($keys[count($keys) - 1] . "=?");

            $query .= " WHERE id=?";

            $stmt = $this->connection->prepare($query);

            if (!empty($values)){
                $typeOfBind = ""; 
                for ($i=0; $i < count($values); $i++){
                    if ($keys[$i] === "teacher_id" && $keys[$i] === "id"){
                        $typeOfBind .= "i";
                    } else {
                        $typeOfBind .= "s";
                    }
                }
                $typeOfBind .= "i";
                $values[] = $id;
            }

            $stmt->bind_param($typeOfBind,...$values);
            
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM assignments WHERE id=?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i',$id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } 
}

?>