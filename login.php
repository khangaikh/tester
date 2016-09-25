<?php
    require_once "config.php";

    session_start();
    class Result {}
    $response = new Result();
    //$date = null;
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
        $stmt->bindValue(':email', 'admin');
        $stmt->bindParam(':password', $_POST['password']);
        $stmt->execute();
        $results = $stmt->fetchAll();
    }
    catch( PDOException $Exception ) {
        // PHP Fatal Error. Second Argument Has To Be An Integer, But PDOException::getCode Returns A
        // String.
        $response = $Exception->getCode( );
    }
    if(count($results)>0){
        $_SESSION['user'] ='admin';
        //$_SESSION['secret'] = $encrypted;
        echo 1;
    }else{
        echo 2;
    }
    
?>