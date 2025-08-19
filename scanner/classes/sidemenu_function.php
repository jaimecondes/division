<?php 
function getmysubjects($host,$db,$user,$hpassword){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$hpassword);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_subjects where  created_by=:created and  hidestatus=0 order by id desc');
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getuserlevel($id,$host,$db,$user,$password){
    try {
        
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from users where email=:id');
        $sl->bindParam(':id', $id, PDO::PARAM_STR);
        $sl->execute();
        $level="";
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $rc){
            $level=$rc['userlevel'];
        }
        return $level;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
?>