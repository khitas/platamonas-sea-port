<?php include "../includes/settings.php"; ?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../libs/jquery/jquery-2.1.0.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../libs/bootstrap/dist/css/bootstrap.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="../libs/bootstrap/dist/css/bootstrap-theme.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="../libs/bootstrap/dist/js/bootstrap.js"></script>
</head>

<script>
</script>

<style>
body {
    padding-top: 20px;
    background-color: #eee;
}

.form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}
.form-signin .form-signin-heading, .form-signin .checkbox
{
    margin-bottom: 10px;
}
.form-signin .checkbox
{
    font-weight: normal;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="text"]
{
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.account-wall
{
    margin-top: 20px;
    padding: 40px 0px 20px 0px;
    background-color: #f7f7f7;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
.login-title
{
    color: #555;
    font-size: 18px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 120px;
    height: 120px;
    margin: 0 auto 10px;
    display: block;
    /*-moz-border-radius: 50%;*/
    /*-webkit-border-radius: 50%;*/
    /*border-radius: 50%;*/
}
.need-help
{
    margin-top: 10px;
}
.new-account
{
    display: block;
    margin-top: 10px;
}
</style>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <h1 class="text-center login-title"><?php echo $AppVars["AppName"]; ?></h1>
                <div class="account-wall">
                    <img class="profile-img" src="../images/logo.png">
                    <form class="form-signin" role="form" action="../" method="post">
                        <input type="text" name="username" class="form-control" placeholder="Όνομα χρήστη" required autofocus>
                        <input type="password" name="password" class="form-control" placeholder="Κωδικός πρόσβασης" required>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">
                            Σύνδεση</button>
                        <label class="checkbox pull-left">
                            <input type="checkbox" value="remember-me">
                            Να με θυμάσαι
                        </label>
                        <a href="#" class="pull-right need-help">Βοήθεια </a><span class="clearfix"></span>
                    </form>
                </div>
<!--                <a href="#" class="text-center new-account">Create an account </a>-->
            </div>
        </div>

        <div class="row"><br><br></div>

    </div>
</body>
</html>