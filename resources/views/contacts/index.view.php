<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contactos</title>
</head>
<body>
    <h1>Lista de Contactos</h1>
    <ul>
        <?php foreach ($contacts as $contact): ?>
            <li><?php echo $contact['name']; ?> - <?php echo $contact['email']; ?></li>
        <?php endforeach; ?>
    </ul>
    <h2>Agregar Contacto</h2>
    <form action="<?= BASE_URL ?>/contacts" method="post">
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Agregar</button>
    </form>
    <a href="<?= BASE_URL ?>">Inicio</a>
</body>
</html>