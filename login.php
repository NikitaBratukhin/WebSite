<?php

// Включаем файл утилит
require_once 'utilities.php';

// Загружаем данные о людях из JSON
$people = json_decode(file_get_contents("people.json"), true);

// Подключаемся к базе данных
$connection = connectToDB();

// Получаем информацию о клиентах
$get_clients = $connection->query("SELECT name, password FROM clients");

// Инициализируем массив для хранения клиентов
$clients = [];

// Функция для получения информации о клиентах и заполнения массива
$clients = getInfo($clients, $get_clients);

// Проверяем метод запроса
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Если метод GET, прерываем выполнение с сообщением
        die('not today');
        break;
    case 'POST':
        // Если метод POST, обрабатываем данные формы
        $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // Проверяем наличие введенного имени пользователя в массиве клиентов
        $clientFound = false;
        foreach ($clients as $client) {
            if ($name === $client['name']) {
                $clientFound = true;
                // Если имя пользователя найдено, проверяем пароль
                if ($password === $client['password']) {
                    // Если пароль верный, устанавливаем куку с именем пользователя и перенаправляем на главную страницу
                    setcookie('username', $name, time() + 2400);
                    file_put_contents('users_text/' . $name . '.txt', json_encode(['username' => $name, 'password' => $password]));
                    header('Location: index.php');
                    die();
                } else {
                    // Если пароль неверный, перенаправляем на главную страницу с сообщением об ошибке
                    header('Location: index.php?error=Wrong password');
                    die();
                }
            }
        }
        // Если имя пользователя не найдено, перенаправляем на главную страницу с сообщением об ошибке
        if (!$clientFound) {
            header('Location: index.php?error=Wrong user name');
            die();
        }
        break;
}

?>