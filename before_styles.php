<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="homepage.css">
    </style>
    </head>
    <body>
        <form action='before_styles.php' method='get'>
            <div class='background'></div>
            <div class=nav-bar1>
                <div class=nav-bar-buttons-container1>
                    <a href='home.php' class='nav-bar-buttons'>Home</a>
                    <a href='about.php' class='nav-bar-buttons'>About</a>
                    <a href='contactus.php' class='nav-bar-buttons' >Contact us</a>
                    <button name="logout" class='nav-bar-buttons'>Logout</button>
                </div>
            </div>
            <div class='info-container'>
                <div style='font-size: 19px'>
                    You must know that the style designed by you can be displayed as an offer to all visitors.<br><br>
                    After creating your own style, you can display it as an offer for 7 days .<br><br>
                    Every time your style offer is purchased, you will be rewarded 5% of its total price.<br><br>
                    To start creating styles, you have to:
                </div>
                <div class='buttons-container'>
        <?php
            session_start();
            $conn = mysqli_connect("localhost", "root", "");
            mysqli_select_db($conn,"project");
            $sql = "select status,payment from account where id=$_SESSION[account_id]";
            $reponse = mysqli_query($conn, $sql);
            $data = @mysqli_fetch_array($reponse);
            if($data['status'] == 1 && $data['payment'] == 1)
                echo "
                        <button name='buy_button' class='disabled-style-button'>Buy at least 10 items from the store</button>
                        <div class='plus-container'>+</div>
                        <button name='pay_button' class='disabled-style-button'>Pay &dollar;50</button>
                    </div>
                    <div class='create-container'><button name='create_button' class='create-button'>Create a style</button></div>
                ";
            else {
                $payment = $data['payment'];
                if($data['status'] == 1)
                echo "
                        <button name='buy_button' class='disabled-style-button'>Buy at least 10 items from the store</button>
                        <div class='plus-container'>+</div>
                        <button name='pay_button' class='style-button'>Pay &dollar;50</button>
                    </div>
                    <div class='create-container'><button name='create_button' class='disabled-create-button'>Create a style</button></div>
                ";
                else{
                    $sql = "select sum(pc.clothe_amount) from purchase p,purchase_clothes pc where p.client_id=$_SESSION[account_id] and p.purchase_nb=pc.purchase_nb";
                    $reponse = mysqli_query($conn, $sql);
                    $data = @mysqli_fetch_array($reponse);
                    $flag = false;
                    if($data['sum(pc.clothe_amount)'] >= 10){
                        $flag = true;
                        $sql = "update account set status=1 where id=$_SESSION[account_id]";
                        $reponse = mysqli_query($conn, $sql);
                    }
                    if($flag == true && $payment == 1)
                        echo "
                                <button name='buy_button' class='disabled-style-button'>Buy at least 10 items from the store</button>
                                <div class='plus-container'>+</div>
                                <button name='pay_button' class='disabled-style-button'>Pay &dollar;50</button>
                            </div>
                            <div class='create-container'><button name='create_button' class='create-button'>Create a style</button></div>
                        ";
                    else if($flag == true && $payment == 0)
                        echo "
                                <button name='buy_button' class='disabled-style-button'>Buy at least 10 items from the store</button>
                                <div class='plus-container'>+</div>
                                <button name='pay_button' class='style-button'>Pay &dollar;50</button>
                            </div>
                            <div class='create-container'><button name='create_button' class='disabled-create-button'>Create a style</button></div>
                        ";
                    else if($flag == false && $payment == 0)
                        echo "
                                <button name='buy_button' class='style-button'>Buy at least 10 items from the store</button>
                                <div class='plus-container'>+</div>
                                <button name='pay_button' class='style-button'>Pay &dollar;50</button>
                            </div>
                            <div class='create-container'><button name='create_button' class='disabled-create-button'>Create a style</button></div>
                        ";
                    else if($flag == false && $payment == 1)
                        echo "
                                <button name='buy_button' class='style-button'>Buy at least 10 items from the store</button>
                                <div class='plus-container'>+</div>
                                <button name='pay_button' class='disabled-style-button'>Pay &dollar;50</button>
                            </div>
                            <div class='create-container'><button name='create_button' class='disabled-create-button'>Create a style</button></div>
                        ";    
                }
            }
            if(isset($_GET['buy_button']))
                header("Location: shop.php");
            if(isset($_GET['create_button']))
                header("Location: do_styles.php");
            if(isset($_GET['pay_button'])){
                echo "
                    <form action='before_styles.php' method='get'>
                        <div class='unclear-container'>
                            <div class='displaying-offer-container'>
                                <button class='exit2-button'>
                                    <img src='./images/exit2.svg' class='exit2-icon'>
                                </button>
                                <div class='disclaimer-title' style='color: black;'><center>Enter card number:</center></div>
                                <input type='text' class='card-number-field' name='style_name'>
                                <div class='purchase-button-container'>
                                    <button name='ok_button' class='buy-button'>Ok</button>
                                </div>
                            </div>
                        </div>
                    </form>
                ";
            }
            if(isset($_GET['ok_button'])){
                $sql = "update account set payment=1 where id=$_SESSION[account_id]";
                $reponse = mysqli_query($conn, $sql);
                header("Location: before_styles.php");
            }
            if(isset($_GET['logout'])){
                unset($_SESSION['account_id']);
                header("Location: home.php");
            }
            echo "
                </div>
            </form>
            ";
        ?>
    </body>
</html>