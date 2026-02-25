<!-- views/errors/404.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error 404 - Página no encontrada</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .error-container {
            max-width: 600px;
            margin: 6rem auto;
            text-align: center;
            background: var(--color-white);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .error-container h1 {
            font-size: 4rem;
            color: var(--color-secondary);
            margin-bottom: 1rem;
        }

        .error-container p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: var(--color-primary);
        }

        .error-container a {
            display: inline-block;
            padding: 12px 20px;
            background-color: var(--color-primary);
            color: var(--color-white);
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .error-container a:hover {
            background-color: var(--color-secondary);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <p>La página que buscas no existe o fue movida.</p>
        <a href="index.php?page=home">Volver al inicio</a>
    </div>
</body>
</html>
