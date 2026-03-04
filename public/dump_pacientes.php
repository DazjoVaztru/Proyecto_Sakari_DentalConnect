<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=dental_connect_db', 'root', '');
$pacientes = $pdo->query('SELECT id_paciente, nombre FROM pacientes')->fetchAll(PDO::FETCH_ASSOC);
file_put_contents('pacientes_dump.txt', print_r($pacientes, true));
echo "Pacientes ubicados.\n";
