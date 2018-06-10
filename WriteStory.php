<?php
	session_start();
	include_once('./inc/inc.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
<link rel="stylesheet" href="css/editor.css" type="text/css" charset="utf-8"/>
<script src="js/ckeditor.js"></script>
<script src="js/sample.js"></script>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/editor.css">
<link rel="stylesheet" href="toolbarconfigurator/lib/codemirror/neo.css">
<link rel="stylesheet" href="css/basicstyle.css">
<link rel="stylesheet" href="css/layout.css">
<link rel="stylesheet" href="css/menustyle.css">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<title>
</title>
<script>
function onSaveStory()
{
	var content = CKEDITOR.instances.editor.getData();
	if("" == content)
	{
		alert("내용을 기입해 주세요");
	}
	else
	{
		document.getElementById("editor").value = content;
		var SubmitForm = document.getElementById("StoryForm");
		SubmitForm.submit();
	}
}
function showPreview(pvObj) {
var wname = "preview";
var wopt = "menubar=yes,scrollbars=yes,status=yes,resizable=yes,width=640,height=480";
var pvObj = document.getElementById("editor");
winResult = window.open("about:blank",wname,wopt);
winResult.document.open("text/html", "replace");
if(" " == pvObj.value)
	{
		alert("내용을 기입해 주세요");
	}
winResult.document.write(pvObj.value);
winResult.document.close();
return false;
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
                <br /><br />
                <a href="#" class="button0">로그아웃</a>
			</div>	
		</div>
	</div>
<center>
        <div class="hangingboard">
       
                하나님이 세상을 이처럼 사랑하사 독생자를 주셨으니 이는 저를 믿는 자마다 멸망치 않고 영생을 얻게 하려 하심이라. <br>
                하나님이 그 아들을 세상에 보내신 것은 세상을 심판하려 하심이 아니요 저로 말미암아 세상이 구원을 받게 하려 하심이라. <br>
                -요한복음 3장 16 ~ 17절-
        </div>
		<br/>

    	<div class="Welcomeboard">
			사랑, 격려, 칭찬, 응원 등 좋은 글들 함께 나누어 주세요.
		</div>
		<br/>
		<div class="grid-container">
			<div class="grid-width-100">
			<form id=StoryForm action="<?php echo $ContentStoreUrl; ?>" method="post">
			<textarea id="editor" name="_editor" > </textarea> <br/>
			<input type="button" class="WriteStoryButton" onclick="return onSaveStory()" value="등록"> &nbsp; <input type="button" class="WriteStoryButton" onclick="onCancel()" value="취소">
			</form>
			</div>
			<br/>
		</div>

</center>
<script>
	var Load_cnt = 1;
	if(Load_cnt == 1) {
	   initSample();
	   Load_cnt = 0;
	}
</script>
</body>
</html>