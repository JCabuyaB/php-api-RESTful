<?php

$routes = explode('/', $_SERVER['REQUEST_URI']);

if (count(array_filter($routes)) == 1) {
    $json = array(
        "detalle" => "no encontrado"
    );
    echo json_encode($json, true);
    return;
} else {
    switch (count(array_filter($routes))) {
        case 2:
            if ($routes[2] == 'cursos') {
                if (isset($_SERVER['REQUEST_METHOD'])) {
                    switch ($_SERVER['REQUEST_METHOD']) {
                        case 'GET':
                            $cursos = new Courses();
                            $cursos->index();
                            break;
                        case 'POST':
                            $datos = [
                                'titulo' => $_POST['titulo'] ?? '',
                                'descripcion' => $_POST['descripcion'] ?? '',
                                'instructor' => $_POST['instructor'] ?? '',
                                'imagen' => $_POST['imagen'] ?? '',
                                'precio' => $_POST['precio'] ?? ''
                            ];

                            $cursos = new Courses();
                            $cursos->create($datos);
                            break;
                    }
                }
            } elseif ($routes[2] == 'registro') {
                if (isset($_SERVER['REQUEST_METHOD'])) {
                    switch ($_SERVER['REQUEST_METHOD']) {
                        case 'POST':
                            $datos = [
                                'nombre' => $_POST['nombre'] ?? '',
                                'apellidos' => $_POST['apellidos'] ?? '',
                                'correo' => $_POST['correo'] ?? ''
                            ];

                            $cliente = new Clients();
                            $cliente->index($datos);
                            break;
                    }
                }
            }
            break;

        case 3:
            if ($routes[2] == 'cursos' && is_numeric($routes[3])) {
                if (isset($_SERVER['REQUEST_METHOD'])) {
                    switch ($_SERVER['REQUEST_METHOD']) {
                        case 'GET':
                            $cursos = new Courses();
                            $cursos->show($routes[3]);
                            break;
                        case 'PUT':
                            $data = [];
                            parse_str(file_get_contents('php://input'), $data);

                            $cursos = new Courses();
                            $cursos->update($routes[3], $data);
                            break;
                        case 'DELETE':
                            $cursos = new Courses();
                            $cursos->delete($routes[3]);
                            break;
                    }
                }
            } elseif ($routes[2] == 'cursos' && isset($_GET['page'])) {
                if(is_numeric($_GET['page']) && $_SERVER['REQUEST_METHOD'] == 'GET'){
                    $page = $_GET['page'];
                    $courseController = new Courses();
                    $courseController->index($page);
                }
            }
            break;
    }
}


