<!-- © 2020 Udrescu Alexandru All Rights Reserved -->

<?php
    //error_reporting(0);
    session_start();
    if(!$_SESSION["is_auth"]){
        header("location:login.php");
        die();
    }
    if($_SESSION["group"] != "admin"){
        header("location:index.php");
        die();
    }
    if($_SESSION['group'] == "" or $_SESSION['group'] == ' ' or $_SESSION['group'] == '  '){
        header("location:logout.php");
    }
?>
<!DOCTYPE html>
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

    <!-- Librarie pentru tooltips -->
    <link href="css/tooltips.min.css" rel="stylesheet">

    <!-- Librarie pentru notificari -->
    <link rel="stylesheet" href="css/iziToast.min.css">
    <script src="js/iziToast.min.js" type="text/javascript"></script>

    <!-- Modul pentru notificari(adaptare la izitoast) facut de mine -->
    <script src="js/notifs.js" type="text/javascript"></script>

    <!-- Animate on scroll ( librarie ) -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Librarie animatii -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body onload="resize_page()" onresize="resize_page()">

    <div id="app">
        <div class="w3-sidebar w3-animate-left hide-scroll" id="side_bar" style="min-width:230px;background-image:url(poze/bg_list.png);">
            <div class="w3-bar" style="border-bottom:5px solid #aeb4b8;background:rgb(255,255,255) !important">
                <button onclick="close_nav()" class="w3-bar-item w3-left w3-margin buton_custom animate__animated animate__bounceInLeft" style="border-radius : 10px;" title="close Sidebar">×</button>
                <span class="w3-bar-item w3-margin animate__animated animate__bounceInDown"><center>Clasele tale</center></span>
            </div>
            <div style="width:100%;min-height:100%;border-right:5px solid #ebf2f7">
            <br>
                <template v-if="exista_clasa('CP')">
                    <div class="w3-bar-block">
                        <a class="w3-bar-item animate__animated animate__bounceInRight" style="border-bottom:5px solid #ebf2f7;"><center><i class="fas fa-school"></i>&nbsp Clasele CP</center></a>
                    </div>
                    <div class="w3-bar-block w3-margin" v-for="(data,index) in clase">
                        <!-- <transition v-enter="" -->
                        <template v-if="data.nume.substr(0, 2) == 'CP'">
                            
                            <a class="w3-bar-item buton_custom animate__animated animate__bounceInLeft unselected_class" style="text-decoration: none;" href="index.php"><i class="fas fa-users w3-hide-small"></i>&nbsp{{ data.nume}} <div class="w3-tag w3-round w3-right" style="background-color:#b9e2fe">{{data.numar_elevi}}</div></a>
                        </template>
                    </div>
                </template>
                <template v-for="n in 12" v-if="exista_clasa(n)">
                    <div class="w3-bar-block">
                        <a class="w3-bar-item animate__animated animate__bounceInRight" style="border-bottom:5px solid #ebf2f7;"><center><i class="fas fa-school"></i>&nbsp Clasele {{n}}</center></a>
                    </div>
                    <div class="w3-bar-block w3-margin" v-for="(data,index) in clase">
                        <template v-if="data.nume.substr(0, 2) == (n+' ') || data.nume.substr(0, 2) == n">
                            <a class="w3-bar-item buton_custom animate__animated animate__bounceInLeft unselected_class" style="text-decoration: none;" href="index.php"><i class="fas fa-users w3-hide-small"></i>&nbsp{{ data.nume }} <div class="w3-tag w3-round w3-right" style="background-color:#b9e2fe">{{data.numar_elevi}}</div></a>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <div id="main" style="margin-left: 230px;">
            <div class="w3-bar nav_bar" style="border-bottom:5px solid #aeb4b8;">
                    <span class="w3-bar-item w3-margin buton_custom" id="openNav" style="border-radius : 10px;" onclick="open_nav()">☰</span>
                    <a href="logout.php" class="w3-bar-item w3-button selected_class w3-margin w3-right animate__animated animate__bounceInDown"><i class="fas fa-sign-out-alt"></i></a>
                    <a href="index.php" class="w3-bar-item w3-button selected_class w3-margin-bottom w3-margin-top w3-right animate__animated animate__bounceInDown"><i class="fas fa-home"></i></a>
            </div>
            <br>
            <div class="w3-container w3-container-display" v-bind:class="{ 'w3-hide-small': opened_meniu_clase }">
                <div class="w3-row w3-margin w3-animate-left">
                    <a href="index.php" class="w3-button selected_class" >Inapoi</a>
                </div>
                <div class="w3-row w3-margin w3-animate-left">
                    <a class="w3-button selected_class w3-margin-bottom w3-block" @click="show = 'add_cont'">Creeaza cont</a>
                    <a class="w3-button selected_class w3-margin-bottom w3-hide-small w3-block" @click="show = 'lista_all_elevi';get_all_el()">Sterge conturi</a>
                    <a class="w3-button selected_class w3-margin-bottom w3-block" @click="show = 'add_clasa'">Creeaza clasa</a>
                    <a class="w3-button selected_class w3-margin-bottom w3-hide-small w3-block" @click="show = 'sterge_clasa'">Sterge clasa</a>
                    <a class="w3-button selected_class w3-margin-bottom w3-hide-small w3-block" @click="show = 'lista_elevin';getter('el_not')">Adauga elev in clasa</a>
                    <a class="w3-button selected_class w3-margin-bottom w3-block" @click="show = 'lista_loguri';getter('loguri')">Istoric actiuni</a>
                </div>
                <div class="w3-row w3-margin" v-if="show == 'add_cont'">
                    <div class="w3-card-4 selected_class w3-round w3-col s12 l12 m12 w3-animate-left">
                        <div class="w3-container">
                            <center><h2>Adauga cont</h2></center>
                        </div>
                        <hr style="width:100%;background-color:black;"></hr>
                        <form class="w3-container" id="form_add_cont" name="form_add_cont">
                            <div class="w3-row w3-padding">
                                <div class="w3-col" style="width:50px">
                                    <i class="w3-xxlarge fas fa-user"></i>
                                </div>
                                <div class="w3-rest">
                                    <input class="w3-input w3-border w3-white" name="first_name" id="first_name" type="text" placeholder="Nume" required>
                                </div>
                            </div>
                            <div class="w3-row w3-padding">
                                <div class="w3-col" style="width:50px">
                                    <i class="w3-xxlarge fas fa-user"></i>
                                </div>
                                <div class="w3-rest">
                                    <input class="w3-input w3-border w3-white" name="last_name" id="last_name" type="text" placeholder="Prenume" required>
                                </div>
                            </div>
                            <div class="w3-row w3-padding">
                                <div class="w3-col" style="width:50px">
                                    <i class="w3-xxlarge fas fa-at"></i>
                                </div>
                                <div class="w3-rest">
                                    <input class="w3-input w3-border w3-white" name="email" id="email" type="mail" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="w3-row w3-padding">
                                <label for="grup">Tip cont :</label>
                                <select name="grup" id="grup" style="width:100%;">
                                    <option value="elev">Elev</option>
                                    <option value="prof">Profesor</option>
                                </select>
                            </div>
                            <div class="w3-row w3-padding">
                                <button type="button" class="w3-button w3-block w3-round" style="background-color:#aeb4b8;color:black;" @click="adder('add_cont')">ADAUGA</button>
                            </div>           
                        </form>
                    </div>
                </div>
                <div class="w3-row w3-margin" v-if="show == 'add_clasa'">
                    <div class="w3-card-4 selected_class w3-col s12 l12 m12 w3-animate-left">
                        <div class="w3-container">
                            <center><h2>Adauga clasa</h2></center>
                        </div>
                        <hr style="width:100%;background-color:black;"></hr>
                        <form class="w3-container" id="form_add_clasa" name="form_add_clasa">
                            <div class="w3-row w3-padding">
                                <div class="w3-col" style="width:50px">
                                    <i class="w3-xxlarge fa fa-user"></i>
                                </div>
                                <div class="w3-rest">
                                    <input class="w3-input w3-border w3-white" name="nume_clasa" id="nume_clasa" type="text" placeholder="Nume clasa;Exemplu : X MIEG" required>
                                </div>
                            </div>
                            <div class="w3-row w3-padding">
                                <button type="button" class="w3-button w3-block w3-round" style="background-color:#aeb4b8;color:black;" @click="adder('add_clasa')">ADAUGA</button>
                            </div>           
                        </form>
                    </div>
                </div>
                <div class="w3-row w3-margin w3-animate-left" v-if="show == 'sterge_clasa'">
                    <div class="w3-col m12 l12 s12">
                        <table class="w3-table">
                            <tr style="background-color:#ebf2f7">
                                <th>Nume Clasa</th>
                                <th class="w3-right">Buton admin</th>
                            </tr>
                            <template v-for="(data,index) in tot_clase">
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>    
                                <tr style="border-left:5px solid green !important;">
                                    <td>{{data}}</td>
                                    <td class="w3-right">
                                        <div class="w3-button selected_class" @click="deleter(index,'clasa')">Sterge clasa</div>
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </div>
                </div>
                <div class="w3-row w3-margin w3-animate-left" v-if="show == 'lista_elevin'">
                    <form class="w3-col m12 l12 s12">
                        <div class="w3-row w3-margin-bottom">
                            <label for="lista_clasa">Adauga in clasa :</label>
                            <select onchange="app.remake()" name="lista_clasa" id="lista_clasa" style="width:100%;">
                                <option v-for="(data,index) in clase" v-bind:value="index">{{data.nume}}</option>
                            </select>
                        </div>
                        <table class="w3-table">
                            <tr style="background-color:#ebf2f7">
                                <th style="width:3%">Grad</th>
                                <th>Nume elev</th>
                                <th class="w3-right">Buton adaugare</th>
                            </tr>
                            <template v-for="(data,index) in elevi_not_inclasa">
                                <tr v-if="data.grad == 'elev' || data.grad == 'prof'">
                                    <td></td>
                                    <td></td>
                                </tr> 
                                <tr style="border-left:5px solid #F2CC8F !important;" v-if="data.grad == 'elev' || data.grad == 'prof'">
                                    <td v-if="data.grad == 'prof'"><div class="w3-tag w3-green">Profesor</div></td>
                                    <td v-if="data.grad == 'elev'"><div class="w3-tag w3-brown">Elev</div></td>
                                    <td>{{data.first_name}} {{data.last_name}}</td>
                                    <td class="w3-right">
                                        <div class="selected_class w3-button" v-bind:id="index" @click = "adder('add_in_clasa',index,$event);">Adauga in clasa</div>
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </form>
                </div>
                <div class="w3-row w3-margin w3-animate-left" v-if="show == 'lista_all_elevi'">
                    <div class="w3-col m12 l12 s12">
                        <table class="w3-table">
                            <tr style="background-color:#ebf2f7">
                                <th>Grad</th>
                                <th>Nume elev</th>
                                <th class="w3-right">Buton admin</th>
                            </tr>
                            <template v-for="(data,index) in elevi_all">
                                <tr v-if="data.grad == 'elev' || data.grad == 'prof'">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr style="border-left:5px solid #F2CC8F !important;" v-if="data.grad == 'elev' || data.grad == 'prof'">
                                    <td v-if="data.grad == 'prof'"><div class="w3-tag w3-green">Profesor</div></td>
                                    <td v-if="data.grad == 'elev'"><div class="w3-tag w3-brown">Elev</div></td>
                                    <td>{{data.first_name}} {{data.last_name}}</td>
                                    <td class="w3-right">
                                        <div class="w3-button selected_class" @click="deleter(index,'cont')">Sterge cont</div>
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </div>
                </div>
                <div class="w3-row w3-margin w3-animate-left" v-if="show == 'lista_loguri'">
                    <div class="w3-col m12 l12 s12">
                        <table class="w3-table">
                            <tr style="background-color:#ebf2f7">
                                <th>Actiune</th>
                                <th class="w3-right">Data</th>
                            </tr>
                            <template v-for="(data,index) in loguri">
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>    
                                <tr style="border-left:5px solid #F2CC8F !important;">
                                    <td>{{data.log_text}}</td>
                                    <td class="w3-right">{{data.data}}
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="crsf" name="crsf" value="<?php echo $_SESSION['crsf']; ?>">
    </div>
    <div class="w3-container animate__animated animate__swing" style="background-color: transparent;bottom:10px;position: fixed;width:100%">
        <center><a class="w3-button w3-round credits_class" style="font-weight: bold;background-color:#3d3321;color:white;">&copy Udrescu Alexandru Mihai</a></center>
    </div>

    <!-- VUE.js -->
    <script src="js/vue.min.js"></script>

    <!-- Toata partea importanta de JS a admin panel-ului , aici sunt ajax-urile complete si toata partea de VUE -->
    <script src="js/admin_l.js"></script>

    <!-- JS de la AOS(Animate on scroll) -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
