

<?php
    // © 2020 Udrescu Alexandru All Rights Reserved
    include "../utils/db_functions.php";
    $cfg = include('../cfg/cfg.php');

    $conn = DbConn($cfg["server_name"],$cfg["username"],$cfg["password"],$cfg["db_name"]);
    // error_reporting(0);
    function split_the_text($str){
        $to_return = array();
        if($str[0] == "/"){
            $inserter = 0;
            $i = 0;
            for($i = 1;$i<strlen($str);$i++){
                if($str[$i] != "/" and $str[i] != "" and $str[i] != " "){
                    $to_return[$inserter].=$str[$i];
                }else{
                    $inserter++;
                }
            }
            return $to_return;
        }
    }

    $stmt_unload = $conn->prepare('SELECT * FROM table_name');
    $stmt_unload->execute();
    $date_unload = $stmt_unload->fetchAll();
    foreach($date_unload as $elev)
    {
        $username = preg_replace('/\s+/', '_', $elev["Nume"]." ".$elev["Prenuma"]);
        $parola = strtolower(preg_replace('/\s+/', '_', $elev["Nume"]." ".$elev["Prenuma"]))."_".rand(1000,9999);
        $email = $elev["email"];

        $date = split_the_text($elev["grup"]);
        if($date[0] != " " or $date[0] != "" or $date[0]){
            if($date[0] == "elevi"){
                $date[0] = "elev";
            }
            if($date[0] == "profesori"){
                $date[0] = "prof";
            }
        }else{
            $date[0] = "elev";
        }

        $stmt_exista_user = $conn->prepare('SELECT * FROM lista_conturi WHERE username = :username');
        $stmt_exista_user->execute(['username' => $username]);
        $ex_user = $stmt_exista_user->fetch(PDO::FETCH_ASSOC);
        if($ex_user){
            $username = $username."_".rand(1,100);
        }
        echo strtoupper($username)." ".$parola." ".$date[0]." ".$elev["Nume"]." ".$elev["Prenuma"]." ".$elev["email"]."<br>";
        create_cont($conn,strtoupper($username),$parola,$date[0],$elev["Nume"],$elev["Prenuma"],$elev["email"]);
        $nume_cls = "";
        if($date[0] != "prof" and $date[1] and $date[1] != " " and $date[1] != ""){
            $nume_cls = $date[1]." ".$date[2];
            $stmt_exista_cls = $conn->prepare('SELECT * FROM lista_clase WHERE nume_clasa = :nume_clasa');
            $stmt_exista_cls->execute(['nume_clasa' => $nume_cls]);
            $ex_cls = $stmt_exista_cls->fetch(PDO::FETCH_ASSOC);
            if(!$ex_cls){
                create_class($conn,$nume_cls);
            }
            $stmt_exista_cls->execute(['nume_clasa' => $nume_cls]);
            $ex_cls = $stmt_exista_cls->fetch(PDO::FETCH_ASSOC);
            $user_creeat = DbReqUser($conn,$username);
            insert_elev_in_clasa($conn,$ex_cls["id_clasa"],$user_creeat["user_id"]);
        }
        $stmt_rem_from = $conn->prepare('DELETE FROM table_name WHERE Prenuma = :nume_elev AND email = :email_elev');
        $stmt_rem_from->execute(['email_elev' => $elev["email"],'nume_elev' => $elev["Prenuma"]]);
    }
    
    DbClose($conn);
?>