<!-- © 2021 Udrescu Alexandru All Rights Reserved -->

<?php
    //error_reporting(0);
    session_start();
    if(!$_SESSION["is_auth"]){
        header("location:login.php");
        die();
    }
    include 'utils/db_functions.php';
    $cfg = include('cfg/cfg.php');
    $conn = DbConn($cfg["servername"],$cfg["username"],$cfg["password"],$cfg["db_name"]);

    $date_user = DbReqUser_id($conn,$_SESSION["user_id"]);
    $_SESSION["user_id"] = $date_user["user_id"];
    $_SESSION["username"] = $date_user["username"];
    $_SESSION["last_name"] = $date_user["last_name"];
    $_SESSION["first_name"] = $date_user["first_name"];
    if($date_user["grad"] == "prof"){
        $_SESSION["group"] = "prof";
    }else{
        $_SESSION["group"] = $date_user["grad"];
    }
    if($_SESSION['group'] == "" or $_SESSION['group'] == ' ' or $_SESSION['group'] == '  '){
        header("location:logout.php");
    }
    $conn = DbClose($conn);
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

    <!-- O librarie pentru tooltips -->
    <link href="css/tooltips.min.css" rel="stylesheet">

    <!-- Libraria iziToast (notificari) -->
    <script src="js/iziToast.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/iziToast.min.css">
    
    <!-- Modul pentru iziToast , am folosit doar 2 notificari si nu avea sens sa stau sa apelez functiile de acolo mereu -->
    <script src="js/notifs.js" type="text/javascript"></script>

    <!-- Librarie pentru Animate On Scroll -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Librarie pentru diferite animatii -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body onload="resize_page()" onresize="resize_page()">

    <div id="app">
        <div class="w3-sidebar w3-animate-left hide-scroll" id="side_bar" style="min-width:230px;background-image:url(poze/bg_list.png)">
            <div class="w3-bar" style="border-bottom:5px solid #aeb4b8;background:rgb(255,255,255) !important">
                <button onclick="close_nav()" class="w3-bar-item w3-left w3-margin buton_custom animate__animated animate__bounceInLeft" style="border-radius : 10px;" title="close Sidebar"><i class="far fa-times-circle"></i></button>
                <span class="w3-bar-item w3-margin animate__animated animate__bounceInDown"><center>Clasele tale</center></span>
            </div>
            <div style="width:100%;min-height:100%;border-right:5px solid #ebf2f7">
            <br>
                <template v-if="exista_clasa('CP')">
                    <div class="w3-bar-block">
                        <a class="w3-bar-item animate__animated animate__bounceInRight" style="border-bottom:5px solid #ebf2f7;"><center><i class="fas fa-school"></i>&nbsp Clasele CP</center></a>
                    </div>
                    <div class="w3-bar-block w3-margin" v-for="(data,index) in clase">
                        <template v-if="data.nume.substr(0, 2) == 'CP'">
                            <a class="w3-bar-item buton_custom animate__animated animate__bounceInLeft clasa_selectata" v-if="selected == index" @click="selected = 0;deselect_cls()"><i class="fas fa-users w3-hide-small"></i>&nbsp{{ data.nume }} <div class="w3-tag w3-round w3-right" style="background-color:#b9e2fe">{{data.numar_elevi}}</div></a>
                            <a class="w3-bar-item buton_custom animate__animated animate__bounceInLeft unselected_class" @click="select_clasa(index)" v-else><i class="fas fa-users w3-hide-small"></i>&nbsp{{ data.nume }} <div class="w3-tag w3-round w3-right" style="background-color:#b9e2fe">{{data.numar_elevi}}</div></a>
                        </template>
                    </div>
                </template>
                <template v-for="n in 12" v-if="exista_clasa(n)">
                    <div class="w3-bar-block">
                        <a class="w3-bar-item animate__animated animate__bounceInRight" style="border-bottom:5px solid #ebf2f7;"><center><i class="fas fa-school"></i>&nbsp Clasele {{n}}</center></a>
                    </div>
                    <div class="w3-bar-block w3-margin" v-for="(data,index) in clase">
                        <template v-if="data.nume.substr(0, 2) == (n+' ') || data.nume.substr(0, 2) == n">
                            <a class="w3-bar-item buton_custom animate__animated animate__bounceInLeft clasa_selectata" v-if="selected == index" @click="selected = 0;deselect_cls()"><i class="fas fa-users w3-hide-small"></i>&nbsp{{ data.nume }} <div class="w3-tag w3-round w3-right" style="background-color:#b9e2fe">{{data.numar_elevi}}</div></a>
                            <a class="w3-bar-item buton_custom animate__animated animate__bounceInLeft unselected_class" @click="select_clasa(index)" v-else><i class="fas fa-users w3-hide-small"></i>&nbsp{{ data.nume }} <div class="w3-tag w3-round w3-right" style="background-color:#b9e2fe">{{data.numar_elevi}}</div></a>
                        </template>
                    </div>
                </template>
            </div>
        </div>
        <div id="main" style="margin-left: 230px;">
            <div class="w3-bar nav_bar" style="border-bottom:5px solid #aeb4b8;">
                    <span class="w3-bar-item w3-margin buton_custom" id="openNav" style="border-radius : 10px;" onclick="open_nav()"><i class="fas fa-brain w3-large"></i></span>
                    <a href="logout.php" class="w3-bar-item w3-button selected_class w3-margin w3-right animate__animated animate__bounceInDown"><i class="fas fa-sign-out-alt"></i> Delogare</a>
                <?php 
                    if($_SESSION["group"] == "admin"){
                ?>
                    <a href="admin_panel.php" class="w3-bar-item w3-button selected_class w3-margin-bottom w3-margin-top w3-right animate__animated animate__bounceInDown"><i class="fas fa-users-cog"></i></a>
                <?php
                    }
                ?>
            </div>
            <div class="w3-container w3-container-display" v-bind:class="{ 'w3-hide-small': opened_meniu_clase }" v-if="show_loader == true">
                <div class="w3-display-middle">
                    <div id="preload">
                        <div class="sk-folding-cube">
                            <div class="sk-cube1 sk-cube"></div>
                            <div class="sk-cube2 sk-cube"></div>
                            <div class="sk-cube4 sk-cube"></div>
                            <div class="sk-cube3 sk-cube"></div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="w3-container w3-container-display" v-bind:class="{ 'w3-hide-small': opened_meniu_clase }" v-if="selected > 0">
                <div v-if="punere_nota == false && show_loader == false">
                    <br>
                    <div class="w3-row w3-margin w3-animate-left" v-if="elev == -1">
                        <div class="w3-col m12 l12 s12 w3-animate-left">
                            <table class="w3-table">
                                <tr style="background-color:#ebf2f7">
                                    <th>Elevi</th>
                                    <th v-bind:class="{'w3-hide-small' : (getWidth() == 380)}">Medie</th>
                                    <th class="w3-right">Actiuni</th>
                                </tr>
                                <?php
                                    if($_SESSION['group'] != "admin"){
                                ?>
                                <template v-for="(data,index) in elevi" v-if="data.grad == 'elev'">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="animate__animated animate__bounceInRight" v-bind:class="{ 'bord-left-ver': (data.user_id == <?php echo $_SESSION['user_id'] ?>),'bord-left-gal' : (data.user_id != <?php echo $_SESSION['user_id'] ?>) }">
                                        <td class="w3-cell w3-cell-middle animate__animated animate__bounceInLeft"><p>{{data.first_name}} {{data.last_name}}</p></td>
                                        <td class="w3-cell w3-cell-middle animate__animated animate__bounceInLeft" v-bind:class="{'w3-hide-small' : (getWidth() == 380)}" v-if="'<?php echo $_SESSION['group']; ?>' =='admin' || '<?php echo $_SESSION['group']; ?>' == 'prof' || '<?php echo $_SESSION['group']; ?>' == 'secretariat' || <?php echo $_SESSION['user_id']; ?> == data.user_id"><p>{{data.medie_elev}}</p></td>
                                        <td class="w3-cell w3-cell-middle animate__animated animate__bounceInLeft" v-else><p>Indisponibil</p></td>
                                        <td class="w3-right">
                                            <p>
                                                <div class="w3-button selected_class w3-hide-small animate__animated animate__bounceInLeft" v-bind:class="{ 'w3-block': (getWidth() < 600),'w3-margin-top':(getWidth() < 600) }" v-if="data.user_id == <?php echo $_SESSION['user_id']; ?> || '<?php echo $_SESSION['group']; ?>' =='admin' || '<?php echo $_SESSION['group']; ?>' == 'prof' || '<?php echo $_SESSION['group']; ?>' == 'secretariat'" @click = "select_elev(index)">Vezi note</div>
                                                <div class="w3-button selected_class w3-hide-small animate__animated animate__bounceInLeft" v-bind:class="{ 'w3-block': (getWidth() < 600),'w3-margin-top': (getWidth() < 600) }" v-if="data.user_id == <?php echo $_SESSION['user_id']; ?> || '<?php echo $_SESSION['group']; ?>' =='admin' || '<?php echo $_SESSION['group']; ?>' == 'prof' || '<?php echo $_SESSION['group']; ?>' == 'secretariat'" @click = "select_elev(index);vezi_absente = true;">Vezi absente</div>
                                            
                                                <?php 
                                                    if($_SESSION["group"] == "prof" or $_SESSION["group"] == "admin"){
                                                ?>
                                                    <div class="w3-button sel_verde animate__animated animate__bounceInLeft" v-bind:class="{ 'w3-block': (getWidth() < 600),'w3-margin-top':(getWidth() < 600) }" @click="punere_nota = true;select_pune_nota(index)">Adauga nota</div>
                                                    <div class="w3-button sel_verde animate__animated animate__bounceInLeft"  v-bind:class="{ 'w3-block': (getWidth() < 600),'w3-margin-top':(getWidth() < 600) }" @click="punere_nota = true;select_pune_nota(index)">Adauga absenta</div>
                                                <?php
                                                    }
                                                ?>
                                            </p>
                                        </td>
                                    </tr>
                                </template>
                                <?php
                                }else{
                                ?>  
                                    <template v-for="(data,index) in elevi">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="animate__animated animate__bounceInRight" style="border-left:5px solid #F2CC8F !important;">
                                            <td class="w3-cell w3-cell-middle"><p><i class="fas fa-cogs" v-if="data.grad == 'prof'"></i><p>{{data.first_name}} {{data.last_name}}</p></p></td>
                                            <td class="w3-cell w3-cell-middle animate__animated animate__bounceInLeft" v-bind:class="{'w3-hide-small' : (getWidth() == 380)}" v-if="'<?php echo $_SESSION['group']; ?>' =='admin' || '<?php echo $_SESSION['group']; ?>' == 'prof' || '<?php echo $_SESSION['group']; ?>' == 'secretariat'"><p>{{data.medie_elev}}</p></td>
                                            <td class="w3-right">
                                                <div class="w3-button selected_class animate__animated animate__bounceInLeft" v-bind:class="{ 'w3-block': (getWidth() < 600) }" v-if="(data.user_id == <?php echo $_SESSION['user_id']; ?> || '<?php echo $_SESSION['group']; ?>' =='admin' || '<?php echo $_SESSION['group']; ?>' == 'prof') && data.grad != 'prof'" @click = "select_elev(index)">Vezi note</div>
                                                <div class="w3-button selected_class animate__animated animate__bounceInLeft" v-bind:class="{ 'w3-block': (getWidth() < 600),'w3-margin-top':(getWidth() < 600) }" v-if="(data.user_id == <?php echo $_SESSION['user_id']; ?> || '<?php echo $_SESSION['group']; ?>' =='admin' || '<?php echo $_SESSION['group']; ?>' == 'prof') && data.grad != 'prof'" @click = "select_elev(index);vezi_absente = true;">Vezi absente</div>
                                                <?php 
                                                    if($_SESSION["group"] == "prof" or $_SESSION["group"] == "admin"){
                                                ?>
                                                    <div class="w3-button sel_verde animate__animated animate__bounceInLeft w3-hide-small" v-if="data.grad != 'prof'" @click="punere_nota = true;select_pune_nota(index)">Adauga nota</div>
                                                    <div class="w3-button sel_verde animate__animated animate__bounceInLeft w3-hide-small" v-if="data.grad != 'prof'" @click="punere_nota = true;select_pune_nota(index)">Adauga absenta</div>
                                                <?php
                                                    }
                                                ?>
                                                <?php 
                                                    if($_SESSION["group"] == "admin"){
                                                ?>
                                                    <div class="w3-button w3-red w3-hide-small animate__animated animate__bounceInLeft" @click="deleter('scoate_din_cls',index)">X</div>
                                                <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </template>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="w3-row w3-margin w3-animate-left" v-if="elev > -1">
                        <div class="w3-col l2 m12 s12">
                            <div class="w3-button selected_class w3-block" @click="elev = -1;vezi_absente = false;" >Inapoi Elevi</div>
                        </div>
                    </div>
                    <div class="w3-row w3-animate-left  w3-margin-left w3-margin-right" v-if="elev > -1 && vezi_absente == false">
                        <div class="w3-col l2 m12 s12" v-if="are_note == false">
                            <div class="w3-panel sel_rosu w3-block">
                                <p>Notele acestui elev nu au fost inca incarcate</p>
                            </div>
                        </div>
                        <div class="w3-col l2 m12 s12" v-else>
                            <div class="w3-panel sel_rosu">
                                <p>Tine cursor-ul peste nota pentru a vedea data</p>
                            </div>
                        </div>
                    </div>
                    <div class="w3-row w3-animate-left w3-margin-left w3-margin-right" v-if="elev > -1 && vezi_absente == false">
                        <table class="w3-table w3-margin-top" style="width:auto;" v-for="(data,index) in materii" v-if="nabs_avut('note',data.id_materie)">
                            <tr>
                                <th class="selected_class" style="text-transform: uppercase;background-color:#aeb4b8 !important;">{{data.nume_materie}}&nbsp</th>
                                <th class="brd_clasa" style="text-transform: uppercase;">Medie : {{get_medie_materie(data.id_materie)}}</th>
                                <th class="tts:right" style="font-weight:normal;background-color:#f5f9fc" aria-label="Note elev">Note</th>
                            </tr>
                            <tr>
                                <th class="tts:right" v-bind:aria-label="date.data" style="font-weight:normal;background-color:#f5f9fc;" v-for="(date,i2) in note_elev" v-if="data.id_materie == date.materie">{{date.nota}}</th>
                            </tr>
                            <tr></tr>
                        </table>
                    </div>
                    <div class="w3-row w3-animate-left" v-if="elev > -1 && vezi_absente == true" style="display:inline-block;">
                        <div class="w3-panel sel_rosu w3-margin" v-if="are_absente == false">
                            <p>Acest elev nu are nici o absenta</p>
                        </div>
                        <div class="w3-panel sel_rosu w3-margin" v-else>
                            <p>Tine cursor-ul peste absenta pentru a vedea data</p>
                        </div>
                        <table class="w3-table w3-margin" style="width:auto;" v-for="(data,index) in materii" v-if="nabs_avut('abs',data.id_materie)">
                            <tr>
                                <th class="selected_class" style="text-transform: uppercase;">{{data.nume_materie}}&nbsp</th>
                                <th class="tts:right" v-bind:aria-label="date.data" style="font-weight:normal;background-color:#f5f9fc" v-for="(date,i2) in absente_elev" v-if="data.id_materie == date.materie">|</th>
                            </tr>
                            <tr>
                            </tr>
                        </table>
                    </div>
                </div>
                <div v-if="punere_nota == true && show_loader == false">
                    <br>
                    <div class="w3-container w3-display-container">
                        <div class="w3-row w3-margin w3-animate-left">
                            <div class="w3-button selected_class w3-animate-left w3-margin-left" @click="elev = -1;punere_nota = false;" >Renunta</div>
                        </div>   
                        <div class="w3-row w3-margin w3-animate-left">
                            <div class="w3-col s12 m6 l6">
                                <div class="w3-card-4 selected_class w3-round w3-margin">
                                    <div class="w3-container">
                                        <center><h2>Nota</h2></center>
                                    </div>
                                    <hr style="width:100%;background-color:black;"></hr>
                                    <form class="w3-container" id="form_pune_nota" name="form_pune_nota">
                                        <div class="w3-row w3-padding">
                                            <input class="w3-input w3-border w3-white" name="nota_pusa" id="nota_pusa" type="text" placeholder="Nota" required>
                                        </div>
                                        <div class="w3-row w3-padding">
                                            <label for="materie">Materie:</label>
                                            <select name="materie" id="materie" style="width:100%;">
                                                <option v-for="(data,index) in materii" v-bind:value="data.id_materie">{{data.nume_materie}}</option>
                                            </select>
                                        </div>
                                        <div class="w3-row w3-padding">
                                            <label for="data_primita">Data:</label>
                                            <input class="w3-input w3-border w3-white" name="data_primita" id="data_primita" type="date" placeholder="Data" required>
                                        </div>
                                        <div class="w3-row w3-padding">
                                            <button type="button" class="w3-button w3-block w3-round" style="background-color:#aeb4b8;color:black;" @click="adder('nota')">ADAUGA</button>
                                        </div>           
                                    </form>
                                </div>
                            </div>
                            <div class="w3-col s12 m6 l6">
                                <div class="w3-card-4 selected_class w3-round w3-margin">
                                    <div class="w3-container">
                                        <center><h2>Absenta</h2></center>
                                    </div>
                                    <hr style="width:100%;background-color:black;"></hr>
                                    <form class="w3-container" id="form_pune_absenta" name="form_pune_absenta">
                                        <div class="w3-row w3-padding">
                                            <label for="materie">Materie:</label>
                                            <select name="materie" id="materie" style="width:100%;">
                                                <option v-for="(data,index) in materii" v-bind:value="data.id_materie">{{data.nume_materie}}</option>
                                            </select>
                                        </div>
                                        <div class="w3-row w3-padding">
                                            <label for="data_primita">Data:</label>
                                            <input class="w3-input w3-border w3-white" name="data_primita" id="data_primita" type="date" placeholder="Data" required>
                                        </div>
                                        <div class="w3-row w3-padding">
                                            <button type="button" class="w3-button w3-block w3-round" style="background-color:#aeb4b8;color:black;" @click="adder('absenta')">ADAUGA</button>
                                        </div>           
                                    </form>
                                </div>
                            </div>
                        </div>                     
                    </div>
                </div>
            </div>
            <div class="w3-container w3-container-display" v-bind:class="{ 'w3-hide-small': opened_meniu_clase }" v-if="selected <= 0 && show_loader == false">
                <br>
                <div class="w3-row main_container w3-margin-bottom w3-margin-top" data-aos="fade-up">
                    <div class="w3-col s12 l12 m12" style="width:100%">
                        <div style="border-bottom: 5px solid #ebf2f7;"class="w3-container w3-animate-right" data-aos="fade-left">
                            <h3 class="w3-round"><i class="fas fa-graduation-cap"></i> Tutorial Elevi/Parinti | Cum se utilizeaza platforma</h3>
                        </div>
                        <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                            <p class="w3-tag  w3-round tutorial_title" style="background-color:#062e4a">Cum se pot vedea notele : </p><p>Pentru a-ti vedea notele , trebuie sa selectezi clasa ta din stanga si apoi pe numele tau , selectezi <a class="w3-tag w3-round" style="background-color:#b9e2fe">vezi note</a>.</p><p>Pentru a vedea data la care aceasta nota a fost inserata , tine cursorul deasupra acesteia.</p>
                        </div>
                        <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                            <p class="w3-tag  w3-round tutorial_title" style="background-color:#062e4a">Cum se pot vedea absentele : </p><p>Pentru a-ti vedea absentele , trebuie sa selectezi clasa ta din stanga si apoi pe numele tau , selectezi <a class="w3-tag w3-round" style="background-color:#b9e2fe">vezi absente</a>.</p><p>Pentru a vedea data la care aceasta absenta a fost inserata , tine cursorul deasupra acesteia.</p>
                        </div>
                        <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                            <p class="w3-tag w3-round tutorial_title" style="background-color:#062e4a"> Notele nu apar : </p><p>Daca iti apare mesajul : "Notele acestui elev nu au fost inca inserate" inseamna ca notele tale nu au fost trecute in catalog de catre profesori.</p>
                        </div>
                        <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                            <p class="w3-tag  w3-round tutorial_title" style="background-color:#062e4a"> Absentele nu apar : </p><p>Daca iti apare mesajul : "Acest elev nu are absente" inseamna ca tu nu ai absente sau , nu au fost incarcate pe platforma.</p>
                        </div>
                        <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                            <p class="w3-tag  w3-round tutorial_title" style="background-color:red"> Nu iti apar clasele ? : </p><p>Contacteaza administratorul <a href="https://www.instagram.com/_alexmihai_/" class="w3-tag w3-round" style="background-color:#b9e2fe">aici</a></p>
                        </div>
                        <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                            <p class="w3-tag  w3-round tutorial_title" style="background-color:red">Coleg fara cont ? : </p><p>Contacteaza administratorul <a href="https://www.instagram.com/_alexmihai_/" class="w3-tag w3-round" style="background-color:#b9e2fe">aici</a></p>
                        </div>
                        <?php
                        if($_SESSION["group"] == "prof" or $_SESSION["group"] == "admin"){
                        ?>
                            <div style="border-bottom: 5px solid #ebf2f7;border-top: 5px solid #ebf2f7;"class="w3-container" data-aos="fade-up">
                                <h3 class="w3-round"><i class="fas fa-graduation-cap"></i> Tutorial profesori</h3>
                            </div>
                            <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                                <p class="w3-tag w3-round tutorial_title" style="background-color:#062e4a"> Cum adaug o nota unui elev : </p><p>Pentru a insera o nota unui elev trebuie sa selectati din stanga clasa in care acesta este , il cautati in lista , si apasati pe : <a class="w3-tag w3-round" style="background-color:#b9e2fe">Adauga nota</a></p>
                            </div>
                            <div class="w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                                <p class="w3-tag  w3-round tutorial_title" style="background-color:#062e4a"> Cum adaug o absenta unui elev : </p><p>Pentru a insera o absenta unui elev trebuie sa selectati din stanga clasa in care acesta este , il cautati in lista , si apasati pe : <a class="w3-tag w3-round" style="background-color:#b9e2fe">Adauga absenta</a></p>
                            </div>
                            <div style="background-color:rgba(244, 67, 54,0.1)!important" class="w3-round w3-container w3-margin-left w3-margin-right" data-aos="fade-left">
                                <p class="w3-tag w3-red w3-round tutorial_title">ATENTIE !!! </p><p>Nota unui elev din moment ce a fost adaugata nu poate sa fie anulata decat de administrator sau director !</p>
                            </div>
                            <?php
                        }
                            ?>  
                            <br>
                    </div>
                    <br>
                    <br>
                </div>             
            </div>
        </div>
        <input type="hidden" id="crsf" name="crsf" value="<?php echo $_SESSION['crsf']; ?>">
    </div>
    <div class="w3-container animate__animated animate__swing" style="background-color: transparent;bottom:10px;position: fixed;width:100%">
        <center><a class="w3-button w3-round credits_class" style="font-weight: bold;background-color:#3d3321;color:white;">&copy Udrescu Alexandru Mihai</a></center>
    </div>
    <!-- Libraria VUE.JS -->
    <script src="js/vue.min.js"></script>

    <!-- Toata partea importanta de JS a index-ului , aici sunt ajax-urile complete si toata partea de VUE -->
    <script src="js/listener.js"></script>

    <!-- JS-ul de la AOS ( Animate on scroll ) -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
