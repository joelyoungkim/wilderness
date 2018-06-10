<?php

$mysql_hostname="localhost";
$mysql_user="joelyoung";
$mysql_password="psXhfdDb4FEhjDf2";
$mysql_database="desertdb";
/*
$mysql_database="joelyoung";
psXhfdDb4FEhjDf2
*/
$mysql_logintable="desertlogin";
$mysql_boardtable="desertboard";
$mysql_replytable="desertreply";

/*
 * Variables for branch.
 */
$ListCount    = 10;
$AddListCount = 5;
// Indicates type of sorting.
$Mainsorttype;
// Indicates the displayed favorite contents.
$prv_RcCnt;
$prv_Uid;



/*
* Global variables for Bookmarks.
*/
$BookmarkedUidArray;
/*
* Global variables for ErrorHandling.
*/
$ErrorMessage;

/*
 * URLs
 */
$sortByRcMainurl = "main.php?sort=recommend";
$sortByTimeMainurl = "main.php?sort=none";
$sortByBookmark = "main.php?sort=bookmark?author=";
$GetAuthorList = "main.php?sort=writer?author=";


$feedmainurl = "main.php?sort=none&action=get&last_list_id=";
$feedmainurlwithRc = "main.php?sort=recommend&action=get&last_list_id=";
$feedbookmark = "main.php?sort=bookmark&action=get&last_list_id=";
$feedauthoURLpost="&action=get&last_list_id=";

$mainurl= "http://www.desert.or.kr/TestVer/main.php";
$loginactionurl = "http://www.desert.or.kr/TestVer/login_action.php?login=yes";
$logoutactionurl = "http://www.desert.or.kr/TestVer/login_action.php?logout=yes";
$signinactionurl = "http://www.desert.or.kr/TestVer/membership_main.php";
$signinrequesturl = "http://www.desert.or.kr/TestVer/membership_welcome.php?login=yes";
$readfullcontenturl = "http://www.desert.or.kr/TestVer/ReadFullContent.php?uniqueid=";
$updatedcontenturl = "http://www.desert.or.kr/TestVer/UpdateContent.php?uniqueid=";
$storeupdatedcontent = "http://www.desert.or.kr/TestVer/storeupdate.php?uniqueid=";
$writefullcontenturl = "http://www.desert.or.kr/TestVer/WriteStory.php";
$contentstoreurl = "http://www.desert.or.kr/TestVer/storestory.php";
$writeReplyurl = "http://www.desert.or.kr/TestVer/storereply.php?uniqueid=";
$dorecommandurl = "http://www.desert.or.kr/TestVer/Dorecommand.php?uniqueid=";
$ReturnURLinStoreStory = "http://www.desert.or.kr/TestVer/main.php";
$Bookmarkurl = "http://www.desert.or.kr/TestVer/Dobookmark.php?uniqueid=";
$RemoveBookmarkurl = "http://www.desert.or.kr/TestVer/Removebookmark.php?uniqueid=";


$dbconn=mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database) or die("Could not connect database");
mysqli_query($dbconn, "SET NAMES 'utf8', character_set_server = 'utf8'");

/*
* Queries
*/
$firstlistquerybytime="SELECT * FROM " . $mysql_boardtable . " ORDER BY uniqueid DESC LIMIT " . $ListCount;
$firstlistquerybyRc = "SELECT * FROM " . $mysql_boardtable . " ORDER BY recommendcnt DESC , uniqueid DESC LIMIT " . $ListCount;

$firstlistquerybyAuthor = "SELECT * FROM " . $mysql_boardtable . " WHERE author = ";
$firstlistquerybyAuthorPost = " ORDER BY uniqueid DESC LIMIT " . $ListCount;

$GetRecommendedList = "SELECT * FROM " . $mysql_logintable . " WHERE nickname = ";
$GetBookmarkList = "SELECT bookmarklist FROM " . $mysql_logintable . " WHERE nickname = ";
$GetListFromBookmarkList = "SELECT * FROM " . $mysql_boardtable . " WHERE uniqueid IN(";
$GetMaxUIDFromBoard = "SELECT MAX(uniqueid) FROM " . $mysql_boardtable;

$GetUserCheckwithNickname = "SELECT * FROM " . $mysql_logintable . " WHERE nickname = ";
$GetUserCheckwithEmail = "SELECT * FROM " . $mysql_logintable . " WHERE email = ";

$UpdateBookmarkList_Pre = "UPDATE " . $mysql_logintable . " SET bookmarklist=";
$UpdateBookmarkList_Post = " WHERE nickname=";

/*
* Functions.
*/
function GetBookmarkListFunc($_writerinfo)
{

  global $dbconn;
  global $GetBookmarkList;

  $querystr = $GetBookmarkList . "'" . $_writerinfo . "'";
  $result = mysqli_query($dbconn, $querystr);
  $Array = $result->fetch_array(MYSQLI_ASSOC);
  
  $_GotList = $Array['bookmarklist'];
  $result->free();

  return $_GotList;
}

function IsThereBookmarkedContent($_uid, $_writerinfo)
{
  global $BookmarkedUidArray;
  
  $b_return = false;
  
  $_BookmarkList = GetBookmarkListFunc($_writerinfo);
  $BookmarkedUidArray = explode(",", $_BookmarkList);
  
  if(sizeof($BookmarkedUidArray) > 0)
  {
      $b_return = true;
  }
  return $b_return;
}

function IsBookmarkedContent($_uid, $_writerinfo)
{
  global $BookmarkedUidArray;
  
  $b_return = false;
  
  $_BookmarkList = GetBookmarkListFunc($_writerinfo);
  $BookmarkedUidArray = explode(",", $_BookmarkList);
  
  for($i=0; $i < sizeof($BookmarkedUidArray); $i++)
  {
    if($_uid == $BookmarkedUidArray[$i])
      $b_return = true;
  }
  return $b_return;
}

function RemoveUidFromBookmarkList($_uid, $_writerinfo)
{
  global $dbconn;
  global $UpdateBookmarkList_Pre;
  global $UpdateBookmarkList_Post; 
  $returnList = "";
  $returnvalue = false;
  
  $_BookmarkList = GetBookmarkListFunc($_writerinfo);
  $BookmarkedUidArray = explode(",", $_BookmarkList);
  $bFirst = true;
  
  for($i=0; $i < sizeof($BookmarkedUidArray); $i++)
  {
    if($_uid != $BookmarkedUidArray[$i])
    {
      if($bFirst)
      {
        $returnList = $BookmarkedUidArray[$i];
        $bFirst = false;
      } else {
        $returnList = ", " . $BookmarkedUidArray[$i];
      }
    }
  }
  
  $querystr = $UpdateBookmarkList_Pre . "'" . $returnList . "'" . $UpdateBookmarkList_Post . "'" . $_writerinfo . "'";
  //echo $querystr;
  
  $result = mysqli_query($dbconn, $querystr);
  
  if(!$result)
  {
    die('Error at RemoveUidFromBookmarkList : ' . mysqli_error($dbconn));
  }else {
    $returnvalue = true;
  }
  
  return $returnvalue;
}

function GetBookmarkContentsFunc($_writerinfo)
{
  global $dbconn;
  global $GetListFromBookmarkList;

  $GotList = GetBookmarkListFunc($_writerinfo);
  $querystr = $GetListFromBookmarkList . $GotList . ")";
  
  //echo $querystr;
  
  $result = mysqli_query($dbconn, $querystr);

  return $result;
}

?>