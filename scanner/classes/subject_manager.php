<?php 

function removemetric($id,$host,$db,$user,$password){
    try {
        
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('delete from metrics where id=:id');
        $sl->bindParam(':id', $id, PDO::PARAM_STR);
        $sl->execute();
        return 1;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function savemetrics($name,$percent,$subID,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from metrics where metric_name=:name and owner=:created and subID=:subID');
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->bindParam(':subID', $subID, PDO::PARAM_STR);
        $sl->bindParam(':name', $name, PDO::PARAM_STR);
        
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)>0){
            $sum=0;
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $c= $pdo->prepare('select sum(percentage) as count from metrics where owner=:created and subID=:subID and metric_name!=:name');
            $c->bindParam(':created', $createby, PDO::PARAM_STR);
            $c->bindParam(':subID', $subID, PDO::PARAM_STR);
            $c->bindParam(':name', $name, PDO::PARAM_STR);
           
            $c->execute();
            $resultc = $c->fetchAll(PDO::FETCH_ASSOC);
            foreach($resultc as $rc){
                $sum=$rc['count']+$percent;
            }
            if($sum>100){
                return 3;
            }else{
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $in= $pdo->prepare('update metrics set metric_name=:name,percentage=:percent where subID=:subID and metric_name=:name and owner=:created');
                $in->bindParam(':created', $createby, PDO::PARAM_STR);
                $in->bindParam(':subID', $subID, PDO::PARAM_STR);
                $in->bindParam(':name', $name, PDO::PARAM_STR);
                $in->bindParam(':percent', $percent, PDO::PARAM_STR);
                $in->execute();
                return 2;
            }

        }else{
            $sum=0;
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $c= $pdo->prepare('select sum(percentage) as count from metrics where owner=:created and subID=:subID');
            $c->bindParam(':created', $createby, PDO::PARAM_STR);
            $c->bindParam(':subID', $subID, PDO::PARAM_STR);
           
            $c->execute();
            $resultc = $c->fetchAll(PDO::FETCH_ASSOC);
            foreach($resultc as $rc){
                $sum=$rc['count']+$percent;
            }
            if($sum>100){
                return 3;
            }else{
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $in= $pdo->prepare('INSERT INTO metrics (metric_name,owner,subID,percentage)
             VALUES (:name, :created ,:subID ,:percent)');
            $in->bindParam(':created', $createby, PDO::PARAM_STR);
            $in->bindParam(':subID', $subID, PDO::PARAM_STR);
            $in->bindParam(':name', $name, PDO::PARAM_STR);
            $in->bindParam(':percent', $percent, PDO::PARAM_STR);
            $in->execute();
            return 1;
            }
        }
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
        
}
function getmetricsratings($subID,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from metrics where subID=:subID');
        $sl->bindParam(':subID', $subID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
       return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getmetrics($subID,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from metrics where owner=:created and subID=:subID');
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->bindParam(':subID', $subID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getmetricsjoined($subID,$host,$db,$user,$password){
    try {
       
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from metrics where subID=:subID');

        $sl->bindParam(':subID', $subID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getscore($actID,$studID,$host,$db,$user,$password){
    try {
       
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity_score where actID=:actID and studID=:studID');
        $sl->bindParam(':studID', $studID, PDO::PARAM_STR);
        $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getsubmetrics($host,$db,$user,$hpassword){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$hpassword);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_subjects where  created_by=:created and hidestatus=0 order by id desc');
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function createsubject($title,$desc,$host,$user,$db,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        $codex=sha1(time());
        $code = mb_substr($codex, 0, 6);
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $status=0;
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_subjects where subject_title=:title and created_by=:created and hidestatus=0');
        $sl->bindParam(':title', $title, PDO::PARAM_STR);
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)>0){
            return 'Subject Already Exist!';
        }else{
            $datecreate=date("Y-m-d");
            $stmt = $pdo->prepare('INSERT INTO tbl_subjects (subject_title, subject_description,date_created,subject_code,created_by,hidestatus)
             VALUES (:title, :desc ,:date ,:code,:createdby,:hidestatus)');
    
        // Bind the parameter values to the named placeholders
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
        $stmt->bindParam(':date', $datecreate, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':createdby', $createby, PDO::PARAM_STR);
        $stmt->bindParam(':hidestatus', $status, PDO::PARAM_STR);
    
    
        // Execute the prepared statement
        $stmt->execute();
        return 1;
        }
        // Prepare the SQL statement with named placeholders
        
    
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getsubjects($host,$db,$user,$hpassword){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$hpassword);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_subjects where  created_by=:created and hidestatus=0 order by id desc');
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        $subject=array();
        foreach($result as $row){
            array_push($subject,$row['subject_title']);
        }
        return $subject;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getallsubjects($host,$db,$user,$hpassword){
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
function getonesubject($subcode,$host,$db,$user,$hpassword){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$hpassword);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_subjects where  subject_code=:code order by id desc');
        $sl->bindParam(':code', $subcode, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function joinsubject($subcode,$uID,$host,$user,$db,$password){
    try {
        
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select id from tbl_subjects where  subject_code=:code');
        $sl->bindParam(':code', $subcode, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)>0){

            $slc=$pdo->prepare('select studID from stud_subjects where subject_code=:code and studID=:studID');
            $slc->bindParam(':code', $subcode, PDO::PARAM_STR);
            $slc->bindParam(':studID', $uID, PDO::PARAM_STR);
            $slc->execute();
            $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
            if(count($resultc)>0){
                return "You have already Joined this Class. Please check your 'My Classes' tab";
            }else{
                $stmt = $pdo->prepare('INSERT INTO stud_subjects (studID, subject_code)
                VALUES (:studID, :code)');
        
            
                $stmt->bindParam(':studID', $uID, PDO::PARAM_STR);
                $stmt->bindParam(':code', $subcode, PDO::PARAM_STR);
            
           
                $stmt->execute();
                return 1;
            }
            
        }else{
            return "Sorry Class Code Doesn't Exist";
        }
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getjoinedsubjects($uID,$host,$db,$user,$hpassword){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$hpassword);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from stud_subjects where studID=:studID');
        $sl->bindParam(':studID', $uID, PDO::PARAM_STR);
        $sl->execute();
        $arr=array();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)>0){
            foreach($result as $row){
                $code=$row['subject_code'];
                $slc=$pdo->prepare('select * from tbl_subjects where subject_code=:code and hidestatus=0');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 array_push($arr,$resultc);   
            }
            return $arr;

        }else{
            return $arr;
        }
        
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getsubjectownner($code,$uemail,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
           
                
                $slc=$pdo->prepare('select * from tbl_subjects where subject_code=:code and created_by=:email and hidestatus=0');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->bindParam(':email', $uemail, PDO::PARAM_STR);
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
function session_save($host,$db,$user,$password){
    try {
        $by=$_COOKIE['user_email'];
       
        $code=md5('megaxsolutions@gmail.com'.time());
        $code=base64_encode($code);
        $hash=hash("sha256", $code);
        $code=$code."".$hash;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
                    $ins=$pdo->prepare('insert into session_generator(generated_by,session_code)
                    values(:by,:code)');
                    $ins->bindParam(':by', $by, PDO::PARAM_STR);
                    $ins->bindParam(':code', $code, PDO::PARAM_STR);
                    $ins->execute();
                 

                 return $code;
                 

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function saveactivity($mID,$form,$type,$subjectID,$code,$teacher,$details,$title,$duedate,$sessioncode,$shuffle,$onpagestart,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('select * from tbl_activity where sessioncode=:sessioncode');
        $slcp->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
         $arr =array(); 
        $slcp->execute();
        $resultcp = $slcp->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultcp)>0){
            $actID="";
            foreach($resultcp as $row){
                $actID=$row['id'];
            }
            $updatestat=1;
            $datecreated=date("Y-m-d");
                $slc=$pdo->prepare('update tbl_activity set teacherID=:teacher,activity_name=:title,date_created=:datecreated,
                subjectID=:subjectID,date_end=:duedate,details=:details,subject_code=:code,date_start=:datestart,type=:type,form=:form,shuffle=:shuffle,onpagestart=:onpagestart where sessioncode=:sesscode');
                 $slc->bindParam(':teacher', $teacher, PDO::PARAM_STR);
                 $slc->bindParam(':title', $title, PDO::PARAM_STR);
                 $slc->bindParam(':datecreated', $datecreated, PDO::PARAM_STR);
                 $slc->bindParam(':subjectID', $subjectID, PDO::PARAM_STR);
                 $slc->bindParam(':duedate', $duedate, PDO::PARAM_STR);
                 $slc->bindParam(':details', $details, PDO::PARAM_STR);
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->bindParam(':datestart', $datecreated, PDO::PARAM_STR);
                 $slc->bindParam(':type', $type, PDO::PARAM_STR);
                 $slc->bindParam(':form', $form, PDO::PARAM_STR);
                 $slc->bindParam(':sesscode', $sessioncode, PDO::PARAM_STR);
                 $slc->bindParam(':shuffle', $shuffle, PDO::PARAM_STR);
                 $slc->bindParam(':onpagestart', $onpagestart, PDO::PARAM_STR);
                 $slc->execute();
                 $lastInsertedId = $pdo->lastInsertId();
                 array_push($arr,$updatestat);
                 array_push($arr,$actID);
            return $arr;
        } else{
           
                
            $updatestat=0;
                $datecreated=date("Y-m-d");
                $slc=$pdo->prepare('insert into tbl_activity(teacherID,activity_name,date_created,subjectID,date_end,details,subject_code,date_start,type,form,sessioncode,shuffle,onpagestart,metricID)
                values(:teacher,:title,:datecreated,:subjectID,:duedate,:details,:code,:datestart,:type,:form,:sesscode,:shuffle,:onpagestart,:metricID)');
                 $slc->bindParam(':teacher', $teacher, PDO::PARAM_STR);
                 $slc->bindParam(':title', $title, PDO::PARAM_STR);
                 $slc->bindParam(':datecreated', $datecreated, PDO::PARAM_STR);
                 $slc->bindParam(':subjectID', $subjectID, PDO::PARAM_STR);
                 $slc->bindParam(':duedate', $duedate, PDO::PARAM_STR);
                 $slc->bindParam(':details', $details, PDO::PARAM_STR);
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->bindParam(':datestart', $datecreated, PDO::PARAM_STR);
                 $slc->bindParam(':type', $type, PDO::PARAM_STR);
                 $slc->bindParam(':form', $form, PDO::PARAM_STR);
                 $slc->bindParam(':sesscode', $sessioncode, PDO::PARAM_STR);
                 $slc->bindParam(':shuffle', $shuffle, PDO::PARAM_STR);
                 $slc->bindParam(':onpagestart', $onpagestart, PDO::PARAM_STR);
                 $slc->bindParam(':metricID', $mID, PDO::PARAM_STR);
                 $slc->execute();
                 $lastInsertedId = $pdo->lastInsertId();
                 array_push($arr,$updatestat);
                 array_push($arr,$lastInsertedId);
            return $arr;
        }  
                
                 

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function checksubmission($sessioncode,$useremail,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('select * from tbl_activity_response where sessioncode=:sessioncode and studID=:studID');
        $slcp->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
        $slcp->bindParam(':studID', $useremail, PDO::PARAM_STR);
         $arr =array(); 
        $slcp->execute();
        $resultcp = $slcp->fetchAll(PDO::FETCH_ASSOC);
        return count($resultcp);
          
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function checktimer($sessioncode,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('select * from tbl_activity where sessioncode=:sessioncode');
        $slcp->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
        $slcp->execute();
        $due="";
        $resultcp = $slcp->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultcp as $row){
            $due=$row['date_end'];
        }
        if(!empty($due)){
            $current=date('Y-m-d H:i:s');
                if ($current>$due){
                    return "expired";
                }else{
                    return 1;
                }
        }else{
            return 0;
        }
          
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
    
}
function checktimer2($studID,$sessioncode,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('select * from tbl_onpagestart where sessioncode=:sessioncode and studID=:studID');
        $slcp->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
        $slcp->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slcp->execute();
        $due="";
        $resultcp = $slcp->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultcp as $row){
            $due=$row['dueend'];
        }
        if(!empty($due)){
            $current=date('Y-m-d H:i:s');
                if ($current>$due){
                    return "expired";
                }else{
                    return 1;
                }
        }else{
            return 0;
        }
          
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
    
}
function checkpoints($actID,$qID,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('select a.qitem,a.id,a.points,a.activityID,b.item_name,b.correct_ans from tbl_activity_questions as a, question_parameters as b 
        where a.id=:qID and b.questionID=:qID and a.activityID=:actID and b.activityID=:actID');
        $slcp->bindParam(':actID', $actID, PDO::PARAM_STR);
        $slcp->bindParam(':qID', $qID, PDO::PARAM_STR);
        $slcp->execute();
        $resultcp = $slcp->fetchAll(PDO::FETCH_ASSOC);
        return $resultcp;
          
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function distinctitem($actID,$qID,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('select points from tbl_activity_questions where id=:qID and activityID=:actID');
        $slcp->bindParam(':actID', $actID, PDO::PARAM_STR);
        $slcp->bindParam(':qID', $qID, PDO::PARAM_STR);
        $slcp->execute();
        $resultcp = $slcp->fetchAll(PDO::FETCH_ASSOC);
        return $resultcp;
          
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function addsubmission($actID,$qID,$sessioncode,$ans,$useremail,$host,$db,$user,$password){
    try {
        $times=1;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('insert into tbl_activity_response(questionID,studID,ans,sessioncode,submit_times,actID)
        values(:qID,:studID,:ans,:sessioncode,:times,:actID)');
        $slcp->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
        $slcp->bindParam(':studID', $useremail, PDO::PARAM_STR);
        $slcp->bindParam(':ans', $ans, PDO::PARAM_STR);
        $slcp->bindParam(':times', $times, PDO::PARAM_STR);
        $slcp->bindParam(':qID', $qID, PDO::PARAM_STR);
        $slcp->bindParam(':actID', $actID, PDO::PARAM_STR);
        $slcp->execute();
        
        return true;
          
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function addscore($actID,$useremail,$score,$total,$host,$db,$user,$password){
    try {
        $times=1;
        if(empty($score)){
            $score=0;
        }
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slcp=$pdo->prepare('insert into tbl_activity_score(actID,studID,score,totalpoints)
        values(:actID,:studID,:score,:total)');
        $slcp->bindParam(':studID', $useremail, PDO::PARAM_STR);
        $slcp->bindParam(':actID', $actID, PDO::PARAM_STR);
        $slcp->bindParam(':score', $score, PDO::PARAM_STR);
        $slcp->bindParam(':total', $total, PDO::PARAM_STR);
        $slcp->execute();
        
        return true;
          
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function updateactivity($id,$details,$title,$duedate,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
           
                $datecreated=date("Y-m-d");
                $slc=$pdo->prepare('update tbl_activity set activity_name=:title,date_end=:duedate,details=:details where id=:id');
                
                 $slc->bindParam(':title', $title, PDO::PARAM_STR);
                 $slc->bindParam(':duedate', $duedate, PDO::PARAM_STR);
                 $slc->bindParam(':details', $details, PDO::PARAM_STR);
                 $slc->bindParam(':id', $id, PDO::PARAM_STR);
                 $slc->execute();
                 return 1;
                 

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function savefile($activityID,$filename,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
                 if(!empty($filename)){
                    $ins=$pdo->prepare('insert into attachments(activityID,filename)
                    values(:activityID,:filename)');
                    $ins->bindParam(':activityID', $activityID, PDO::PARAM_STR);
                    $ins->bindParam(':filename', $filename, PDO::PARAM_STR);
                    $ins->execute();
                 }

                 return 1;
                 

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function saveactfile($comment,$actID,$studID,$filename,$code,$host,$db,$user,$password){
    try {
        $datesubmit=date("Y-m-d h:i:s");
        $status=1;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
         
                 $slc=$pdo->prepare('select * from tbl_turnin where actID=:actID and studID=:studID and code=:code');
                 $slc->bindParam(':actID', $actID, PDO::PARAM_STR);
                 $slc->bindParam(':studID', $studID, PDO::PARAM_STR);
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                
                 if(count($resultc)>0){
                    return "You already turned in!";
                 }else{
                    $ins=$pdo->prepare('insert into tbl_turnin(actID,date_submitted,studID,attachments,status,code,comments)
                    values(:actID,:date_submitted,:studID,:filename,:status,:code,:comment)');
                    $ins->bindParam(':actID', $actID, PDO::PARAM_STR);
                    $ins->bindParam(':date_submitted', $datesubmit, PDO::PARAM_STR);
                    $ins->bindParam(':studID', $studID, PDO::PARAM_STR);
                    $ins->bindParam(':filename', $filename, PDO::PARAM_STR);
                    $ins->bindParam(':status', $status, PDO::PARAM_STR);
                    $ins->bindParam(':code', $code, PDO::PARAM_STR);
                    $ins->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $ins->execute();
                    return 1;
                 }

                 
                 

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function savepostfile($mtitle,$mdetails,$filename,$posttype,$mcode,$vidlink,$host,$db,$user,$password){
    try {
       
        $ownerID=$_COOKIE['user_email'];
               $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
         
                    $ins=$pdo->prepare('insert into materials(title,details,fileName,ownerID,classcode,posttype,vidlink)
                    values(:title,:details,:filename,:ownerID,:code,:posttype,:vidlink)');
                    $ins->bindParam(':title', $mtitle, PDO::PARAM_STR);
                    $ins->bindParam(':details', $mdetails, PDO::PARAM_STR);
                    $ins->bindParam(':filename', $filename, PDO::PARAM_STR);
                    $ins->bindParam(':ownerID', $ownerID, PDO::PARAM_STR);
                    $ins->bindParam(':code', $mcode, PDO::PARAM_STR);
                    $ins->bindParam(':posttype', $posttype, PDO::PARAM_STR);
                    $ins->bindParam(':vidlink', $vidlink, PDO::PARAM_STR);
                    $ins->execute();
                    return 1;
    
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function removematerial($id,$host,$db,$user,$password){
    try {
       
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
             $ins=$pdo->prepare('delete from materials where id=:id');
             $ins->bindParam(':id', $id, PDO::PARAM_STR);
             $ins->execute();
             
             return 1;

} catch (PDOException $e) {
 echo 'Query failed: ' . $e->getMessage();
}
}
function getposts($mcode,$host,$db,$user,$password){
    try {
       
               $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
                    $ins=$pdo->prepare('select * from materials where classcode=:code order by id desc');
                    $ins->bindParam(':code', $mcode, PDO::PARAM_STR);
                    $ins->execute();
                    $resultc = $ins->fetchAll(PDO::FETCH_ASSOC);
                    return $resultc;
    
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getsubjectID($code,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
           
                
                $slc=$pdo->prepare('select * from tbl_subjects where subject_code=:code');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 foreach($resultc as $row){
                    return $row['id'];
                 }
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function removesubject($id,$host,$user,$db,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
           
                
                $slc=$pdo->prepare('update tbl_subjects set hidestatus=1 where id=:id');
                 $slc->bindParam(':id', $id, PDO::PARAM_STR);
                
                 $slc->execute();
                 return 1;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}

function getstudents($code,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
                $slc=$pdo->prepare('SELECT a.studID,b.fname,b.lname FROM stud_subjects as a, users as b where a.studID=b.id and a.subject_code=:code order by b.lname');
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 return $resultc;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getstudentsemail($id,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
                $slc=$pdo->prepare('select email from users where id=:id');
                 $slc->bindParam(':id', $id, PDO::PARAM_STR);
                
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 $email="";
                 foreach($resultc as $t){
                    $email=$t['email'];
                 }
                 return  $email;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getstudentsname($id,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
                $slc=$pdo->prepare('select * from users where id=:id or email=:id order by lname asc');
                 $slc->bindParam(':id', $id, PDO::PARAM_STR);
                
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 return $resultc;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getconvertedgrademetric($email,$subID,$subcode,$host,$db,$user,$password){
    $display=[];
    $totalscore=0;
    $metricratings=getmetricsratings($subID,$host,$db,$user,$password);
    foreach($metricratings as $mrating){
        $mID=$mrating['id'];
        $mname=$mrating['metric_name'];
        $percentage=$mrating['percentage'];
        $percent=$percentage/100;
        $metricname=strtolower($mname);
        $word_to_find = "attendance";
        $attendanceratings=0;
        $score=0;
       
    if (strpos($metricname, $word_to_find) !== false) {
        $score=getattendanceratingmetric($email,$subID,$host,$db,$user,$password);
    } else {
        $score=getactivityratingmetric($mID,$email,$subcode,$host,$db,$user,$password);
    }
    $totalscore+=$score*$percent;
}
    array_push($display,$totalscore);
    $singlepercent=number_format($totalscore,0);
    
    try {
        $grade="";
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $arr=[];
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $slc=$pdo->prepare("select * from gradeconverter where percentage=:percentage");
        $slc->bindParam(':percentage', $singlepercent, PDO::PARAM_STR);

        $slc->execute();
        $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultc)>0){
           
            foreach($resultc as $row){
                $grade=$row['grade_equiv'];
            }
            
        }
        if($totalscore<55){
            $grade=5.0;
        }
        if($totalscore>95){
            $grade=1.0;
        }
        if(empty($totalscore)){
            $grade="N/A";
        }
        array_push($display,$grade);
        return  $display;
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
 }

function getconvertedgrade($overall,$host,$db,$user,$password){
  
    $singlepercent=number_format($overall,0);
    
    try {
        $grade="";
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $arr=[];
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $slc=$pdo->prepare("select * from gradeconverter where percentage=:percentage");
        $slc->bindParam(':percentage', $singlepercent, PDO::PARAM_STR);

        $slc->execute();
        $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultc)>0){
           
            foreach($resultc as $row){
                $grade=$row['grade_equiv'];
            }
            
        }
        if($overall<55){
            $grade=5.0;
        }
        if($overall>95){
            $grade=1.0;
        }
        if(empty($overall)){
            $grade="N/A";
        }
        
        return  $grade;
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }

}
function getdue($studID,$sessioncode,$host,$db,$user,$password){
    try {
        
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $arr=[];
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $slc=$pdo->prepare("select dueend from tbl_onpagestart where sessioncode=:sessioncode and studID=:studID");
        $slc->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slc->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
        $slc->execute();
        $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultc)>0){
           
            foreach($resultc as $row){
                array_push($arr,$row['dueend']);
            }
            return $arr;
        }
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function insertpagestart($studID,$sessioncode,$actID,$minutes,$duestart,$dueend,$host,$db,$user,$password){
    try {
        
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $arr=[];
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $slc=$pdo->prepare("select dueend from tbl_onpagestart where sessioncode=:sessioncode and studID=:studID");
        $slc->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slc->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
        $slc->execute();
        $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultc)>0){
            array_push($arr,1);
            foreach($resultc as $row){
                array_push($arr,$row['dueend']);
            }
            return $arr;
        }else{
            $sl=$pdo->prepare('insert into tbl_onpagestart(studID,sessioncode,actID,minutes,duestart,dueend)values(:studID,:sessioncode,:actID,:minutes,:duestart,:dueend)');
            $sl->bindParam(':studID', $studID, PDO::PARAM_STR);
            $sl->bindParam(':sessioncode', $sessioncode, PDO::PARAM_STR);
            $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
            $sl->bindParam(':minutes', $minutes, PDO::PARAM_STR);
            $sl->bindParam(':duestart', $duestart, PDO::PARAM_STR);
            $sl->bindParam(':dueend', $dueend, PDO::PARAM_STR);
            $sl->execute();
            array_push($arr,2);
            array_push($arr,0);
            return $arr;
        }

        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function gettask($code,$type,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity where  teacherID=:created and type=:type and subject_code=:code order by id desc');
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->bindParam(':type', $type, PDO::PARAM_STR);
        $sl->bindParam(':code', $code, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function gettaskform($sessID,$code,$type,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity where  teacherID=:created and type=:type and subject_code=:code and sessioncode=:sessID order by id desc');
        $sl->bindParam(':created', $createby, PDO::PARAM_STR);
        $sl->bindParam(':type', $type, PDO::PARAM_STR);
        $sl->bindParam(':code', $code, PDO::PARAM_STR);
        $sl->bindParam(':sessID', $sessID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function gettaskformjoined($sessID,$code,$type,$host,$db,$user,$password){
    try {
        
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity where type=:type and subject_code=:code and sessioncode=:sessID order by id desc');
        
        $sl->bindParam(':type', $type, PDO::PARAM_STR);
        $sl->bindParam(':code', $code, PDO::PARAM_STR);
        $sl->bindParam(':sessID', $sessID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getactivityID($sessID,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity where sessioncode=:sessID');
        $sl->bindParam(':sessID', $sessID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        $aID="";
        foreach($result as $t){
            $aID=$t['id'];
        }
        return $aID;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function checkemail_exist($email,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from users where email=:email');
        $sl->bindParam(':email', $email, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)>0){
            return 1;
        }else{
            return 0;
        }
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getactivityquestions($actID,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity_questions where  activityID=:actID');
        $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getactivityquestionsshuffle($itemID,$actID,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity_questions where  activityID=:actID and qitem=:itemID');
        $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
        $sl->bindParam(':itemID', $itemID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getquestionsparam($actID,$qID,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from question_parameters where  activityID=:actID and questionID=:qID');
        $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
        $sl->bindParam(':qID', $qID, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function removetype($id,$host,$db,$user,$password){
    try {
        // Create a new PDO instance
        $createby=$_COOKIE['user_email'];
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('delete from tbl_activity where  id=:id');
        $sl->bindParam(':id', $id, PDO::PARAM_STR);
        $sl->execute();
        
        return 1;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function gettaskjoined($code,$type,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity where  type=:type and subject_code=:code order by id desc');
        $sl->bindParam(':type', $type, PDO::PARAM_STR);
        $sl->bindParam(':code', $code, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function gettaskin($code,$id,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from tbl_activity where id=:id and subject_code=:code');
        $sl->bindParam(':code', $code, PDO::PARAM_STR);
        $sl->bindParam(':id', $id, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getfileattached($id,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('select * from attachments where activityID=:id');
        $sl->bindParam(':id', $id, PDO::PARAM_STR);
        $sl->execute();
        $result = $sl->fetchAll(PDO::FETCH_ASSOC);
        $arr=array();
        foreach($result as $res){
            
            array_push($arr,$res['filename']);
            
        }
        return $arr;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function deleteattch($id,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('delete from attachments where id=:id');
        $sl->bindParam(':id', $id, PDO::PARAM_STR);
        $sl->execute();
        return 1;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function insertatt($mID,$studID,$subID,$date,$host,$db,$user,$password){
    try {
        $count=0;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        
        $slp=$pdo->prepare('select studID from attendance where studID=:studID and day=:date and subjectID=:subID');
        $slp->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slp->bindParam(':date', $date, PDO::PARAM_STR);
        $slp->bindParam(':subID', $subID, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultp)>0){
            $count=0; 
        }else{
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sl=$pdo->prepare('insert into attendance(studID,day,subjectID,metricID)values(:studID,:day,:subID,:metricID)');
            $sl->bindParam(':studID', $studID, PDO::PARAM_STR);
            $sl->bindParam(':day', $date, PDO::PARAM_STR);
            $sl->bindParam(':subID', $subID, PDO::PARAM_STR);
            $sl->bindParam(':metricID', $mID, PDO::PARAM_STR);
            $sl->execute();
            $count=1;
        }
        
        return $count;
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function savescore($actID,$useremail,$score,$host,$db,$user,$password){
    try {
        $count=0;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        
        $slp=$pdo->prepare('select studID,score from tbl_activity_score where studID=:studID and actID=:actID');
        $slp->bindParam(':studID', $useremail, PDO::PARAM_STR);
        $slp->bindParam(':actID', $actID, PDO::PARAM_STR);
       
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultp)>0){
            $count=0; 
            foreach($resultp as $row){
               
                    if(empty($score)){
                        $currscore=$row['score'];
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sl=$pdo->prepare('update tbl_activity_score set score=:score where studID=:id and actID=:actID');
                        $sl->bindParam(':id',  $useremail, PDO::PARAM_STR);
                        $sl->bindParam(':score', $currscore, PDO::PARAM_STR);
                        $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
                        $sl->execute();
                        $count=1;
                    }else{
                        
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sl=$pdo->prepare('update tbl_activity_score set score=:score where studID=:id and actID=:actID');
                        $sl->bindParam(':id',  $useremail, PDO::PARAM_STR);
                        $sl->bindParam(':score', $score, PDO::PARAM_STR);
                        $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
                        $sl->execute();
                        $count=1;
                    }

            }
        }else{
            $totalpoints=100;
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sl=$pdo->prepare('insert into tbl_activity_score(studID,actID,score,totalpoints)values(:studID,:actID,:score,:totalpoints)');
            $sl->bindParam(':studID', $useremail, PDO::PARAM_STR);
            $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
            $sl->bindParam(':score', $score, PDO::PARAM_STR);
            $sl->bindParam(':totalpoints', $totalpoints, PDO::PARAM_STR);
            $sl->execute();
            $count=1;
        }
        
        return $count;
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function savesinglescore($recID,$score,$host,$db,$user,$password){
    try {
        $count=0;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        
        $slp=$pdo->prepare('update tbl_turnin set score=:score where id=:id');
        $slp->bindParam(':id', $recID, PDO::PARAM_STR);
        $slp->bindParam(':score', $score, PDO::PARAM_STR);
        $slp->execute();
        
        return 1;
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function unsubmit($userID,$code,$actID,$host,$db,$user,$password){
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sl=$pdo->prepare('delete from tbl_turnin where actID=:actID and studID=:userID and code=:code');
        $sl->bindParam(':actID', $actID, PDO::PARAM_STR);
        $sl->bindParam(':userID', $userID, PDO::PARAM_STR);
        $sl->bindParam(':code', $code, PDO::PARAM_STR);
        $sl->execute();
        return 1;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function checkturnin($userID,$code,$actID,$host,$db,$user,$password){
    try {
        $datesubmit=date("Y-m-d h:i:s");
        $status=1;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
         
                $slc=$pdo->prepare('select * from tbl_turnin where actID=:actID and studID=:studID and code=:code');
                 $slc->bindParam(':actID', $actID, PDO::PARAM_STR);
                 $slc->bindParam(':studID', $userID, PDO::PARAM_STR);
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                
                 if(count($resultc)>0){
                    foreach($resultc as $row){
                        return $row['date_submitted'];
                    }
                    
                 }else{
                    return 0;
                 }

                 
                 

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getturnin($studID,$code,$actID,$host,$db,$user,$password){
    try {
       
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
         
                $slc=$pdo->prepare('select * from tbl_turnin where actID=:actID and code=:code and studID=:studID');
                 $slc->bindParam(':actID', $actID, PDO::PARAM_STR);
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->bindParam(':studID', $studID, PDO::PARAM_STR);
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 
                 return $resultc;    
                      

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getactscore($studID,$actID,$host,$db,$user,$password){
    try {
       
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
         
                $slc=$pdo->prepare('select * from tbl_activity_score where actID=:actID and studID=:studID');
                 $slc->bindParam(':actID', $actID, PDO::PARAM_STR);
                 $slc->bindParam(':studID', $studID, PDO::PARAM_STR);
                 $slc->execute();
                 $resultc = $slc->fetchAll(PDO::FETCH_ASSOC);
                 
                 return $resultc;    
                      

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function scorestud($score,$studID,$code,$actID,$host,$db,$user,$password){
    try {
       
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
         
                $slc=$pdo->prepare('update tbl_turnin set score=:score where actID=:actID and code=:code and studID=:studID');
                 $slc->bindParam(':actID', $actID, PDO::PARAM_STR);
                 $slc->bindParam(':code', $code, PDO::PARAM_STR);
                 $slc->bindParam(':studID', $studID, PDO::PARAM_STR);
                 $slc->bindParam(':score', $score, PDO::PARAM_STR);
                 $slc->execute();
                 return 1;    
                      

    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getattendancerating($studID,$subID,$host,$db,$user,$password){
    try {
        $arr=[];
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $rate=0;
        $slpw=$pdo->prepare('select id from users where email=:studID');
        $slpw->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slpw->execute();
        $result = $slpw->fetchAll(PDO::FETCH_ASSOC);
        $stID="";
        foreach($result as $row1){
            $stID=$row1['id'];
        }
        
        
        $slp=$pdo->prepare('select count(studID) as count from attendance where studID=:studID and subjectID=:subID');
        $slp->bindParam(':studID', $stID, PDO::PARAM_STR);
        $slp->bindParam(':subID', $subID, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
        $totalpresent=0;
        foreach($resultp as $row){
            $totalpresent= $row['count'];
        }
        
        array_push($arr,$totalpresent);

        $slpc=$pdo->prepare('select DISTINCT(day) from attendance where subjectID=:subID');
        $slpc->bindParam(':subID', $subID, PDO::PARAM_STR);
        $slpc->execute();
        $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
        $totalattendance=count($resultpc);
        array_push($arr,$totalattendance);
        if($totalattendance>0){
             $rate=($totalpresent/$totalattendance);
             
        }
       
       return  $rate;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getattendanceratingmetric($studID,$subID,$host,$db,$user,$password){
    try {
        $arr=[];
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $rate=0;
        $slpw=$pdo->prepare('select id from users where email=:studID');
        $slpw->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slpw->execute();
        $result = $slpw->fetchAll(PDO::FETCH_ASSOC);
        $stID="";
        foreach($result as $row1){
            $stID=$row1['id'];
        }
        
        
        $slp=$pdo->prepare('select count(studID) as count from attendance where studID=:studID and subjectID=:subID');
        $slp->bindParam(':studID', $stID, PDO::PARAM_STR);
        $slp->bindParam(':subID', $subID, PDO::PARAM_STR);
        
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
        $totalpresent=0;
        foreach($resultp as $row){
            $totalpresent= $row['count'];
        }
        
        array_push($arr,$totalpresent);

        $slpc=$pdo->prepare('select DISTINCT(day) from attendance where subjectID=:subID');
        $slpc->bindParam(':subID', $subID, PDO::PARAM_STR);
       
        $slpc->execute();
        $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
        $totalattendance=count($resultpc);
        array_push($arr,$totalattendance);
        if($totalattendance>0){
             $rate=($totalpresent/$totalattendance);
             
        }
       $final=$rate*100;
       
       return   $final;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getattsample($studID,$subID,$host,$db,$user,$password){
    try {
        $arr=[];
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $rate=0;
        $slpw=$pdo->prepare('select id from users where email=:studID');
        $slpw->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slpw->execute();
        $result = $slpw->fetchAll(PDO::FETCH_ASSOC);
        $stID="";
        foreach($result as $row1){
            $stID=$row1['id'];
        }
        
        
        $slp=$pdo->prepare('select count(studID) as count from attendance where studID=:studID and subjectID=:subID');
        $slp->bindParam(':studID', $stID, PDO::PARAM_STR);
        $slp->bindParam(':subID', $subID, PDO::PARAM_STR);
        
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
        $totalpresent=0;
        foreach($resultp as $row){
            $totalpresent= $row['count'];
        }
        
        array_push($arr,$totalpresent);

        $slpc=$pdo->prepare('select DISTINCT(day) from attendance where subjectID=:subID');
        $slpc->bindParam(':subID', $subID, PDO::PARAM_STR);
       
        $slpc->execute();
        $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
        $totalattendance=count($resultpc);
        array_push($arr,$totalattendance);
        if($totalattendance>0){
             $rate=($totalpresent/$totalattendance);
             
        }
       $final=$rate*100;
       
       return   $arr;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getactivityratingmetric($metricID,$studID,$subcode,$host,$db,$user,$password){
    try {
       
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        
        $totalscore=0;
        $finalscore=0;
        $score=0;
        $form=0;
        $scores=[];
        $arr=[];
        $arrp=[];
        $slp=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and metricID=:metricID');
        $slp->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slp->bindParam(':metricID', $metricID, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultp as $row){
            array_push($arr,$row['id']);
        }

        $slpt=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and metricID=:metricID and form=:form');
        $slpt->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slpt->bindParam(':form', $form, PDO::PARAM_STR);
        $slpt->bindParam(':metricID', $metricID, PDO::PARAM_STR);
        $slpt->execute();
        $resultpt = $slpt->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultpt as $rowt){
            array_push($arrp,100);
        }
        
        $totalscore=0;
       for($i=0;$i<count($arr);$i++){
            $actID=$arr[$i];
            $slpc=$pdo->prepare('select * from tbl_activity_score where actID=:actID and studID=:studID');
            $slpc->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpc->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpc->execute();
            $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
            
           foreach($resultpc as $rowc){
               $score=trim($rowc['score']);
               array_push($arrp,$rowc['totalpoints']);
               if(is_numeric($score)){
                 $totalscore+=$score;
                 array_push($scores,$score);
               }
                
           }
           
           $slpct=$pdo->prepare('select * from tbl_turnin where actID=:actID and studID=:studID');
            $slpct->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpct->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpct->execute();
            $resultpct = $slpct->fetchAll(PDO::FETCH_ASSOC);
           foreach($resultpct as $rowct){
                if(is_numeric($rowct['score'])){
                    $score=$rowct['score'];
                    $totalscore+=$score;
                    array_push($scores,$score);
                }
                
           }
           
       }
       
       if(count($arrp)>0){
        $totalscore=$totalscore/array_sum($arrp);
       }
       $finalscore=$totalscore * 100;
       
       return $finalscore;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getactivityrating($studID,$subcode,$host,$db,$user,$password){
    try {
        $arr=[];
        $arrp=[];
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $type='Activities';
        $totalscore=0;
        $score=0;
        $form=0;
        $slp=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type');
        $slp->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slp->bindParam(':type', $type, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultp as $row){
            array_push($arr,$row['id']);
        }

        $slpt=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type and form=:form');
        $slpt->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slpt->bindParam(':form', $form, PDO::PARAM_STR);
        $slpt->bindParam(':type', $type, PDO::PARAM_STR);
        $slpt->execute();
        $resultpt = $slpt->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultpt as $rowt){
            array_push($arrp,100);
        }
        
        $totalscore=0;
       for($i=0;$i<count($arr);$i++){
            $actID=$arr[$i];
            $slpc=$pdo->prepare('select * from tbl_activity_score where actID=:actID and studID=:studID');
            $slpc->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpc->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpc->execute();
            $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
            
           foreach($resultpc as $rowc){
               $score=trim($rowc['score']);
               array_push($arrp,$rowc['totalpoints']);
               if(is_numeric($score)){
                 $totalscore+=$score;
               }
                
           }
           
           $slpct=$pdo->prepare('select * from tbl_turnin where actID=:actID and studID=:studID');
            $slpct->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpct->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpct->execute();
            $resultpct = $slpct->fetchAll(PDO::FETCH_ASSOC);
           foreach($resultpct as $rowct){
                $score=$rowct['score'];
                $totalscore+=$score;
           }
           
       }
       
       if(count($arrp)>0){
        $totalscore=$totalscore/array_sum($arrp);
       }
       
       return  $totalscore*100;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getprojectrating($studID,$subcode,$host,$db,$user,$password){
    try {
        $arr=[];
        $arrp=[];
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $type='Project';
        $totalscore=0;
        $score=0;
        $form=0;
        $slp=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type');
        $slp->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slp->bindParam(':type', $type, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultp as $row){
            array_push($arr,$row['id']);
        }

        $slpt=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type and form=:form');
        $slpt->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slpt->bindParam(':form', $form, PDO::PARAM_STR);
        $slpt->bindParam(':type', $type, PDO::PARAM_STR);
        $slpt->execute();
        $resultpt = $slpt->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultpt as $rowt){
            array_push($arrp,100);
        }
        
        $totalscore=0;
       for($i=0;$i<count($arr);$i++){
            $actID=$arr[$i];
            $slpc=$pdo->prepare('select * from tbl_activity_score where actID=:actID and studID=:studID');
            $slpc->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpc->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpc->execute();
            $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
            
           foreach($resultpc as $rowc){
               $score=trim($rowc['score']);
               array_push($arrp,$rowc['totalpoints']);
               if(is_numeric($score)){
                 $totalscore+=$score;
               }
                
           }
           
           $slpct=$pdo->prepare('select * from tbl_turnin where actID=:actID and studID=:studID');
            $slpct->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpct->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpct->execute();
            $resultpct = $slpct->fetchAll(PDO::FETCH_ASSOC);
           foreach($resultpct as $rowct){
                $score=$rowct['score'];
                $totalscore+=$score;
           }
           
       }
       
       if(count($arrp)>0){
        $totalscore=$totalscore/array_sum($arrp);
       }
       
       return  $totalscore*100;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getmidtermrating($studID,$subcode,$host,$db,$user,$password){
    try {
        $arr=[];
        $arrp=[];
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $type='Midterm';
        $totalscore=0;
        $score=0;
        $form=0;
        $slp=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type');
        $slp->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slp->bindParam(':type', $type, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultp as $row){
            array_push($arr,$row['id']);
        }

        $slpt=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type and form=:form');
        $slpt->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slpt->bindParam(':form', $form, PDO::PARAM_STR);
        $slpt->bindParam(':type', $type, PDO::PARAM_STR);
        $slpt->execute();
        $resultpt = $slpt->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultpt as $rowt){
            array_push($arrp,100);
        }
        
        $totalscore=0;
       for($i=0;$i<count($arr);$i++){
            $actID=$arr[$i];
            $slpc=$pdo->prepare('select * from tbl_activity_score where actID=:actID and studID=:studID');
            $slpc->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpc->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpc->execute();
            $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
            
           foreach($resultpc as $rowc){
               $score=trim($rowc['score']);
               array_push($arrp,$rowc['totalpoints']);
               if(is_numeric($score)){
                 $totalscore+=$score;
               }
                
           }
           
           $slpct=$pdo->prepare('select * from tbl_turnin where actID=:actID and studID=:studID');
            $slpct->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpct->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpct->execute();
            $resultpct = $slpct->fetchAll(PDO::FETCH_ASSOC);
           foreach($resultpct as $rowct){
                $score=$rowct['score'];
                $totalscore+=$score;
           }
           
       }
       
       if(count($arrp)>0){
        $totalscore=$totalscore/array_sum($arrp);
       }
       
       return  $totalscore*100;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getfinalrating($studID,$subcode,$host,$db,$user,$password){
    try {
        $arr=[];
        $arrp=[];
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $type='Finals';
        $totalscore=0;
        $score=0;
        $form=0;
        $slp=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type');
        $slp->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slp->bindParam(':type', $type, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultp as $row){
            array_push($arr,$row['id']);
        }

        $slpt=$pdo->prepare('select * from tbl_activity where subject_code=:subcode and type=:type and form=:form');
        $slpt->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $slpt->bindParam(':form', $form, PDO::PARAM_STR);
        $slpt->bindParam(':type', $type, PDO::PARAM_STR);
        $slpt->execute();
        $resultpt = $slpt->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($resultpt as $rowt){
            array_push($arrp,100);
        }
        
        $totalscore=0;
       for($i=0;$i<count($arr);$i++){
            $actID=$arr[$i];
            $slpc=$pdo->prepare('select * from tbl_activity_score where actID=:actID and studID=:studID');
            $slpc->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpc->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpc->execute();
            $resultpc = $slpc->fetchAll(PDO::FETCH_ASSOC);
            
           foreach($resultpc as $rowc){
               $score=trim($rowc['score']);
               array_push($arrp,$rowc['totalpoints']);
               if(is_numeric($score)){
                 $totalscore+=$score;
               }
                
           }
           
           $slpct=$pdo->prepare('select * from tbl_turnin where actID=:actID and studID=:studID');
            $slpct->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpct->bindParam(':studID', $studID, PDO::PARAM_STR);
            $slpct->execute();
            $resultpct = $slpct->fetchAll(PDO::FETCH_ASSOC);
           foreach($resultpct as $rowct){
                $score=$rowct['score'];
                $totalscore+=$score;
           }
           
       }
       
       if(count($arrp)>0){
        $totalscore=$totalscore/array_sum($arrp);
       }
       
       return  $totalscore*100;
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function getattendance($studID,$date,$subID,$host,$db,$user,$password){
    try {
        $count=0;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        
        $slp=$pdo->prepare('select studID from attendance where studID=:studID and day=:date and subjectID=:subID');
        $slp->bindParam(':studID', $studID, PDO::PARAM_STR);
        $slp->bindParam(':date', $date, PDO::PARAM_STR);
        $slp->bindParam(':subID', $subID, PDO::PARAM_STR);
        $slp->execute();
        $resultp = $slp->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultp)>0){
            return 1;
        }else{
            return 0;
        }
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function savequestionsact($actID,$question,$selector,$points,$updatestat,$sessioncode,$qitem,$host,$db,$user,$password){
    try {
        $count=0;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $slps=$pdo->prepare('select * from tbl_activity_questions where activityID=:actID and qitem=:qitem');
        $slps->bindParam(':actID', $actID, PDO::PARAM_STR);
        $slps->bindParam(':qitem', $qitem, PDO::PARAM_STR);
        $slps->execute();
        $resultps = $slps->fetchAll(PDO::FETCH_ASSOC);
        $qID="";
        if(count($resultps)>0){
            $count++;
            
            foreach($resultps as $rt){
                $qID=$rt['id'];
            }
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $slp=$pdo->prepare('update tbl_activity_questions set question=:question,selectortype=:selector,points=:points,activityID=:actID where activityID=:actID and qitem=:qitem');
            $slp->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slp->bindParam(':question', $question, PDO::PARAM_STR);
            $slp->bindParam(':selector', $selector, PDO::PARAM_STR);
            $slp->bindParam(':points', $points, PDO::PARAM_STR);
            $slp->bindParam(':qitem', $qitem, PDO::PARAM_STR);
            $slp->execute();
            return $qID;

        }else{
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $slp=$pdo->prepare('insert into tbl_activity_questions(question,selectortype,points,activityID,qitem)values(:question,:selector,:points,:actID,:qitem)');
            $slp->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slp->bindParam(':question', $question, PDO::PARAM_STR);
            $slp->bindParam(':selector', $selector, PDO::PARAM_STR);
            $slp->bindParam(':points', $points, PDO::PARAM_STR);
            $slp->bindParam(':qitem', $qitem, PDO::PARAM_STR);
            $slp->execute();
            $lastInsertedId = $pdo->lastInsertId();
            return $lastInsertedId;
        }
        
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
function saveoptions($actID,$question,$questionID,$correctans,$updatestat,$sessioncode,$qitem,$host,$db,$user,$password){
    try {
        $count=0;
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $slpu=$pdo->prepare('select * from question_parameters where activityID=:actID and qitem=:qitem and questionID=:questionID');
            $slpu->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slpu->bindParam(':questionID', $questionID, PDO::PARAM_STR);
            $slpu->bindParam(':qitem', $qitem, PDO::PARAM_STR);
            $slpu->execute();
            $resultps = $slpu->fetchAll(PDO::FETCH_ASSOC);
            if(count($resultps)>0){
                $updatestat=1;
            }else{
                $updatestat=0;
            }
        if($updatestat==1){
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $slp=$pdo->prepare('update question_parameters set activityID=:actID,item_name=:question,questionID=:questionID,correct_ans=:correctans where qitem=:qitem and questionID=:questionID');
            $slp->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slp->bindParam(':question', $question, PDO::PARAM_STR);
            $slp->bindParam(':questionID', $questionID, PDO::PARAM_STR);
            $slp->bindParam(':correctans', $correctans, PDO::PARAM_STR);
            $slp->bindParam(':qitem', $qitem, PDO::PARAM_STR);
            $slp->execute();
            
            return 1;
        }else{

        
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $slp=$pdo->prepare('insert into question_parameters(activityID,item_name,questionID,correct_ans,qitem)values(:actID,:question,:questionID,:correctans,:qitem)');
            $slp->bindParam(':actID', $actID, PDO::PARAM_STR);
            $slp->bindParam(':question', $question, PDO::PARAM_STR);
            $slp->bindParam(':questionID', $questionID, PDO::PARAM_STR);
            $slp->bindParam(':correctans', $correctans, PDO::PARAM_STR);
            $slp->bindParam(':qitem', $qitem, PDO::PARAM_STR);
            $slp->execute();
            
            return 1;
        }
        
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
    }
}
?>