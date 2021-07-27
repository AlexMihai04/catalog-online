<?php
    error_reporting(0);
    include "../utils/db_functions.php";
    $cfg = include('../cfg/cfg.php');
    session_start();

    if(!$_SESSION["is_auth"] && $_POST["action"] != "login"){
        header("location:../index.php");
        die();
    }
    if($_POST["crsf"] != $_SESSION["crsf"]) header("location:../logout.php");

    $conn = DbConn($cfg["servername"],$cfg["username"],$cfg["password"],$cfg["db_name"]);

    if($_POST["action"] == "get_classes"){
        $needed = array();

        $clase_totate = get_clase($conn);
        $clase_user = get_clase_usr($conn,$_SESSION["user_id"],$_SESSION["group"]);
        if($_SESSION["group"] != "admin" and $_SESSION["group"] != 'prof' and $_SESSION["group"] != 'secretariat'){
            foreach($clase_user as $clasa){

                foreach($clase_totate as $back){
                    if($back["id_clasa"] == $clasa["clasa"]){
                        $needed[$back["id_clasa"]] = ['nume' => $back["nume_clasa"],'numar_elevi' => get_nr_elevi($conn,$back["id_clasa"])];
                    }
                }
            }
        }else{
            foreach($clase_totate as $back){
                $needed[$back["id_clasa"]] = ['nume' => $back["nume_clasa"],'numar_elevi' => get_nr_elevi($conn,$back["id_clasa"])];
            }
        }

        echo json_encode($needed);
        $date_user = DbReqUser_id($conn,$_SESSION["user_id"]);
        $_SESSION["username"] = $date_user["username"];
        $_SESSION["last_name"] = $date_user["last_name"];
        $_SESSION["first_name"] = $date_user["first_name"];
        if($date_user["grad"] == "prof"){
            $_SESSION["group"] = "prof";
        }else{
            $_SESSION["group"] = $date_user["grad"];
        }
        $_SESSION["is_auth"] = true;
    }elseif($_POST["action"] == "get_materii"){
        $materii = get_materii($conn);
        echo json_encode($materii);
    }elseif($_POST["action"] == "select_elev"){
        if(preg_match("^[0-9]{1,5}$^",$_POST["id_elev"])){
            echo json_encode(get_note($conn,$_POST["id_elev"]));
        }else{
            echo "";
        }
    }elseif($_POST["action"] == "get_absente"){
        if(preg_match("^[0-9]{1,5}$^",$_POST["id_elev"])){
            echo json_encode(get_absente($conn,$_POST["id_elev"]));   
        }else{
            echo "";
        }
    }elseif($_POST["action"] == "get_elevi"){
        $date_need = [];
        if(preg_match("^[0-9]{1,5}$^",$_POST["id_clasa"])){
            $clase = get_elevi($conn,$_POST["id_clasa"]);
            $i = 0;
            foreach($clase as $elev){
                $date = DbReqUser_id($conn,$elev["user_id"]);
                if($date){
                    $date_need[$i] = $date;
                    unset($date_need[$i]["parola"]);
                    $i+=1;
                }
            }
        }

        echo json_encode($date_need);
    }elseif($_POST["action"] == "login"){
        $timeout_time = 60; //SECUNDE



        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');
        if(!$_SESSION["tries"]) $_SESSION["tries"] = 0;
        if(!$_SESSION["timeout"]) $_SESSION["timeout"] = false;
        if($_SESSION["timeout"]){
            if(time() - $_SESSION["timeout"] > $timeout_time){
                $_SESSION["timeout"] = false;
                $_SESSION["tries"] = 0;
            }else{
                $response["mesaj"] = "Trebuie sa mai astepti : ".($timeout_time-(time()-$_SESSION["timeout"]))." secunde pentru a te putea loga !";
            }
        }
        if(!$_SESSION["timeout"]){
            if(isset($_POST["login_username"]) and isset($_POST["login_password"]) and preg_match("^[a-zA-Z0-9]{4,16}$^",$_POST['login_username'])){

                //VARIABILE PENTRU USERNAME SI PAROLA
                $l_user = $_POST["login_username"];
                $l_password = $_POST["login_password"];
        
                $date_user = DbReqUser($conn,$l_user);
        
                if(password_verify($l_password,$date_user["parola"])){
                    $_SESSION["user_id"] = $date_user["user_id"];
                    $_SESSION["username"] = $date_user["username"];
                    $_SESSION["last_name"] = $date_user["last_name"];
                    $_SESSION["first_name"] = $date_user["first_name"];
                    if($date_user["grad"] == "prof"){
                        $_SESSION["group"] = "prof";
                    }else{
                        $_SESSION["group"] = $date_user["grad"];
                    }
                    $_SESSION["is_auth"] = true;
                    $_SESSION["clase"] = $date_user["clase"];
                    if(!$_SESSION["crsf"]) $_SESSION["crsf"] = get_token();
                    $response["response"] = 1;
                }else{
                    $_SESSION["tries"] = $_SESSION["tries"] + 1;
                    $response["mesaj"] = "Username sau parola gresite ! ".(5-$_SESSION["tries"])."/5 incercari ramase !" ;
                }
            }else{
                $_SESSION["tries"] = $_SESSION["tries"] + 1;
                $response["mesaj"] = "Caractere invalide ! ".(5-$_SESSION["tries"])."/5 incercari ramase !";
            }
        }
        if($_SESSION["tries"] == 5) $_SESSION["timeout"] = time();
        echo json_encode($response);
    }
    DbClose($conn);
?>