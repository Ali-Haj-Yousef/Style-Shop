<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="homepage.css">
    </style>
    </head>
    <body>
        <?php
            session_start();
            echo "
            <form action='home.php' method='get'>
                <div class='background'></div>
                <div class=nav-bar1>
                    <div class=nav-bar-buttons-container1>
                            <a href='home.php' class='nav-bar-buttons'>Home</a>
                            <a href='about.php' class='nav-bar-buttons'>About</a>
                            <a href='contactus.php' class='nav-bar-buttons'>Contact us</a>";
            if(isset($_SESSION['account_id'])){
                echo "<button class='nav-bar-buttons' name='logout'>Logout</button>";
            }
            else
                echo "<a href='login.php' class='nav-bar-buttons'>Login</a>";
            echo "    
                    </div>
                </div>
                <div class='welcome-container'>
                    Welcome ";
            if(isset($_SESSION['account_id'])){
                $conn = mysqli_connect("localhost", "root", "");
                mysqli_select_db($conn, "project");
                $sql = "select first_name from account where id=$_SESSION[account_id]";
                $reponse = mysqli_query($conn, $sql);
                $data = @mysqli_fetch_array($reponse);
                echo $data['first_name'];
            }
            else
                echo   " to<br>
                    <div><img src='./images/Logo.png' style='width: 600px;margin-top:15px;margin-bottom:15px'></div>
                    clothes store website";
            if(isset($_SESSION['account_id'])){
                echo "
                    <div class='text-container'>Navigate our store website by shopping and styling</div>
                    <div class='decide-buttons-container'>
                        <button class='decide-button' name='shop'>Shop</button>
                        <button class='decide-button' name='style'>Style</button>
                    </div>
                ";
            }
            if(isset($_GET['shop']))
                header("Location: shop.php");
            if(isset($_GET['style']))
                header("Location: before_styles.php");
            if(isset($_GET['logout'])){
                unset($_SESSION['account_id']);
                header("Location: home.php");
            }
            echo "</div></form>";
        ?>
    </body>
</html>