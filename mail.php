<?php
if (isset($_POST['contactName']) && isset($_POST['contactEmail']) && isset($_POST['contactMessage'])) {   
    $contactName = $_POST['contactName'];
    $contactEmail = $_POST['contactEmail'];
    $contactMessage = nl2br($_POST['contactMessage']);

    $to = "love2singtestmail@gmail.com";
    $subject = "Contactformulier van koor";
    $message = "
    <h1 style='font-size: 22px;'>Het contact formulier is ingevuld door ".$contactName."</h1>
    
    <h2 style='font-size: 18px;'>Het volgende bericht is gestuurd:</h2>
    
    <p style='font-size: 14px;'>". $contactMessage."</p>
    <div style='font-size: 14px;'>
        <h2 style='font-size: 18px;'>Gegevens:</h2>
        <b>Naam: </b>".$contactName."<br>
        <b>E-mail: </b>".$contactEmail."<br>
        <b>Verstuurd op: </b>".date("d-m-Y H:i:s")."
    </div>";
    $headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: '.$contactEmail.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers.="Content-type: text/html; charset=\"UTF-8\" \r\n";

    //echo $message;
    if(mail($to,$subject, $message, $headers)){
        echo "1";
    }
    else { 
        echo "0";
    }
}
else {
    echo "2";
}
// er mag alleen een 0 voor een fout worden geprint, en een 1 voor succes, alle andere tekens zorgen voor een foutmelding met technische fout
?>