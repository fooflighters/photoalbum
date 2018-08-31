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
    <title>Photo Retriever</title>
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
            <h1 class="header center grey-text text-darken-2">Photo Retriever</h1>

            <?php 

            require_once 'app/database_settings.php';   //database settings

            /*
            * Display the table of results
            */
            function showTable() {
                global $conn;
                global $result;
                
                if(!$result) {
                    echo "<p>There was something wrong with the query</p>";
                } else {
                    echo "<table id=\"display_all\">\n";
                    echo "<tr>\n"
                        ."<th scope=\"col\">Photo Name</th>\n"
                        ."<th scope=\"col\">Photo Description</th>\n"
                        ."<th scope=\"col\">Photo Date</th>\n"
                        ."<th scope=\"col\">Photo URL</th>\n"
                        ."</tr>\n";
                    if(mysqli_num_rows($result) > 0) {    
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>\n";
                            echo "<td>", $row['photo_name'],"</td>\n";
                            echo "<td>", $row['photo_description'],"</td>\n";
                            echo "<td>", $row['photo_date'],"</td>\n";
                            echo "<td><a href='", $row['photo_url'],"'>View</a></td>\n";
                            echo "</tr>\n";
                        }
                        echo "</table>\n";
                    } else {
                        echo "<tr><td colspan=\"4\">No results found</td></tr>\n ";
                        echo "</table>\n";                       
                    }
                }

                mysqli_free_result($result);    
                mysqli_close($conn);   

            }

            //get the connection to database
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

            if(isset($_POST['display_all'])) {
                $query = "SELECT photo_name, photo_description, photo_date, photo_url FROM photo_album ORDER BY photo_date";
                $result = mysqli_query($conn, $query); 
                showTable();
            }    

            if(isset($_POST['search_title'])) {
                $query_photo_name = $_POST['query_photo_name'];
                $query = "SELECT photo_name, photo_description, photo_date, photo_url FROM photo_album WHERE photo_name LIKE '$query_photo_name%'";
                $result = mysqli_query($conn, $query); 
                showTable();
            } 

            if(isset($_POST['search_description'])) {
                $query_photo_description = $_POST['query_photo_description'];
                $query = "SELECT photo_name, photo_description, photo_date, photo_url FROM photo_album WHERE photo_description LIKE '$query_photo_description%'";
                $result = mysqli_query($conn, $query); 
                showTable();
            }

            if(isset($_POST['search_date'])) {
                $query_photo_date = $_POST['query_photo_date'];
                $query = "SELECT photo_name, photo_description, photo_date, photo_url FROM photo_album WHERE photo_date = DATE'$query_photo_date'";
                $result = mysqli_query($conn, $query); 
                showTable();                
            }

            if(isset($_POST['search_keywords'])) {
                $query_photo_keywords = $_POST['query_photo_keywords'];
                $query = "SELECT * FROM photo_album";
                $result = mysqli_query($conn, $query); 
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>",$row['keywords'],"</p>";
                }
                
            }

            ?>   
                     
            <br>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <fieldset>
                    <div class="input-field">
                        <p><label>Display All Photos: </label></p>
                        <button class="btn grey darken-2" type="submit" name="display_all">Display All</button>
                    </div>                    
                    <div class="input-field">
                        <label>Photo title: </label>
                        <input type="text" name="query_photo_name" />
                        <button class="btn grey darken-2" type="submit" name="search_title">Search by Title</button>
                    </div>
                    <div class="input-field">
                        <label>Description: </label>
                        <input type="text" name="query_photo_description" />
                        <button class="btn grey darken-2" type="submit" name="search_description">Search by Description</button>
                    </div>
                    <div class="input-field">
                        <label>Date: </label>
                        <input type="date" name="query_photo_date" placeholder="dd/mm/yyyy"/>
                        <button class="btn grey darken-2" type="submit" name="search_date">Search by Date</button>
                    </div>
                    <div class="input-field">
                        <label>Keywords: </label>
                        <input type="text" name="query_photo_keywords" />
                        <button class="btn grey darken-2" type="submit" name="search_keywords">Search by Keywords</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <br><br>

    <?php include('footer.php'); ?>

    <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script> 

</body>
</html>