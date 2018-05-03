<?php



/* Connect to Database etc. */

  $dsn = 'mysql:host=127.0.0.1:8889;dbname=EMC';
  $user = 'root';
  $password = 'root';
try {
 $pdo = new PDO($dsn, $user, $password);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       } catch (PDOException $e) {
     echo 'Connection failed: ' . $e->getMessage();
           die('Sorry, database problem');
       }

/* Perform the Query */

       $query = $_POST['Query'];
       $geojson = array( 'type' => 'FeatureCollection', 'features' => array());
       $sql = "SELECT * FROM geodata WHERE Latitude !='' AND Longitude !='' AND RulerName LIKE ? LIMIT 1000";
       $stmt = $pdo->prepare($sql);
       $stmt->bindParam(1, $query, PDO::PARAM_STR, 12);
       $stmt->execute();
       $results = $stmt->fetchAll();
       foreach ($results as $result) {


/* Turns the Data into a geoJson compaitble format */

           $marker = array(
           'type' => 'Feature',
           'properties' => array(
             'title' => $result['RulerName'],
             'marker-color' => '#f00',
             'marker-size' => 'small'
           ),
           'geometry' => array(
             'type' => 'Point',
             'latitude' => $result['Latitude'],
             'longitude' =>   $result['Longitude']

           )
         );
         array_push($geojson['features'], $marker);


       }

/* Encodes the Array as Json and then Preg_replaces etc. to make it GeoJson compatible */

    $geojson = json_encode($geojson);
    $pattern = '\'"([\\-\\d\\.]+)"\'';
    $replacement = '$1';
    $geojson = preg_replace($pattern, $replacement , $geojson);


?>
