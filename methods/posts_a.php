<?php
    // TOATE POST-URILE DIN AJAX SUNT PUSE AICI
    // © 2021 Udrescu Alexandru All Rights Reserved
    error_reporting(0);
    include "../utils/db_functions.php";
    $cfg = include('../cfg/cfg.php');
    session_start();

    if($_SESSION["group"] != "admin" || !$_SESSION["is_auth"]){
        header("location:../index.php");
        die();
    }
    if($_POST["crsf"] != $_SESSION["crsf"]) header("location:../logout.php");

    $conn = DbConn($cfg["servername"],$cfg["username"],$cfg["password"],$cfg["db_name"]);
    //adm_req
    if($_POST["action"] == "add_in_class"){
        $resp = array('response' => 0,'mesaj' => 'A aparut o eroare');
        if(isset($_POST["id_elev"]) and isset($_POST["id_clasa"]) and preg_match("^[0-9]{1,5}$^",$_POST["id_elev"]) and preg_match("^[0-9]{1,5}$^",$_POST["id_clasa"])){
            add_log($conn,$_SESSION["user_id"]." a adaugat pe : ".$_POST["id_elev"]." in clasa : ".$_POST["id_clasa"],date("d-m-Y"));
            insert_elev_in_clasa($conn,$_POST["id_clasa"],$_POST["id_elev"]);
            $resp["mesaj"] = "Elevul a fost adaugat in clasa cu succes !";
            $resp["response"] = 1;
        }else{
            $resp["mesaj"] = "Ceva nu a functionat bine !";
        }
        echo json_encode($resp);
    }elseif($_POST["action"] == "add_class"){
        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');
        $c_in = $_POST["nume_clasa"];
        if(isset($_POST["nume_clasa"]) and preg_match("^[a-zA-Z0-9]{1,16}$^",$_POST["nume_clasa"])){
            add_log($conn,$_SESSION["user_id"]." a creat clasa cu numele : ".$c_in,date("d-m-Y"));
            create_class($conn,$c_in);
            $response["response"] = 1;
            $response["mesaj"] = "Clasa a fost creata cu succes !";
        }else{
            $response["mesaj"] = 'A aparut o eroare. Trebuie sa completezi toate campurile. Cod : #2'; 
        }
        echo json_encode($response);
    }elseif($_POST["action"] == "create_acc"){
        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');
        $g_in = $_POST["grup"];
        $f_in = $_POST["first_name"];
        $l_in = $_POST["last_name"];
        $m_in = $_POST["email"];
        if(filter_var($m_in, FILTER_VALIDATE_EMAIL) and isset($_POST["grup"]) and isset($_POST["first_name"]) and isset($_POST["last_name"]) and preg_match("^[a-zA-Z0-9]{1,40}$^",$_POST["first_name"]) and preg_match("^[a-zA-Z0-9]{1,40}$^",$_POST["last_name"]) and preg_match("^[a-zA-Z]{1,10}$^",$_POST["grup"])){
            $u_in = preg_replace('/\s+/', '_', $_POST["first_name"]." ".$_POST["last_name"]);
            $test_ex = DbReqUser($conn,$u_in);
            if($test_ex){
                $u_in = $u_in.rand(1000,9999);
            }
            $p_in = strtolower(preg_replace('/\s+/', '_', $_POST["first_name"]." ".$_POST["last_name"]))."_".rand(1000,9999);
            add_log($conn,$_SESSION["user_id"]." a creat contul cu numele : ".$u_in,date("d-m-Y"));
            create_cont($conn,$u_in,$p_in,$g_in,$f_in,$l_in,$m_in);
            $response["response"] = 1;
            $response["mesaj"] = "Contul a fost creat cu succes !";
        }else{
            $response["mesaj"] = 'A aparut o eroare. Trebuie sa completezi toate campurile. Cod : #2'; 
        }
        echo json_encode($response);
    }elseif($_POST["action"] == "del_class"){
        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');
        if(isset($_POST["id_clasa"]) and preg_match("^[0-9]{1,5}$^",$_POST["id_clasa"])){
            add_log($conn,$_SESSION["user_id"]." a sters clasa cu id-ul : ".$_POST["id_clasa"]." din baza de date",date("d-m-Y"));
            delete_cls($conn,$_POST["id_clasa"]);
            $response["mesaj"] = "Clasa a fost stearsa cu succes !";
            $response["response"] = 1;
        }
        echo json_encode($response);
    }elseif($_POST["action"] == "del_acc"){
        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');
        if(isset($_POST["id_elev"]) and preg_match("^[0-9]{1,5}$^",$_POST["id_elev"])){
            add_log($conn,$_SESSION["user_id"]." a sters contul cu id-ul : ".$_POST["id_elev"]." din baza de date",date("d-m-Y"));
            delete_cont($conn,$_POST["id_elev"]);
            $response["mesaj"] = "Contul a fost sters cu succes !";
            $response["response"] = 1;
        }
        echo json_encode($response);
    }elseif($_POST["action"] == "sterge_din_clasa"){
        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');

        if(isset($_POST["id_user"]) and isset($_POST["id_clasa"]) and preg_match("^[0-9]{1,5}$^",$_POST["id_clasa"]) and preg_match("^[0-9]{1,5}$^",$_POST["id_user"])){
            add_log($conn,$_SESSION["user_id"]." a sters clasa cu id-ul : ".$_POST["id_clasa"],date("d-m-Y"));
            delete_om_clasa($conn,$_POST["id_clasa"],$_POST["id_user"]);
            $response["mesaj"] = "Ai scos elevul cu success din clasa !";
            $response["response"] = 1;
        }else{
            $response["mesaj"] = "A aparut o eroare. Cod #4";
        }
        echo json_encode($response);
    }
    
    DbClose($conn);


?>
