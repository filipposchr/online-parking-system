<?php
session_start();
if(!isset($_SESSION['user_session'])){
    header("Location: index.php");
}
include_once("db_connect.php");
$sql = "SELECT username, password FROM admin WHERE username='".$_SESSION['user_session']."'";
$result = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
$row = mysqli_fetch_assoc($result);
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
            integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
            crossorigin=""></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="styleinmap.css"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="adminmap_style2.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>


<body>
<title>Εξομοίωση Διαχειριστή</title>
<script src="map.js"></script>

<div class="toggle"></div>
<div class="overlay"></div>
<div class="menu">
    <ul class="nav-links">
        <li><a  class="other" href="managedb.php">Διαχειριση</a></li>
        <li><a  class="current" href="adminmap.php">Χαρτης</a></li>
        <li><a class="other" href="usermap.html">λειτουργια χρηστη</a></li>
        <li><a class="other" href="logout.php">Εξοδος</a></li>
    </ul>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.toggle').click(function(){
            $('.toggle').toggleClass('active')
            $('.overlay').toggleClass('active')
            $('.menu').toggleClass('active')
        })

    })

</script>

<div class="simulation">
    <h3> ΕΞΟΜΟΙΩΣΗ </h3>
    <form id="sim-form">
        <label id="hour_box" for="input-hour"> Ώρα Εξομοίωσης: </label>
        <br>
        <input id="input-hour" name="inputhour" type="time"/>
        <br>
        <label id="hour_box" for="input-offset"> Με μεταβολή κατά: </label>
        <input id="input-offset" name="offset" type="number" min="0">
        <br>
        <select id="input-select" name="offsetchoice">
            <option value="hour">Ώρα/ες</option>
            <option value="min">Λεπτό/ά</option>
        </select>
        <br>
        <button id="sim" type="submit"> Εκτέλεση Εξομοίωσης </button>
    </form>
    <div id="reset"> <button id ="reset_btn" type="button" onclick="resetEverything()"> Επαναφορά όλων </button> </div>
    <br>
    <div id="current-time"> </div>
    <div id="before"> </div>
    <div id="after"> </div>
</div>
<div id="container-main">


    <div id="mapid">
        <div id="loader" class="d-flex justify-content-center text-center">
            <div class="spinner-border text-primary" role="status">
            </div>
        </div>
    </div>
</div>


<script src="simulation.js"> </script>


</body>
