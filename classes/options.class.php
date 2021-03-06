<?php 
class Options extends Database{
    
    public $value;
    
    public function __construct(){
        parent::__construct();
    }
    
    //flexible method to query different options general to the system
    public function getOption($option){
        $query = "SELECT option_value FROM options WHERE option_name = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s', $option);
        $statement->execute();
        
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        $this->value = $row['option_value'];
        
        return $this->value;
    }
    
    //flexible method to query different options general to the system
    public function switchOption($option,$value){
        $query = "UPDATE options SET option_value =? WHERE option_name = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('ss', $value, $option);
        $statement->execute();
        
        $success = $statement->execute() ? true : false;
        
        return $success;
    }
    
}