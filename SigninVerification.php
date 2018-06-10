<?php
	// Start session.
	session_start();
	include_once('./inc/inc.php');

	if('true' == $_SESSION['msg_status'])
	{
		echo "<script>
			alert(\"".$_SESSION['msg']."\");
			</script>";
		$_SESSION['msg_status'] = 'false';
		$_SESSION['msg'] = '';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="euc-kr">
	<title>광야 인증 코드 확인</title>
	<link rel="stylesheet" href="css/basicstyle.css">
	<link rel="stylesheet" href="css/layout.css">
	<link rel="stylesheet" href="css/menustyle.css">
</head>
<body>
    <center>
        <div class="hangingboard">
            하나님이 세상을 이처럼 사랑하사 독생자를 주셨으니 이는 저를 믿는 자마다 멸망치 않고 영생을 얻게 하려 하심이라. <br>
            하나님이 그 아들을 세상에 보내신 것은 세상을 심판하려 하심이 아니요 저로 말미암아 세상이 구원을 받게 하려 하심이라. <br>
            -요한복음 3장 16 ~ 17절-
        </div>
    </center>
    <div class="col-2 col-m-2">
        <p></p>
    </div>
    <div class="col-8 col-m-10">
        <div class="signinform">
            <br />
                아래 인증 코드 확인 창에 이메일로 보내드린 인증 코드를 입력하신 후 확인을 눌러 주세요<br />
                (주의 사항: 발송해 드린 이메일이 spam 편지함에 들어가 있을 수도 있습니다). <br /><br />
                인증 코드를 분실하신 분은 첫 화면으로 돌아가서 다시 회원 가입을 진행해 주세요.
                 <br /><br />
                
            <form action="SigninVerification_Proc.php" method="post">
                <fieldset>
                    <legend>인증 코드 입력</legend> <br />
                    <img src="captcha.php" />
                    <input name="Joelcaptcha" type="text" placeholder="위 그림에 나타난 4자리 숫자를 입력해주세요" required>  
                    인증 코드:<br>
                    <input type="text" name="VerificationCode" placeholder="이메일로 받으신 인증 코드를 입력해 주세요" required><br>
                    <input type="submit" value="확인">
                </fieldset>
            </form>
			<!--
            <form method="post">
                <fieldset>
                    <legend>인증코드 재발행 </legend> <br /> 
                    <input type="submit" value="인증코드 재발행">
                </fieldset>
            </form>
			-->
            <span class="psw"><a href="http://www.desert.or.kr/">첫 화면으로 돌아가기</a></span>
        </div>
    </div>
    <div class="col-2 col-m-12">
        <p></p>
    </div>
    
</body>
</html>