<?php

require 'app/start.php';

$objects = $s3->getIterator('ListObjects', [
    'Bucket' => $config['s3']['bucket']
]);


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Bucket</title>
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
            <h1 class="header center grey-text text-darken-2">View Bucket</h1>
            <br><br>
            <table>
                <thead>
                    <tr>
                        <th>File</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($objects as $object): ?> 
                    <tr>
                        <td><?php echo $object['Key']; ?></td>
                        <td><a href="<?php echo $s3->getObjectUrl($config['s3']['bucket'], $object['Key']); ?>">View</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
    <br><br>
    <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>
 

</body>
</html>