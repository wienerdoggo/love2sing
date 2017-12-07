<?php
include("adminpageheader.php"); 
//als de knop word ingedrukt
$formstyle = "";
$buttonstyle = "style='display: none;'";
if (isset($_POST['text'])) {
    //Upload functie aanroepen
    $file = $_FILES['jpeg'];
    $type =  array ("jpg", "jpeg");
    $name = "image";
    $fileName = "test";
    $resultaat = fileUpload($file,$type);
    $imgurl = $resultaat[0];        
    
    $formstyle = "style='display: none;'";
    $buttonstyle = "";
    //Beschrijving foto
    $text = $_POST['text'];
    if ($resultaat[1] == 1) {

        $sql = "INSERT INTO photoalbum(photoalbumUrl, photoalbumDescription) VALUES ('$imgurl', '$text')";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount()== 1) {
            message("Success","Opgeslagen in database", ""); 
        }
        else {
            message("danger","Niet opgeslagen in database", "");
        }
    }
    else {
        message("danger","Niet opgeslagen in database", "");
    }
}
?>
<section id="musicupload">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-heading text-uppercase">Upload foto</h2>
            </div>
        </div>
        <form id="musicForm" name="uploadPhoto" method="post" enctype="multipart/form-data">
            <div id="musicform" >
                <div class="row">
                    <div class="col md6" <?= $formstyle ?> >
                        <h3>Informatie foto</h3>
                        <div class="form-group">
                            Titel
                            <input class="form-control" id="title" name="text" type="text" placeholder="Beschrijving van de foto" required data-validation-required-message="Vul a.u.b een beschrijving in">
                            <p class="help-block text-danger"></p>
                        </div>



                        <h3>Foto</h3>
                        <div class="form-group">
                            Foto
                            <input class="form-control" id="jpeg" name="jpeg" type="file" placeholder="Beschrijving van de foto" accept=".jpeg, .jpg">
                            <p class="help-block text-danger"></p>
                        </div>

                        <div class="clearfix"></div>
                        <div id="success"></div>
                        <button id="uploadButton" class="btn btn-primary btn-xl text-uppercase" type="submit" >Uploaden</button>
                        
                    </div>
                    <div  class="col md6"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">     
                    <div id="message"></div>
                    <?= $message ?>
                    <a id="uploadMore" class="btn btn-primary btn-xl text-uppercase" href="uploadfoto.php" <?= $buttonstyle ?> >Upload nog een foto</a>
                </div>
            </div>
        </form>

    </div>
</section>

<!-- melingen weergeven -->


<?php
    include("adminpagefooter.php");
?>