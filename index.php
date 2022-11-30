<?php
/*
  Created on : Nov 19, 2022, 6:00:00 PM
  Author     : Marcelo Guimaraes Junior
 */
session_destroy();
setcookie('auth', session_id(), time() - 1);
$_SESSION['errormsg'] = "";
$_SESSION['origin'] = bin2hex("login");
$url_path = 'login.php';
header("HTTP/1.1 302 Redirected");
header("Location: " . $url_path . "");
echo "<script>window.location = '" . $url_path . "';</script>";
echo "window.location.replace('" . $url_path . "');";
exit;
?>


