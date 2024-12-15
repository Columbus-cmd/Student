<?php

session_start();
require_once 'db.php';
$approved_list = ['png', 'jpg'];

$file_name = $_FILES['file']['name'];
$fileName = [];

$file_extension = [];

    foreach ($file_name as $item_file) {
        $file_extension[] = pathinfo($item_file, PATHINFO_EXTENSION);
    }

if (empty($file_name)) {
    $_SESSION['error'] = 'Загрузите файл';
    header("Location: /");
    exit;
}

for($i=0;$i <count($file_extension);$i++){
    if (!in_array(($file_extension[$i]), $approved_list)) {

        $_SESSION['error'] = 'Можно загрузить только jpg,png';
        header("Location: /");
        exit;
    }
}


for($i=0;$i <count($file_extension);$i++){
    $fileName[$i] = uniqid() . '.' . $file_extension[$i];

}

foreach ($fileName as $key => $item) {
    move_uploaded_file($_FILES['file']['tmp_name'][$key], "uploads/" . $item);
    $statement = $pdo->prepare("INSERT INTO images (images) VALUES (:images)");
    $statement->execute(['images' => $item]);
}

$_SESSION['success'] = 'Изображение загружено';

header("Location: /");
exit;