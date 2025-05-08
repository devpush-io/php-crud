<?php

require __DIR__ . '/dbConnect.php';

$sql = 'SELECT * FROM customers';
$pdoStatement = $pdo->prepare($sql);

$pdoStatement->execute();
$customers = $pdoStatement->fetchAll(PDO::FETCH_ASSOC); ?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD System</title>
        <!-- Centered viewport -->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.colors.min.css"
        />
    </head>
    <body>
        <main>
            <h1>Customers</h1>
            <?php
            if (isset($_GET['msg'])) { ?>
                <article class="pico-background-green-500 pico-color-white-50">
                    <div><?= $_GET['msg'] ?></div>
                </article>
            <?php
            } ?>
            <button>
                <a href="/form.php" class="pico-color-blue-50">Add Customer</a>
            </button>
            <table style="margin-top: 20px">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Firstname</th>
                        <th scope="col">Surname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($customers):
                        foreach ($customers as $customer): ?>
                            <tr>
                                <th><?= $customer['id'] ?></th>
                                <th><?= $customer['firstname'] ?></th>
                                <td><?= $customer['lastname'] ?></td>
                                <td><?= $customer['email'] ?></td>
                                <td><?= $customer['phone'] ?></td>
                                <td>
                                    <a href="/form.php?id=<?= $customer['id'] ?>">Edit</a>
                                    <a href="/delete.php?id=<?= $customer['id'] ?>">Delete</a>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else: ?>
                        <tr>
                            <th colspan="5">No customers added</th>
                        </tr>
                    <?php
                    endif; ?>
                </tbody>
            </table>
        </main>
    </body>
</html>