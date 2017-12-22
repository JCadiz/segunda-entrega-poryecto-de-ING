<?php


 include("../connection.php");
 $db = new dbObj();
 $connection =  $db->getConnstring();
 
 $request_method=$_SERVER["REQUEST_METHOD"];



switch($request_method)
 {
 case 'GET':

 if(!empty($_GET["id"]))
 {
 $id=intval($_GET["id"]);
 get_tareas($id);
 }
 else
 {
 get_tareas();
 }
 break;

case 'POST':

insertar_tarea();
break;

case 'PUT':

$id=intval($_GET["id"]);
Actualizar_tarea($id);
break;

case 'DELETE':

$id=intval($_GET["id"]);
Eliminar_tarea($id);
break;

 default:
 header("HTTP/1.0 405 Metodo no permitido");
 break;
 }


function get_tareas()
 {
 global $connection;
 $query="SELECT id, Nombre, Descripcion, Estado, Fecha FROM tareas";
 $response=array();
 $result=mysqli_query($connection, $query);
 while($row=mysqli_fetch_assoc($result))
 {
 $response[]=$row;
 }
 header('Content-Type: application/json');
 echo json_encode($response);
 }

/*
function get_tareas($id=0)
{
 global $connection;
 $query="SELECT id, Nombre, Descripcion, Estado, Fecha FROM tareas";
 if($id != 0)
 {
 $query.=" WHERE id=".$id." LIMIT 1";
 }
 $response=array();
 $result=mysqli_query($connection, $query);
 while($row=mysqli_fetch_assoc($result))
 {
 $response[]=$row;
 }
 header('Content-Type: application/json');
 echo json_encode($response);
}

*/


function insertar_tarea()
 {
 global $connection;
 
 $data = json_decode(file_get_contents('php://input'), true);
 $Nombre='jonathan';
 $Descripcion='entrega de proyecto';
 $Estado=1;
 $Fecha='2017-12-11';
 echo $query="INSERT INTO tareas SET Nombre='".$Nombre."', Descripcion='".$Descripcion."', Estado='".$Estado."', Fecha='".$Fecha."'";
 if(mysqli_query($connection, $query))
 {
 $response=array(
 'status' => 200,
 'status_message' =>'Tarea agregada satisfactoriamente.'
 );
 }
 else
 {
 $response=array(
 'status' => 500,
 'status_message' =>'Error en el servidor.'
 );
 }
 header('Content-Type: application/json');
 echo json_encode($response);
 }


function Actualizar_tarea($id)
 {
 global $connection;
 $post_vars = json_decode(file_get_contents("php://input"),true);
 $Nombre=$post_vars["Nombre"];
 $Descripcion=$post_vars["Descripcion"];
 $Estado=$post_vars["Estado"];
 $Fecha=$post_vars["Fecha"];
 $query="UPDATE tareas SET Nombre='".$Nombre."', Descripcion='".$Descripcion."', Estado='".$Estado."', Fecha='".$Fecha."' WHERE id=".$id;
 $query2 = "SELECT id FROM tareas WHERE id=".$id;
 $stmt = mysqli_query($connection, $query2);

 if (!$stmt){
$response=array(
 'status' => 500,
 'status_message' =>'Error en el servidor.'
 );

} else {
$row = mysqli_fetch_assoc($stmt);
 if ($row > 0){
  mysqli_query($connection, $query);
 
 $response=array(
 'status' => 200,
 'status_message' =>'Modificado.'
 );

}else {
 $response=array(
 'status' => 404,
 'status_message' =>'TODO no encontrado.'
 );

}

}


 header('Content-Type: application/json');
 echo json_encode($response);
 }


function Eliminar_tarea($id)
{
 global $connection;
 $query ="DELETE FROM tareas WHERE id=".$id;
 $query2 = "SELECT id FROM tareas WHERE id=".$id;
 $stmt = mysqli_query($connection, $query2);
 if (!$stmt){
$response=array(
 'status' => 500,
 'status_message' =>'Error en el servidor.'
 );

} else {
$row = mysqli_fetch_assoc($stmt);
 if ($row > 0){
  mysqli_query($connection, $query);
 
 $response=array(
 'status' => 200,
 'status_message' =>'La tarea fue borrada con exito.'
 );

}else {
 $response=array(
 'status' => 404,
 'status_message' =>'TODO no encontrado.'
 );

}

}

 header('Content-Type: application/json');
 echo json_encode($response);
}



?>