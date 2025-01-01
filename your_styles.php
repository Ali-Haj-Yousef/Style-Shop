<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="p2(stylesheet).css">
    </head>
    <body>
        <form action='your_styles.php' method='get'>
            <div class=nav-bar1>
                <div>
                    <img src='./images/Logo.png' style='width: 180px;margin-left:12px'>
                </div>
                <div class=nav-bar-buttons-container1>
                    <a href='home.php' class='nav-bar-buttons'>Home</a>
                    <a href='about.php' class='nav-bar-buttons'>About</a>
                    <a href='contactus.php' class='nav-bar-buttons' >Contact us</a>
                    <button name='logout_button' class='nav-bar-buttons'>Logout</button>
                </div>
            </div>
            <div class=nav-bar>
                <div class=nav-bar-buttons-container1>
                        <button class='nav-bar-buttons' name='do_style_button'>Do a style</button>
                        <button class='nav-bar-buttons' name='your_styles_button'>Your styles</button>
                </div>
            </div>
        <div class='styles-container'>
        <?php
            session_start();
            date_default_timezone_set('Asia/Beirut');
            $conn = mysqli_connect("localhost", "root", "");
            mysqli_select_db($conn,"project");
            $sql = "select style_nb,style_name,stylist_earnings from style where stylist_id=$_SESSION[account_id]";
            $reponse = mysqli_query($conn, $sql);
            while($data = @mysqli_fetch_array($reponse)){
                echo "<div class='your-style-container'>
                        <div class='style-name-container'>
                            <button class='style-name-button' name='style' value=$data[style_nb]>$data[style_name]</button>
                        </div>
                        <div class='display-and-earnings-container'>
                            <label>earnings:</label><input type='text' size=12 readonly class='earnings-field' value=&dollar;$data[stylist_earnings]>
                        </div>
                        </div>";
            }
            echo "</div>";
            if(isset($_GET['do_style_button'])){
                unset($_SESSION['season']);
                unset($_SESSION['season_items']);
                unset($_SESSION['items']);
                unset($_SESSION['needed']);
                unset($_SESSION['key']);
                unset($_SESSION['query']);
                header("Location: do_styles.php");
            }
            if(isset($_GET['your_styles_button']))
                header("Location: your_styles.php");
            if(isset($_GET['style'])){
                echo "  <div class='unclear-container'>
                            <div class='offer-items-container'>";
                $sql = "select c.src from clothe c,style_details s where s.style_nb=$_GET[style] and c.id=s.clothe_id";
                $reponse = mysqli_query($conn, $sql);
                while($data = @mysqli_fetch_array($reponse)){
                    echo "<div class='item'>
                            <div class='item-img-container'>
                                <img class='item-img' src='$data[src]'>
                            </div>
                          </div>";
                }
                echo "  </div>
                        <div class='add-offer-container'>
                            <button class='add-offer-button' name='display' value=$_GET[style]>Display as offer</button>
                        </div>
                    </div>";              
            }
            if(isset($_GET['display'])){
                $sql = "select end_date from month_of_offers";
                $reponse = mysqli_query($conn, $sql);
                $data = mysqli_fetch_array($reponse);
                $offers_end_date_str = $data['end_date'];
                $offers_end_date = strtotime($offers_end_date_str);
                $offer_start_date_str = date('Y-m-d H:i:s');
                $offer_start_date = strtotime($offer_start_date_str);
                $addition = 7;
                $offer_end_date_str = date('Y-m-d H:i:s', strtotime("+$addition days", $offer_start_date));
                $offer_end_date = strtotime($offer_end_date_str);
                if($offer_end_date > $offers_end_date)
                    $offer_end_date_str = $offers_end_date_str;
                $sql = "insert into offer (style_nb,start_date,end_date) values ($_GET[display],'$offer_start_date_str','$offer_end_date_str')";
                $reponse = mysqli_query($conn, $sql);
            }
            if(isset($_GET['logout_button'])){
                session_unset();
                session_destroy();
                header("Location: home.php");
            }
            mysqli_close($conn);
        ?>
    </body>
</html>