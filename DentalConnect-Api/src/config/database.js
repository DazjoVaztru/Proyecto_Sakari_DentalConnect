const { Sequelize } = require('sequelize');
require('dotenv').config();

// Crear la instancia de conexión
const sequelize = new Sequelize(
    process.env.DB_NAME,
    process.env.DB_USER,
    process.env.DB_PASS,
    {
        host: process.env.DB_HOST,
        dialect: process.env.DB_DIALECT || 'mysql',
        logging: false // Cambiar a true para ver las consultas SQL en la consola
    }
);

// Función asíncrona para verificar la conexión
const testConnection = async () => {
    try {
        await sequelize.authenticate();
        console.log('Conexión establecida con la Base de Datos.');
    } catch (error) {
        console.error('Error al conectar con la Base de Datos:', error);
    }
};

// Exportamos la instancia y la función de prueba
module.exports = { sequelize, testConnection };
