<?php

require_once 'config/database.php';

class ClientsModel
{

    public function insert($data)
    {
        // sentencia
        $conexion = Database::getInstance()->getConnection();
        $stmt = $conexion->prepare('INSERT INTO clientes(primer_nombre, primer_apellido, email, id_cliente, llave_secreta) VALUES (:nombre, :apellidos, :correo, :id_cliente, :llave_secreta)');

        // parametros
        $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $data['apellidos'], PDO::PARAM_STR);
        $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
        $stmt->bindParam(':id_cliente', $data['client_id'], PDO::PARAM_STR);
        $stmt->bindParam(':llave_secreta', $data['secret_key'], PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->rowCount();
        $stmt->closeCursor();

        return $result > 0;
    }

    public function verificar($email)
    {
        $conexion = Database::getInstance()->getConnection();

        $stmt = $conexion->prepare('SELECT * FROM clientes WHERE email = :correo');
        $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->rowCount();
        $stmt->closeCursor();

        return $result > 0;
    }

    // verificar credenciales del usuario
    public function credentials($credentials)
    {
        $conexion = Database::getInstance()->getConnection();
        $id = base64_decode($credentials['client_id']);
        $pw = base64_decode($credentials['secret_key']);

        $stmt = $conexion->prepare('SELECT * from clientes WHERE id_cliente = :client_id and llave_secreta = :secret_key');

        $stmt->bindParam(':client_id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':secret_key', $pw, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch();

        $stmt->closeCursor();

        return $result !== false;

    }
}