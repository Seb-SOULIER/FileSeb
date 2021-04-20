<?php
// Je vérifie si le formulaire est soumis comme d'habitude
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = array_map('trim', $_POST);
    if($data['detruit']=== "1"){
        if(file_exists($data['image'])){unlink($data['image']);}
        header('form.php');
    }else{
    // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'images/';
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // Les extensions autorisées
    $extensions_ok = ['jpg', 'webp', 'png', 'jpeg'];
    // Le poids max géré par PHP par défaut est de 2M
    $maxFileSize = 1048576;
    
    $fileName = uniqid('',true).'.'.$extension;
    // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . $fileName;
    // Je récupère l'extension du fichier

    // Je sécurise et effectue mes tests
    /****** Si l'extension est autorisée *************/
    if ((!in_array($extension, $extensions_ok))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou webp ou Png !';
    }
    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
        $errors[] = "Votre fichier doit faire moins de 2M !";
    }

    if (empty($errors)) {
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
        $confirm= "La carte est bien générée";
        $data = array_map('trim', $_POST);
    }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cedarville+Cursive&display=swap" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Taxicab License</title>
</head>

<body>
    <form method="post" enctype="multipart/form-data" action="form.php">
        <div class="formul">
            <input type="hidden" value="0" name="detruit">
            <h1>Incription : </h1>
            <div class="mb-3">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="name" required>
            </div>
            <div class="mb-3">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="firstname" required>
            </div>
            <div class="mb-3">
                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sex" required>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="adress">Adresse :</label>
                <input type="text" id="adress" name="adress" required>
            </div>
            <div class="mb-3">
                <label for="codepost">Code postal :</label>
                <input type="text" id="codepost" name="cp" required>
            </div>
            <div class="mb-3">
                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" required>
            </div>
            <div>
                <label for="imageUpload">Insert ta photo</label>
                <input type="file" name="avatar" id="imageUpload" />
            </div>
            <button class="btn btn-primary" name="send">Envoyer</button>
        </div>
    </form>
    <p><br></p>
    <div class="confirm">
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error;
        }
    }
    if (!empty($confirm)) {
            echo $confirm;
    }
    ?>
</div>
    <p><br></p>
    <div class="boxcarte">
        <div class="carte">
            <div class="photo">
                <img class="ph" src="<?php if (!empty($uploadFile)) {echo $uploadFile;} ?>" />
            </div>
            <div class="info">
                <div class="haut">
                    <h1>TAXICAB</h1>
                    <h1>LICENSE</h1>
                </div>
                <div class="bas">
                    <div class="identite">
                        <para> <?php if (!empty($data['name'])) {echo $data['name']." ".$data['firstname'] ;} ?> </para>
                        <para> <?php if (!empty($data['adress'])) {echo $data['adress'];} ?> </para>
                        <para> <?php if (!empty($data['ville'])) {echo $data['cp']." ".$data['ville'];} ?> </para>
                    </div>
                    <div class="logo">
                        <div>
                            <img class="imgseb" src="fond/logo.jpg" />
                        </div>
                        <div class="sex">
                            <para>SEX: <?php if (!empty($data['sex'])) {echo $data['sex'];} ?> </para>
                            <p class="signature"> <?php if (!empty($data['name'])) {echo $data['name']." ".$data['firstname'];} ?> </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div>
        <p><br></p>
    <form class="formul" method="post" action="form.php">
    <input type="hidden" value="1" name="detruit">
    <input type="hidden" value="<?php if (!empty($uploadFile)) {echo $uploadFile;} ?>" name="image">
        <button class="btn btn-primary" name="send">Détruire</button>
    </form>
    </div>
     <!-- enctype="multipart/form-data"  -->
</body>

</html>
