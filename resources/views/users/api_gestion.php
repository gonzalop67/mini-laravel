<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=mvc;charset=utf8', 'root', '');

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $_REQUEST['accion'] ?? '';

if ($metodo === 'GET') {
    
    // 1. Listar todos los roles del sistema
    if ($accion === 'listar_roles') {
        $stmt = $pdo->query("SELECT id, name FROM roles ORDER BY name ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }
    
    // 2. Listar usuarios con sus nombres de roles e IDs agrupados por coma (GROUP_CONCAT)
    if ($accion === 'listar_usuarios') {
        $query = "SELECT u.id, u.username, 
                         GROUP_CONCAT(r.name SEPARATOR ', ') as roles,
                         GROUP_CONCAT(r.id SEPARATOR ',') as id_roles
                  FROM usuarios u
                  LEFT JOIN usuario_rol ur ON u.id = ur.usuario_id
                  LEFT JOIN roles r ON ur.rol_id = r.id
                  GROUP BY u.id";
        $stmt = $pdo->query($query);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }
}

if ($metodo === 'POST' && $accion === 'actualizar_roles') {
    $id_usuario = intval($_POST['id_usuario'] ?? 0);
    $roles_seleccionados = $_POST['roles'] ?? []; // Array con los IDs de los checkboxes marcados

    if ($id_usuario === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no válido.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Eliminar los roles previos del usuario para evitar duplicaciones
        $stmtDelete = $pdo->prepare("DELETE FROM usuario_rol WHERE usuario_id = :id_usuario");
        $stmtDelete->execute(['id_usuario' => $id_usuario]);

        // Insertar la nueva selección de roles si el array no está vacío
        if (!empty($roles_seleccionados)) {
            $stmtInsert = $pdo->prepare("INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (:id_usuario, :id_rol)");
            foreach ($roles_seleccionados as $id_rol) {
                $stmtInsert->execute([
                    'usuario_id' => $id_usuario,
                    'rol_id' => intval($id_rol)
                ]);
            }
        }

        $pdo->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
