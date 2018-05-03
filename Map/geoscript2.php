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

/* Performs the Query */

       $data_jsons = array();
       $query = $_POST['Query'];
       $sql = "SELECT Latitude , Longitude FROM geodata WHERE Latitude !='' AND Longitude !='' AND RulerName LIKE ? LIMIT 1000";
       $stmt = $pdo->prepare($sql);
       $stmt->bindParam(1, $query, PDO::PARAM_STR, 12);
       $stmt->execute();
       $results = $stmt->fetchAll();
       foreach ($results as $result ) {

/* Turns each record into the format the Google Heat map API recognises for
the Heat Map Layer... not sure why it doesn't like geoJSON */


      $point = "new google.maps.LatLng( " . $result['Latitude'] . " , "  . $result['Longitude'] . " ), " ;


    array_push($data_jsons, $point);
       }




?>
