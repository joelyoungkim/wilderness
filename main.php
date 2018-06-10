<?php
	// Start session.
	session_start();
	include_once('./inc/inc.php');
	// unset the variables stored in session.
	//unset($_SESSION['SESS_NICKNAME']);

	$LastListId=$_GET['LastListId'];
	$Command = $_GET['Command'];
	$SortType = $_GET['SortType'];

	if('true' == $_SESSION['msg_status'])
	{
		echo "<script>
			alert(\"".$_SESSION['msg']."\");
			</script>";
		$_SESSION['msg_status'] = 'false';
		$_SESSION['msg'] = '';
	}

	switch($SortType)
	{
		case "Recommend":
		case "WrittenDate":
		case "Bookmark":
			$MainSortType = $SortType;
		break;
		case "Writer":
			list($Tmp, $Index1, $Value1) = split("[?=]", $SortType);
			$MainSortType = $Tmp;
			$WriterInfo = $Value1;
		break;
		default:
			$MainSortType = "WrittenDate";
		break;
	}

	// Note: If the "$MainSortType" or "GSortType" is not matched with any defined value, then "writtendate" should be applied as default.
	$_SESSION["GSortType"] = $MainSortType;
	
	//echo "Path : $path";
	//require "$path";
	//echo "Path : $path";

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="euc-kr">
	<title>광야</title>
	<link rel="stylesheet" href="css/basicstyle.css">
	<link rel="stylesheet" href="css/layout.css">
	<link rel="stylesheet" href="css/menustyle.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <script type="text/javascript">
		// Get the modal
		var modal = document.getElementById('LoginDiv');

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}

		// Add contents for max height
		$(document).ready(function () 
		{
			function feed_function(){
				var ID=$(".list_box:last").attr("id");
				alert("id is" + ID);
		
				$.post("<?php 
					switch($Mainsorttype)
					{
						case "recommend":
							echo $feedmainurlwithRc;
						break;
						case "bookmark":
							echo $sortByBookmark; echo $writerinfo; echo $feedauthoURLpost;
						break;
						case "author":
							echo $GetAuthorList; echo $writerinfo; echo $feedauthoURLpost;
						break;
						default:
							echo $feedmainurl;
						break;
					}
					?>"+ID,
					function(data){
						if(data != ""){
							$(".list_box:last").after(data);
							alert("data is" + data);
						}
						$('div#last_list_loader').empty();
					});
			};
			$(document).scroll(function() 
			{
				var maxHeight = $(document).height();
				var currentScroll = $(window).scrollTop() + $(window).height();
				//if (maxHeight <= currentScroll + 100) {
				if (maxHeight <= currentScroll) {
					//alert("End of Page!");
					// Extend lists.
					feed_function();
				}
			})
		});

		function validateEmail(email) {
		  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		  return re.test(email);
		}
		function SubmitLogin()
		{
			var email = $("#WildEmail").val();
			if (validateEmail(email)) {
				var _Login = document.getElementById("LoginSbumit");
				_Login.submit();
			} else {	
				alert("아이디로 사용하는 이메일을 입력해 주세요");
			}
		}
		function SubmitSignin()
		{
			var _Signin = document.getElementById("SigninSbumit");
			_Signin.submit();
		}
    </script>
</head>
<body>
	<div class="header">
		<div class="col-3 col-m-3">
			<div class="titlecenter">
                <a style="color:black" href="main.php"><h1>광야(Desert)</h1></a>
			</div>
		</div>
		<div class="col-8 col-m-11">
			<p></p>
		</div>
		<div class="col-1 col-m-12">
			<div class="loginicon">
<?php
if (('true' == $_SESSION['Is_login']) && ('' != $_SESSION['UserID']) && (null != $_SESSION['UserID']))
//if (1)
{
?>
				<!-- <a href="<?php echo $LogoutUrl; ?>" onclick="window.open(this.href, "Logoout","width=510,height=620");return false;" target="<?php echo $MainUrl; ?>">로그 아웃</a> -->
				<br><br>
				<a href="<?php echo $LogoutUrl; ?>" style="font-size: 11pt" >로그 아웃</a>
				<!-- 
				<form action="Login_Proc.php" id="LoginSbumit" method="post">
				<button type="submit" class="ButtonlikeLink">로그아웃</button>
				</form> -->
                <!-- <button class="SigninbuttonInMain" onclick="document.getElementById('SigninDiv').style.display = 'block'" style="width:auto;"> 비밀번호 변경 </button> -->
<?php
} else {
?>
                <button class="LoginbuttonInMain" onclick="document.getElementById('LoginDiv').style.display = 'block'" style="width:auto;"> 로그인 </button>
                <button class="SigninbuttonInMain" onclick="document.getElementById('SigninDiv').style.display = 'block'" style="width:auto;"> 회원가입 </button>
<?php
}
?>
			</div>	
		</div>
	</div>
    <div id="LoginDiv" class="modal">
		<form class="modal-content animate" action="Login_Proc.php" id="LoginSbumit" method="post">
            <div class="LogInimgcontainer">
                <h2>로그인 화면</h2>
                <span onclick="document.getElementById('LoginDiv').style.display = 'none'" class="close" title="Close Modal">&times;</span>
				<img src=".\img\beautiful-skies-eternity.jpg" alt="Avatar" class="avatar"> <br/><br/>
                하나님이 세상을 이처럼 사랑하사 독생자를 주셨으니 이는 저를 믿는 자마다 멸망치 않고 영생을 얻게 하려 하심이라. <br>
                하나님이 그 아들을 세상에 보내신 것은 세상을 심판하려 하심이 아니요 저로 말미암아 세상이 구원을 받게 하려 하심이라. <br>
                -요한복음 3장 16 ~ 17절-
            </div>
            <div class="LoginFormContainer">
                <label><b>Username</b></label>
                <input type="text" placeholder="Enter your email" name="WildID" id="WildEmail" required>
                <label><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="WildPW" required>
                <button type="submit" class="Loginbutton" >Login</button>
            </div>
            <div class="LoginFormContainer" style="background-color:#f1f1f1">
                <button type="button" onclick="document.getElementById('LoginDiv').style.display = 'none'" class="LoginCancelbtn">Cancel</button>
                <span class="psw">Forgot <a href="#">password?</a></span>
            </div>
        </form>
    </div>
    <div id="SigninDiv" class="modal">
		<form class="modal-content animate" action="Signin_Proc.php" id="SigninSbumit" method="post">
            <div class="SignInimgcontainer">
                <h2>회원 가입 화면</h2>
                <span onclick="document.getElementById('SigninDiv').style.display = 'none'" class="close" title="Close Modal">&times;</span>
				<img src=".\img\beautiful-skies-eternity.jpg" alt="Avatar" class="avatar"> <br/>
				<!-- <br/>
                하나님이 세상을 이처럼 사랑하사 독생자를 주셨으니 이는 저를 믿는 자마다 멸망치 않고 영생을 얻게 하려 하심이라. <br>
                하나님이 그 아들을 세상에 보내신 것은 세상을 심판하려 하심이 아니요 저로 말미암아 세상이 구원을 받게 하려 하심이라. <br>
                -요한복음 3장 16 ~ 17절-
				-->
            </div>
			<div class="SigninFormContainer">
				<center>
				<br /> 
                회원 가입을 신청하시면 입력하신 개인 이메일 계정으로 인증 코드를 보내드립니다.<br />
                인증 코드 확인 창에 해당 인증 코드를 입력하시면 회원 가입이 완료 됩니다. <br />
				</center>
                <fieldset>
                    <legend>아이드 등록</legend> <br />
                    이메일 계정:<br>
                    <input type="text" name="email" placeholder="이메일계정 (예: xxxxxx@google.co.kr)" required><br>
                    비밀 번호:<br>
                    <input type="password" name="password" placeholder="비밀 번호를 입력해 주세요" required><br>
                    비밀 번호 확인:<br>
                    <input type="password" name="password_confirmation" placeholder="비밀 번호를 다시 한번 입력 해주세요" required><br><br>
                    <input type="submit" value="제출">
                </fieldset>
			</div>
            <div class="SigninFormContainer" style="background-color:#f1f1f1">
                <button type="button" onclick="document.getElementById('SigninDiv').style.display = 'none'" class="SigninCancelbtn">Cancel</button>
            </div>
        </form>
    </div>
    <center>
        <div class="hangingboard">
                하나님이 세상을 이처럼 사랑하사 독생자를 주셨으니 이는 저를 믿는 자마다 멸망치 않고 영생을 얻게 하려 하심이라. <br>
                하나님이 그 아들을 세상에 보내신 것은 세상을 심판하려 하심이 아니요 저로 말미암아 세상이 구원을 받게 하려 하심이라. <br>
                -요한복음 3장 16 ~ 17절-
        </div>
	<div class="navclass">
		<div class="col-2 col-m-2">
			<p></p>
		</div>
		<div class="col-8 col-m-10">
            <center>
                <div class="menucenter">
                    <label for="show-menu" class="show-menu">Show Menu</label>
                    <input type="checkbox" id="show-menu" role="button">
                    <ul class="menu-header" id="menuheader">
                        <li><a href="#">글쓰기</a></li>
                        <li><a href="#">내 책갈피 목록</a></li>
                        <li><a href="#">추천순 정렬</a></li>
                        <li><a href="#">시간순 정렬</a></li>
                    </ul>
                </div>
            </center>
		</div>
		<div class="col-2 col-m-12">
			<p></p>
		</div>
	</div>
    <br /><br /><br /><br />
    	<div class="Welcomeboard">
		                    회원 가입은 개인 이메일 계정을 통한 인증을 통하여 이루어 집니다 . 기타 주민 등록 번호 같은 개인 정보들은 요구하지 않습니다. <br/>
		                     사랑, 격려, 칭찬, 응원 등 좋은 글들 함께 나누어 주세요.
		</div>
		<div class="textbody">
			<div class="col-2 col-m-2">
				<div class="bodysidecenter">
	                <br /><br />
	                <div class="siteconnection">
	                    <a href="http://juwang.org/" target="_blank">
	                        <img src=".\img\LordKingChurch.png" alt="주왕 교회" style="width:120px;height:30px">
	                    </a>
	                    <br /><br />
	                    <a href="http://www.holybible.or.kr/" target="_blank">
	                        <img src=".\img\bibl_logo4.gif" alt="Mountain View" style="width:100px;height:25px">
	                    </a>
	                    <br /><br />
	                    <a href="http://www.hosanna.net/" target="_blank">
	                        <img src=".\img\hosanna_logo0.gif" alt="Mountain View" style="width:100px;height:25px">
	                    </a>
	                </div>
	                <br />
	                <div class="adminemail">
	                    <a href="mailto:joel.kim0517@gmail.com?subject=광야 관리자에게" style="font-size: 11pt">
	                        	<linkfont> 문의 메일 보내기</linkfont>
	                    </a>
	                </div>
				</div>
			</div>
			<div class="col-8 col-m-10">
				<br/><br/>
				<div class="bodycenter">
<?php
if($Command <> "GetList")
{		
				include('load_list.php');
}
else
{
	//GetList command
}
?>
				<br>
				<br>
				</div>
			</div>
			<div class="col-2 col-m-12">
				<div class="bodysidecenter">
				</div>
			</div>
		</div>
	</center>
	<div class="footer">
	<div class="col-2 col-m-2"></div>
	<div class="col-8 col-m-10"></div>
	<div class="col-2 col-m-12"></div>
		<div class="footercenter">
			<H3></H3>
		</div>
	</div>
</body>
</html>

