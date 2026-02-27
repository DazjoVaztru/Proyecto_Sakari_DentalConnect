<?php
header('Content-Type: application/json');
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=dental_connect_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT id_paciente, nombre, apellido_paterno FROM pacientes WHERE id_paciente IN (3, 4, 5, 6, 7, 8)");
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT id_cita, id_paciente, id_servicio, fecha_hora_inicio, estado_cita FROM citas ORDER BY fecha_hora_inicio DESC LIMIT 20");
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'citas' => $citas, 'pacientes' => $pacientes], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
