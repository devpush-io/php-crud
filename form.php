<?php

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form fields
    $firstname = $_POST['firstname'];
    $lastname  = $_POST['lastname'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];

    // Do validation checks
    $errors = [];

    if (empty($firstname)) {
        $errors[] = 'Firstname is required';
        $firstnameInvalid = true;
    }
    if (empty($lastname)) {
        $errors[] = 'Lastname is required';
        $lastnameInvalid = true;
    }
    if (empty($email)) {
        $errors[] = 'Email is required';
        $emailInvalid = true;
    }
    if (empty($phone)) {
        $errors[] = 'Phone is required';
        $phoneInvalid = true;
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = 'Email address is invalid';
        $emailInvalid = true;
    }
    if (strlen($phone) != 10) {
        $errors[] = 'Phone number must be made up of 10 numbers';
        $phoneInvalid = true;
    }

    if (empty($errors)) {
        // Do database checks
        require __DIR__ . '/dbConnect.php';

        // Check if email address exists
        $sql = 'SELECT * FROM customers WHERE email = ?';
        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute([$email]);
        $result = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['id'] != $id) {
            // Email already exists, show error
            $errors[] = 'Email address has already been used for a customer';
        } elseif ($id > 0) {
            $sql = 'UPDATE customers
                    SET firstname = ?, lastname = ?, email = ?, phone = ?
                    WHERE id = ?';
            $pdoStatement = $pdo->prepare($sql);

            $result = $pdoStatement->execute([
                $firstname,
                $lastname,
                $email,
                $phone,
                $id
            ]);

            if ($result) {
                header('Location: /?msg=Existing customer has been updated');
                exit;
            } else {
                $errors[] = 'Error registering updating customer';
            }
        } else {
            $sql = 'INSERT INTO customers (firstname, lastname, email, phone) 
                    VALUES (?, ?, ?, ?)';
            $pdoStatement = $pdo->prepare($sql);

            $result = $pdoStatement->execute([$firstname, $lastname, $email, $phone]);

            if ($result) {
                header('Location: /?msg=New customer has been created');
                exit;
            } else {
                $errors[] = 'Error registering new customer';
            }
        }
    }
} else if ($id > 0) {
    require __DIR__ . '/dbConnect.php';

    // Get existing customer
    $sql = 'SELECT * FROM customers WHERE id = ?';
    $pdoStatement = $pdo->prepare($sql);

    $pdoStatement->execute([$_GET['id']]);
    $customer = $pdoStatement->fetch(PDO::FETCH_ASSOC);

    // Get customer fields
    $firstname = $customer['firstname'];
    $lastname  = $customer['lastname'];
    $email     = $customer['email'];
    $phone     = $customer['phone'];
} ?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer form</title>
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
            <h1><?= $id > 0 ? 'Edit Customer' : 'Create Customer' ?></h1>
            <button>
                <a href="/" class="pico-color-blue-50">Go back</a>
            </button>
            <div style="max-width: 600px; margin-top: 40px">
                <?php
                if (empty($errors) == false) { ?>
                    <article class="pico-background-red-500 pico-color-white-50">
                        <?php
                        foreach ($errors as $error) { ?>
                            <div><?= $error ?></div>
                        <?php
                        } ?>
                    </article>
                <?php
                } ?>
                <form action="/form.php" method="post">
                    <input type="hidden" name="id" value="<?= $id ?>" />
                    <div>
                        <label for="firstname">Firstname</label>
                        <input
                            type="text"
                            id="firstname"
                            name="firstname"
                            value="<?= $firstname ?? '' ?>" />
                    </div>
                    <div>
                        <label for="lastname">Lastname</label>
                        <input
                            type="text"
                            id="lastname"
                            name="lastname"
                            value="<?= $lastname ?? '' ?>" />
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            value="<?= $email ?? '' ?>" />
                    </div>
                    <div>
                        <label for="phone">Phone</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="<?= $phone ?? '' ?>" />
                    </div>
                    <input type="submit" value="Submit" />
                </form>
            </div>
        </main>
    </body>
</html>