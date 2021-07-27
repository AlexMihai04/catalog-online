<!-- © 2020 Udrescu Alexandru All Rights Reserved -->

<?php
    include 'utils/utils.php';
    error_reporting(0);
    session_start();
    if($_SESSION["is_auth"]){
        header("location:index.php");
    }
    if(!$_SESSION["crsf"]) $_SESSION["crsf"] = get_token();
?>


<html>

<head>

    <link rel="icon" type="image/png" href="poze/imagine_site.png" />
    <title>Catalog Online</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Libraria W3 -->
    <link rel="stylesheet" href="css/w3.css">

    <!-- Style adaugat de mine -->
    <link rel="stylesheet" href="css/style.css">

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- Un fel de cfg -->
    <script src="cfg/cfg.js" type="text/javascript"></script>

    <script>
        if(window.location.href.match('^https://')){
            window.location.href = window.location.href.replace("https://","http://")
        }
    </script>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/f1ead0569a.js" crossorigin="anonymous"></script>

    <!-- O librarie pentru tooltips -->
    <link href="css/tooltips.min.css" rel="stylesheet">

    <!-- Libraria iziToast -->
    <link rel="stylesheet" href="css/iziToast.min.css">
    <script src="js/iziToast.min.js" type="text/javascript"></script>

    <!-- Modul pentru iziToast , am folosit doar 2 notificari si nu avea sens sa stau sa apelez functiile de acolo mereu -->
    <script src="js/notifs.js" type="text/javascript"></script>

    <!-- O librarie pentru animatii -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body,html {
            height: 100%;
            background-image:url(poze/bg_list.png);
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="w3-row" style="height:auto;width:100%">
            <div class="w3-col" style="width:100%;">
                <div id="login" class="w3-display-container w3-opacity-min w3-text-grey" style="height: 100%;width:100%">
                    <div class="w3-display-left" style="width:100%">
                        <center>
                            <div class="w3-card-4 selected_class w3-round animate__animated animate__bounceInLeft login_case">
                                <div class="w3-container">
                                    <center><h3>Login</h3></center>
                                </div>
                                <hr style="width: 100%;background-color:black;"></hr>
                                <form class="w3-container" id="login_form" name="login_form">
                                    <div class="w3-row w3-padding">
                                        <div class="w3-col animate__animated animate__bounceInDown" style="width:50px">
                                            <i class="w3-xxlarge fa fa-user"></i>
                                        </div>
                                        <div class="w3-rest animate__animated animate__bounceInRight">
                                            <input class="w3-input w3-border w3-white" name="login_username" id="login_username" type="text" placeholder="Username" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="w3-row w3-padding">
                                        <div class="w3-col animate__animated animate__bounceInDown" style="width:50px">
                                            <i class="w3-xxlarge fas fa-key"></i>
                                        </div>
                                        <div class="w3-rest animate__animated animate__bounceInRight">
                                            <input class="w3-input w3-border w3-white" name="login_password" id="login_password" type="password" placeholder="Parola" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="w3-row w3-padding">
                                        <button type="button" @click="login()" class="w3-button w3-block w3-round" style="background-color:#aeb4b8;color:black;">Login</button>
                                    </div>
                                </form>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="crsf" name="crsf" value="<?php echo $_SESSION['crsf']; ?>">
    </div>
    <div class="w3-container animate__animated animate__swing" style="background-color: transparent;bottom:10px;position: fixed;width:100%">
        <center><a class="w3-button w3-round credits_class" style="font-weight: bold;background-color:#3d3321;color:white;">&copy Udrescu Alexandru Mihai</a></center>
    </div>

    <script src="js/vue.min.js"></script>
    <script src="js/login.js"></script>
</body>

</html>