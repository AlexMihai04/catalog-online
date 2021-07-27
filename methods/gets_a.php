<?php
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

    if($_POST["action"] == "get_all_el"){
        $date_need = [];
        $date = get_all_elevi($conn);
        $i = 0;
        foreach($date as $elev){
            $data = DbReqUser_id($conn,$elev["user_id"]);
            $date_need[$i] = $data; 
            unset($date_need[$i]["parola"]);
            $i+=1;
        }
        echo json_encode($date_need);
    }elseif($_POST["action"] == "get_logs"){
        $logs = get_loguri($conn);
        echo json_encode($logs);
    }elseif($_POST["action"] == "get_all_classes"){
        $needed = array();
        $clase_totate = get_clase($conn);

        foreach($clase_totate as $back){
            $needed[$back["id_clasa"]] = $back["nume_clasa"];
        }

        echo json_encode($needed);
    }elseif($_POST["action"] == "get_not_in_class"){
        $date_need = [];
        $date = get_not_in_clasa($conn);
        $i = 0;
        foreach($date as $elev){
            $data = DbReqUser_id($conn,$elev["user_id"]);
            $date_need[$i] = $data; 
            unset($date_need[$i]["parola"]);
            $i+=1;
        }
        echo json_encode($date_need);
    }


    DbClose($conn);

?>