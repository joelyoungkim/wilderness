<?php
	session_start(); 
	include_once('./inc/inc.php');

  if('true' == $_SESSION['Is_login']){
    // 이미 로그인 된 상태임. 에러는 아닌가? Go to Main page??
    //header('Location: ./blog_manage_pdo.php');
    session_unset();
    $_SESSION['msg'] = '로그아웃 하였습니다';
    $_SESSION['msg_status'] = 'true';
    $_SESSION['Is_login']=false;  
    
    header('Location: main.php');
  } else { //if('true' == $_SESSION['Is_login'])
	  $LoginID = Safe_input($_POST["WildID"]);
	  $LoginPW = Safe_input($_POST["WildPW"]);
    // email 여부확인.
    if (!filter_var($LoginID, FILTER_VALIDATE_EMAIL)) {
      $LoginID = $LoginPW = null;
      $emailErr = "Invalid email format"; 
      $_SESSION['Is_login']=false;
      $_SESSION['msg'] = '아이디는 이메일 정보를 입력하여 주세요.';
      $_SESSION['msg_status'] = 'true';
      header('Location: main.php');
    } else { //if (!filter_var($ID, FILTER_VALIDATE_EMAIL)) 
      if(($LoginID!=null)&&($LoginPW!=null)){
	      // Connectto DB.
	      $conn = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

	      if (!$conn) {
		      //echo "<script>
		      //	alert(\"DB Error : " . mysqli_connect_error() . " \");
		      //	window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		      //	</script>";
		      //echo "데이터베이스 접속에 실패하였습니다!";
          $_SESSION['Is_login']=false;
		      $_SESSION['msg'] = '데이터베이스 접속에 실패하였습니다!';
		      $_SESSION['msg_status'] = 'true';
			    header('Location: main.php');
	      } else{ // !$conn
		      $sql = $GetUserCheckwithEmail  . "'" . $LoginID . "'";
		      $result = mysqli_query($dbconn, $sql);
		      if (!$result) {
			      mysqli_close($conn);
            $_SESSION['Is_login']=false;
			      $_SESSION['msg'] = '사용자 정보 확인에 실패하였습니다.';
			      $_SESSION['msg_status'] = 'true';
            header('Location: main.php');
		      } else { //if (!$result) 
			      $row = mysqli_fetch_array($result);
			      mysqli_free_result($vid_check_rst);
			      mysqli_close($dbconn);

			      if($LoginPW == $row['UserPassword']){
				      // Check if this user's email was verified.
				      if("1" == $row['EmailVerified']){
					      $_SESSION['Is_login']=true;
                $_SESSION['msg'] = '로그인 하였습니다';
					      $_SESSION["User"] = $row['Nickname'];
                $_SESSION['UserID'] = $LoginID;
                $_SESSION['msg_status'] = 'true';
					      header('Location: main.php');
				      } else {    //  if("1" == $row['EmailVerified'])
					      // User has to confirm his email.
					      //$EmailCode = $row['EmailCode'];
					      // PHP 변수 값들을 Java Script에 배열로 넘기기 위한 작업. ==> Confirm code를 JS로 넘기는 것은 너무 위험.
					      // JS 에서 $CodeEncryp 를 제거해야 한다.
					      //$PhpArray =  "'".$EmailCode.$CodeEncryp."'".","."'".$ID."'";
					      // 사용자를 Confirm page로 유도하고 사용자의 입력값을 받아서 서버에서 확인한 후 Return 해 주도록 하자.
					      //echo "사용자 이메일 확인 필요합니다";
                $_SESSION['msg'] = '인증 코드 확인이 필요합니다. \r\n나오는 페이지에서 입력하신 이메일로 보내드렸던 인증 코드를 입력해 주세요';
                $_SESSION["User"] = '';
                $_SESSION['UserID'] = '';
                $_SESSION['VerifyingUserID'] = $LoginID;
                $_SESSION['Is_login']=false;
                $_SESSION['msg_status'] = 'true';
                header('Location: SigninVerification.php');
				      } //if("1" == $row['EmailVerified'])
			      } else { //if($PW == $row['UserPassword'])
				      // Wrong Password
				      // 다시 로그인 페이지로 유도. 
				      //echo "비밀 번호가 일치하지 않습니다";
              $_SESSION['Is_login']=false;
				      $_SESSION['msg'] = '비밀 번호가 일치하지 않습니다.';
				      $_SESSION['msg_status'] = 'true';
              header('Location: main.php');
			      } ////if($PW == $row['UserPassword']) 
		      } //if (!$result)
	      } // if (!$conn)
        
      } else {  //if(($_POST['WildID']!=null)&&($_POST['WildPW']!=null))
        $_SESSION['Is_login']=false;
		    $_SESSION['msg'] = '이메일 계정과 비밀번호를 입력해 주세요.';
		    $_SESSION['msg_status'] = 'true';
		    header('Location: main.php');
      } //if(($_POST['WildID']!=null)&&($_POST['WildPW']!=null))
    } //if (!filter_var($ID, FILTER_VALIDATE_EMAIL))
  } //if('true' == $_SESSION['Is_login'])
  
  function Safe_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>