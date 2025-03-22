<?php
require_once("class/BiblioManager.php");

$manager = new BiblioManager();
$books = [
    ["El Quijote", "Miguel de Cervantes", 1605],
    ["Cien años de soledad", "Gabriel García Márquez", 1967],
    ["1984", "George Orwell", 1949],
    ["Moby Dick", "Herman Melville", 1851],
    ["Orgullo y prejuicio", "Jane Austen", 1813],
    ["Crónica de una muerte anunciada", "Gabriel García Márquez", 1981],
    ["El gran Gatsby", "F. Scott Fitzgerald", 1925],
    ["El viaje al centro de la Tierra", "Julio Verne", 1864],
    ["Rayuela", "Julio Cortázar", 1963],
    ["El túnel", "Ernesto Sabato", 1948],
    ["Ficciones", "Jorge Luis Borges", 1944],
    ["El Aleph", "Jorge Luis Borges", 1949],
    ["Los detectives salvajes", "Roberto Bolaño", 1998],
    ["La sombra del viento", "Carlos Ruiz Zafón", 2001],
    ["El amor en los tiempos del cólera", "Gabriel García Márquez", 1985],
    ["La casa de los espíritus", "Isabel Allende", 1982],
    ["Memoria de mis putas tristes", "Gabriel García Márquez", 2004],
    ["Ensayo sobre la ceguera", "José Saramago", 1995],
    ["El beso de la mujer araña", "Manuel Puig", 1976],
];

$totalBooks = count($books);
$booksPage = 4; // Libros por página
$totalPages = ceil($totalBooks / $booksPage); // ceil redondea hacia arriba
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Página por defecto
$startIndex = ($page - 1) * $booksPage; // Índice de inicio
$booksToShow = array_slice($books, $startIndex, $booksPage); // Libros a mostrar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $title = $_POST['title'] ?? '';
                $author = $_POST['author'] ?? '';
                $year = (int) ($_POST['year'] ?? 0);
                $var = $_POST['var'] ?? '';

                if (!empty($title) && !empty($author) && !empty($var)) {
                    $manager->addPublication($title, $author, $year, $var);
                    echo "<p>Publicación '$title' agregada correctamente.</p>";
                } else {
                    echo "<p>Por favor, completa todos los campos correctamente.</p>";
                }
                break;

            case 'delete':
                $index = (int) ($_POST['index'] ?? -1);
                $var = $_POST['var'] ?? '';

                if ($index >= 0) {
                    if (is_numeric($var)) {
                        $manager->deleteBook($index);
                        echo "<p>Libro eliminado correctamente.</p>";
                    } else {
                        $manager->deleteMagazine($index);
                        echo "<p>Revista eliminada correctamente.</p>";
                    }
                } else {
                    echo "<p>Índice no válido para eliminación.</p>";
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
        <?php if ($totalBooks == 0): ?>
            <p>No hay libros registrados.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($booksToShow as $index => $book): ?>
                    <li>
                        <?php echo "Título: " . $book[0] . ", Autor: " . $book[1] . ", Año: " . $book[2]; ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="index" value="<?php echo $startIndex + $index; ?>">
                            <input type="hidden" name="var" value="Páginas">
                            <button type="submit" name="action" value="delete">Eliminar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div>
                <?php if ($page > 1): ?>
                    <a href="?page=1">
                        <<< /a>
                            <a href="?page=<?php echo $page - 1; ?>">
                                << /a>
                                <?php endif; ?>
                                <span> Página <?php echo $page; ?> de <?php echo $totalPages; ?></span>
                                <?php if ($page < $totalPages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>"> > </a>
                                    <a href="?page=<?php echo $totalPages; ?>"> >> </a>
                                <?php endif; ?>
            </div>
        <?php endif; ?>

        <h2>Listado de Revistas</h2>
        <?php if (count($manager->getMagazines()) == 0): ?>
            <p>No hay revistas registradas.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($manager->getMagazines() as $index => $magazine): ?>
                    <li>
                        <?php echo "Título: " . $magazine->getTitle() . ", Autor: " . $magazine->getAuthor() . ", Año: " . $magazine->getYear() . ", Tipo: " . $magazine->getType(); ?>
                        <form method="POST" class="delete-form" style="display:inline;">
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