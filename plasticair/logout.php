<?
session_start();
$_SESSION['usersID'] = '';
$_SESSION['users_name_sess']="";
header("Location: /login.php");
?>