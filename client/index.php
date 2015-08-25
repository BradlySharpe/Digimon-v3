<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="author" content="Bradly Sharpe">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <title>Digimon</title>
    <!--
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="canonical" href="">

    <link rel="icon" type="image/png" href="images/favicon.png">

    <meta property="og:title" content="Digimon" />
    <meta property="og:description" content="" />
    <meta property="og:locale" content="en_GB" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@BradlySharpeIT" />
    <meta name="twitter:title" content="Digimon" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:url" content="" />
    <meta name="twitter:image" content="" />
    -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/skeleton.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/stylesheet.css">
  </head>
  <body>
    <div id="stage" class="stage"></div>
    <div id="login" class="login">
      <div class="row">
        <h1>Login</h1>
      </div>
      <form id="loginForm" method="post">
        <div class="row">
          <input required="required" type="email" name="email" id="email" placeholder="Username" title="Enter your email address" value="fake@bradlysharpe.com.au" />
        </div>
        <div class="row">
          <input required="required" type="password" name="password" id="password" placeholder="Password" title="Enter your password" value="password" />
        </div>
        <div id="userDetails" class="userDetails">
          <div class="row">
            <input type="text" name="firstName" id="firstName" placeholder="First Name" title="Enter your first name" value="Bradly" />
          </div>
          <div class="row">
            <input type="text" name="lastName" id="lastName" placeholder="Last Name" title="Enter your last name" value="Sharpe" />
          </div>
          <!--
          TODO: Add DOB and validation
          <div class="row">
            <input type="date" name="dob" id="dob" placeholder="Date of Birth" title="Enter date of birth" value="1989-12-02" />
          </div>
          -->
        </div>
        <div class="row">
          <div class="six columns">
            <input type="button" id="createUser" class="button action" disabled="disabled" title="Create an account" value="Create" />
          </div>
          <div class="six columns">
            <input type="submit" class="button action" disabled="disabled" title="Login" value="Login" />
          </div>
        </div>
      </form>
    </div>
    <div id="status" class="status">
      <p id="statusMessage">
        JavaScript not enabled!
      </p>
    </div>
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/sha1.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
  </body>

  </html>
