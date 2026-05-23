<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #9a3412;">Nuevo mensaje recibido desde la web</h2>
    <p><strong>Nombre:</strong> {{ $data['nombre'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Asunto:</strong> {{ $data['asunto'] }}</p>
    <p><strong>Mensaje:</strong></p>
    <div style="background: #fdf2e9; padding: 15px; border-radius: 8px; border-left: 4px solid #9a3412;">
        {{ $data['mensaje'] }}
    </div>
    <hr>
    <p style="font-size: 0.8rem; color: #777;">Este mensaje fue enviado automáticamente desde el formulario de contacto de Woof.</p>
</body>
</html>
