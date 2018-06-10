<?php
	session_start();
	include_once('./inc/inc.php');
?>
<!DOCTYPE HTML>
<HTML>
<HEAD>
<!-- <meta http-equiv="Content-Type" content="text/html; width=device-width, charset=utf-8"> -->
<meta http-equiv="Content-Type" content="text/html; width=device-width, initial-scale=1.0" charset="euc-kr">
<TITLE>
</TITLE>
</HEAD>
<BODY>
<?php
	$NeedLogin = false;

	switch($MainSortType)
	{
		case "Recommend":
			$result = mysqli_query($dbconn, $QuerybyRecommendCount);
			$_SESSION["prv_Uid"] = -1;
		break;
		case "Writer";
			$querystr = $QuerybyAuthor . "'" . $WriterInfo . "'" . $QuerybyAuthorPost;
			$result = mysqli_query($dbconn, $querystr);
		break;
		case "Bookmark";
		// NOTE: UserInfo value has to be transferred as parameter. If there is no log-in user, then web should shows pop-up or guide user to log in or sign in page.
			if (!CheckLogin())
				$NeedLogin = true;
			else
				$result = GetBookmarkContentsFunc($UserInfo);
		break;
		case "WrittenDate";
			$result = mysqli_query($dbconn, $QuerybyWrittenDate);
		break;
		default:
			$result = mysqli_query($dbconn, $QuerybyWrittenDate);
		break;
	}

	if ($NeedLogin)
	{
		$NeedLogin = false;
?>
		로그인해주세요!!!!
		<script> alert("책갈피 목록 서비스는 로그인이 필요합니다."); location.href='<?php echo("$mainurl");?>'; </script>
<?php
	
	}
	else
	{
		if(0 < $result->num_rows)
		{
			while($row = mysqli_fetch_array($result))
			{
				$UniqueId = $row['ID'];
				$Title = $row['Title'];
				$Content = $row['Contents'];
				$Author = $row['Nickname'];
				$WrittenDate = $row['WrittenDate'];
				$HasReply = $row['HasReply'];
				$ReplyCount = $row['ReplyCount'];
				$HitCount = $row['HitCount'];
				$RCCnt = $row['RecommendedCount'];
				$NonRCCnt = $row['NonRecommendedCount'];
				$PartofContent = $row['PartofContents'];

				$SampleContent = mb_substr($Content, 0, 100, 'UTF-8');
				
				if($MainSortType == "Recommend")
					$_SESSION["prv_Uid"] = $UniqueId;
?>
				<div id="<?php 
					if($MainSortType == "Recommend")
						echo $RCCnt;
					else 
						echo $UniqueId;
					?>"  align="left" class="list_box" >

				<!-- <?php $teststr="UID is" . $gotuniqueid; echo $teststr; ?> -->

				<div id="eachuidnumber">
				<center> 
					<table class="OverviewTable">

<?php 
if($_SESSION["CurrentUser"] == $Author)
{
?>
						<tr>
							<td class="SimpleInfo" colspan="3" align="center"> <?php echo $Author; ?> 님이 글을 남겼습니다</td>
							<td class="SimpleInfo" colspan="2" align="right"><?php echo $WrittenDate; ?></td>
						</tr>
						<!--
						<tr>
							<td class="TitleInOverviewTable" colspan="3">제목: <?php echo $Title; ?> </td>
							<td class="TitleInOverviewTable" colspan="2" align="right"><?php echo $WrittenDate; ?></td>
						</tr>
						-->
						<tr>
							<td class="SimpleContentInOverviewTable" colspan="5">
								<a href= "<pho? echo("$ReadFullContentUrl$UniqueId"); ?>
								<?php echo $PartofContent; ?>
								</a>
							</td>
						</tr>
<?php 
} else {
?>
						<tr>
							<td class="SimpleInfo" colspan="3" align="center"> <?php echo $Author; ?> 님이 글을 남겼습니다</td>
							<td class="SimpleInfo" align="right"><?php echo $WrittenDate; ?></td>
						</tr>
						<!--
						<tr>
							<td class="TitleInOverviewTable" colspan="3">제목: <?php echo $Title; ?> </td>
							<td class="TitleInOverviewTable" align="right"><?php echo $WrittenDate; ?></td>
						</tr>
						-->
						<tr>
							<td class="SimpleContentInOverviewTable" colspan="4">
								<a href= "<pho? echo("$ReadFullContentUrl$UniqueId"); ?>
								<?php echo $PartofContent; ?>
								</a>
							</td>
						</tr>
<?php 
}
?>

						<tr align="right">
<?php 
if($_SESSION["CurrentUser"] == $Author)
{
?>
				            <td class="overviewTd20"> <img src=".\img\Modify.JPG" alt="수정" style="width:21px;height:23px;">수정 </td>
							<td class="overviewTd20"> <img src=".\img\Smile.JPG" alt="공감" style="width:22px;height:23px;"> <?php echo $RCCnt; ?></td>
							<td class="overviewTd20"> <img src=".\img\NotAgree.JPG" alt="비공감" style="width:22px;height:23px;"> <?php echo $NonRCCnt; ?></td>
							<td class="overviewTd20"> <img src=".\img\Read.JPG" alt="조회" style="width:21px;height:23px;"> <?php echo $HitCount; ?></td>
							<td class="overviewTd20"> &nbsp;&nbsp;<img src=".\img\Comment.JPG" alt="댓글" style="width:21px;height:23px;"> <?php echo $ReplyCount; ?></td>
<?php 
} else {
?>
							<td class="overviewTd25"> <img src=".\img\Smile.JPG" alt="공감" style="width:22px;height:23px;"> <?php echo $RCCnt; ?></td>
							<td class="overviewTd25"> <img src=".\img\NotAgree.JPG" alt="비공감" style="width:22px;height:23px;"> <?php echo $NonRCCnt; ?></td>
							<td class="overviewTd25"> <img src=".\img\Read.JPG" alt="조회" style="width:21px;height:23px;"> <?php echo $HitCount; ?></td>
							<td class="overviewTd25"> &nbsp;&nbsp;<img src=".\img\Comment.JPG" alt="댓글" style="width:21px;height:23px;"> <?php echo $ReplyCount; ?></td>
<?php
}
?>
						</tr>				
					</table>
				</center>
				<hr><hr>
				</div>
				
				</div>
<?php 
			}
			mysqli_close($dbconn);
			mysqli_free_result($result);

		} else {	// if(0 != $result->num_rows)
			if($Mainsorttype == "Bookmark")
			{
?>
			<script> alert("현재 저장해 놓으신 책갈피 목록이 없습니다."); location.href='<?php echo("$mainurl");?>'; </script>
<?php
			}
?>
			<br>
			기록된 게시글이 아직 없습니다. <br>
			첫 번째 게시글을 작성해 주시겠습니까?
<?php
			mysqli_close($dbconn);
			//mysqli_free_result($result);
		}
	}
?>
</BODY>
</HTML>
