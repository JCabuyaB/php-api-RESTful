<?php

require_once 'config/database.php';

class CoursesModel
{
    public function index($page = null, $begin = null, $elements = null)
    {
        try {
            $conexion = Database::getInstance()->getConnection();
            if ($page !== null & $begin !== null && $elements !== null) {
                $stmt = $conexion->prepare("SELECT * FROM cursos LIMIT :inicio, :elementos");
                $stmt->bindParam(':inicio', $begin, PDO::PARAM_INT);
                $stmt->bindParam(':elementos', $elements, PDO::PARAM_INT);
            } else {
                $stmt = $conexion->prepare("SELECT * FROM cursos");
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_CLASS);

            $stmt->closeCursor();

            return $result;
        } catch (PDOException $e) {
            $json = ['detalle' => 'Error en la consulta ' . $e->getMessage()];
            return json_encode($json);
        }

    }

    public function insert($data)
    {
        $conexion = Database::getInstance()->getConnection();

        $stmt = $conexion->prepare('INSERT INTO cursos(titulo, descripcion, instructor, imagen, precio) VALUES (:titulo, :descripcion, :instructor, :imagen, :precio)');

        $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(':instructor', $data['instructor'], PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $data['imagen'], PDO::PARAM_STR);
        $stmt->bindParam(':precio', $data['precio'], PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->rowCount();
        $stmt->closeCursor();

        return $result > 0;
    }

    public function show($id)
    {
        try {
            $conexion = Database::getInstance()->getConnection();

            $stmt = $conexion->prepare('SELECT * FROM cursos WHERE id = :id');

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();


            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS);
            } else {
                $result = 'No se encontro un curso con el id ' . $id;
            }

            $stmt->closeCursor();
            return $result;
        } catch (PDOException $e) {
            $result = ['detalle' => 'No se completo la solicitud ' . $e->getMessage()];
            return $result;
        }
    }

    public function update($data)
    {
        try {
            $conexion = Database::getInstance()->getConnection();

            $stmt = $conexion->prepare('UPDATE cursos SET titulo=:titulo,descripcion=:descripcion,instructor=:instructor,imagen=:imagen,precio=:precio WHERE id = :id');


            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(':instructor', $data['instructor'], PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $data['imagen'], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $data['precio'], PDO::PARAM_STR);

            $stmt->execute();

            $result = $stmt->rowCount();
            $stmt->closeCursor();

            return $result > 0;
        } catch (PDOException $e) {
            return 'Hubo un error ' . $e->getMessage();
        }
    }

    public function delete($id)
    {
        $conexion = Database::getInstance()->getConnection();

        $stmt = $conexion->prepare('DELETE FROM cursos WHERE id = :id');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->rowCount();
        $stmt->closeCursor();

        return $result > 0;
    }
}