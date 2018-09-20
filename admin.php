<?php

require 'app/start.php';

$objects = $s3->getIterator('ListObjects', [
    'Bucket' => $config['s3']['bucket']
]);

require_once ("app/database_settings.php");
$conn = @mysqli_connect($host,
    $user,
    $pwd,
    $sql_db
);

if(!$conn) {
    echo "<p>Database connection failure</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>
</head>
<body>

<style type="text/css">
    html, body {
        height: 100%;
    }
    .full-height {
        height: 100%;
    }
</style>

<?php include('menu.php'); ?>  

    <div class="section full-height">
        <div class="container">
            <h1 class="header center grey-text text-darken-2">Admin</h1>
            <br>

            <?php 

            if(isset($_GET['delete'])) {
                $photoKey = $_GET['photoKey'];  
                //remove from s3 bucket    
                $s3result = $s3->deleteMatchingObjects($config['s3']['bucket'], $photoKey);    
                if($s3result) {
                    //remove from database
                    $query = "DELETE from photo_album WHERE photo_name = '$photoKey'";
                    $dbResult = mysqli_query($conn, $query);
                    if($dbResult) {
                        echo "<script>M.toast({html: '". $photoKey. " was successfully deleted'});</script>";
                    } else {
                        echo "<p class='text-red'>There was an error removing the photo details from the database</p>";
                    }                      
                } else {
                    echo "<p class='text-red'>There was an error removing the photo details from the s3 bucket</p>";
                }      
            }

            ?>
            
            <table>
                <thead>
                    <tr>
                        <th>File</th>
                        <th>View</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($objects as $object): ?> 
                    <tr>
                        <td><?php echo $object['Key']; ?></td>
                        <td><a href="<?php echo $s3->getObjectUrl($config['s3']['bucket'], $object['Key']); ?>" class="btn grey darken-2">View</a></td>
                        <td><a href="admin.php?photoKey=<?php echo $object['Key'];?>&delete=true" class="btn grey darken-2">Delete</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
    <br><br>

    <?php include('footer.php'); ?>
 

</body>
</html>