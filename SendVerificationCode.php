<?php
	session_start(); 
	include_once('./inc/inc.php');

  function IsRegisteredEmail($_dbconn, $_email) {
			$return = "";
      global $mysql_logintable;
      global $GetUserCheckwithEmail;
      
      // check whether or not email info is already there.
      $sqlquery = $GetUserCheckwithEmail . "'" . $_email . "'";
			$queryresult = mysqli_query($_dbconn, $sqlquery);
			$row = mysqli_fetch_array($queryresult);

      mysqli_free_result($queryresult);
      if("" != $row['UserEmail']){
        // There is already registered email
        if(1 == $row['EmailVerified'])
        {
          // It is also verified. 'verified email'
          $return = "VerifiedEmail";    
        } else {
          // But, it is not verified yet. 'not verified email'
          $return = "NotVerifiedEmail";    
        }
      } else {
        // There is no registered email
        $return = "NotRegisteredEmail";
      }
      
      return $return;
  }

	$ID = Safe_input($_POST["email"]);
	$PW = Safe_input($_POST["password"]);
  $PWConfirm = Safe_input($_POST["password_confirmation"]);

  // email 여부확인.
  if (!filter_var($ID, FILTER_VALIDATE_EMAIL)) {
    $ID = $PW = $PWConfirm = null;
    $_SESSION['Is_login']=false;
    $_SESSION['msg'] = '아이디는 이메일 정보를 입력하여 주세요.';
    $_SESSION['msg_status'] = 'true';
    header('Location: main.php');
  } else if ( ($PW != $PWConfirm) && ($PW != null) ) {
    $ID = $PW = $PWConfirm = null; 
    $_SESSION['Is_login']=false;
    $_SESSION['msg'] = '입력하신 비밀번호가 서로 일치하지 않습니다. 비밀 번호를 다시 입력해 주세요';
    $_SESSION['msg_status'] = 'true';
    header('Location: main.php');
  }
  else { //if (!filter_var($ID, FILTER_VALIDATE_EMAIL)) 
	    // Connectto DB.
	    $conn = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

	    if (!$conn) {

        $ID = $PW = $PWConfirm = null;
        $_SESSION['Is_login']=false;
		    $_SESSION['msg'] = '죄송합니다. 회원 확인을 위한 데이터베이스 접속에 실패하였습니다! 다시 시도해 주세요';
		    $_SESSION['msg_status'] = 'true';
          
        header('Location: main.php');
			
	    } else{ // !$conn
        // check whether or not email info is already there.
        $CheckResult = IsRegisteredEmail($conn,$ID);
          
        if ( ('NotRegisteredEmail' == $CheckResult) || ('NotVerifiedEmail' == $CheckResult))
        {
          // Generate random number and save user infomration with it.
          $RandomeSeed = rand();
          if ('NotVerifiedEmail' == $CheckResult){
            //echo "<br>update query <br>";
	          $sqlquery = "UPDATE " . $mysql_logintable . " SET EmailCode='$RandomeSeed', EmailVerified='false', UserPassword='$PW', Nickname='$ID' WHERE UserEmail= '$ID'";
          } else {
            $sqlquery = "INSERT INTO " . $mysql_logintable . " (UserEmail, Nickname, UserPassword, EmailCode, EmailVerified) VALUES ('$ID', '$ID', '$PW', '$RandomeSeed', 'false')";
          }
            
          $updatequery = mysqli_query($conn, $sqlquery);

          mysqli_close($conn);
            
          if (!$updatequery) {
            $_SESSION['Is_login']=false;
  				  $_SESSION['msg'] = '죄송합니다. 인증 코드 업데이트에 실패하였습니다. 처음부터 다시 시작해 주세요';
  				  $_SESSION['msg_status'] = 'true';
              
            header('Location: main.php');
              
          } else {
            $subject = "[광야] 새로운 인증코드를 드립니다";
            $emailmsg = "안녕하세요. 인증 코드 확인 창에 아래의 인증코드를 입력하여 주세요. \r\n\r\n 인증코드: " . " " . $RandomeSeed;
            $emailmsg = $emailmsg . "\r\n\r\n 좋은 하루 보내세요";

            // Send an email to the user.
            $newsubject='=?UTF-8?B?'.base64_encode($subject).'?=';
            $newemailmsg = $emailmsg;
            $headers .= 'MIME-Version: 1.0' . "\r\n"; 
				    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n"; 
            if(mail($ID, $newsubject, $emailmsg, $_header)){
              // Send Email is ok.
              $_SESSION['Is_login']=false;
  				    $_SESSION['msg'] = '';
  				    $_SESSION['msg_status'] = 'false';
              $_SESSION['VerifyingUserID'] = $ID;
              $_SESSION['_Trycount'] = 0;
              header('Location: SigninVerification.php'); // => 인증코드 입력화면으로 가자.
                
            } else { //if(mail($ID, $newsubject, $emailmsg, $_header))
              $_SESSION['Is_login']=false;
  				    $_SESSION['msg'] = '죄송합니다. 등록하신 이메일로 인증 코드를 전송하는데 실패하였습니다. 처음부터 다시 시작해 주세요';
  				    $_SESSION['msg_status'] = 'true';
                
              header('Location: main.php');
            }
          }
        } else if ('VerifiedEmail' == $CheckResult) { // if ( ("NotRegisteredEmail" == $CheckResult) || ("NotVerifiedEmail" == $CheckResult))
          $_SESSION['Is_login']=false;
  				$_SESSION['msg'] = '이미 등록 완료된 이메일입니다.';
  				$_SESSION['msg_status'] = 'true';
            
          header('Location: main.php');
            
        } else {
          $_SESSION['Is_login']=false;
  				$_SESSION['msg'] = '알 수 없는 에러로 등록에 실패하였습니다. 다시 시도해 주세요.' . 'Error: '. $CheckResult ;
  				$_SESSION['msg_status'] = 'true';
            
          header('Location: main.php');
        } // if ( ("NotRegisteredEmail" == $CheckResult) || ("NotVerifiedEmail" == $CheckResult))
	    } // if (!$conn)

        
  } //if (!filter_var($ID, FILTER_VALIDATE_EMAIL))

  
  function Safe_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>