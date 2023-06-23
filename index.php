
<?php
echo '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In</title>
    <link rel="stylesheet" href="./style.css" />
  </head>
  <body>
    <form method="POST">
      <div class="container form" style="padding: 50px">
        <h2 class="title-banner">Sign In</h2>
        
          <input
            required
            type="text"
            name="email"
            id="email"
            placeholder="Email Address"
            style="text-indent: 10px"
          />
          <input
            required
            type="password"
            name="password"
            id="password"
            placeholder="Password"
            style="text-indent: 10px"
          />
          <a href="./forgotpass.php" style="font-size: 13px; color: grey; text-decoration: none">Forgot password?</a>
        <button class="btn-submit" type="submit">Log In</button>

        <label style="font-size: 13px; margin-left: auto; margin-right: auto; color: grey" >------------------------------OR-----------------------------</label>
        <button class="btn-submit" onclick="redirect()" type="button">Create Account</button>
        
      </div>
    </form>
    <script>
    function redirect(){
      window.location.href = "signup.php"
    }
    </script>
    
  </body>
</html>
';
include 'footer.php';
//checks if the request is POST
session_start();
if(isset($_SESSION["Sid"])){
  session_destroy();
}
if($_SERVER["REQUEST_METHOD"]=="POST"){

    
    //initialization
    $email = $_POST["email"];
    $password = $_POST["password"];
    $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  

    //checks if the entered email is valid
    if (!preg_match ($pattern, $email)){
        echo '<script>alert("Please Enter A Valid Email!");</script>';
    }
    //if email is valid then check if it exist in DB
    else{
        
        if(user_exist($email, $password)){

          //starts the session
          session_start();
          $_SESSION['Sid']=1;

          echo '<script>alert("Log In Successful!");window.location.href = "homepage.php";</script>';
          //echo '<script>window.location.href = "homepage.php";</script>';
        }
        else{
          echo '<script>alert("Account Does Not Exist!");</script>';
        }
    }
}
//function to know if the email exist and if the password is matched
function user_exist($email,$password){
  //creating connection to the DB
  $conn = mysqli_connect('localhost', 'root', '','project_db');
        // if connection failed
        if(!$conn){
            echo 'Connection Failed : '.mysqli_error($conn);
        }
        //if connected
        else{
          
            //creating query
            $sqlqry = "SELECT `USER_EMAIL`,`USER_PASSWORD` FROM `user` WHERE `USER_EMAIL` = '$email'";
            //executing the query to check the password
            $result = mysqli_query($conn,$sqlqry);
            $row = mysqli_fetch_assoc($result);
            
            mysqli_close($conn);
            if(mysqli_num_rows($result) >0 &&  password_verify($password, $row["USER_PASSWORD"]) >=1){
              return true;
            }
            else{
              return false;
            }
        }
}

?>

