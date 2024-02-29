<?php

require_once("Database.php");

class RecordModel {
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

        $query = "SELECT $selectClause FROM cve_record ";

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
                $query .= "description LIKE CONCAT('%',?,'%')";
            }
            
        }

        $query .= $limitClause;
        
        $stmt = $this->connection->prepare($query);

        if (!empty($values) || $searchKey){
            $typeOfBind = ""; 

            if (!empty($values)){
                for ($i=0; $i < count($values); $i++){
                    if ($keys[$i] == "CVE_ID" || $keys[$i] == "Mestasploit" || $keys[$i] == "Verified"){
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
            
            $query = "INSERT INTO cve_record (" . implode(", ", $keys) . ") VALUES (";

            for ($i=0; $i < count($keys) - 1; $i++){
                $query .= "?, ";
            }
            $query .= "?)";

            $stmt = $this->connection->prepare($query);

            if (!empty($values)){
                $typeOfBind = ""; 
                for ($i=0; $i < count($values); $i++){
                    if ($keys[$i] == "CVE_ID" || $keys[$i] == "Mestasploit" || $keys[$i] == "Verified"){
                        $typeOfBind .= "i";
                    } else {
                        $typeOfBind .= "s";
                    }
                }
            }
            
            
            $stmt->bind_param($typeOfBind,...$values);

            try {
                $stmt->execute();
                return "Success";
            } catch (Exception $e){
        
                if (strpos($e->getMessage(), "Duplicate entry" ) !== NULL){
                    return "ID existed";
                } else {
                    return "Failed";
                }
            }
        } else {
            return "Failed";
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
            $query = "UPDATE cve_record SET ";

            for ($i=0; $i < count($keys) - 1; $i++){
                $query .= ($keys[$i] . "=? , ");
            }
            $query .= ($keys[count($keys) - 1] . "=?");

            $query .= " WHERE CVE_ID=?";

            $stmt = $this->connection->prepare($query);

            if (!empty($values)){
                $typeOfBind = ""; 
                for ($i=0; $i < count($values); $i++){
                    if ($keys[$i] == "CVE_ID" || $keys[$i] == "Mestasploit" || $keys[$i] == "Verified"){
                        $typeOfBind .= "i";
                    } else {
                        $typeOfBind .= "s";
                    }
                }
                $typeOfBind .= "i";
                $values[] = $id;
            }

            $stmt->bind_param($typeOfBind,...$values);
            
            try {
                $stmt->execute();
                return "Success";
            } catch (Exception $e){
                echo $e->getMessage();
                if (strpos($e->getMessage(), "Duplicate entry" ) !== NULL){
                    return "ID existed";
                } else {
                    return "Failed";
                }
            }
        } else {
            return "Failed";
        }

        

        
    }

    public function delete($id) {
        $query = "DELETE FROM cve_record WHERE CVE_ID=?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i',$id);
        $result = $stmt->execute();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function selectFilter($name){
        $query = "SELECT DISTINCT $name FROM cve_record";
        $result = $this->connection->query($query);
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
    
}

?>