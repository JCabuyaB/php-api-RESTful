<?php

require_once 'models/courses.php';
require_once 'models/clients.php';

class Courses
{
    public function index($page = null)
    {

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $clientModel = new ClientsModel();
            $flag_data = [
                'client_id' => base64_encode($_SERVER['PHP_AUTH_USER']),
                'secret_key' => base64_encode($_SERVER['PHP_AUTH_PW'])
            ];

            if ($clientModel->credentials($flag_data)) {
                $courseModel = new CoursesModel();
                if ($page !== null && $page > 0) {
                    $cantidad = 10;
                    $inicio = ($page - 1) * 10;

                    $result = $courseModel->index($page, $inicio, $cantidad);
                } else {
                    $result = $courseModel->index();
                }

                $response = [
                    'status' => 200,
                    'detalle' => $result
                ];

                echo json_encode($response);
            } else {
                $json = [
                    'detalle' => 'credenciales invalidas'
                ];

                echo json_encode($json, true);
            }
        }
    }

    public function create($data)
    {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $clientModel = new ClientsModel();
            $flag_data = [
                'client_id' => base64_encode($_SERVER['PHP_AUTH_USER']),
                'secret_key' => base64_encode($_SERVER['PHP_AUTH_PW'])
            ];

            if ($clientModel->credentials($flag_data)) {
                // validar
                $errors = [];

                if (isset($data['titulo']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['titulo'])) {
                    $errors['titulo'] = 'El titulo no es valido';
                }
                if (isset($data['descripcion']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['descripcion'])) {
                    $errors['descripcion'] = 'La descripcion no es valido';
                }
                if (isset($data['instructor']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['instructor'])) {
                    $errors['instructor'] = 'El instructor no es valido';
                }
                if (isset($data['imagen']) && !filter_var($data['imagen'], FILTER_VALIDATE_URL)) {
                    $errors['imagen'] = 'La imagen no es valido';
                }
                if (isset($data['precio']) && !is_numeric($data['precio'])) {
                    $errors['precio'] = 'El precio no es valido';
                }

                if (count($errors) > 0) {
                    echo json_encode($errors);
                } else {
                    $courseModel = new CoursesModel();

                    if ($courseModel->insert($data)) {
                        $json = [
                            'status' => 200,
                            'detalle' => 'Se insertó el curso'
                        ];
                    } else {
                        $json = [
                            'status' => 404,
                            'detalle' => 'Error al insertar'
                        ];
                    }
                    echo json_encode($json, true);
                    return;
                }
            } else {
                $json = [
                    'status' => 400,
                    'detalle' => 'credenciales invalidas'
                ];

                echo json_encode($json, true);
                return;
            }
        }
    }

    public function show($id)
    {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $clientModel = new ClientsModel();
            $flag_data = [
                'client_id' => base64_encode($_SERVER['PHP_AUTH_USER']),
                'secret_key' => base64_encode($_SERVER['PHP_AUTH_PW'])
            ];

            if ($clientModel->credentials($flag_data)) {
                $courseModel = new CoursesModel();
                $curso = $courseModel->show($id);

                $json = [];
                if ($curso) {
                    $json = [
                        'status' => 200,
                        'detalle' => $curso
                    ];
                } else {
                    $json = [
                        'status' => 404,
                        'detalle' => $curso
                    ];
                }

                echo json_encode($json, true);
                return;
            } else {
                $json = [
                    'status' => 400,
                    'detalle' => 'credenciales invalidas'
                ];

                echo json_encode($json, true);
                return;
            }
        }
    }

    public function update($id, $data)
    {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $clientModel = new ClientsModel();
            $flag_data = [
                'client_id' => base64_encode($_SERVER['PHP_AUTH_USER']),
                'secret_key' => base64_encode($_SERVER['PHP_AUTH_PW'])
            ];

            if ($clientModel->credentials($flag_data)) {

                // validar
                $errors = [];

                if (isset($data['titulo']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['titulo'])) {
                    $errors['titulo'] = 'El titulo no es valido';
                }
                if (isset($data['descripcion']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['descripcion'])) {
                    $errors['descripcion'] = 'La descripcion no es valido';
                }
                if (isset($data['instructor']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['instructor'])) {
                    $errors['instructor'] = 'El instructor no es valido';
                }
                if (isset($data['imagen']) && !filter_var($data['imagen'], FILTER_VALIDATE_URL)) {
                    $errors['imagen'] = 'La imagen no es valido';
                }
                if (isset($data['precio']) && !is_numeric($data['precio'])) {
                    $errors['precio'] = 'El precio no es valido';
                }

                $json = [];
                if (count($errors) > 0) {
                    echo json_encode($errors);
                } else {
                    $validated_data = [
                        'id' => $id,
                        'titulo' => $data['titulo'],
                        'descripcion' => $data['descripcion'],
                        'instructor' => $data['instructor'],
                        'imagen' => $data['imagen'],
                        'precio' => $data['precio']
                    ];

                    $courseModel = new CoursesModel();
                    if ($courseModel->update($validated_data)) {
                        $json = [
                            'status' => 200,
                            'detalle' => 'Se actualizó el curso'
                        ];
                    } else {
                        $json = [
                            'status' => 404,
                            'detalle' => 'No se completó la solicitud'
                        ];
                    }

                    echo json_encode($json, true);
                    return;
                }
            } else {
                $json = [
                    'status' => 400,
                    'detalle' => 'credenciales invalidas'
                ];

                echo json_encode($json, true);
                return;
            }
        }
    }

    public function delete($id)
    {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $clientModel = new ClientsModel();
            $flag_data = [
                'client_id' => base64_encode($_SERVER['PHP_AUTH_USER']),
                'secret_key' => base64_encode($_SERVER['PHP_AUTH_PW'])
            ];

            $json = [];

            if ($clientModel->credentials($flag_data)) {
                $courseModel = new CoursesModel();
                if ($courseModel->delete($id)) {
                    $json = [
                        'status' => 200,
                        'detalle' => 'Curso eliminado'
                    ];
                } else {
                    // al no realizar la busqueda del curso se asume que no se encontró (no recomendado)
                    $json = [
                        'status' => 200,
                        'detalle' => 'El curso a eliminar no existe'
                    ];
                }
                echo json_encode($json, true);
                return;
            } else {
                $json = [
                    'status' => 400,
                    'detalle' => 'credenciales invalidas'
                ];

                echo json_encode($json, true);
                return;
            }
        }
    }
}