<!-- © 2020 Udrescu Alexandru All Rights Reserved -->

<?php
    session_start();
    $_SESSION["user_id"] = false;
    $_SESSION["username"] = false;
    $_SESSION["last_name"] = false;
    $_SESSION["first_name"] = false;
    $_SESSION["group"] = false;
    $_SESSION["is_auth"] = false;
    header('Location: login.php');
?>