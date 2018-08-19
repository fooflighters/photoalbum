
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photo uploader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
    <nav class="green darken-2">
        <div class="nav-wrapper container">
            <ul class="left">
                <li><a href="getphoto.php">Get photo</a></li>
            </ul>
            <ul class="right">
                <li>Phu Dao 101335460</li>
            </ul>            
        </div>
    </nav>

    <div class="section">
        <div class="container">
            <br>
            <h1 class="header center grey-text text-darken-2">Photo Uploader</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <fieldset>
                    <div class="input-field">
                        <label>Photo Title: </label>
                        <input type="text" name="photo_title" pattern="[A-Za-z0-9_]{1,30}" title="must only contain alpha characters, underscores, or numbers" />
                    </div>
                    <div class="file-field input-field">
                        <div class="btn grey darken-2">
                            <span>File</span>
                            <input type="file" name="file" />
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Select a photo">
                        </div>
                    </div>
                    <div class="input-field">
                        <label>Description: </label>
                        <input type="text" name="photo_description" pattern="[A-Za-z0-9 ]{1,30}" title="must only contain alpha characters or numbers" />
                    </div>
                    <div class="input-field">
                        <label>Date: </label>
                        <input type="date" name="photo_date" placeholder="dd/mm/yyyy" />
                    </div>
                    <div class="input-field">
                        <label>Keywords (separated by a semicolon, e.g. keyword1; keyword2; etc.): </label>
                        <input type="text" name="photo_keywords">
                    </div>
                    <p></p> 
                    <button class="btn grey darken-2" type="submit">Upload</button>  
                </fieldset>
            </form>
        </div>
    </div>

    <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>

<?php

use Aws\S3\Exception\S3Exception;

require 'app/start.php';

if(isset($_FILES['file'])) {

    $file = $_FILES['file'];

    //file details
    $name = $file['name'];
    $tmp_name = $file['tmp_name'];

    $extension = explode('.', $name);
    $extension = strtolower(end($extension));

    //temp details
    $key = md5(uniqid());
    $tmp_file_name = "{$key}.{$extension}";
    $tmp_file_path = "files/{$tmp_file_name}";

    //move the file
    move_uploaded_file($tmp_name, $tmp_file_path);

    try {

        $s3->putObject([
            'Bucket' => $config['s3']['bucket'],
            'Key' => "photoalbum/{$name}",
            'Body' => fopen($tmp_file_path, 'rb'),
            'ACL' => 'public-read'
        ]);

        //remove the file
        unlink($tmp_file_path);

    } catch(S3Exception $e) {
        die($e);
    }
}

?>

</body>
</html>
 