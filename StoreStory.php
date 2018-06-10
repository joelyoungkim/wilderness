<?php
	session_start();
	include_once('./inc/inc.php');
	if(!isset($_SESSION['Is_login'])){
        // Go to log in page.
		header('Location: ./blog_manage_pdo.php');
    }
	/*
	 * title, content, writtendate, hasreply, uniqueid, author, hitcount, IsReply, ReplyToUid
	 * 
	 */
	$conn = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

	// Check connection
	if (!$conn) {
		echo "<script>
			alert(\"DB Error : " . mysqli_connect_error() . " \");
			window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
			</script>";
	} else {

		$Contents = addslashes($_POST["_editor"]);
		$charset = 'UTF-8';
		$length = 100;

		if(mb_strlen($Contents, $charset) > $length) {
		  $PartofContents = mb_substr($Contents, 0, $length, $charset) . '...';
		} else {
		  $PartofContents = $Contents;
		}

		$setwrittendate=date("Y-m-d");

		mysqli_query($conn, "set session character_set_connection=utf8;");
		mysqli_query($conn, "set session character_set_results=utf8;");
		mysqli_query($conn, "set session character_set_client=utf8;");

		$sql =	"INSERT INTO $mysql_boardtable (`" . Contents . "`,`" . Nickname . "`,`" . WrittenDate . "`,`" . PartofContents . "`)
			VALUES ( '" . $Contents . "','Tester','" . $setwrittendate . "','" . $PartofContents . "')"; 
		
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			echo "<script>
			alert(\"Table Update Error : Failed to update data base \");
			window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
			</script>";
		} 

		mysqli_close($conn);
	}
	$return_url = $ReturnURLinStoreStory;
?>	
<script>
location.href='<?php echo "$ReturnURLinStoreStory"; ?>';
</script>
