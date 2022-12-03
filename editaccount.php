<?php
/*
  Created on : Nov 19, 2022, 6:00:00 PM
  Author     : Marcelo Guimaraes Junior
 */
session_start();
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if ($_SESSION['origin'] != hex2bin("editaccount")) {
    $_SESSION['errormsg'] = "";
}
if ((filter_input(INPUT_COOKIE, 'auth') != session_id())) {
    //check for required fields from the form or valid session
    //redirect back to login form if missing username or password
    $_SESSION['errormsg'] = "<small>Invalid session.</small>";
    $_SESSION['origin'] = hex2bin("login");
    $url_path = "index.php";
    header("HTTP/1.1 302 Redirected");
    header("Location: " . $url_path . "");
    echo "<script>window.location = '" . $url_path . "';</script>";
    echo "window.location.replace('" . $url_path . "');";
    exit;
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Account</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="images/favicon.png">
    </head>
    <?php
    if (isset($_FILES['fileupload']) && filter_input(INPUT_POST, 'upload')) {
        $username = $_SESSION['username'];
        $path = "/var/www/html/FIFAWorldCups/";
        $folder = "images/users/" . $username . "/";
        $newpng = $folder . basename($username . '_avatar.png');
        $newfile = $path . $newpng;
        if ($_FILES['fileupload']['type'] == "image/gif") {
            $file = imagepng(imagecreatefromgif($_FILES['fileupload']['tmp_name']), $newfile);
        } else if ($_FILES['fileupload']['type'] == "image/jpeg") {
            $file = imagepng(imagecreatefromjpeg($_FILES['fileupload']['tmp_name']), $newfile);
        } else if ($_FILES['fileupload']['type'] == "image/wbmp") {
            $file = imagepng(imagecreatefromwbmp($_FILES['fileupload']['tmp_name']), $newfile);            
        } else {
            $file = basename($_FILES['fileupload']['tmp_name']);
        }
        if ($file && move_uploaded_file($_FILES['fileupload']['tmp_name'], $newfile)) {
            $display_account = "<h6>The file \"" 
                    . basename($_FILES['fileupload']['name'])
                    . "\" has been uploaded.</h6>";
        } else {
            $display_account = "<h5>There was an error uploading the file</h5>"
                    . "<h6>Please make sure you have selected an image file.</h5>";
        } 
        $display_avatar = "<div class=\"col-auto\">"
                . "<img src=\"" . $newpng . "\" class=\"avatar\"/></div>";
    }

    //connect to server and select database
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "fifaDB");

    //create a prepared SELECT statement to avoid SQL Injection
    //bind parameters and issue the query
    //get result to variable
    //fetch result values
    $sqlusername = "SELECT * FROM auth_users WHERE username = ?";
    $stmtusername = mysqli_prepare($mysqli, $sqlusername);
    mysqli_stmt_bind_param($stmtusername, "s", $_SESSION['username']);
    mysqli_stmt_execute($stmtusername);
    $queryusername = mysqli_stmt_get_result($stmtusername);

    //get the record or the user
    $record = mysqli_fetch_array($queryusername);
    $username = $record['username'];
    $firstname = $record['fname'];
    $lastname = $record['lname'];
    $password = $record['password'];
    $email = $record['email'];
    $country = $record['country'];
    mysqli_close($mysqli);

    $display_avatar = "<img src='images/default_avatar.png' id='avatar' class='avatar'/>";
    $path = "/var/www/html/FIFAWorldCups/";
    $folder = "images/users/" . $username . "/";
    $useravatar = $path . $folder . basename($username . '_avatar.png');
    $avatar = $folder . basename($username . '_avatar.png');
    if (file_exists($useravatar)) {
        $display_avatar = "<a href='main.php'><img src='" . $avatar . "' id='avatar' class='avatar'/></a>";
    }
    ?>
    <body class="loadAnimation">
        <div class="container mt-3 mb-3">
            <?php echo $display_avatar; ?>
            <div class="m-3 col-12">
                <!form method="post" action="<?php echo $PHP_SELF; ?>">
                <form method="post" id="account" action="">
                    <div class="row mb-3 mt-3">
                        <div class="col-6">
                            <h3>World Cups Account</h3>
                        </div>
                        <div class="col-2">
                            <a href="index.php" type="button" class="btn btn-info">Return to Login</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="firstname" class="col-2 col-form-label">First Name: </label>
                        <div class="col-4">
                            <input type="text" name="firstname" class="form-control-plaintext" value="<?php echo $firstname ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="lastname" class="col-2 col-form-label">Last Name: </label>                                                   
                        <div class="col-4">
                            <input type="text" name="lastname" class="form-control-plaintext" value="<?php echo $lastname ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-2 col-form-label">Email: </label>                        
                        <div class="col-4">
                            <input type="text" name="email" class="form-control-plaintext" value="<?php echo $email ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="country" class="col-2 col-form-label">Country: </label>                        
                        <div class="col-4">
                            <input type="text" name="country" class="form-control-plaintext" value="<?php echo $country ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="username" class="col-2 col-form-label">User Name: </label>
                        <div class="col-4">
                            <input type="text" name="username" class="form-control-plaintext" value="<?php echo $username ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="password">Change Password: </label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="password">Confirm New Password: </label>
                            <input type="password" name="password-check" class="form-control">
                            <small id="passwordlHelp" class="form-text text-muted justify-content-end">
                                You can change your password if you want.
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <button type="submit" name="save" value="Save" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    <!form method="POST" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data">
                    <form method="POST" id="upload" action="" enctype="multipart/form-data" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-row">
                            <div class="col">
                                <input type="hidden" name="MAX_FILE_SIZE" value="5000000000"/>
                                <label for="fileupload">Upload your avatar: </label> 
                                <input type="file" name="fileupload" class="form-control"/>
                            </div>
                            <div class="col">
                                <button type="submit" name="upload" value="Upload" class="btn btn-primary form-control">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row mt-3">
                    <?php echo $display_account; ?>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/fifaworldcups.js"></script>
</html>