<?php 
function authcode($userID,$code,$host,$db,$user,$password){
    try {
        $expire=date("Y-m-d");
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
                $slc=$pdo->prepare('select * from auth where auth_code=:code and userID=:userID');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->bindParam(':userID', $userID, PDO::PARAM_STR);
                
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                
                 if(count($resultc)>0){
                    return 1;
                    
                 }else{
                    return 0;
                 }

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function authreset($code,$host,$db,$user,$password){
    try {
        $expire=date("Y-m-d");
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
                $slc=$pdo->prepare('select * from auth where auth_code=:code and used!=1');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                
                 if(count($resultc)>0){
                    $used=0;
                    foreach($resultc as $row){
                        $used=$row['used'];
                    }
                    return 1;
                    
                 }else{
                    return 0;
                 }

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function insertcode($userID,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        //generating
        $original_date=date("Y-m-d");
        $date = new DateTime($original_date);
        $date->modify('+1 day');
        $expiry = $date->format('Y-m-d');
        //token
        $pass="Kk9566678@!";
        $data = $userID;
        $sha512_hash = hash('sha512', $pass);
        $encryption_key = $sha512_hash;
        $encryption_algo = "AES-256-CBC";
        $iv = random_bytes(openssl_cipher_iv_length($encryption_algo));
        $encrypttoken = openssl_encrypt($data, $encryption_algo, $encryption_key, 0, $iv);
        $token=base64_encode($encrypttoken);
        $token=$token.$sha512_hash;
        //inserting
        
        $ins=$pdo->prepare('insert into auth (auth_code,userID,expiry,used)values(:code,:userID,:expiry,0)');
        $ins->bindParam(':code', $token, PDO::PARAM_STR);
        $ins->bindParam(':userID', $userID, PDO::PARAM_STR);
        $ins->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $ins->execute();
        
        return $token;     

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function newpassword($email,$pass,$code,$host,$db,$user,$password){
    try {
        $pass=md5($pass);
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
                $slc=$pdo->prepare('update users set password=:pass where email=:email');
                 $slc->bindParam(':pass', $pass, PDO::PARAM_STR);
                 $slc->bindParam(':email', $email, PDO::PARAM_STR);
                 $slc->execute();

                 $ins=$pdo->prepare('update auth set used=1 where auth_code=:code ');
                 $ins->bindParam(':code', $code, PDO::PARAM_STR);
                 $ins->execute();

                 return 1;   

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getemail($code,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
                $slc=$pdo->prepare('select * from auth where auth_code=:code');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 $email="";
                foreach($resultc as $row){
                    $email=$row['userID'];

                } 
                return $email;

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
?>