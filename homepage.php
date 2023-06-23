<?php
session_start();
if(!isset($_SESSION['Sid'])) {
  echo'<script>
    window.location.href = "index.php";
</script>';
}

//visits counter
$total_visits=0;
//gets the current user ip address
$ipAddress = $_SERVER['REMOTE_ADDR'];

//connection for database
$conn = mysqli_connect('localhost', 'root', '','project_db');

if(!$conn){
  echo 'Connection Failed : '.mysqli_error($conn);
}
//if connected
else{
  //query to check if the current user ip address exist in the database
  $sqlqry = "SELECT `IP_ADDRESS` FROM `USER_IP` WHERE `IP_ADDRESS` = '$ipAddress'";
  $result = mysqli_query($conn,$sqlqry);
  //executing the query
  $row = mysqli_fetch_assoc($result);
    //if the result has returned 0 rows meaning it does not exist in the database
   if(mysqli_num_rows($result)==0){
    //query to insert the new ip address
    $sqlqry2 = "INSERT INTO `USER_IP` (`IP_ADDRESS`) VALUES ('$ipAddress')";
    $result2 = mysqli_query($conn,$sqlqry2);
    header("Refresh:0");
   }
   else{
    //query to get the total visits based on the unique ip address
    $sqlqry2 = "SELECT COUNT(*) AS `TOTAL` FROM `USER_IP`";
    $result = mysqli_query($conn,$sqlqry2);
    $row = mysqli_fetch_assoc($result);
    $total_visits = $row["TOTAL"];
   }
}

echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Page</title>
    <link rel="stylesheet" href="./style.css" />
    <link href="https://fonts.cdnfonts.com/css/common-pixel" rel="stylesheet">
  </head>
  <body style="margin: 0; height: 100vh">
    <div
      style="
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 150px;
        margin-top: 10px;
      "
    >
      <h1
        style="
        color: white;
          font-size: 20px;
          text-align: left;
          text-indent: 20px;
          font-family: '."'Common Pixel'".', sans-serif;
        "
      >
        &#127796; Home Page
      </h1>
      <button class="btn-submit" onclick="redirect()" type="button">
        Log Out
      </button>
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr">
      <div>
        <p
          style="
            font-size: 50px;
            text-align: center;
            font-weight: bold;
            color: white;
            font-family: '."'Common Pixel'".', sans-serif;
          "
        >
          &#9874; Total Visits: <br /> '.$total_visits.'
        </p>
      </div>
      <img
        src="./welcome-robot.gif"
        style="height: 100vh; width: 100%; object-fit: contain"
        alt=""
      />
      
    </div>
    <footer
      class="footer"
      style="
        position: relative;
        bottom: 0px;
        background-color: rgb(0, 0, 0);
        width: 100%;
        border-top: solid 2px rgb(10, 67, 255);
        color: white;
      "
    >
      <div
        class="container"
        style="display: grid; grid-template-columns: 1fr 1fr 1fr; height: 40px"
      >
        <p style="text-indent: 40px">&#9874; Oneal Ryan D. Torres</p>
        <p style="text-indent: 20px">Intergative Programming</p>
        <p style="text-indent: 20px">Final Project</p>
      </div>
      <div
        class="container"
        style="display: grid; grid-template-columns: 1fr 1fr 1fr; height: 45px"
      >
        <p style="text-indent: 40px">
          <a
            style="
              background-color: rgb(207, 41, 0);
              color: rgb(255, 255, 255);
              padding: 6px 9px 6px 9px;
              border-radius: 100%;
              font-family: Klavika;
              text-decoration: none;
            "
            href="https://mail.google.com/mail/u/0/#inbox"
            >G</a
          >
          @onealryan.torres@ctu.edu.ph
        </p>
        <p style="text-indent: 20px">
          <a
            style="
              background-color: rgb(0, 81, 202);
              color: rgb(255, 255, 255);
              padding: 5px 12px 5px 12px;
              border-radius: 100%;
              font-family: Klavika;
              text-decoration: none;
            "
            target="_blank"
            href="https://www.facebook.com/onealryan.torres.9"
            >f</a
          >
          Oneal Ryan Torres
        </p>
        <p style="text-indent: 20px">05/12/2023</p>
      </div>
    </footer>
    <script>
      function redirect() {
        window.location.href = "index.php";
      }
    </script>
  </body>
</html>
';
?>