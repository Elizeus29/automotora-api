<?php



include "config.php";
include "utils.php";


$dbConn =  connect($db);

header('Access-Control-Allow-Origin: *');

/*
  listar todos los vehiculo o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['patente']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM vehiculo where patente=:patente");
      $sql->bindValue(':patente', $_GET['patente']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM vehiculo");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}

// Crear un nuevo post
if ($_POST['METHOD'] == 'POST')
{      
    unset($_POST['METHOD']);
    $patente=$_POST['patente'];
    $color=$_POST['color'];
    $anio=$_POST['anio'];
    $precio=$_POST['precio'];
    $kilometros=$_POST['kilometros'];
    $id_marca=$_POST['id_marca'];
    $id_modelo=$_POST['id_modelo'];
	
    $sql = "INSERT INTO vehiculo
          (patente, color, anio, precio, kilometros, id_marca, id_modelo)
          VALUES
          ('$patente', '$color', '$anio', '$precio', '$kilometros', '$id_marca', '$id_modelo')";
    $statement = $dbConn->prepare($sql);
    $statement->execute();
      echo json_encode($statement);
	header("HTTP/1.1 200 OK");
      exit();
	 
}

//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['patente'];
  $statement = $dbConn->prepare("DELETE FROM vehiculo where patente=:patente");
  $statement->bindValue(':patente', $id);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}

//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['patente'];
    $fields = getParams($input);

    $sql = "
          UPDATE vehiculo
          SET $fields
          WHERE patente='$postId'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}


//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>
