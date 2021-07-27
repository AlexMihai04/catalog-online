

<?php
    // © 2020 Udrescu Alexandru All Rights Reserved
    //functie pentru a ma conecta la baza de date
    
    function DbConn($servername,$username,$password,$db_name)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    //functie pentru a da drop la conexiunea de la baza de date
    function DbClose($conn)
    {
        $conn = null;
        return $conn;
    }

    function add_log($conn,$text,$data){
        $stmt = $conn->prepare('INSERT INTO lista_loguri (log_text, data) VALUES (:log_text, :data)');
        $stmt->execute(['log_text'=>$text,'data' => $data]);
    }

    function get_nr_elevi($conn,$id_clasa){
        $stmt = $conn->prepare('SELECT COUNT(*) FROM lista_date WHERE clasa = :clasa');
        $stmt->execute(['clasa'=>$id_clasa]);
        $date = $stmt->fetchColumn();
        return $date;
        $stmt->close;
    }

    function get_loguri($conn){
        $stmt = $conn->prepare('SELECT * FROM lista_loguri');
        $stmt->execute();
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    function get_elevi($conn,$id_clasa){
        $stmt = $conn->prepare('SELECT * FROM lista_date WHERE clasa = :clasa');
        $stmt->execute(['clasa'=>$id_clasa]);
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    function insert_elev_in_clasa($conn,$id_clasa,$id_user){
        $stmt = $conn->prepare('DELETE FROM lista_date WHERE user_id = :user_id AND clasa = :clasa;INSERT INTO lista_date (user_id, clasa) VALUES (:user_id, :clasa) ON DUPLICATE KEY UPDATE user_id=user_id');
        $stmt->execute(['clasa'=>$id_clasa,'user_id' => $id_user]);
    }

    function get_all_elevi($conn){
        $stmt = $conn->prepare('SELECT * FROM lista_conturi');
        $stmt->execute();
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    function insert_elev($conn,$id_clasa,$id_user){
        $stmt = $conn->prepare('INSERT INTO lista_conturi (id_clasa, id_user) VALUES (:id_clasa, :id_user)');
        $stmt->execute(['id_clasa'=>$id_clasa,'id_user' => $id_user]);
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    function get_clase($conn){
        $stmt = $conn->prepare('SELECT * FROM lista_clase');
        $stmt->execute();
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close();
    }

    function get_clase_usr($conn,$u_id){
        $stmt = $conn->prepare('SELECT * FROM lista_date WHERE user_id = :user_id');
        $stmt->execute(['user_id'=>$u_id]);
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    function get_materii($conn){
        $stmt = $conn->prepare('SELECT * FROM lista_materii');
        $stmt->execute();
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    //functie de a lua datele userului din baza de date dupa username
    function DbReqUser($conn,$username)
    {
        $stmt = $conn->prepare('SELECT * FROM lista_conturi WHERE username = :username');
        $stmt->execute(['username'=>$username]);
        $date = $stmt->fetch(PDO::FETCH_ASSOC);
        return $date;
        $stmt->close;
    }

    function DbReqUser_id($conn,$id)
    {
        $stmt = $conn->prepare('SELECT * FROM lista_conturi WHERE user_id = :user_id');
        $stmt->execute(['user_id'=>$id]);
        $date = $stmt->fetch(PDO::FETCH_ASSOC);
        return $date;
        $stmt->close;
    }

    function add_nota($conn,$user_id,$nota,$materie,$data)
    {
        $stmt = $conn->prepare('INSERT INTO lista_note (id_elev, materie, nota,data) VALUES (:id_elev, :materie, :nota,:data)');
        $stmt->execute(['id_elev'=>$user_id,'materie' => $materie,'nota' => $nota,'data' => $data]);
    }

    function add_absenta($conn,$user_id,$materie,$data)
    {
        $stmt = $conn->prepare('INSERT INTO lista_absente (id_elev, materie,data) VALUES (:id_elev,:materie,:data)');
        $stmt->execute(['id_elev'=>$user_id,'materie' => $materie,'data' => $data]);
    }
    // ,"'.$last.'","'.$first.'"
    function create_cont($conn,$username,$parola,$grup,$first_name,$last_name,$m_in)
    {
        $stmt = $conn->prepare('INSERT INTO lista_conturi (username, parola,last_name,first_name,grad,email) VALUES (:username, :parola,:last_name,:first_name,:grad,:email)');
        $stmt->execute(['username' => $username,'parola' => password_hash($parola,PASSWORD_DEFAULT),'last_name' => $last_name,'first_name' => $first_name,'grad'=>$grup,'email'=>$m_in]);

        if($grup == 'prof'){
            $grup = 'profesor';
        }

        $subject = "Catalog online | Ti-a fost creat contul";
        $message = '
        <html>
        <body style="font-family: Segoe UI,Arial,sans-serif;font-weight: 400;">
            <div style="margin:0 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
                <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                    <h2> Creare cont catalog online</h2>
                </div>
                <div style="margin : 16px;">
                    <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Detalii</div> Pe aceasta cale dorim sa te informam ca in urma cu putin timp ti-a fost creat un cont pentru catalogul online.
                </div>
                <div style="margin : 16px;">
                    <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Detalii</div> Mai jos ai detaliile contului tau , acestea sunt stiute doar de tine si NU pot sa fie resetate / schimbate , ai grija de ele
                </div>
                <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                    <h2> Detalii cont</h2>
                </div>
                <div style="margin : 16px;">
                    <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Detalii</div> Contul tau de : '.$grup.' a fost creeat cu succes
                </div>
                <div style="margin : 16px;">
                    <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Username</div> '.$username.'
                </div>
                <div style="margin : 16px;">
                    <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Parola</div> '.$parola.'
                </div>
            </div>
            <div style="margin:10px 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
                <div style="margin : 16px;">
                    <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Creator</div> Platforma creeata de <a href="https://www.instagram.com/_alexmihai_/">Udrescu Alexandru Mihai</a>
                </div>
            </div>
        </body>
        </html>
        ';

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <catalog-online@alex-mihai.ro>' . "\r\n";

        mail($m_in, $subject, $message, $headers);
    }

    function delete_cont($conn,$u_id)
    {
        $stmt = $conn->prepare('DELETE FROM lista_conturi WHERE user_id = :user_id;DELETE FROM lista_date WHERE user_id = :user_id;DELETE FROM lista_note WHERE id_elev = :user_id;DELETE FROM lista_absente WHERE id_elev = :user_id;');
        $stmt->execute(['user_id' => $u_id]);
    }
    
    function delete_cls($conn,$c_id)
    {
        $stmt = $conn->prepare('DELETE FROM lista_clase WHERE id_clasa = :id_clasa;DELETE FROM lista_date WHERE clasa=:id_clasa;');
        $stmt->execute(['id_clasa' => $c_id]);
    }

    function om_rem_del($conn,$c_id,$u_id)
    {
        $stmt = $conn->prepare('DELETE FROM lista_date WHERE user_id = :user_id;DELETE FROM lista_note WHERE id_elev=:user_id;');
        $stmt->execute(['user_id' => $u_id,'clasa' => $c_id]);
    }

    function delete_om_clasa($conn,$c_id,$u_id)
    {
        $stmt = $conn->prepare('DELETE FROM lista_date WHERE user_id = :user_id AND clasa = :clasa;DELETE FROM lista_note WHERE id_elev=:user_id;');
        $stmt->execute(['user_id' => $u_id,'clasa' => $c_id]);
    }

    function create_class($conn,$nume_clasa)
    {
        $stmt = $conn->prepare('INSERT INTO lista_clase (nume_clasa) VALUES (:nume_clasa)');
        $stmt->execute(['nume_clasa' => $nume_clasa]);
    }

    function get_note($conn,$u_id){
        $stmt = $conn->prepare('SELECT * FROM lista_note WHERE id_elev = :id_elev');
        $stmt->execute(['id_elev' => $u_id]);
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    // SELECT * FROM lista_conturi WHERE NOT EXISTS (SELECT * FROM lista_date WHERE lista_conturi.user_id = lista_date.user_id);
    function get_absente($conn,$u_id){
        $stmt = $conn->prepare('SELECT * FROM lista_absente WHERE id_elev = :id_elev');
        $stmt->execute(['id_elev' => $u_id]);
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }

    function get_not_in_clasa($conn){
        $stmt = $conn->prepare('SELECT * FROM lista_conturi WHERE (NOT EXISTS (SELECT * FROM lista_date WHERE lista_conturi.user_id = lista_date.user_id) OR lista_conturi.grad = "prof"); ');
        $stmt->execute();
        $date = $stmt->fetchAll();
        return $date;
        $stmt->close;
    }
?>