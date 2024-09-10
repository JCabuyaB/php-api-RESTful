<?php
require_once 'models/clients.php';

class Clients
{
    public function index($data)
    {
        // modelo
        $clientObj = new ClientsModel();

        // errores
        $errores = [];

        // validar data
        if (isset($data['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['nombre'])) {
            $errores['nombre'] = 'El nombre no es valido';
        }

        if (isset($data['apellidos']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $data['apellidos'])) {
            $errores['apellidos'] = 'El apellido no es valido';
        }

        if (isset($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = 'El correo no es valido';
        } else {
            $exists = $clientObj->verificar($data['correo']);
            if ($exists) {
                $errores['correo'] = 'El correo ya está registrado';
            }
        }



        if (count($errores) > 0) {
            $errores['status'] = 400;
            echo json_encode($errores, true);
        } else {
            // generar id y clave del client
            $client_id = hash('sha256', $data['nombre'] . $data['apellidos'] . $data['correo'], false);
            $secret_key = hash('sha256', $data['correo'] . $data['apellidos'] . $data['nombre'], false);

            $allData = [
                'nombre' => $data['nombre'],
                'apellidos' => $data['apellidos'],
                'correo' => $data['correo'],
                'client_id' => $client_id,
                'secret_key' => $secret_key,
            ];

            $result = $clientObj->insert($allData);

            if ($result) {
                $json = [
                    'status' => 200,
                    'detalle' => 'Usuario registrado con exito'
                ];

                echo json_encode($json, true);
                return;
            } else {
                $json = [
                    'status' => 400,
                    'detalle' => 'No se completo la petición'
                ];

                echo json_encode($json, true);
                return;
            }
        }
    }
}