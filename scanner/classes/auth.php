<?php 
function authuser($email,$password,$host,$user,$db,$hpassword){
   
    $pass=md5($password);
    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $hpassword);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from users where email=:email');
        $sl->bindParam(':email', $email, PDO::PARAM_STR);
        
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)>0){
            
            $expirationTime = time() + (3600 * 24 * 30); // Expiration time set to 30 days
            foreach($result as $row){
                if($row['src']=='form'){
                    if($row['password']==$pass){
                        setcookie('user_email', $row['email'], $expirationTime);
                        setcookie('user_name',  $row['fname'], $expirationTime);
                        setcookie('user_lastname', $row['lname'], $expirationTime);
                        setcookie('user_lastname', $row['lname'], $expirationTime);
                        
                        //generating
                        $original_date=date("Y-m-d");
                        $date = new DateTime($original_date);
                        $date->modify('+1 day');
                        $expiry = $date->format('Y-m-d');
                        //token
                        $data = $row['email'];
                        $sha512_hash = hash('sha512', $pass);
                        $encryption_key = $sha512_hash;
                        $encryption_algo = "AES-256-CBC";
                        $iv = random_bytes(openssl_cipher_iv_length($encryption_algo));
                        $encrypttoken = openssl_encrypt($data, $encryption_algo, $encryption_key, 0, $iv);
                        $token=base64_encode($encrypttoken);
                        $token=$token.$sha512_hash;
                        //inserting
                        $ins=$pdo->prepare('insert into auth (auth_code,userID,expiry)values(:code,:userID,:expiry)');
                        $ins->bindParam(':code', $token, PDO::PARAM_STR);
                        $ins->bindParam(':userID', $row['email'], PDO::PARAM_STR);
                        $ins->bindParam(':expiry', $expiry, PDO::PARAM_STR);
                        $ins->execute();
                        setcookie('authorized', $token, $expirationTime);

                        return 1;
                    }else{
                        return "Password Incorrect!";
                    }
                }else{
                    return "Sorry,Sign in using your google account instead!";
                }
                
                
            }
            
           
        }else{
            return "Email Doesn't Exist!";
        }
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function authuser2($email,$host,$user,$db,$hpassword){
   
    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $hpassword);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from users where email=:email');
        $sl->bindParam(':email', $email, PDO::PARAM_STR);
        
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)>0){
            
            $expirationTime = time() + (3600 * 24 * 30); // Expiration time set to 30 days
            foreach($result as $row){
                if($row['src']=='google'){
                        setcookie('user_email', $row['email'], $expirationTime);
                        setcookie('user_name',  $row['fname'], $expirationTime);
                        setcookie('user_lastname', $row['lname'], $expirationTime);
                         //generating
                         $original_date=date("Y-m-d");
                         $date = new DateTime($original_date);
                         $date->modify('+1 day');
                         $expiry = $date->format('Y-m-d');
                         //token
                         $data = $row['email'];
                         $sha512_hash = hash('sha512', $data);
                         $encryption_key = $sha512_hash;
                         $encryption_algo = "AES-256-CBC";
                         $iv = random_bytes(openssl_cipher_iv_length($encryption_algo));
                         $encrypttoken = openssl_encrypt($data, $encryption_algo, $encryption_key, 0, $iv);
                         $token=base64_encode($encrypttoken);
                         $token=$token.$sha512_hash;
                         //inserting
                         $ins=$pdo->prepare('insert into auth (auth_code,userID,expiry)values(:code,:userID,:expiry)');
                         $ins->bindParam(':code', $token, PDO::PARAM_STR);
                         $ins->bindParam(':userID', $row['email'], PDO::PARAM_STR);
                         $ins->bindParam(':expiry', $expiry, PDO::PARAM_STR);
                         $ins->execute();
                         setcookie('authorized', $token, $expirationTime);
                        return 1;
                    
                }else{
                    return "Sorry,Sign in using your login form instead!";
                }
                
                
            }
            
           
        }else{
            return "Email Doesn't Exist!";
        }
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
?>