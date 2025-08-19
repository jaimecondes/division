<?php 
function recordLog($code,$type,$logdate,$host,$user,$db,$password){
    
    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt=$pdo->prepare('select id from users where qr_code=:code');
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userID="";
        foreach($result as $res){
            $userID=$res['id'];
        }
        if($userID){
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt=$pdo->prepare('insert into logs(log_time,qr_code,log_type,userID)values(:logdate,:code,:type,:userID)');
            $stmt->bindParam(':logdate', $logdate, PDO::PARAM_STR);
            $stmt->bindParam(':code', $code, PDO::PARAM_STR);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return 1;
        }else{
            return "QR Code invalid!";
            
        }
        // Set PDO error mode to exception
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
?>