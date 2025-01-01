<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="homepage.css">
    </style>
    </head>
    <body>
        <form action='login.php' method='get'>
            <div class='background'></div>
            <div class=nav-bar1>
                <div class=nav-bar-buttons-container1>
                    <a href='home.php' class='nav-bar-buttons'>Home</a>
                    <a href='about.php' class='nav-bar-buttons'>About</a>
                    <a href='contactus.php' class='nav-bar-buttons' >Contact us</a>
                    <a href='login.php' class='nav-bar-buttons'>Login</a>
                </div>
            </div>
    <?php
        session_start();
        $conn = mysqli_connect("localhost", "root", "");
        mysqli_select_db($conn,"project");
        echo "
        <fieldset class='login-container'>
        <legend align='center'>
            <img src='./images/Logo.png' style='width: 300px'>
        </legend>
        <div class='item'>
            <div class='tag'>Email</div>
            <input type='text' name='email'>
        </div>
        <div class='item'>
            <div class='tag'>Password</div>
            <input type='password' name='password'>
        </div>
        <button name='login' class='signup-button'>Login</button>";
        if(isset($_GET['login'])){
            $i = $_GET['email'];
            $sql = "select * from account where email = '$i'";
            $reponse = mysqli_query($conn, $sql);
            $data = @mysqli_fetch_array($reponse);
            if($data == null)
                echo "<div class='error-message'>The entered account doesn't exist.</div>";
            else if($data['password'] != $_GET['password'])
                        echo "<div class='error-message'>Wrong password.</div>";
            else{
                $_SESSION['account_id'] = $data['id'];
                header("Location: home.php");
            }       
        }
        echo "<div>Don't have an account? <a href='signup.php'>Signup</a></div>
    </fieldset>
        ";
        mysqli_close($conn);
    ?>
    </body>
</html>