<?php
/*
  Created on : Nov 19, 2022, 6:00:00 PM
  Author     : Marcelo Guimaraes Junior
 */
session_start();
if ($_SESSION['origin'] != hex2bin("createaccount")) {
    $errors = array();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create Account</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="images/favicon.png">
    </head>
    <?php
    if (filter_input(INPUT_POST, 'create')) {
        $username = filter_input(INPUT_POST, 'username');
        $firstname = filter_input(INPUT_POST, 'firstname');
        $lastname = filter_input(INPUT_POST, 'lastname');
        $password = filter_input(INPUT_POST, 'password');
        $passwordcheck = filter_input(INPUT_POST, 'password-check');
        $email = strtolower(filter_input(INPUT_POST, 'email'));
        $country = filter_input(INPUT_POST, 'country');
         
        if ($password != $passwordcheck) {
            $errors['password'] = "<p>The confirmation password doesn't match!"
                    . "</br>Please make sure to confirm your password.</p>";
            $_SESSION['origin'] = bin2hex("createaccount");
        }
         
        //connect to server and select database
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "fifaDB");

        //create a prepared SELECT statement to avoid SQL Injection
        //bind parameters and issue the query
        //get result to variable
        //fetch result values
        $sqlemail = "SELECT * FROM auth_users WHERE email = ?";
        $stmtemail = mysqli_prepare($mysqli, $sqlemail);
        mysqli_stmt_bind_param($stmtemail, "s", $email);
        mysqli_stmt_execute($stmtemail);
        $queryemail = mysqli_stmt_get_result($stmtemail);

        $sqluser = "SELECT * FROM auth_users WHERE username = ?" ;
        $stmtuser = mysqli_prepare($mysqli, $sqluser);
        mysqli_stmt_bind_param($stmtuser, "s", $username);
        mysqli_stmt_execute($stmtuser);
        $queryusername = mysqli_stmt_get_result($stmtuser);

//      //get the number of rows in the result sets
        //if there's a match, alert that email/user already exists and return
        if (mysqli_num_rows($queryemail) === 1) {
            $errors['email'] = "<p>Your email address has already been used!"
                    . "</br>Please use a different email address to create a new account.</p>";
            $_SESSION['origin'] = bin2hex("createaccount");
        }
        if (mysqli_num_rows($queryusername) === 1) {
            $errors['username'] = "<p>This username has already been used!"
                    . "</br>Please choose a different username for your new account.</p>";
            $_SESSION['origin'] = bin2hex("createaccount");
        }
        if (sizeof($errors) == 0) {
            //user does not exist, create the new user
            //create a prepared INSERT statement to avoid SQL Injection
            //bind parameters and issue the query
            $sqlnewuser = "INSERT INTO auth_users VALUES (null,?,?,?,SHA1(?),?,?,CURDATE())";
            $stmtnewuser = mysqli_prepare($mysqli, $sqlnewuser);
            mysqli_stmt_bind_param($stmtnewuser, "ssssss", $username, $firstname, $lastname, $password,
                    $email, $country);
            mysqli_stmt_execute($stmtnewuser);
            mysqli_close($mysqli);

            $path_name = "/var/www/html/FIFAWorldCups/images/users/";
            $target_path = $path_name . $username;
            mkdir($target_path, 0755);

            $display_account = "<h5>Your new account has been created.<h5>"
                    . "<h6>Thank you for joining us!</h6>";

            //set authorization cookie using curent Session ID
            setcookie("auth", session_id(), time() + 60 * 30, "/", "", 0);
            $_SESSION['username'] = $username;
            $_SESSION['state'] = "creating";
        }
    }
    if (isset($_FILES['fileupload']) && filter_input(INPUT_POST, 'upload')) {
        $username = $_SESSION['username'];
        $path = "/var/www/html/FIFAWorldCups/";
        $folder = "images/users/" . $username . "/";
        $newfile = $path . $folder . basename($username . '_avatar.png');
        if ($_FILES['fileupload']['type'] == "image/gif") {
            $file = imagepng(imagecreatefromgif($_FILES['fileupload']['tmp_name']), $newfile);
        } elseif ($_FILES['fileupload']['type'] == "image/jpeg") {
            $file = imagepng(imagecreatefromjpeg($_FILES['fileupload']['tmp_name']), $newfile);
        }
        if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $newfile)) {
            $display_account = "<h6>The file \"" . basename($_FILES['fileupload']['name'])
                    . "\" has been uploaded.</h6>";
        } else {
            $display_account = "<h6>There was an error uploading the file, please try again.</h6>";
        }
        $display_avatar = "<div class=\"col-auto\">"
                . "<img src=\"" . $newpng . "\" class=\"avatar\"/></div>";
        header("Refresh:0");
        $_SESSION['state'] = "created";
    }
    
    if ($_SESSION['state'] == "creating" || $_SESSION['state'] == 'created') {
        //connect to server and select database
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "fifaDB");

        //create a prepared SELECT statement to avoid SQL Injection
        //bind parameters and issue the query
        //get result to variable
        //fetch result values
        $sqlusername = "SELECT * FROM auth_users WHERE username = ?";
        $stmtusername = mysqli_prepare($mysqli, $sqlusername);
        mysqli_stmt_bind_param($stmtusername, "s", $username);
        mysqli_stmt_execute($stmtusername);
        $queryusername = mysqli_stmt_get_result($stmtusername);
        
        //get the record for the user
        $record = mysqli_fetch_array($queryusername);
        $username = $record['username'];
        $firstname = $record['fname'];
        $lastname = $record['lname'];
        $email = $record['email'];
        
        //display the user avatar
        $display_avatar = "<img src='images/default_avatar.png' id='avatar' class='avatar'/>";
        $path = "/var/www/html/FIFAWorldCups/";
        $folder = "images/users/" . $username . "/";
        $useravatar = $path . $folder . basename($username . '_avatar.png');
        $avatar = $folder . basename($username . '_avatar.png');
        $country = $record['country'];
        if (file_exists($useravatar)) {
            $display_avatar = "<a href='main.php'><img src='" . $avatar . "' id='avatar' class='avatar'/></a>";
        }
        ?>
    <body>
        <div class="container mt-3 mb-3">
            <?php echo $display_avatar; ?>
            <div class="m-3 col-12">
                <!form method="post" action="<?php echo $PHP_SELF; ?>">
                <form method="post" id="account" action="">
                    <div class="form-group row mt-3 mb-3">
                        <div class="col-6">
                            <h3>World Cups Account Created</h3>
                        </div>
                        <div class="col">
                            <a href="index.php" type="button" class="btn btn-info">Login</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="firstname">First Name: </label>
                            <input type="text" name="firstname" class="form-control-plaintext" value="<?php echo $firstname; ?>" readonly>
                        </div>
                        <div class="col-6">
                            <label for="lastname">Last Name: </label>                           
                            <input type="text" name="lastname" class="form-control-plaintext" value="<?php echo $lastname; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="username">User Name: </label>
                            <input type="text" name="username" class="form-control-plaintext" value="<?php echo $username; ?>" readonly>
                        </div>
                        <div class="col-6">
                            <label for="email">Email: </label>
                            <input type="text" name="email" class="form-control-plaintext" value="<?php echo $email; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="country">Country: </label>
                            <input type="text" name="country" class="form-control-plaintext" value="<?php echo $country; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="password">Change Password: </label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="password">Confirm New Password: </label>
                            <input type="password" name="password-check" class="form-control">
                            <small id="passwordlHelp" class="form-text text-muted justify-content-end">
                                You can change your password if you want. &#x1F609;
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
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
                                <input type="hidden" name="MAX_FILE_SIZE" value="100000000"/>
                                <label for="fileupload">Upload your avatar: </label> 
                                <input type="file" name="fileupload" class="form-control"/>
                            </div>
                            <div class="col">
                                <button type="submit" name="upload" value="Upload" class="btn btn-primary form-control">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    <?php
    } else {
        //connect to server and select database
        $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "fifaDB");
        //create and issue the query
        $sqlquery = "SELECT * FROM countries;";
        $result = mysqli_query($mysqli, $sqlquery) or die(mysqli_error($mysqli));
        $country_options = "<option value=''></option>";
        while ($data = mysqli_fetch_array($result)) {
            $countries[$data[0]] = [$data[0], $data[1]];
        }
        asort($countries);
        foreach ($countries as $country) {
            $country_options .= "<option value=" . $country[0] . ">" . $country[1] . "</option>";
        }
        ?>
        <body class="loadAnimation">
            <div class="container mt-3 mb-3">
                <div class="m-3 col-12">
                    <!form method="post" action="<?php echo $PHP_SELF; ?>">
                    <form method="post" id="account" action="">
                        <div class="form-group row mt-3 mb-3">
                            <div class="col-6">
                                <h3>World Cups Create Account</h3>
                            </div>
                            <div class="col">
                                <a href="index.php" type="button" class="btn btn-info">Return to Login</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="firstname">First Name: </label>
                                <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($_POST['firstname']);?>" required>
                            </div>
                            <div class="col">
                                <label for="lastname">Last Name: </label>                           
                                <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($_POST['lastname']);?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="username">User Name: </label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($_POST['username']);?>" required>
                                <small id="emailHelp" class="form-text text-muted">
                                    <?php if ($errors['username'] != null) echo $errors['username']; ?>
                                </small>
                            </div>
                            <div class="col">
                                <label for="email">Email: </label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email']);?>" required>
                                <?php if ($errors['email'] != null) {
                                echo "<small id='emailHelp' class='form-text text-muted'> Don't worry, we won't share your email with anyone. &#x1F609\;</small>";
                                } else { echo "<small id='emailError' class='errormsg form-text text-muted'>" . $errors['email'] . "</small>";
                                } ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="password">Password: </label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="password">Confirm Password: </label>
                                <input type="password" name="password-check" class="form-control" required>
                                <?php if ($errors['password'] != null) {
                                echo "<small id='emailError' class='errormsg form-text text-muted'>" . $errors['password'] . "</small>"; 
                                }?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <label for="country">Country: </label>
                                <select name="country" class="form-control" required>
                                    <?php echo $country_options; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <button type="submit" name="create" value="Submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                    <?php
                }
                ?>
            <div class="row mt-3 mb-3">
                <?php echo $display_account; ?>                        
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/fifaworldcups.js"></script>
</html>