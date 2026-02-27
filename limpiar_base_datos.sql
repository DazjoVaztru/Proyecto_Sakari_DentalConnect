SET FOREIGN_KEY_CHECKS = 0;

-- 1. Limpiar historiales y caja
TRUNCATE TABLE `ingresos_caja`;
TRUNCATE TABLE `seguimiento_clinico`;
TRUNCATE TABLE `citas_historico`;
TRUNCATE TABLE `odontograma`;
TRUNCATE TABLE `signos_vitales`;
TRUNCATE TABLE `evolucion_tratamiento`;

-- 2. Limpiar citas
TRUNCATE TABLE `citas`;

-- 3. Limpiar catálogos de pacientes
TRUNCATE TABLE `paciente_alergias`;
TRUNCATE TABLE `paciente_enfermedades`;

-- 4. Limpiar Pacientes principales
TRUNCATE TABLE `pacientes`;

-- 5. Eliminar cuentas de acceso de los pacientes
DELETE FROM `users` WHERE `rol` = 'paciente';

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Limpieza completada con exito' as status;
