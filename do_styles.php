<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="p2(stylesheet).css">
    </head>
    <body>
        <form action='do_styles.php' method=get>
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
            <div class=nav-bar2-style>
                <div class=search-container>
                    <div class=search-container2>
                        <input type=text class=search-field name=search_field placeholder=Search>
                        <button class=search-button name=search_button>
                            <img src='./images/search icon.svg' width='80%' height='80%' style='vertical-align: middle;'>
                        </button>
                    </div>
                </div>
                <div class=filter-container>
        <?php
            session_start();
            $conn = mysqli_connect("localhost", "root", "");
            mysqli_select_db($conn,"project");
            if(isset($_GET['logout_button'])){
                session_unset();
                session_destroy();
                header("Location: home.php");
            }
                if(isset($_SESSION['season']) || isset($_GET['season']))
                    echo "
                        <button class=season-buttons-after-clicked>Winter</button>
                        <button class=season-buttons-after-clicked>Summer</
                        button>
                        
                    ";
                else 
                    echo "
                        <button name='season' value='winter' class=season-buttons-before-clicked>Winter</button>
                        <button name='season' value='summer' class=season-buttons-before-clicked>Summer</
                        button>
                    "; 
                echo "</div>
                <div class=cart-container>
                    <button name=style_components class=style-button>Style components</button>
                </div>
            </div>";
            function still_need(){
                $i = 0;
                foreach($_SESSION['season_items'] as $val){
                    if($val != 'null'){
                        $needed_items[$i] = $val;
                        $i++;
                    }
                }
                if($i == 0){
                    echo "<div class='green-message-after-action'>Now the $_SESSION[season] style includes all the needed items.</div>";
                    unset($_SESSION['needed']);
                }  
                else {
                    $_SESSION['needed'] = implode(',',$needed_items);
                    echo "<div class='yellow-message-after-action'>Still need a $_SESSION[needed].</div>";
                }
            }
            if(isset($_GET['style_components']) || isset($_GET['remove_button']))
                echo "<div style='width:80%'>";
            else
                echo "<div style='width:100%'>";
            if(isset($_GET['add_button'])){
                if(!isset($_SESSION['season']))
                    echo "<div class='red-message-after-action'>Please choose the desired season first.</div>";
                else if(isset($_SESSION['items']) && !isset($_SESSION['needed']))
                        echo "<div class='red-message-after-action'>The $_SESSION[season] style already includes all the needed items.</div>";
                else{
                    $reponse = mysqli_query($conn, "select description from clothe where id=$_GET[add_button]");
                    $data = @mysqli_fetch_array($reponse);
                    $stop = false;
                    foreach($_SESSION['items'] as $val){
                        $reponse2 = mysqli_query($conn, "select description from clothe where id=$val");
                        $data2 = @mysqli_fetch_array($reponse2);
                        if($data['description'] == $data2['description']){
                            $stop = true;
                            break;
                        }   
                    }
                    if($stop == false){
                        $key = array_search($data['description'],$_SESSION['season_items']);
                        if(!isset($_SESSION['key']))
                            $_SESSION['key'] = -1;
                        $_SESSION['key']++;
                        $_SESSION['items'][$_SESSION['key']] = $_GET['add_button'];
                        $_SESSION['season_items'][$key] = 'null';
                    }
                    still_need();
                }
            }
            else if(isset($_GET['remove_button'])){
                $reponse = mysqli_query($conn, "select description from clothe where id=$_GET[remove_button]");
                $data = @mysqli_fetch_array($reponse);
                foreach($_SESSION['season_items'] as $key => $val){
                    if($val == 'null'){
                        $_SESSION['season_items'][$key] = $data['description'];
                        break;
                    }
                }
                still_need();
            }
            else if(isset($_GET['season'])){
                if($_GET['season'] == 'winter'){
                    $_SESSION['season_items'] = ['shirt', 'pants', 'shoes', 'jacket'];
                    $season_items = implode(',',$_SESSION['season_items']);
                    echo "<div class='yellow-message-after-action'>Since you have chosen the winter season, your desired style should include a $season_items.</div>";
                }
                else{
                    $_SESSION['season_items'] = ['shirt' , 'shoes'];
                    $season_items = implode(',',$_SESSION['season_items']);
                    echo "<div class='yellow-message-after-action'>Since you have chosen the summer season, your desired style should include a $season_items.</div>";
                }
            }
            else if(isset($_SESSION['season'])){
                if(!isset($_SESSION['items'])){
                    if($_SESSION['season'] == 'winter')
                        echo "<div class='yellow-message-after-action'>Since you have chosen the winter season, your desired style should include a shirt,pants,shoes,jacket.</div>";
                    else
                        echo "<div class='yellow-message-after-action'>Since you have chosen the summer season, your desired style should include a shirt,shoes.</div>";
                }
                else if(!isset($_SESSION['needed']))
                    echo "<div class='green-message-after-action'>Now the style includes all the needed items.</div>";
                else
                    echo "<div class='yellow-message-after-action'>Still need a $_SESSION[needed].</div>";
            }
            else
                echo "<div class='message-before-action'>welcome</div>";
                
            echo "    <div class=items-container>
                    
        ";
        if(isset($_GET['your_styles_button']))
            header("Location: your_styles.php");
        if(isset($_GET['do_style_button'])){
            unset($_SESSION['season']);
            unset($_SESSION['season_items']);
            unset($_SESSION['items']);
            unset($_SESSION['needed']);
            unset($_SESSION['key']);
            unset($_SESSION['query']);
            header("Location: do_styles.php");
        }
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
        else if((isset($_GET['add_button']) || isset($_GET['style_components']) || isset($_GET['remove_button']) || isset($_GET['exit_button'])) && isset($_SESSION['query']))
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
                                <button class='add-to-style-button-before-clicked' name=add_button value=$data[id]>Add to style</button>
                        </div>
                    </div>";
        }
        echo "
                </div>
            </div>
        ";
        function go(){
            $conn = mysqli_connect("localhost", "root", "");
            mysqli_select_db($conn,"project");
            $sql = "select id,src from clothe where id in (";
            foreach($_SESSION['items'] as $val)
                if($val != 'null')
                    $sql.= $val.",";
            $sql.= $_SESSION['items'][0].")";
            echo "<div class='side-bar'>
                    <button class='exit-button' name='exit_button'>
                        <img class='exit-icon' src='./images/exit icon.svg'>
                    </button>";
            $reponse = mysqli_query($conn, $sql);
            while($data = @mysqli_fetch_array($reponse)){
                echo "
                    <div class='row-in-side-bar'>
                        <div style='width: 50%;height: 100%'>
                            <img src='$data[src]' style='height: 100%;width:100%; object-fit: contain'>
                        </div>
                        <div>
                            <button class='remove-button' name='remove_button' value=$data[id]>Remove</button>
                        </div>
                    </div>
                ";
            }
            echo "
                <div class='decision-buttons'>
                    <button style='width:50%' class='cancel-button' name='cancel_button' value='sdc'>Cancel</button>";
            $i = 0;
            foreach($_SESSION['season_items'] as $val){
                if($val != 'null'){
                    $i = 1;
                    break;
                }
            }
            if($i == 0)
                echo "<button style='width:50%' class='done-button-after-finishing-style' name='done_button'>Done</button>";
            else
                echo "<button style='width:50%' class='done-button-before-finishing-style' name='done_button'>Done</button>";
            echo "
                </div>
                </div></form>";
        }
        if(isset($_GET['remove_button'])){
            $i = array_search($_GET['remove_button'],$_SESSION['items']);
            $_SESSION['items'][$i] = 'null';
            go();
        }
        if(isset($_GET['style_components'])){
            go();
        }
        if(isset($_GET['cancel_button'])){
            unset($_SESSION['season']);
            unset($_SESSION['season_items']);
            unset($_SESSION['items']);
            unset($_SESSION['needed']);
            unset($_SESSION['key']);
            unset($_SESSION['query']);
        }
        if(isset($_GET['create_button'])){
            $sql = "select sum(unit_price) from clothe where id in (";
            foreach($_SESSION['items'] as $val)
                if($val != 'null')
                    $sql.= $val.",";
            $sql.= "'null')";
            $reponse = mysqli_query($conn, $sql);
            $data = @mysqli_fetch_array($reponse);
            $style_price = $data['sum(unit_price)'];
            $stylist_id = $_SESSION['account_id'];
            $sql = "insert into style (style_name,style_price,stylist_id,stylist_earnings) values ('$_GET[style_name]',$style_price,$stylist_id,0)";
            $reponse = mysqli_query($conn, $sql);
            $sql = "select max(style_nb) from style";
            $reponse = mysqli_query($conn, $sql);
            $data = @mysqli_fetch_array($reponse);
            $style_nb = $data['max(style_nb)'];
            foreach($_SESSION['items'] as $val)
                if($val != 'null'){
                    $sql = "insert into style_details (style_nb,clothe_id) values ($style_nb,$val)";
                    $reponse = mysqli_query($conn, $sql);
                }
            unset($_SESSION['season']);
            unset($_SESSION['season_items']);
            unset($_SESSION['items']);
            unset($_SESSION['needed']);
            unset($_SESSION['key']);
            unset($_SESSION['query']);
            header("Location: do_styles.php");
        }
        if(isset($_GET['done_button'])){
            echo "
                <form action='do_styles.php' method='get'>
                    <div class='unclear-container'>
                        <div class='displaying-style-container'>
                            <div>
                                <button class='exit2-button'>
                                    <img src='./images/exit2.svg' class='exit2-icon'>
                                </button>
                            </div>
                            <div class='disclaimer-title'><center>Give a style name</center></div>
                            <input type='text' class='card-number-field' name='style_name'>
                            <div class='purchase-button-container'>
                                <button name='create_button' class='buy-button'>Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            ";
        }
        mysqli_close($conn);
        ?>          
    </body>
</html>