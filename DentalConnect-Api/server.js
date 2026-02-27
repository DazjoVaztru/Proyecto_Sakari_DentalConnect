require('dotenv').config();
const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const { sequelize, testConnection } = require('./src/config/database');

const app = express();
const PORT = process.env.PORT || 4000;

// 1. Middlewares (Configuración Global)
app.use(helmet()); // Añade cabeceras de seguridad HTTP
app.use(cors()); // Permite peticiones desde otros dominios (como React/Laravel)
app.use(express.json()); // Permite recibir datos JSON en el cuerpo de las peticiones

// 2. Rutas
const authRoutes = require('./src/routes/auth.routes');
app.use('/api/auth', authRoutes);

app.get('/api/status', (req, res) => {
    res.json({ status: "API Online", version: "1.0.0" });
});

// 3. Inicialización del Sistema
const startServer = async () => {
    try {
        // A) Verificar conexión con la BD
        await testConnection();

        // B) Sincronización de Modelos
        // await sequelize.sync({ force: false });
        // console.log('Tablas sincronizadas correctamente.');

        // C) Levantar el servidor Express
        app.listen(PORT, () => {
            console.log(`Servidor corriendo en http://localhost:${PORT}`);
        });

    } catch (error) {
        console.error('Error fatal al iniciar el servicio:', error);
    }
};

startServer();
