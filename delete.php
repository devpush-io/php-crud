<?php

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

if ($id > 0) {
    require __DIR__ . '/dbConnect.php';
    
    $sql = 'DELETE FROM customers WHERE id = ?';
    $pdoStatement = $pdo->prepare($sql);
    $pdoStatement->execute([$id]);

    header('Location: /?msg=Customer has been deleted');
    exit;
}
