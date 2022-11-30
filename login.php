<?php
/*
  Created on : Nov 19, 2022, 6:00:00 PM
  Author     : Marcelo Guimaraes Junior
 */
session_start();
if ($_SESSION['origin'] != hex2bin("login")) {
    session_destroy();
    setcookie('auth', session_id(), time() - 1);
    $_SESSION['errormsg'] = "";
}
session_start();
$_SESSION['origin'] = bin2hex("login");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>FIFA&reg; World Cups</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="images/favicon.png">
    </head>
    <body>
        <div class="container login mt-3 mb-3">
            <div class="card loadAnimation m-3">
                <div class="m-3">
                    <form method="post" id="login" action="main.php">
                        <h3><img src="images/FIFA.svg" class="logo"/> World Cups</h3>
                        <div class="row"></div>
                        <div class="row form-group">
                            <div class="col-12">
                            <label for="username">Username: </label>
                            <input type="text" name="username" class="form-control" required/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                            <label for="passsword">Password:</label>
                            <input type="password" name="password"  class="form-control" required/>
                            </div>
                        </div>
                        <div class="row"></div>
                        <div class="row form-group">
                            <div class="col-4">
                                <input type="submit" id="loginButton" name="login" value="Login" class="form-control"/>
                            </div>
                        </div>
                    </form>
                    <div class="row"></div>
                    <div class="row justify-content-end">
                        <div class="col-12">
                            <a href="createaccount.php">Create Account</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <?php echo $_SESSION['errormsg']; ?>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/fifaworldcups.js"></script>
</html>

