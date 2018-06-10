<?php

/*
 * SQL Identification
 */
$mysql_hostname="localhost";
$mysql_user="joelyoung";
$mysql_password="joel1021";
$mysql_database="joelyoung";

/*
 * SQL Tables
 */
$mysql_logintable="User_Information";
$mysql_boardtable="Desert_Board";
$mysql_replytable="Desert_Reply";

/*
 * Variables for List and Sorting
 */
$DefaultCountInList    = 2;
$DefaultAddCountToList = 2;
$SelectedSortType = "WrittenDate";
$CodeEncryp = "#1c";

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
/*
 * URLs
 */
$sortByTimeMainUrl = "main.php?SortType=WrittenDate";
$SortByRecommendMainUrl = "main.php?SortType=Recommend";
$sortByWriterMainUrl = "main.php?SortType=Writer?author=";
$sortByBookmarkMainUrl = "main.php?SortType=Bookmark";

$FeedMainUrlwithWrittenDate = "main.php?SortType=WrittenDate&action=get&last_list_id=";
$FeedMainUrlwithRecommendCount = "main.php?SortType=Recommend&action=get&last_list_id=";
$FeedMainUrlwithWriter = "main.php?SortType=Writer&action=get&last_list_id=";
/*$FeedMainUrlwithBookmark = "main.php?SortType=Bookmark&action=get&last_list_id=";*/
$FeedMainUrlwithBookmark = "main.php?SortType=Bookmark&action=get&last_list_id=";
$FeedAuthoURLpost="&action=get&last_list_id=";


$MainUrl= "http://www.desert.or.kr/TestVer/main.php";
$LoginUrl = "http://www.desert.or.kr/TestVer/login_action.php?login=yes";
$LogoutUrl = "http://www.desert.or.kr/TestVer/Login_Proc.php";
$SigninUrl = "http://www.desert.or.kr/TestVer/membership_main.php";
$SigninRequestUrl = "http://www.desert.or.kr/TestVer/membership_welcome.php?login=yes";
$ReadFullContentUrl = "http://www.desert.or.kr/TestVer/ReadFullContent.php?uniqueid=";
$UpdatedContentUrl = "http://www.desert.or.kr/TestVer/UpdateContent.php?uniqueid=";
$StoreUpdatedContentUrl = "http://www.desert.or.kr/TestVer/storeupdate.php?uniqueid=";
$WriteFullContentUrl = "http://www.desert.or.kr/TestVer/WriteStory.php";
$ContentStoreUrl = "http://www.desert.or.kr/TestVer/StoreStory.php";
$WriteReplyUrl = "http://www.desert.or.kr/TestVer/storereply.php?uniqueid=";
$DorecommandUrl = "http://www.desert.or.kr/TestVer/Dorecommand.php?uniqueid=";
$ReturnUrlinStoreStory = "http://www.desert.or.kr/TestVer/main.php";
$BookmarkUrl = "http://www.desert.or.kr/TestVer/Dobookmark.php?uniqueid=";
$RemoveBookmarkUrl = "http://www.desert.or.kr/TestVer/Removebookmark.php?uniqueid=";

/*
* Queries
*/
$QuerybyWrittenDate="SELECT * FROM " . $mysql_boardtable . " ORDER BY ID DESC LIMIT " . $DefaultCountInList;
$QuerybyRecommendCount = "SELECT * FROM " . $mysql_boardtable . " ORDER BY RecommendedCount DESC , uniqueid DESC LIMIT " . $DefaultCountInList;

$QuerybyAuthor = "SELECT * FROM " . $mysql_boardtable . " WHERE author = ";
$QuerybyAuthorPost = " ORDER BY uniqueid DESC LIMIT " . $DefaultCountInList;

$GetRecommendedList = "SELECT * FROM " . $mysql_logintable . " WHERE nickname = ";
$GetBookmarkList = "SELECT bookmarklist FROM " . $mysql_logintable . " WHERE nickname = ";
$GetListFromBookmarkList = "SELECT * FROM " . $mysql_boardtable . " WHERE uniqueid IN(";
$GetMaxIDFromBoard = "SELECT MAX(ID) FROM " . $mysql_boardtable;

$GetUserCheckwithNickname = "SELECT * FROM " . $mysql_logintable . " WHERE nickname = ";
$GetUserCheckwithEmail = "SELECT * FROM " . $mysql_logintable . " WHERE UserEmail = ";

$UpdateBookmarkList_Pre = "UPDATE " . $mysql_logintable . " SET bookmarklist=";
$UpdateBookmarkList_Post = " WHERE nickname=";


$dbconn=mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database) or die("Could not connect database");
mysqli_query($dbconn, "SET NAMES 'utf8', character_set_server = 'utf8'");


/*
* Functions.
*/
function CheckLogin()
{
  //If user logged in, then return user log in user id. If not, then return -1 
  $result = FALSE;
  return $result;
}

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