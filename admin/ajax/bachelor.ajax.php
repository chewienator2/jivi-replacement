<?php
session_start();

//include the autoloader class
include('../../autoloader.php');

//check request method
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $response = array();
    $error = array();
    
    $bachelor = new Bachelor();
    
    //EDIT bachelor
    if($_POST['a'] == 'e'){
       
        if(strlen($_POST['id']) == 0){
            $error['id'] = "Something went wrong please try again.";
        }
        if(strlen($_POST['name']) == 0){
            $error['name'] = 'Name can\'t be empty.';
        }
        if(strlen($_POST['cricos']) == 0){
            $error['cricos'] = 'Cricos code can\'t be empty.';
        }
        
        if(strlen($error) == 0){
            $edit = $bachelor->edit($_POST['id'], $_POST['name'], $_POST['cricos'], $_POST['active']);
            
            if($edit == true){ //edited successfully
                $response = array('success' => true, 'redirect'=> 'none', 'msg' => 'Bachelor Edited succesfully.');
            }else{
                $response = array('success' => false, 'redirect'=> 'none', 'msg' => 'Something went wrong please try again.');
            }
        }else{
            $response = array('success' => false, 'redirect'=> 'none', 'msg' => $error);
        }
    }
    
    //CREATE bachelor
    if($_POST['a'] == 'n'){
        
        if(strlen($_POST['name']) == 0){
            $error = 'Name can\'t be empty.';
        }
        if(strlen($_POST['cricos']) == 0){
            $error = 'Cricos code can\'t be empty.';
        }
        
        if(strlen($error) == 0){
            $new = $bachelor->create($_POST['name'], $_POST['cricos']);
            
            if($new > 0){ //created successfully
                $response = array('success' => true, 'redirect'=> "bachelor_list.php", 'msg' => 'Bachelor Created succesfully.');
            }else{
                $response = array('success' => false, 'redirect'=> 'none', 'msg' => 'Something went wrong please try again.');
            }
        }else{
            $response = array('success' => false, 'redirect'=> 'none', 'msg' => $error);
        }
    }
    
    //DEACTIVATE bachelor
    if($_POST['a'] == 'd'){
        
        $error = "";
        
        if(strlen($_POST['id']) == 0){
            $error = 'System error please try again later';
        }
        
        if(strlen($error) == 0){
            $deactivate = $bachelor->deactivate($_POST['id']);
            
            if($deactivate){ //created successfully
                $response = array('success' => true, 'redirect'=> 'none', 'msg' => 'Bachelor Deleted succesfully.');
            }else{
                $response = array('success' => false, 'redirect'=> 'none', 'msg' => 'Something went wrong please try again.');
            }
        }else{
            $response = array('success' => false, 'redirect'=> 'none', 'msg' => $error);
        }
    }
    
    echo json_encode($response);
}

?>