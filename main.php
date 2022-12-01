<?php
/*
  Created on : Nov 19, 2022, 6:00:00 PM
  Author     : Marcelo Guimaraes Junior
 */
session_start();
$_SESSION['origin'] = bin2hex("main");
//connect to server and select database
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = mysqli_connect("localhost", "cs213user", "letmein", "fifaDB");
if ((filter_input(INPUT_COOKIE, 'auth') == session_id())) {

    //create and issue the user validation query
    $targetname = $_SESSION['username'];
    $targetemail = $_SESSION['email'];
    $sqlquery = "SELECT * "
            . "FROM auth_users "
            . "WHERE username = '" . $targetname . "' "
            . "OR email = '" . $targetemail . "'";
    $queryuser = mysqli_query($mysqli, $sqlquery) or die(mysqli_error($mysqli));
} else {

    //check for required fields from the form or valid session
    if ((!filter_input(INPUT_POST, 'username')) || (!filter_input(INPUT_POST, 'password'))) {

        //redirect back to login form if missing username or password
        $_SESSION['errormsg'] = "<small>Please enter username and password.</small>";
        $_SESSION['origin'] = hex2bin("login");
        $url_path = "login.php";
        header("HTTP/1.1 302 Redirected");
        header("Location: " . $url_path . "");
        // Backup code
        echo "<script>window.location = '" . $url_path . "';</script>";
        echo "window.location.replace('" . $url_path . "');";
        exit;
    }

    //create and issue the user validation query
    $targetname = bin2hex(filter_input(INPUT_POST, 'username'));
    $targetpasswd = bin2hex(filter_input(INPUT_POST, 'password'));

    $sqluser = "SELECT * FROM auth_users WHERE username = ? AND password = SHA1(?)";
    $stmtuser = mysqli_prepare($mysqli, $sqluser);
    mysqli_stmt_bind_param($stmtuser, "ss", hex2bin($targetname), hex2bin($targetpasswd));
    mysqli_stmt_execute($stmtuser);
    $queryuser = mysqli_stmt_get_result($stmtuser);
}
if (mysqli_num_rows($queryuser) === 1) {
    //get the record for the user
    $record = mysqli_fetch_array($queryuser);
    $username = $record['username'];
    $firstname = $record['fname'];
    $lastname = $record['lname'];
    $email = $record['email'];
    $country = $record['country'];

    //set authorization cookie using current Session ID
    setcookie('auth', session_id(), time() + 60 * 30, "/", "", 0);
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
    $_SESSION['errormsg'] = "";

    $display_avatar = "<img src='images/default_avatar.png' id='avatar' class='avatar'/>";
    $path = "/var/www/html/FIFAWorldCups/";
    $folder = "images/users/" . $username . "/";
    $useravatar = $path . $folder . basename($username . '_avatar.png');
    $avatar = $folder . basename($username . '_avatar.png');
    if (file_exists($useravatar)) {
        $display_avatar = "<img src='" . $avatar . "' id='avatar' class='avatar'/>";
    }
} else {

    //redirect back to login form if not authorized
    $_SESSION['errormsg'] = "<small>Invalid username or password.<small>";
    $_SESSION['origin'] = hex2bin("login");
    $url_path = 'login.php';
    header("HTTP/1.1 302 Redirected");
    header("Location: " . $url_path . "");
    // Backup code
    echo "<script>window.location = '" . $url_path . "';</script>";
    echo "window.location.replace('" . $url_path . "');";
    exit;
}

//create and issue the query for countries select options
$country_options1 = "<option value=''></option>";
$country_options2 = "<option value=''></option>";
$sqlquery = "SELECT * FROM countries";
$result = mysqli_query($mysqli, $sqlquery) or die(mysqli_error($mysqli));
while ($data = mysqli_fetch_array($result)) {
    $countries[$data[0]] = [$data[0], $data[1]];
}
asort($countries);
$country_name = $countries[$country][1];
 
if (filter_input(INPUT_POST, 'country1') 
        && !filter_input(INPUT_POST,'update') 
        && filter_input(INPUT_POST, 'country1') != $_SESSION['country1']) {
        $country1 = filter_input(INPUT_POST, 'country1');
        $country1_name = $countries[$country1][1];
        $away_country = $country1;
        $away_country_name = $country1_name;
        $country2 = $_SESSION['country2'];
        $country2_name = $_SESSION['country2_name'];
} else if (filter_input(INPUT_POST, 'country2') 
        && !filter_input(INPUT_POST,'update') 
        && filter_input(INPUT_POST, 'country2') != $_SESSION['country2']) {
        $country2 = filter_input(INPUT_POST, 'country2');
        $country2_name = $countries[$country2][1];
        $away_country = $country2;
        $away_country_name = $country2_name;
        $country1 = $_SESSION['country1'];
        $country1_name = $_SESSION['country1_name'];
} else {
    $countries_sort = $countries;
    sort($countries_sort);
    $random = 0;
    while ($country1 == $countries_sort[$country][0]) {
        $random = rand(0, sizeof($countries_sort) - 1);
        $country1 = $countries_sort[$random][0];
    }
    while ($country2 == $countries_sort[$country][0] || $country2 == $country1) {
        $random = rand(0, sizeof($countries_sort) - 1);
        $country2 = $countries_sort[$random][0];
    }
    $country1_name = $countries[$country1][1];
    $country2_name = $countries[$country2][1];
    $away_country_name = $country1_name;
    $away_country = $country1;
}
foreach ($countries as $countryinfo) {
    if ($countryinfo[0] == $country1) {
        $country_options1 .= "<option value=" . $countryinfo[0] . " selected>" . $countryinfo[1] . "</option>";
    } else {
        $country_options1 .= "<option value=" . $countryinfo[0] . ">" . $countryinfo[1] . "</option>";
    }
}

foreach ($countries as $countryinfo) {
    if ($countryinfo[0] == $country2) {
        $country_options2 .= "<option value=" . $countryinfo[0] . " selected>" . $countryinfo[1] . "</option>";
    } else {
        $country_options2 .= "<option value=" . $countryinfo[0] . ">" . $countryinfo[1] . "</option>";
    }
}

$home_country_match = "<h4>" . $country_name . "</h4>";
$away_country_match = "<h4>" . $away_country_name . "</h4>";

//create and issue the query for stats data
$sqlquery = "SELECT * "
        . "FROM worldcupmatches "
        . "WHERE home_team_initials in ('" . $country . "', '" . $country1 . "', '" . $country2 . "') "
        . "or away_team_initials in ('" . $country . "', '" . $country1 . "', '" . $country2 . "')";
$result = mysqli_query($mysqli, $sqlquery) or die(mysqli_error($mysqli));
$country_matches = 0;
$country1_matches = 0;
$country2_matches = 0;
$country_won = 0;
$country1_won = 0;
$country2_won = 0;

while ($match = mysqli_fetch_array($result)) {
    if ($match['Home_Team_Initials'] == $country) {
        $country_matches++;
        if ($match['Home_Team_Goals'] > $match['Away_Team_Goals'])
            $country_won++;
    }
    if ($match['Away_Team_Initials'] == $country) {
        $country_matches++;
        if ($match['Away_Team_Goals'] > $match['Home_Team_Goals'])
            $country_won++;
    }
    if ($match['Home_Team_Initials'] == $country1) {
        $country1_matches++;
        if ($match['Home_Team_Goals'] > $match['Away_Team_Goals'])
            $country1_won++;
    }
    if ($match['Away_Team_Initials'] == $country1) {
        $country1_matches++;
        if ($match['Away_Team_Goals'] > $match['Home_Team_Goals'])
            $country1_won++;
    }
    if ($match['Home_Team_Initials'] == $country2) {
        $country2_matches++;
        if ($match['Home_Team_Goals'] > $match['Away_Team_Goals'])
            $country2_won++;
    }
    if ($match['Away_Team_Initials'] == $country2) {
        $country2_matches++;
        if ($match['Away_Team_Goals'] > $match['Home_Team_Goals'])
            $country2_won++;
    }
}

$sqlquery = "SELECT * FROM worldcups";
$result = mysqli_query($mysqli, $sqlquery) or die(mysqli_error($mysqli));
$country_cups = 0;
$country1_cups = 0;
$country2_cups = 0;
while ($cup = mysqli_fetch_array($result)) {
    if ($cup['Winner'] == $country_name) {
        $country_cups++;
    }
    if ($cup['Winner'] == $country1_name) {
        $country1_cups++;
    }
    if ($cup['Winner'] == $country2_name) {
        $country2_cups++;
    }
}

$table_open = "
    <table class='table table-hover'>
    <thead>
        <tr>
        <th scope='col' class='col-3'>
                <div class='col-2 mb-2'>
                    <input type='submit' id='update' name='update' value='Update' class='btn btn-primary btn-sm'/>
                </div>
        </th>
      
        <th scope='col' class='col-3'>
            <img src='images/flags/$country.svg' class='stats-flag'/>
            <select name='country' class='form-control-plaintext readonly stats-country mb-2'><option value='$country'>$country_name</option></select></th>

        <th scope='col' class='col-3'>
            <img src='images/flags/$country1.svg' class='stats-flag'/>
                <select name='country1' class='form-control-plaintext stats-country mb-2' onchange='this.form.submit()'>$country_options1</select></th>

        <th scope='col' class='col-3 country2'>
        <img src='images/flags/$country2.svg' class='stats-flag'/>
        <select name='country2' class='country2 form-control-plaintext stats-country mb-2' onchange='this.form.submit()'>$country_options2</select></th>
        </tr>
    </thead>
    <tbody>
        <tr>
          <th scope='row'>Matches</th>
          <td>$country_matches</td>
          <td>$country1_matches</td>
          <td class='country2'>$country2_matches</td>
        </tr>
        <tr>
          <th scope='row'>Matches Won</th>
          <td>$country_won</td>
          <td>$country1_won</td>
          <td class='country2'>$country2_won</td>
        </tr>
        <tr>
          <th scope='row'>World Cups</th>
          <td>$country_cups</td>
          <td>$country1_cups</td>
          <td class='country2'>$country2_cups</td>
        </tr>
    </tbody>
    </table>";

//create and issue the query for cups data
$sqlquery = "SELECT * FROM worldcups WHERE winner in ('" . $country_name . "', '" . $country1_name . "')";
$result = mysqli_query($mysqli, $sqlquery) or die(mysqli_error($mysqli));
while ($data = mysqli_fetch_array($result)) {
    $cup_year = $data['year'];
    $host_country = $data['country'];
    $runner = $data['runners'];
}

$_SESSION['country1'] = $country1;
$_SESSION['country1_name'] = $country1_name;
$_SESSION['country2'] = $country2;
$_SESSION['country2_name'] = $country2_name;
mysqli_close($mysqli);
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
    <body class="loadAnimation">
        <div class="header">
            <h3><img src="images/FIFA.svg" class="logo"/> World Cups</h3>
            <div>
                <?php echo "<span class='name'>" . $fname . " " . $lname . "</span>"; ?>
                <?php echo $display_avatar; ?>
                <img src="images/flags/<?php echo $country; ?>.svg" class='header-flag'/>
                <div id="menucover" class="menucover"></div>
                <div id="menu" class="menu text-center">
                    <p><a href="editaccount.php">profile</a></p>
                    <p><a href="index.php">logout</a></p>
                </div>
            </div>
        </div>
        <div class="container main">
            <div class="row mt-3">
                <div class="col-auto">
                    <h2>All Time Stats</h2>
                </div>  
            </div>
            <!form method="post" action="<?php echo $PHP_SELF; ?>">
            <form method="post" id="update" action="">
                <div class="row mt-3 mb-3 match">
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php echo $table_open; ?>
                    </div>
                </div>
            </form>
            <div class="row match-band home">
                <div class="col-auto ms-auto home-name">
                    <?php echo $home_country_match; ?>
                </div>
                <div class="col-auto ms-auto home flag">
                    <img src="images/flags/<?php echo $country; ?>.svg" class="match-flag home"/>
                </div>
            </div>
            <div class="row match-band away">
                <div class="col-auto me-auto away flag">
                    <img src="images/flags/<?php echo $away_country; ?>.svg" class="match-flag away"/>
                </div>
                <div class="col-auto me-auto away-name">
                    <?php echo $away_country_match; ?>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/fifaworldcups.js"></script>
</html>
