<!DOCTYPE html>
<html>
    <head><link rel="stylesheet" href="p2(stylesheet).css"></head>
    <body>
        <form action='shop.php' method=get>
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
            <div class='nav-bar2'>
                <div class=search-container>
                    <div class=search-container2>
                        <input type=text class=search-field name=search_field placeholder=Search>
                        <button class=search-button name=search_button>
                            <img src='./images/search icon.svg' width='80%' height='80%' style='vertical-align: middle;'>
                        </button>
                    </div>
                    
                </div>
                <div class=filter-container>
                    <button name='season' value='winter' class=season-buttons-before-clicked>Winter</button>
                    <button name='season' value='summer' class=season-buttons-before-clicked>Summer</button>
                </div>
                <div class=cart-container>
                        <button class='cart-button' name='cart_button'>
                            <img src='./images/cart.svg' class='cart-icon'>
                        </button>
                </div>
            </div>
        <?php
            session_start();
            if(isset($_GET['logout_button'])){
                session_unset();
                session_destroy();
                header("Location: home.php");
            }
            if(isset($_GET['cart_button']) || isset($_GET['remove_button']) || isset($_GET['calculate']))
                echo "<div style='width:70%'>";
            else
                echo "<div style='width:100%'>";
            date_default_timezone_set('Asia/Beirut');
            $conn = mysqli_connect("localhost", "root", "");
            mysqli_select_db($conn,"project");
            $sql ="select end_date from month_of_offers";
            $reponse = mysqli_query($conn, $sql);
            $data = mysqli_fetch_array($reponse);
            $expiredDatetime = $data['end_date'];
            $currentDatetime = date('Y-m-d H:i:s'); 
            $remainingTime = strtotime($expiredDatetime) - strtotime($currentDatetime);
            if($remainingTime >= 0){
                echo "<div class='month-of-offers-timer'></div>";
                $js= '
                <script>
                    var remainingTime = "'.$remainingTime.'"
                    function countdown() {
                        var days = Math.floor(remainingTime / (60 * 60 * 24));
                        var hours = Math.floor((remainingTime % (60 * 60 * 24)) / (60 * 60));
                        var minutes = Math.floor((remainingTime % (60 * 60)) / 60);
                        var seconds = remainingTime % 60;
                        if(hours < 10)
                            hours = "0" + hours; 
                        if(minutes < 10)
                                minutes = "0" + minutes;
                        if(seconds < 10)
                                seconds = "0" + seconds;
                        if (remainingTime < 0) {
                            clearInterval(timer);
                        } else {
                            document.querySelector(".month-of-offers-timer").innerHTML = `Month of offers ends after &nbsp;&nbsp; ${days} days : ${hours} hours : ${minutes} minutes : ${seconds} seconds`;
                            remainingTime--;
                        }
                    }
                    var timer = setInterval(countdown, 1000);
                </script>
                ';
                echo $js;
                echo "<div class='offers-container'>";
                $sql = "select a.first_name,a.last_name,s.style_nb,s.style_name,s.style_price,o.end_date from account a,style s,offer o where a.id=s.stylist_id and s.style_nb=o.style_nb";
                $reponse = mysqli_query($conn, $sql);
                while($data = @mysqli_fetch_array($reponse)){
                    $remainingTime = strtotime($data['end_date']) - strtotime($currentDatetime);
                    $remainingDays = floor($remainingTime / (60 * 60 * 24));
                    $remainingHours = floor(($remainingTime % (60 * 60 * 24)) / (60 * 60));
                    $remainingMinutes = floor(($remainingTime % (60 * 60)) / 60);
                    echo "
                        <div class='offer-container'>
                            <button class='offer-name-button' name='style_details_button' value='$data[style_nb]' style='border-radius: 20px'>$data[style_name]</button>
                            <div class='expire-date-container'>$remainingDays days $remainingHours hours $remainingMinutes mins</div>
                            <div class='stylist-name-container'><span class='designed-by'>Designed by&nbsp;</span> $data[first_name] $data[last_name] &#169;</div>
                        </div>
                    ";
                }
                echo "</div>";
            }
            echo "<div class='shop-items-container'>";
            $sql = "select id,src,unit_price from clothe";
            if(isset($_GET['season'])){
                $sql.= " where season='$_GET[season]'";
                $_SESSION['season'] = $_GET['season'];
                $_SESSION['query'] = $sql;
            } 
            else if(isset($_GET['search_button'])){
                $sql.= " where description='$_GET[search_field]'";
                if(isset($_SESSION['season']))
                    $sql.= " and season='$_SESSION[season]'";
                $_SESSION['query'] = $sql;
            }
            else if((isset($_GET['add_button']) || isset($_GET['cart_button']) || isset($_GET['remove_button']) || isset($_GET['exit_button']) || isset($_GET['back_button']) || isset($_GET['add_offer']) || isset($_GET['calculate'])) && isset($_SESSION['query']))
                $sql = $_SESSION['query'];    
            $reponse = mysqli_query($conn, $sql);
            while($data = mysqli_fetch_array($reponse)){
                echo "
                        <div class='item'>
                            <div class='item-img-container'>
                                <img class='item-img' src='$data[src]'>
                            </div>
                            <div class='details-row'>
                                <div style='color: white;'>&dollar;$data[unit_price]</div>
                                <button class='add-to-style-button-before-clicked' name='add_button' value=$data[id]>Add to cart</button>
                            </div>
                        </div>
                ";
            }
            echo "
                    </div>
            ";
            if(isset($_GET['style_details_button'])){
                echo "  <div class='blurred-container'>
                            <div class='offer-items-container'>";
                $sql = "select c.src,s.style_price from clothe c,style s,style_details d where s.style_nb=d.style_nb and d.style_nb=$_GET[style_details_button] and c.id=d.clothe_id";
                $reponse = mysqli_query($conn, $sql);
                while($data = @mysqli_fetch_array($reponse)){
                    echo "<div class='item'>
                            <div class='item-img-container'>
                                <img class='item-img' src='$data[src]'>
                            </div>
                          </div>";
                }
                $sql = "select style_price from style where style_nb=$_GET[style_details_button] ";
                $reponse = mysqli_query($conn, $sql);
                $data = @mysqli_fetch_array($reponse);
                $init_price = $data['style_price'];
                $new_price = $init_price - $init_price * 0.1;
                echo "  </div>
                        <div class='add-offer-container'>
                            <div>
                                <button class='back-button' name='back_button'>
                                <img src='./images/back-arrow.svg' style='width: 25px'>
                                <span>Back</span></button>
                            </div>
                            <div class='offer-percent-container'>10% off</div>
                            <div class='new-price-container'>$new_price</div>
                            <div class='init-price-container'>$init_price</div>
                            <button class='add-offer-button' name='add_offer' value=$_GET[style_details_button]>Add to cart</button>
                        </div>
                    </div>";          
            }
            if(isset($_GET['add_offer'])){
                $_SESSION['offer_exists'] = 'yes';
                if(!isset($_SESSION['item_key']))
                    $_SESSION['item_key'] = -1;
                $_SESSION['item_key']++;
                $_SESSION['shop_items'][$_SESSION['item_key']] = $_GET['add_offer'];
            }
            if(isset($_GET['add_button'])){
                $_SESSION['clothe_exists'] = 'yes';
                if(!isset($_SESSION['item_key']))
                    $_SESSION['item_key'] = -1;
                $_SESSION['item_key']++;
                $_SESSION['shop_items'][$_SESSION['item_key']] = $_GET['add_button'];
            }
            function go(){
                $i = 0;
                $conn = mysqli_connect("localhost", "root", "");
                mysqli_select_db($conn,"project");
                echo "<div class='cart-bar'>
                            <button class='exit-button' name='exit_button'>
                                <img class='exit-icon' src='./images/exit icon.svg'>
                            </button>";
                if(isset($_SESSION['offer_exists'])){
                    $sql = "select s.style_nb,s.style_name,a.first_name,a.last_name from style s,account a where a.id=s.stylist_id and style_nb in (";
                    foreach($_SESSION['shop_items'] as $val)
                        if($val != 'null' && $val >= 500)
                            $sql.= $val.",";
                    $sql.= $_SESSION['shop_items'][0].")";
                    $reponse = mysqli_query($conn, $sql);
                    while($data = @mysqli_fetch_array($reponse)){
                        $_SESSION['new_items'][$i] = $data['style_nb'];
                        echo "
                            <div class='row-in-side-bar'>
                                <div class='min-offer-container'>
                                    <div>$data[style_name]</div>
                                    <div class='min-stylist-name-container'>By $data[first_name] $data[last_name]</div>
                                </div>";
                        if(isset($_SESSION['items_amount'][$_SESSION['new_items'][$i]]) && $_SESSION['items_amount'][$_SESSION['new_items'][$i]] != 0){
                            $mag = $_SESSION['items_amount'][$_SESSION['new_items'][$i]];
                            echo "<input type='text' size=5 name='quantity_field[]' value=$mag class='quantity-field'>";
                        }
                        else
                            echo "<input type='text' size=5 name='quantity_field[]' class='quantity-field'>";
                        echo  "
                                <div>
                                    <button class='remove-button' name='remove_button' value=$data[style_nb]>Remove</button>
                                </div>
                            </div>
                        ";
                        $i++;
                    }
                }
                if(isset($_SESSION['clothe_exists'])){
                    $sql = "select id,src from clothe where id in (";
                    foreach($_SESSION['shop_items'] as $val)
                        if($val != 'null' && $val < 500)
                            $sql.= $val.",";
                    $sql.= $_SESSION['shop_items'][0].")";
                    $reponse = mysqli_query($conn, $sql);
                    while($data = @mysqli_fetch_array($reponse)){
                        $_SESSION['new_items'][$i] = $data['id'];
                        echo "
                            <div class='row-in-side-bar'>
                                <div style='width: 50%;height: 100%'>
                                    <img src='$data[src]' style='height: 100%;width:100%; object-fit: contain'>
                                </div>";
                        if(isset($_SESSION['items_amount'][$_SESSION['new_items'][$i]]) && $_SESSION['items_amount'][$_SESSION['new_items'][$i]] != 0){
                            $mag = $_SESSION['items_amount'][$_SESSION['new_items'][$i]];
                            echo "<input type='text' size=5 name='quantity_field[]' value=$mag class='quantity-field'>";
                        }
                        else
                            echo "<input type='text' size=5 name='quantity_field[]' class='quantity-field'>";        
                        echo "  <div>
                                    <button class='remove-button' name='remove_button' value=$data[id]>Remove</button>
                                </div>
                            </div>
                        ";
                        $i++;
                    }
                }
                if(!isset($_SESSION['total_price']))
                    $_SESSION['total_price'] = 0;
                echo "<div class='buy-container'>
                        <div>
                            <button class='calculate-button' name='calculate'>Calculate total price</button>
                            <input type='text' size=10 class='total-price' value=&dollar;$_SESSION[total_price] readonly>
                        </div>
                        <button class='buy-button' name='buy'>Buy</button>
                      </div>
                </div>";
            }
            if(isset($_GET['cart_button'])){
                go();
            }
            if(isset($_GET['remove_button'])){
                $i = array_search($_GET['remove_button'],$_SESSION['shop_items']);
                $_SESSION['shop_items'][$i] = 'null';
                $_SESSION['items_amount'][$_GET['remove_button']] = 0;
                unset($_SESSION['new_items']);
                go();
            }
            if(isset($_GET['calculate'])){
                $i = 0;
                $fields = $_GET['quantity_field'];
                foreach($fields as $val){
                    if($val != ''){
                        $_SESSION['items_amount'][$_SESSION['new_items'][$i]] = $val;
                        if($_SESSION['new_items'][$i] <500)
                            $_SESSION['a_clothe'] = 'yes';
                        else
                            $_SESSION['an_offer'] = 'yes';
                    }
                    else{
                        $_SESSION['items_amount'][$_SESSION['new_items'][$i]] = 0;
                        $_SESSION['new_items'][$i] = 'null';
                    }
                    $i++;
                }
                $_SESSION['total_price'] = 0;
                if(isset($_SESSION['an_offer'])){
                    $sql = "select style_nb,style_price from style where style_nb in (";
                    foreach($_SESSION['new_items'] as $val)
                        if($val != 'null' && $val >= 500)
                            $sql.= $val.",";
                    $sql.= $_SESSION['new_items'][0].")";
                    $reponse = mysqli_query($conn, $sql);
                    while($data = @mysqli_fetch_array($reponse)){
                        $price = $data['style_price'] - $data['style_price'] * 0.1;
                        $_SESSION['total_price'] = $_SESSION['total_price'] + $price * $_SESSION['items_amount'][$data['style_nb']];
                    }
                }
                if(isset($_SESSION['a_clothe'])){
                    $sql = "select id,unit_price from clothe where id in (";
                    foreach($_SESSION['new_items'] as $val)
                        if($val != 'null' && $val < 500)
                            $sql.= $val.",";
                    $sql.= $_SESSION['new_items'][0].")";
                    $reponse = mysqli_query($conn, $sql);
                    while($data = @mysqli_fetch_array($reponse)){
                        $_SESSION['total_price'] = $_SESSION['total_price'] + $data['unit_price'] * $_SESSION['items_amount'][$data['id']];
                    }
                }
                go();
            }
            if(isset($_GET['buy'])){
                echo "
                    <form action='shop.php' method='get'>
                        <div class='unclear-container'>
                            <div class='displaying-offer-container'>
                                <button class='exit2-button'>
                                    <img src='./images/exit2.svg' class='exit2-icon'>
                                </button>
                                <div class='disclaimer-title'><center>Enter card number:</center></div>
                                <input type='text' class='card-number-field' name='style_name'>
                                <div class='purchase-button-container'>
                                    <button name='purchase_button' class='buy-button'>Purchase</button>
                                </div>
                            </div>
                        </div>
                    </form>
                ";
            }
            if(isset($_GET['purchase_button'])){
                $sql = "insert into purchase (purchase_date,client_id) values ('$currentDatetime',$_SESSION[account_id])";
                $reponse = mysqli_query($conn, $sql);
                $sql = "select max(purchase_nb) from purchase";
                $reponse = mysqli_query($conn, $sql);
                $data = @mysqli_fetch_array($reponse);
                $purchase_nb = $data['max(purchase_nb)'];
                foreach($_SESSION['items_amount'] as $key => $val){
                    if($val != 0){
                        if($key >= 500){
                            $sql = "select style_price from style where style_nb=$key";
                            $reponse = mysqli_query($conn, $sql);
                            $data = @mysqli_fetch_array($reponse);
                            $style_price = $data['style_price'];
                            $exp = 0.05*$style_price*$val;
                            $sql = "update style set stylist_earnings = stylist_earnings+$exp where style_nb=$key";
                            $reponse = mysqli_query($conn, $sql);
                            $sql = "select offer_nb from offer where style_nb=$key";
                            $reponse = mysqli_query($conn, $sql);
                            $data = @mysqli_fetch_array($reponse);
                            $offer_nb = $data['offer_nb'];
                            $sql = "insert into purchase_offers (purchase_nb,offer_nb,offer_amount) values($purchase_nb,$offer_nb,$val)";
                        }
                        else
                            $sql = "insert into purchase_clothes (purchase_nb,clothe_id,clothe_amount) values($purchase_nb,$key,$val)";
                        $reponse = mysqli_query($conn, $sql);
                    }
                }
                unset($_SESSION['season']);
                unset($_SESSION['query']);
                unset($_SESSION['offer_exists']);
                unset($_SESSION['clothe_exists']);
                unset($_SESSION['item_key']);
                unset($_SESSION['shop_items']);
                unset($_SESSION['new_items']);
                unset($_SESSION['items_amount']);
                unset($_SESSION['total_price']);
                unset($_SESSION['a_clothe']);
                unset($_SESSION['an_offer']);
            }
            echo "</div></form>";
        ?>
    </body>
</html>