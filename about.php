<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="homepage.css">
    </style>
    </head>
    <body>
        <form action='about.php' method='get'>
            <div class='background'></div>
            <div class=nav-bar1>
                <div class=nav-bar-buttons-container1>
                    <a href='home.php' class='nav-bar-buttons'>Home</a>
                    <a href='about.php' class='nav-bar-buttons'>About</a>
                    <a href='contactus.php' class='nav-bar-buttons' >Contact us</a>
        <?php
            session_start();
            if(isset($_SESSION['account_id']))
                echo "<button name='logout' class='nav-bar-buttons'>Logout</button>";
            else
                echo "<button name='login' class='nav-bar-buttons'>Login</button>";
            echo "
                    </div>
                    </div>
                    <div class='login-container'>
                        <div style='font-size: 19px'>
                            You must know that our website is not a traditional one as other websites.<br><br>
                            Behind the shopping side, this clothes store website gives the visitor a possibility of making styles which may save for him a financial benefit later. 
                        </div>           
                    </div>
                </form>
            ";
            if(isset($_GET['logout'])){
                unset($_SESSION['account_id']);
                header("Location: home.php");
            }
            if(isset($_GET['login']))
                header("Location: login.php");
        ?>
    </body>
</html>