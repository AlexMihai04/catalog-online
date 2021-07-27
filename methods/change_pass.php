

<?php
    // © 2020 Udrescu Alexandru All Rights Reserved
    error_reporting(0);
    session_start();
    if($_SESSION["group"] != "admin"){
        header("location:../index.php");
        die();
    }
    include "../utils/db_functions.php";
    
    
    $conn = DbConn();
    
    if(isset($_POST["id_elev"]) and isset($_POST["parola_noua"]) and preg_match("^[0-9a-zA-Z]{4,16}$^",$_POST["parola_noua"]) and preg_match("^[0-9]{1,5}$^",$_POST["id_elev"])){
        $stmt = $conn->prepare('UPDATE lista_conturi SET parola = :parola WHERE user_id = :user_id');
        $stmt->execute(['parola' => $_POST["parola_noua"],'user_id' => $_POST["id_elev"]]);
    }

    DbClose($conn);
?>