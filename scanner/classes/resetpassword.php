<?php 
function resetnow($newpass,$code,$host,$db,$user,$password){
    $newpassword=md5($newpass);
    try {
       
                $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
                $slc=$pdo->prepare('select * from password_reset where auth_key=:code');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                
                 if(count($resultc)>0){
                    $email="";
                    foreach($resultc as $row){
                        $email=$row['generated_by'];
                    }
                   
                    $slcc=$pdo->prepare('update users set password=:pass where email=:email');
                    $slcc->bindParam(':pass', $newpassword, PDO::PARAM_STR);
                    $slcc->bindParam(':email', $email, PDO::PARAM_STR);
                    $slcc->execute();

                    return 1;
                 }else{
                    return 0;
                 }

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function authkey($code,$host,$db,$user,$password){
    try {
        $expire=date("Y-m-d H:i:s");
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
                $slc=$pdo->prepare('select * from password_reset where auth_key=:code and expiry>:expiry');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->bindParam(':expiry', $expire, PDO::PARAM_STR);
                
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
function resetpass($email,$host,$user,$db,$password){
    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select email,src from users where email=:email');
        $sl->bindParam(':email', $email, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)>0){
            foreach ($result as $row){
                if($row['src']=='google'){
                    return "You used google account to this website sign in with your google account instead!";
                }else{
                    return 1;    
                }
            }
           
        }else{
          return "Email Doesn't Exist!";
        }
       
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function generatecode($email,$host,$user,$db,$password){
    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $dateTime = new DateTime('now');

        // Add one hour to the DateTime object
        $dateTime->add(new DateInterval('PT1H'));

        // Display the updated DateTime
        $expiry=$dateTime->format('Y-m-d H:i:s');
        $data="jaime";
        $sha512_hash = hash('sha512', $data);
        $encryption_key = $sha512_hash;
        $encryption_algo = "AES-256-CBC";
        $iv = random_bytes(openssl_cipher_iv_length($encryption_algo));
        $encrypttoken = openssl_encrypt($data, $encryption_algo, $encryption_key, 0, $iv);
        $token=base64_encode($encrypttoken);
        $token=$token.$sha512_hash;

                        //inserting
        $ins=$pdo->prepare('insert into password_reset(auth_key,generated_by,expiry)values(:code,:userID,:expiry)');
        $ins->bindParam(':code', $token, PDO::PARAM_STR);
        $ins->bindParam(':userID', $email, PDO::PARAM_STR);
        $ins->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $ins->execute();
       
        return $token; 
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
?>