const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');
const User = require('./models/user');

const app = express();
const port = process.env.PORT || 5000;

app.use(cors());
app.use(bodyParser.json());


// Conexión a MongoDB
mongoose.connect('mongodb+srv://miltonsabogalmintic:N5IbGEuFbkFbarX3@cluster0.qmiea.mongodb.net/', { useNewUrlParser: true, useUnifiedTopology: true });

// Ruta para registrar un nuevo usuario (POST /register)
app.post('/register', async (req, res) => {
    try {
        const newUser = new User({
            email: req.body.email,
            password: req.body.password // Considera hashear la contraseña
        });
        const savedUser = await newUser.save();
        res.json(savedUser);
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

// Ruta para iniciar sesión (POST /login)
app.post('/login', async (req, res) => {
    try {
        const user = await User.findOne({ email: req.body.email });
        if (!user) {
            return res.status(404).json({
                message: 'Usuario no encontrado'
            });
        }
        // Comparar contraseñas (aquí deberías usar una función de comparación segura)
        // Si las contraseñas coinciden, enviar un token o sesión
        res.json({ message: 'Inicio de sesión exitoso' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.listen(port, () => {
    console.log(`Servidor iniciado en el puerto ${port}`);
});