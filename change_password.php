<?php
    //vključim sejo da, preverim če je uporabnik vpisan
    include_once './session.php';
    //vključim bazo, ker bom vpisoval v bazo
    include_once './database.php';
    if (!isset($_SESSION['user_id'])) {
        header("Location: login_user.php");
    }
    //shranim id uporabnika v spremenljivko
    $user_id   = $_SESSION['user_id'];
    //pridobim podatke, ki jih je uporabnik vpisal v profile.php formo
    $old_pass  = mysqli_real_escape_string($link, $_POST['pass1']);
    $new_pass1 = mysqli_real_escape_string($link, $_POST['pass2']);
    $new_pass2 = mysqli_real_escape_string($link, $_POST['pass2']);
    //pregledam če so vsi podatki vpisani, ter če se novi gesli ujemata
    if (!empty($old_pass) && !empty($new_pass1) && !empty($new_pass2) && $new_pass1 == $new_pass2) {
        //zakodiram geslo v md5
        $old_pass = md5($old_pass);
        $new_pass = md5($new_pass1);
        //preverim če obstaja uporabnik s tem idjem ter geslom
        $query = "SELECT pass FROM users WHERE id_users='$user_id' AND pass='$old_pass'";
        //pošljem poizvedbo, ter jo shranim v spremenljivko $result
        $result = mysqli_query($link, $query);
        //če najde en rezultat pomeni, da obstaja ta uporabnik
        if (mysqli_num_rows($result) == 1) {
            //posodobim geslo za tega uporabnika
            $query = "UPDATE users SET pass='$new_pass' WHERE id_users='$user_id'";
            mysqli_query($link, $query);
            mysqli_close($link);
            header("Location: logout.php");
            die();
        } else {
            mysqli_close($link);
            header("Location: index.php");
            die();
        }
    } else {
        mysqli_close($link);
        header("Location: index.php");
        die();
    }
?>