
<?php
echo'
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./style.css" />
  </head>
  <body style="margin:0">
    <form method="POST">
      <div class="container form" style="padding: 50px">
        <h2 class="title-banner">Forgot Password</h2>
        
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
            type="text"
            name="secretq"
            id="secretq"
            placeholder="Secret Question"
            style="text-indent: 10px"
          />
          <input
            required
            type="text"
            name="secreta"
            id="secreta"
            placeholder="Secret Answer"
            style="text-indent: 10px"
          />
          <input
            required
            type="password"
            name="password"
            id="password"
            placeholder="New Password"
            style="text-indent: 10px"
          />

        <button class="btn-submit" type="submit">Retrieve</button>

        <label style="font-size: 13px; margin-left: auto; margin-right: auto; color: grey" >------------------------------OR-----------------------------</label>
        <button class="btn-submit" onclick="redirect()" type="button">Sign In</button>


        
      </div>
    </form>
    <script>
    function redirect(){
      window.location.href = "index.php"
    }
    </script>
  </body>
</html>
';
include 'footer.php';
//checks if the request is POST
if($_SERVER["REQUEST_METHOD"]=="POST"){
    //initialization
    $email = $_POST["email"];
    $password = $_POST["password"];
    $secretq = $_POST["secretq"];
    $secreta = $_POST["secreta"];
    $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  

    //validation of inputs
    if (!preg_match ($pattern, $email)){
        echo '<script>alert("Please Enter A Valid Email!");</script>';
    }
    else if(strlen($password)<=6){
      echo '<script>alert("Enter 6 or more characters!");</script>';
    }
    //if the inputs are valid then it checks the email, secret question and secret answer
    else{
        
        if(user_exist($email,$password,$secretq,$secreta)){
          echo '<script>alert("Password Updated Successfully!");</script>';
          echo"<script>window.location.href = 'index.php'</script>";
        }
        else{
          echo '<script>alert("Account Does Not Exist!");</script>';
        }
    }
}
//checks if the inputted values have matched the values from the DB
function user_exist($email,$password,$secretq,$secreta){
  //creating connection to the DB
  $conn = mysqli_connect('localhost', 'root', '','project_db');
         // if connection failed
        if(!$conn){
            echo 'Connection Failed : '.mysqli_error($conn);
        }
        //if connected
        else{
          
          //creating query
            $sqlqry = "SELECT `USER_EMAIL`,`USER_PASSWORD`, `USER_SECRET_Q`,`USER_SECRET_A` FROM `user` WHERE `USER_EMAIL` = '$email'";
            $result = mysqli_query($conn,$sqlqry);
            //executing the query to check the secret question and secret answer
            $row = mysqli_fetch_assoc($result);
            //hashing the new password
            $encrypt_pass = password_hash($password,PASSWORD_DEFAULT);
           
            if(mysqli_num_rows($result) >0 && $secretq = $row["USER_SECRET_Q"] && $secreta = $row["USER_SECRET_A"]){
              if(!$conn){
                echo 'Connection Failed : '.mysqli_error($conn);
                return false;
              }else{
                //query and executing the query to update the password of the account
                $sqlqry = "UPDATE `user` SET `USER_PASSWORD`='$encrypt_pass' WHERE `USER_EMAIL` = '$email'";
                $result = mysqli_query($conn,$sqlqry);
                
              }
              mysqli_close($conn);
              return true;
            }
            else{
              return false;
            }
        }
}
?>
