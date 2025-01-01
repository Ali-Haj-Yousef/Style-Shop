<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="homepage.css">
</head>
<body>
    <div class="background"></div>
    <form action='signup.php' method='get'>
        <div class=nav-bar1>
            <div class=nav-bar-buttons-container1>
                <a href="home.php" class='nav-bar-buttons'>Home</a>
                <a href="about.php" class='nav-bar-buttons'>About</a>
                <a href="contactus.php" class='nav-bar-buttons'>Contact us</a>
                <a href="login.php" class='nav-bar-buttons'>Login</a>
            </div>
        </div>
        <fieldset class="signup-container">
            <legend align="center">
                <img src="./images/Logo.png" style="width: 300px">
            </legend>
            <div class="item">
                <div class="tag">First Name</div>
                <input type='text' name='firstname'>
            </div>
            <div class="item">
                <div class="tag">Last Name</div>
                <input type='text' name='lastname'>
            </div>
            <div class="item">
                <div class="tag">Email</div>
                <input type='text' name='email'>
            </div>
            <div class="item">
                <div class="tag">Password</div>
                <input type='password' name='password'>
            </div>
            <button name='signup' class="signup-button">Signup</button>
            <div>Already have an account? <a href="login.php">Login</a></div>
        </fieldset>
    </form>
    <?php
        session_start();
        $conn = mysqli_connect("localhost", "root", "");
        mysqli_select_db($conn,"project");
        if(isset($_GET['signup'])){
            $sql = "insert into account (first_name,last_name,email,password,status,payment) values ('$_GET[firstname]','$_GET[lastname]','$_GET[email]','$_GET[password]',0,0)";
            $reponse = mysqli_query($conn, $sql);
            $sql = "select id from account where email='$_GET[email]'";
            $reponse = mysqli_query($conn, $sql);
            $data = @mysqli_fetch_array($reponse);
            $_SESSION['account_id'] = $data['id'];
            header("Location: home.php");
        }
        mysqli_close($conn);
    ?>   
</body>
</html>
