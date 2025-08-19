<?php 
function getuserID($email,$host,$db,$user,$password){
    try {
        
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from users where email=:email');
        $sl->bindParam(':email', $email, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row){
            return $row['id'];
        }
           
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
?>