<?php
    // © 2021 Udrescu Alexandru All Rights Reserved
    error_reporting(0);
    include "../utils/db_functions.php";
    $cfg = include('../cfg/cfg.php');
    session_start();

    if(!$_SESSION["is_auth"]){
        header("location:../index.php");
        die();
    }
    if($_POST["crsf"] != $_SESSION["crsf"]) header("location:../logout.php");

    $conn = DbConn($cfg["servername"],$cfg["username"],$cfg["password"],$cfg["db_name"]);


    if($_POST["action"] == "add_absenta_back"){
        if($_SESSION['group'] == "" or $_SESSION['group'] == ' ' or $_SESSION['group'] == '  '){
            header("location:logout.php");
        }
        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');
        if($_SESSION["group"] == 'prof' or $_SESSION["group"] == 'admin'){
            if(isset($_POST["om_selectat"]) and isset($_POST["materie"]) and preg_match("^[0-9]{2}-[0-9]{2}-[0-9]{1,4}$^",$_POST["data_primita"]) and preg_match("^[0-9]{1,5}$^",$_POST["om_selectat"]) and !preg_match("^[a-zA-Z]{3,20}$^",$_POST["materie"])){
                add_absenta($conn,$_POST["om_selectat"],$_POST["materie"],$_POST["data_primita"]);
                add_log($conn,$_SESSION["user_id"]." i-a pus absenta lui : ".$_POST["om_selectat"],$_POST["data_primita"]);

                $date_usr = DbReqUser_id($conn,$_POST["om_selectat"]);

                $subject = "Catalog online | Absenta noua";
                $message = '
                <html>
                <body style="font-family: Segoe UI,Arial,sans-serif;font-weight: 400;color:black;">
                    <div style="margin:0 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
                        <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                            <h2> Ai primit o absenta noua !</h2>
                        </div>
                        <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                            <h2> Detalii </h2>
                        </div>
                        <div style="margin : 16px;">
                            <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Detalii</div>Data : '.$_POST["data_primita"].'
                        </div>
                        <div style="margin : 16px;">
                            <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Detalii</div>Materia : '.$_POST["nume_materie"].'
                        </div>
                    </div>
                    <div style="margin:10px 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
                        <div style="margin : 16px;">
                            <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Creator</div> Platforma creata de <a href="https://www.instagram.com/_alexmihai_/">Udrescu Alexandru Mihai</a>
                        </div>
                    </div>
                </body>
                </html>
                ';

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <catalog-online@alex-mihai.ro>' . "\r\n";

                mail($date_usr["email"], $subject, $message, $headers);

                $response["response"] = 1;
                $response["mesaj"] = "Absenta a fost adaugata cu success";
            }else{
                $response["mesaj"] = 'A aparut o eroare. Trebuie sa completezi toate campurile. Cod : #2'; 
            }
        }else{
            $response["mesaj"] = 'A aparut o eroare. Cod : #1'; 
        }
        echo json_encode($response);
    }elseif($_POST["action"] == "add_nota_back"){
        if($_SESSION['group'] == "" or $_SESSION['group'] == ' ' or $_SESSION['group'] == '  '){
            header("location:logout.php");
        }
        $response = array('response' => 0,'mesaj' => 'A aparut o eroare. Cod : #0');
        if($_SESSION["group"] == 'prof' or $_SESSION["group"] == 'admin'){
            if(isset($_POST["om_selectat"]) and isset($_POST["data_primita"]) and preg_match("^[0-9]{2}-[0-9]{2}-[0-9]{1,4}$^",$_POST["data_primita"]) and isset($_POST["materie"]) and isset($_POST["nota_pusa"]) and preg_match("^[0-9]{1,5}$^",$_POST["om_selectat"]) and !preg_match("^[a-zA-Z]{3,20}$^",$_POST["materie"]) and preg_match("^[0-9]{1,2}$^",$_POST["nota_pusa"])){
                if($_POST["nota_pusa"] >= 1 and $_POST["nota_pusa"] <= 10){
                    add_nota($conn,$_POST["om_selectat"],$_POST["nota_pusa"],$_POST["materie"],$_POST["data_primita"]);
                    add_log($conn,$_SESSION["user_id"]." i-a pus nota : ".$_POST["nota_pusa"]." lui : ".$_POST["om_selectat"],$_POST["data_primita"]);

                    $date_usr = DbReqUser_id($conn,$_POST["om_selectat"]);

                    $subject = "Catalog online | Nota noua";
                    $message = '
                    <html>
                    <body style="font-family: Segoe UI,Arial,sans-serif;font-weight: 400;color:black;">
                        <div style="margin:0 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
                            <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                                <h2> Ai primit o nota noua !</h2>
                            </div>
                            <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                                <h2> Detalii </h2>
                            </div>
                            <div style="margin : 16px;">
                                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Detalii</div>Data : '.$_POST["data_primita"].'
                            </div>
                            <div style="margin : 16px;">
                                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Detalii</div>Nota : '.$_POST["nota_pusa"].'
                            </div>
                            <div style="margin : 16px;">
                                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Detalii</div>Materia : '.$_POST["nume_materie"].'
                            </div>
                        </div>
                        <div style="margin:10px 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
                            <div style="margin : 16px;">
                                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Creator</div> Platforma creata de <a href="https://www.instagram.com/_alexmihai_/">Udrescu Alexandru Mihai</a>
                            </div>
                        </div>
                    </body>
                    </html>
                    ';

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: <catalog-online@alex-mihai.ro>' . "\r\n";

                    mail($date_usr["email"], $subject, $message, $headers);

                    $response["mesaj"] = "Nota a fost adaugata cu succes !"; 
                    $response["response"] = 1;
                }else{
                    $response["mesaj"] = "Nota pusa trebuie sa fie cuprinsa intre 1 si 10. Cod eroare #3";
                }
            }else{
                $response["mesaj"] = 'A aparut o eroare. Trebuie sa completezi toate campurile. Cod : #2'; 
            }
        }else{
            $response["mesaj"] = 'A aparut o eroare. Cod : #1'; 
        }
        echo json_encode($response);
    }elseif($_POST["action"] == "get_elevi"){
        $date_need = [];
        if(preg_match("^[0-9]{1,5}$^",$_POST["id_clasa"])){
            $clase = get_elevi($conn,$_POST["id_clasa"]);
            $i = 0;
            foreach($clase as $elev){
                $date = DbReqUser_id($conn,$elev["user_id"]);
                $date_need[$i] = $date;
                unset($date_need[$i]["parola"]);
                $i+=1;
            }
        }

        echo json_encode($date_need);
    }
    DbClose($conn);
?>
