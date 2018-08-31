
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

    <?php include('menu.php'); ?>

    <div class="section">
        <div class="container">
            <br>
            <h1 class="header center grey-text text-darken-2">Photo Uploader</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <fieldset>
                    <div class="file-field input-field">
                        <div class="btn grey darken-2">
                            <span>File</span>
                            <input type="file" name="file" required="required" />
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
require_once 'app/database_settings.php';

$photo_url = "https://s3-ap-southeast-2.amazonaws.com/prescriptionprogrammer/"; //this is url of the s3 bucket

//connect to mysql database
$conn = @mysqli_connect($host,
        $user,
        $pwd,
        $sql_db
    );
if (!$conn) {
    echo "Error connecting to MySQL";
    echo "Debugging errno: ". mysqli_connect_errno();
    echo "Debbuging error: ". mysqli_connect_error();
}

/*
* Creates the table customers in the database if one does not already exist
*/
function createTable() {
    global $conn;
    if (!$conn) {
        echo "<p>Database connection failure</p>";
    } else {
        $sql_table = "photo_album";
        $query = "CREATE TABLE IF NOT EXISTS $sql_table (
                    photo_id            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    photo_name          VARCHAR(50) NOT NULL,
                    photo_description   VARCHAR(255),
                    photo_date          DATE,
                    keywords            VARCHAR(255),
                    photo_url           VARCHAR(255)
                )";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "<p>There was an error with the query</p>";
        }
    }       
}

/*
* Saves data of the photo to database
*/
function saveData($name, $description, $date, $keywords, $photo_url) {
    global $conn;

    if(!$conn) {
        echo "<p>Database connection failure</p>";
    } else {
        $sql_table = "photo_album";
        $query = "INSERT INTO $sql_table (
            photo_name,
            photo_description,
            photo_date,
            keywords,
            photo_url
        ) VALUES (
            '$name',
            '$description',
            '$date',
            '$keywords',
            '$photo_url'
        )";

        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<p>The photo was successfully uploaded!</p>";
            return true;
        } else {
            echo "<p>There was an error with saving data</p>";
            return false;
        }
    }

}

/*
* Removes special characters from data
*/
function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if(isset($_FILES['file'])) {
    //get photo details
    $file = $_FILES['file'];
    $description = $_POST['photo_description'];
    $date = $_POST['photo_date'];
    $keywords = $_POST['photo_keywords'];
    $name = $file['name'];
    $photo_url .= $name;

    $description = sanitise_input($description);
    $keywords = sanitise_input($keywords);

    createTable();

    //file details    
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
            'Key' => "{$name}",
            'Body' => fopen($tmp_file_path, 'rb'),
            'ACL' => 'public-read'
        ]);

        //remove the file
        unlink($tmp_file_path);

        //save to database
        if(saveData($name, $description, $date, $keywords, $photo_url)) {
            header('Location: uploadsuccess.php');
        } else {
            echo "Error uploading photo";
        }

    } catch(S3Exception $e) {
        die($e);
    }
}

?>
<br><br>

<?php include('footer.php'); ?>

</body>
</html>
 