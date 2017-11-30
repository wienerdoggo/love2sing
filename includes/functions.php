<?php

session_start();

//DATABASE CONNECT

class DbHelper{
    var $connect;

    //maakt de DB connectie aan
    public function __construct(){
        $this -> connect = new PDO('mysql:host=localhost; dbname=love2sing;','root','');
    } 


//SELECT USER FUNCTION
    
function selectUser(){
    //select query voor de users
    $create = 'SELECT * FROM user WHERE username=:username AND userPassword=:password';
    
    //zorgt dat de connectie wordt gestart vanuit de db
    $statement = $this->connect->prepare($create);
    
    //haalt de gegevens op uit deze rijen
    $statement->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
    $statement->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
    
    $statement->execute();
    
    //controlleerd of alles klopt
    $result = $statement->fetchAll();
    
    //functie userrights
    if(isset($result) && isset($result[0])){
        $user = $result[0];
        
        if($user[0] > 0){
            $_SESSION['logIn'] = 'true';
            $_SESSION['username'] = $user[1];
            $_SESSION['userId'] = $user[0];
            $_SESSION['userRights'] = 'user';
            
            header('Location: index.php');
        }
    }else{
        //select query voor de employees
        $create = 'SELECT * FROM admin WHERE adminUsername=:username AND adminPassword=:password';
    
        //zorgt dat de connectie wordt gestart vanuit de db
        $statement = $this->connect->prepare($create);

        //haalt de gegevens op uit deze rijen
        $statement->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $statement->bindParam(':password', $_POST['password'], PDO::PARAM_STR);

        $statement->execute();

        //controlleerd of alles klopt
        $result = $statement->fetchAll();

        //functie userrights
        if(isset($result) && isset($result[0])){
            $user = $result[0];

            if($user[0] > 0){
                $_SESSION['logIn'] = 'true';
                $_SESSION['username'] = $user[1];
                $_SESSION['userId'] = $user[0];

                $_SESSION['userRights'] = 'admin';

                header('Location: index.php');
            }
        }else{
            //select query voor de employees
            $create = 'SELECT * FROM user WHERE username=:username AND userPassword=:password';

            //zorgt dat de connectie wordt gestart vanuit de db
            $statement = $this->connect->prepare($create);

            //haalt de gegevens op uit deze rijen
            $statement->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
            $statement->bindParam(':password', $_POST['password'], PDO::PARAM_STR);

            $statement->execute();

            //controlleerd of alles klopt
            $result = $statement->fetchAll();

            //functie userrights
            if(isset($result) && isset($result[0])){
                $user = $result[0];

                if($user[0] > 0){
                    $_SESSION['logIn'] = 'true';
                    $_SESSION['username'] = $user[1] . ' ' . $user[2] . ' ' . $user[3];
                    $_SESSION['userId'] = $user[0];
                    $_SESSION['userRights'] = '0';

                    header('Location: index.php');
                }
            }

        }

    }
}
}

// bestand uploaden

// code's: 
// 0    technische fout
// 1    gelukt
// 2    bestand bestaat al
// 3    bestand is te groot
// 4    bestandstype is verkeerd
// 5    er is geen bestand gekozen of bijgevoegd

// aanroepen:
// Simpele manier:
// upload($_FILES["file_input_name"],"type",tijdelijke naam,"bestandsnaam op server zonder extensie");

// Uitgebreide manier: 
// $name = "mp3-file"; // tijdelijke naam voor het uitlezen van opslaglocatie
// $var = upload($_FILES["file_input_name"],"type",tijdelijke naam,"bestandsnaam op server zonder       extensie");   $var krijgt gereturnde foutcodes terug
// $opslaglocatievoordb = $fileUrl[$name]; // variable maken met opslaglocatie van bestand in database

$fileUrl = null;
function upload($file,$type,$name,$fileName) {    
    $fileUrl[$name] = null;
    if ($file["error"] == 4) {
        return 5;
    }
    else {
        $target_dir = "uploads/";
        if ($fileName != null) {
            $target_file = $target_dir . $fileName . "." . $type;
        }
        else {
            $target_file = $target_dir . basename($file["name"]);
        }
        $uploadOk = 1;
        $fileType = pathinfo(basename($file["name"]),PATHINFO_EXTENSION);
        $result = "";

        if (file_exists($target_file)) {
            $uploadOk = 0;
            $result .= "2";           
            // check of er al een bestand is met dezelfde naam, zo ja: foutcode 2
        }
        if ($file["size"] > 2500000) {
            $uploadOk = 0;
            $result .= "3";
            // check of het bestand te groot is, zo ja: foutcode 3
        }
        if($fileType != $type) {
            $uploadOk = 0;
            $result .= "4";
            // check of het bestand geen $type type is, zo ja: foutcode 4
        }

        if ($uploadOk == 0) {
            return $result;
            // er zijn fouten opgetreden, de foutcodes worden gereturnd
        } 
        else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                global $fileUrl;
                $fileUrl[$name] = $target_file;
                return 1;
                // bestand is succesvol geupload
            }
            else {
                return 0;
                // er is een probleem opgetreden met uploaden
            }
        }
    }
}

// mail versturen

// code's: 
// 0    technische fout
// 1    gelukt

// aanroepen:
// sendMail(onderwerp,bericht,beantwoorden naar) // als er geen beantwoorden naar nodig is null invoeren

// Uitgebreide manier: 
// $var = sendMail(onderwerp,bericht,beantwoorden naar) // foutcode wordt doorgegeven aan $var

//  inloggen op gmail
//  love2singtestmail@gmail.com
//  love2singmail
//  php.ini en sendmail.ini aanpassen, zie trello->programming rules

function sendMail($subject,$message,$replyTo) {
    $to = "love2singtestmail@gmail.com";
    
    if ($replyTo == null) {
        $replyTo = "love2singtestmail@gmail.com";
    }
    $headers = 'From: love2singtestmail@gmail.com' . "\r\n" .
    'Reply-To: '.$replyTo.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers.="Content-type: text/html; charset=\"UTF-8\" \r\n";

    if(mail($to,$subject, $message, $headers)){
        return 1;
    }
    else { 
        return 0;
    }
}
    //EDIT USER FUNCTION
    
function editUserStart($case){
    //select query die de gegevens van de klant ophaald zodat hij/zij deze kan veranderen
    $create = "SELECT * FROM user WHERE userId = :id";
    
    //deze functie zorgt ervoor dat de gegevens uit de databse worden gehaald
    $statement = $this-> connect -> prepare($create);
    
    //zorgt ervoor dat het juiste id wordt gepakt
    $statement->bindParam(':id', $_SESSION['editPassword'], PDO::PARAM_STR);
    
    $statement -> execute();
    
    $user = $statement->fetchAll();
    
    $userEdit = $user[0];
    
    
    //zorgt ervoor dat wanneer de klant iets vergeet, deze melding in beeld komt
    if(!isset($case)){
        echo "<p>Er is een fout opgetreden met het wijzigen van uw gegevens. Probeer het opnieuw.</p>";
    }else{
        //zorgt ervoor dat wanneer alles correct is, de gegevens worden doorgestuurd naar de db
        print $userEdit[$case];
    
}
    
function editUser(){
    //update query voor de gebruikers
    $edit = "UPDATE user SET  
    userPassword = :password
    WHERE userId = :id";
    
    //bereid de db voor
    $statement = $this -> connect ->prepare($edit);
    
    //de gegevens die gewijzigd mogen worden (het id wordt NIET aangepast)
    $statement->bindParam(':id', $_SESSION['userId'], PDO::PARAM_STR);
    $statement->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
    
    $statement->execute();
}
    



?>