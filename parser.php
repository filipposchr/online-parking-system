<?php

foreach (glob("uploads/*.kml") as $file) {} //euresi kml arxeiwn sto directory
$kml = simplexml_load_file($file); //fortosi arxeiou pou exei vretei
$arr = array();
$population = array();
$kml_inst = new DomDocument();
$kml_inst->load($file);
$conn = mysqli_connect("localhost", "root", "806175", "parking");
$descr_tag = $kml_inst->getElementsByTagName("description"); //vriskei ola ta elements tou xml me onoma description
$i=0;

require_once('getCentroid.php'); //function gia tin evresi twn kentroidwn kathe poligonou

//Adeiasma pinakwn prin na ginei eisagwgi stin vasi
mysqli_query($conn, 'TRUNCATE TABLE centroids');
mysqli_query($conn, 'TRUNCATE TABLE kamp');
mysqli_query($conn, 'TRUNCATE TABLE polygon');
mysqli_query($conn, 'TRUNCATE TABLE pol_info');
mysqli_query($conn, 'TRUNCATE TABLE info');

if (!$conn) {
    die("SQL connection failed: " . mysqli_connect_error());
}

//Evresi plithismou apo to kml arxeio gia to kathe poligono
foreach($descr_tag as $d) {
    $pop = simplexml_load_string("<base>".$d->nodeValue."</base>");
    $population[]  = (string)$pop->xpath("//li[strong/span[text()='Population']]")[0]->span;
}

//Evresi longitute, latitude, altitude kml kai eisagwgi timwn ston pinaka info
foreach($kml->Document as $doc) {
    $map_longitude = $doc->LookAt->longitude;
    $map_latitude = $doc->LookAt->latitude;
    $map_altitude = $doc->LookAt->altitude;
	$query = "INSERT INTO `info` (`x`, `y`) VALUES ('$map_longitude', '$map_latitude')";
	if (mysqli_query($conn, $query)) {}
    else {
         echo "Error: " . $query . "<br>" . mysqli_error($conn);
         echo "<br />";
    }
}

$polygon_counter = 0;
//Ipologismos sintetagmenwn x kai y kathe poligonou
foreach($kml->Document->Folder->Placemark as $pm) {
    if(isset($pm->MultiGeometry->Polygon)) { //an exei vrethei element 'Polygon'
        $coordinates = $pm->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
        $cordsData = trim($coordinates); //diagrafi whitespaces xaraktirwn
        $explodedData = explode("\n", $cordsData); //split tou string otan vriskei kainouria grammi
        $explodedData = array_map('trim', $explodedData);

            //ta coordinates pou vriskoume gia to kathe poligono einai se morfi:
            //<coordinates>x1,y1 x2,y2 ... </coordinates>
            foreach ($explodedData as $index => $coordinateString) {
                //se kathe thesi tou coordinateSet array apothikevontai ta simia se morfi x,y
                $coordinateSet = array_map('trim', explode(' ', $coordinateString));
                //apothikefsi se morfi x,y
            }

            $k = 0;
            $counter_coordinateSet = count($coordinateSet);
            $points_counter = 2 * count($coordinateSet); //exoume x kai y gia kathe coordinate

            for ($m = 0; $m < $points_counter; $m++) {
                $points = explode(',', $coordinateSet[$m]); //diagrafi tou ',' se kathe thesi tou coordinateSet array
                $arr[$polygon_counter][$k] = $points[0]; //sto points[0] exw to x
                $k++;
                $arr[$polygon_counter][$k] = $points[1]; //sto points[1] exw to y
                $k++;
            }

                //Klisi getCentroid sinartisis kai apothikeusi kentroidwn tou
                //poligonou stis x_centroid kai y_centroid metavlites
                list($x_centroid,$y_centroid) = getCentroid($arr, $points_counter, $polygon_counter);
                $query1 = "";
                //eisagwgi kentroidwn ston pinaka centroids gia to kathe poligono
                $query1 = "INSERT INTO `centroids` (`id`, `x`, `y`) VALUES (NULL, $x_centroid, $y_centroid)";
                if (mysqli_query($conn, $query1)) {}
                 else {
                    echo "Error: " . $query1 . "<br>" . mysqli_error($conn);
                    echo "<br />";
                 }

              $counter = 0;

              //Eisagwgi ston pinaka polygon ta stoixeia tou kathe poligwnou
              while($counter < $points_counter) {
                  $query = "";
                  $query = "INSERT INTO `polygon` (`id`,`population`,`tetragono_id`,`X`,`Y`) VALUES (NULL, '" . (int)$population[$polygon_counter] . "', '" . $polygon_counter ."' , '" . $arr[$polygon_counter][$counter] . "', '" . $arr[$polygon_counter][$counter+1] . "')";
                  $counter=$counter + 2;;
                  if (mysqli_query($conn, $query)) {} //epitixis ektelesi query
                  else {
                      echo "Error: " . $query . "<br>" . mysqli_error($conn);
                      echo "<br />";
                  }
              }
              $polygon_counter++;
    }
}

mysqli_close($conn);
echo "Ok.";

?>