
<?php

echo'
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <link rel="stylesheet" href="./style.css" />
  </head>
  <body>
    <form method="POST">
      <div class="container form" style="padding: 50px; margin-bottom: 50px">
        <h2 class="title-banner">Sign Up</h2>
        <div class="name">
          <input
            required
            type="text"
            name="fname"
            id="fname"
            placeholder="First Name"
            style="text-indent: 10px"
          />
          <input
            required
            type="text"
            name="lname"
            id="lname"
            placeholder="Last Name"
            style="text-indent: 10px"
          />
        </div>
        <input
          required
          type="email"
          name="email"
          id="email"
          placeholder="Email"
          style="text-indent: 10px"
        />

        <div class="gender">
          <label for="">Gender: </label>
          <input required type="radio"  name="gender" id="male" value="Male"/><label
            for="male"
            >Male</label
          >
          <input required type="radio" name="gender" id="female" value="Female" /><label
            for="female"
            >Female</label
          >
        </div>
        <input
          required
          type="text"
          name="mobile"
          id="mobile"
          placeholder="Mobile Number"
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
        <input
          required
          type="password"
          name="confirm"
          id="password"
          placeholder="Confirm Password"
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
          type="text"
          name="address"
          id="address"
          placeholder="Address"
          style="text-indent: 10px"
        />
        
        <button class="btn-submit" type="submit">Create Account</button>
        <label style="font-size: 13px; margin-left: auto; margin-right: auto; color: grey" >------------------------------OR-----------------------------</label>
        <button class="btn-submit" onclick="redirect()" type="button">Log In</button>
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
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $number = $_POST["mobile"];
    $password = $_POST["password"];
    $conpass = $_POST["confirm"];
    $address = $_POST["address"];
    $secretq = $_POST["secretq"];
    $secreta = $_POST["secreta"];

    $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
    //validation of inputs
    if(email_taken($email)){
      echo '<script>alert("Email Is Already Taken!");</script>';
    }
    elseif(!is_numeric($number)){
      echo '<script>alert("Enter Digits Only!");</script>';
    }
    elseif(strlen($number)>11 || strlen($number)<11){
      echo '<script>alert("Invalid Mobile Number!");</script>';
    }
    else if (!ctype_alpha(str_replace(' ','',$fname))){
        echo '<script>alert("Please Enter A Valid First Name!");</script>';
    }
    else if (!ctype_alpha(str_replace(' ','',$lname))){
        echo '<script>alert("Please Enter A Valid Last Name!");</script>';
    }
    else if (!preg_match ($pattern, $email)){
        echo '<script>alert("Please Enter A Valid Email!");</script>';
    }
    else if($password != $conpass){
        echo '<script>alert("Entered Password Did Not Match!");</script>';
    }
    else if(strlen($password)<=6){
        echo '<script>alert("Enter 7 or more characters!");</script>';
    }
    //if the inputs are valid
    else{
        //creating connection to the DB
        $conn = mysqli_connect('localhost', 'root', '','project_db');
        
        // if connection failed
        if(!$conn){
            echo 'Connection Failed : '.mysqli_error($conn);
        }
        //if connected
        else{
          //hashing the new password and reformatting the name
            $encrypt_pass = password_hash($password,PASSWORD_DEFAULT);
            $fname = ucwords(strtolower($fname));
            $lname = ucwords(strtolower($lname));
            
            //creating query
            $sqlqry = "INSERT INTO `user`(`USER_FNAME`, `USER_LNAME`, `USER_EMAIL`, `USER_GENDER`, `USER_MOBILE`, `USER_PASSWORD`, `USER_ADDRESS`, `USER_SECRET_Q`,`USER_SECRET_A`) VALUES ('$fname','$lname','$email','$gender','$number','$encrypt_pass','$address','$secretq','$secreta')";
            //executing the query to insert all the values to DB
            $result = mysqli_query($conn,$sqlqry);
            if($result){
                echo '<script>alert("Registration Successful!");</script>';
                echo '<script>window.location.href = "index.php ";</script>';
            }
            else{
                echo '<script>alert("Registration Failed!");</script>';
            }

            mysqli_close($conn);
            
        }
    }
}

//checks if the email entered is already taken 
function email_taken($email){
  $conn = mysqli_connect('localhost', 'root', '','project_db');

        if(!$conn){
            echo 'Connection Failed : '.mysqli_error($conn);
        }
        else{
          //query and execution to select and get the rows returned
            $sqlqry = "SELECT `USER_EMAIL` FROM `user` WHERE `USER_EMAIL` = '$email'";
            $result = mysqli_query($conn,$sqlqry);
            mysqli_close($conn);
            if(mysqli_num_rows($result) >0){
              return true;
            }
            else{
              return false;
            }
        }
}
?>
