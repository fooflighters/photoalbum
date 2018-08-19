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
    <nav class="green darken-2">
        <div class="nav-wrapper container">
            <ul class="left">
                <li><a href="upload.php">Upload photo</a></li>
            </ul>
            <ul class="right">
                <li>Phu Dao 101335460</li>
            </ul>            
        </div>
    </nav>

    <div class="section">
        <div class="container">
            <br>
            <h1 class="header center grey-text text-darken-2">Photo Retriever</h1>
            <form method="post">
                <fieldset>
                    <div class="input-field">
                        <label>Photo title: </label>
                        <input type="text" name="photo_title" />
                        <button class="btn grey darken-2" type="submit" name="search_title">Search by Title</button>
                    </div>
                    <div class="input-field">
                        <label>Description: </label>
                        <input type="text" name="photo_description" />
                        <button class="btn grey darken-2" type="submit" name="search_description">Search by Description</button>
                    </div>
                    <div class="input-field">
                        <label>Date: </label>
                        <input type="date" name="photo_date" placeholder="dd/mm/yyyy"/>
                        <button class="btn grey darken-2" type="submit" name="search_date">Search by Date</button>
                    </div>
                    <div class="input-field">
                        <label>Keywords: </label>
                        <input type="text" name="search_keywords" />
                        <button class="btn grey darken-2" type="submit" name="search_date">Search by Keywords</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
    <br><br>
    <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>

    

</body>
</html>