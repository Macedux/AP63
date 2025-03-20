<?php
require_once("class/BiblioManager.php");

$manager = new BiblioManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $title = $_POST['title'] ?? '';
                $author = $_POST['author'] ?? '';
                $year = (int)($_POST['year'] ?? 0);
                $var = $_POST['var'];

                if (!empty($title) && !empty($author) && !empty($var)) {
                    $manager->addPublication($title, $author, $year, $var);
                    echo "<p>Publicación '$title' agregado correctamente.</p>";
                }else{
                    echo "<p>Por favor, completa todos los campos correctamente.</p>";
                }
                break;

            case 'delete':
                $index = (int)($_POST['index'] ?? -1);
                $var = ($_POST['var']);
                if ($index >= 0) {
                    if (is_numeric($var)){
                        $manager->deleteBook($index);
                        echo "<p>Libro eliminado correctamente.</p>";
                    }else{
                        $manager->deleteMagazine($index);
                        echo "<p>Revista eliminado correctamente.</p>";
                    }
                    
                }
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Biblioteca</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <h1>Gestor de Biblioteca</h1>

    <h2>Añadir Publicación (Libro o Revista)</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add">

        <label for="title">Título:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="author">Autor:</label>
        <input type="text" id="author" name="author" required><br>

        <label for="year">Año:</label>
        <input type="number" id="year" name="year" min="0" required><br>

        <label for="var">Páginas de libro o Tipo de revista:</label>
        <input type="text" id="var" name="var" required><br>

        <button type="submit">Añadir Publicación</button>
    </form>

    <h2>Listado de Libros</h2>
    <?php if (count($manager->getBooks()) == 0): ?>
        <p>No hay libros registrados.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($manager->getBooks() as $index => $book): ?>
                <li>
                    <?php echo "Título: " . $book->getTitle() . ", Autor: " . $book->getAuthor() . ", Año: " . $book->getYear() . ", Páginas: " . $book->getPages(); ?>
                    <form method="POST" class="delete-form">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="hidden" name="var" value="<?php echo $book->getPages(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <h2>Listado de Revistas</h2>
    <?php if (count($manager->getMagazines()) == 0): ?>
        <p>No hay revistas registrados.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($manager->getMagazines() as $index => $magazine): ?>
                <li>
                    <?php echo "Título: " . $magazine->getTitle() . ", Autor: " . $magazine->getAuthor() . ", Año: " . $magazine->getYear() . ", Tipo: " . $magazine->getType(); ?>
                    <form method="POST" class="delete-form">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="hidden" name="var" value="<?php echo $magazine->getType(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>





