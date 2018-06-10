<?php
	session_start(); 
	include_once('./inc/inc.php');

  function IsVerifiedCode($_dbconn, $_email, $_Code) {
    global $GetUserCheckwithEmail;
    global $mysql_logintable;
    
    $return = "";

      // check whether or not email info is already there.
    $sqlquery = $GetUserCheckwithEmail . "'" . $_email . "'";
    $queryresult = mysqli_query($_dbconn, $sqlquery);
		
    $row = mysqli_fetch_array($queryresult);
    mysqli_free_result($queryresult); 
    
    $_CodeFromDB = $row['EmailCode'];
    
    if($_Code == $_CodeFromDB){
      // Update the bverified value.
      $sqlquery = "UPDATE " . $mysql_logintable . " SET
      EmailVerified = '1' WHERE UserEmail = '$_email' and EmailCode = '$_Code'";
			
      $queryresult = mysqli_query($_dbconn, $sqlquery);
      if (!$queryresult)
      {
        $return = "QueryError";
      } else {
        $return = "ConfirmedCode";
      }
    } else {
     // $return = "UnmatchedCode";
     $return = $_CodeFromDB;
    }
    return $return;
  }

  if('true' == $_SESSION['Is_login']){
    // 이미 로그인 된 상태임. 에러는 아닌가? Go to Main page??
    //header('Location: ./blog_manage_pdo.php');
    $_SESSION['msg'] = '로그아웃 하였습니다';
    $_SESSION['msg_status'] = 'true';
    $_SESSION['Is_login']=false;
      
    header('Location: main.php');
  } else if ($_SESSION["ImgNumCode"] != Safe_input($_POST["Joelcaptcha"])) {
    $_SESSION['Is_login']=false;
		$_SESSION['msg'] = '입력하신 숫자가 그림의 숫자와 일치하지 않습니다.'; // 인증 절차는 문제 없는가?
    $_SESSION['_Trycount'] =  $_SESSION['_Trycount'] + 1;
		$_SESSION['msg_status'] = 'true';
    header('Location: SigninVerification.php');
  } else { //if('true' == $_SESSION['Is_login'])
    $Verification_Code = Safe_input($_POST["VerificationCode"]);
    // Connectto DB.
	  $conn = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

	  if (!$conn) {
      $_SESSION['Is_login']=false;
		  $_SESSION['msg'] = '인증 번호 확인을 위한 데이터베이스 접속에 실패하였습니다!';
		  $_SESSION['msg_status'] = 'true';
    } else {  // if (!$conn)
      // Call function to check if the verification code is right one. 
      $CheckResult = IsVerifiedCode($conn,$_SESSION['VerifyingUserID'], $Verification_Code);
      
      mysqli_close($conn);
      
      if ('ConfirmedCode' == $CheckResult)
      {
        $_SESSION['Is_login']=false;
		    $_SESSION['msg'] = '축하드립니다. 인증 코드가 확인되어 로그인에 필요한 이메일 과 비밀번호 등록이 완료 되었습니다. 로그인을 진행해 주세요';
		    $_SESSION['msg_status'] = 'true';
        $_SESSION['VerifyingUserID'] = '';
        
        header('Location: main.php');
        
      } else if ('QueryError' == $CheckResult) {
        $_SESSION['Is_login']=false;
		    $_SESSION['msg'] = '데이터 베이스 업데이트에 실패하였습니다. 인증 코드를 다시 입력해 주세요'; // 인증 절차는 문제 없는가?
		    $_SESSION['msg_status'] = 'true';

        header('Location: SigninVerification.php');
      } else {
        $_SESSION['Is_login']=false;
		    $_SESSION['msg_status'] = 'true';
        
        if ( 6 > $_SESSION['_Trycount']) {
          $_SESSION['_Trycount'] =  $_SESSION['_Trycount'] + 1;
          $_SESSION['msg'] = '인증 코드가 맞지 않습니다. 인증 코드를 다시 입력해 주세요. 시도한 회수: 5/' . $_SESSION['_Trycount'] . '\r\n 입력한 인증코드: ' . $Verification_Code . ' 서버 코드: ' . $CheckResult ; // 인증 절차는 문제 없는가?
          header('Location: SigninVerification.php');
        } else {
          $_SESSION['VerifyingUserID'] = "";
          $_SESSION['msg'] = '5회 이상 인증코드 확인에 실패하였습니다. 처음부터 다시 해 주세요'; // 인증 절차는 문제 없는가?
          $_SESSION["User"] = '';
          $_SESSION['UserID'] = '';
          $_SESSION['VerifyingUserID'] = '';
          $_SESSION['_Trycount'] = 0;
          header('Location: main.php');
        }
      }
    }
  } //if('true' == $_SESSION['Is_login'])
  
  function Safe_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>