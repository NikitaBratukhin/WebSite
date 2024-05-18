<?php
// Получаем имя пользователя из cookie
$name = $_COOKIE['username'];

// Устанавливаем директорию для загрузки файлов профилей
$target_dir = "profile_images/";

// Формируем путь к загружаемому файлу
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

// Устанавливаем флаг успешной загрузки
$uploadOk = 1;

// Получаем расширение загружаемого файла
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Проверяем, был ли отправлен файл для загрузки
if (isset($_POST["submit"])) {
    // Проверяем, является ли загружаемый файл изображением
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Проверяем, существует ли уже файл с таким именем
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Проверяем размер файла
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Разрешаем определенные форматы файлов
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Проверяем, была ли установлена ошибка при загрузке файла
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    // Если все в порядке, пытаемся загрузить файл
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Перенаправляем пользователя на главную страницу
        header('Location:index.php');
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>