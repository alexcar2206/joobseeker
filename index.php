<?php


if(isset($_POST['service'])){

    //echo 'ENTRO AQUÍ';

    /*$servername = "localhost";
    $username = "root";
    $password = "";
    $database = "empresasdb";
    */

    $servername = "192.168.1.34";
    $username = "admin";
    $password = "#Covid19_2023";
    $database = "empresasdb";




    $dbc = mysqli_connect($servername, $username, $password);


    if ($dbc = mysqli_connect('localhost', 'root')) {
        //echo 'conexión exitosa';

    }else{
        echo 'fallo en la conexión';
    }

    
    //Seleccionamos la base de datos
    if (mysqli_select_db($dbc,'empresasdb')) {
        //echo 'conexión a base de datos exitosa';
    }else{
        echo 'fallo en la conexiona la base de datos';
    }

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }






    $service = $_POST['service'];
    

    switch ($service) {


        case 'GET':

           // echo 'ENTRO EN GET';

            $order = $_POST['order'];
            $petition = $_POST['petition'];

            switch($petition){

                case 'ALL':
                   // echo 'ENTRO EN ALL';

                    $sql = "SELECT * FROM empresas order by Date_send $order";
                    break;

                case 'SEARCH':

                    $nombre = $_POST['nombre'];
                    $sql = "SELECT * FROM empresas WHERE Nombre LIKE '$nombre%' OR Nombre LIKE '%$nombre%' ORDER BY Date_send $order";
                    break;    
            }


            /*
            // Ejecuta la consulta
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Inicializa un arreglo para almacenar los resultados
                $data = array();

                // Recorre las filas y agrega los resultados al arreglo
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                // Devuelve la respuesta como JSON
                header('Content-Type: application/json');
                echo json_encode($data);
         
            } else {
                echo "NORESULTS";
            }   
            */    


            

            break;    
           
        
        case 'SEND':

            $nombre =  $_POST['value'];
            $enviado = "YES";
            $aceptado = "EN ESPERA";
            $fechaActual = date("Y-m-d");


            $sql = "INSERT INTO empresas (`Nombre`, `Enviado`, `Aceptado`, `Date_send`) VALUES ('$nombre', '$enviado', '$aceptado', '$fechaActual')";

            $result = $conn->query($sql);

            // Verificar si la consulta se ejecutó correctamente
            if ($result) {

                $order = $_POST['order'];
                $sql = "SELECT * FROM empresas order by Date_send $order";
            } else {
                echo "Error al insertar el registro: " . mysql_error(); // Si estás utilizando MySQL original (obsoleto)
            }



            
            break; 
            

        case 'DELETE':

            $ids = $_POST['ids'];
            $idArray = explode(",", $ids); // Divide la cadena en un array de IDs

            if (is_array($idArray) && !empty($idArray)) {
                foreach ($idArray as $id) {
                    $sql = "DELETE FROM empresas WHERE id = $id";
                    $result = $conn->query($sql);

                    if ($result) {
                        
                    } else {
                        echo "Error al eliminar el registro: " . $conn->error;
                    }
                }
            } else {
                echo "Error al dividir la cadena de IDs o la lista de IDs está vacía.";
            }


            $order = $_POST['order'];
            $sql = "SELECT * FROM empresas order by Date_send $order";

            break;    
        
        default:
            # code...
            break;
 
    }

	
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Inicializa un arreglo para almacenar los resultados
        $data = array();

        // Recorre las filas y agrega los resultados al arreglo
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Devuelve la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($data);
         
    } else {
        echo "NORESULTS";
    }       
    

    // Cerrar la conexión
    $conn->close();
	
}else{
	
	$response = 'Error: Se esperaba una solicitud POST';
    echo $response;
	
}

?>