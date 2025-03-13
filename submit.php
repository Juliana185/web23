<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "sql203.infinityfree.com"; // Вставь сюда "MySQL Hostname"
$username = "if0_38513067"; // Вставь сюда "Username"
$password = "Q2GwcClvL1Mlf3"; // Вставь сюда "Password" который использовал при создании аккаунта
$dbname = "if0_38513067_developers_db"; // Вставь сюда имя базы

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $bio = trim($_POST['bio']);
    $agree = isset($_POST['agree']) ? 1 : 0;
    $languages = $_POST['languages'] ?? [];

    // Запись пользователя
    $stmt = $conn->prepare("INSERT INTO users (name, phone, email, birthdate, gender, bio, agree) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $name, $phone, $email, $birthdate, $gender, $bio, $agree);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $stmt->close();

        foreach ($languages as $lang) {
            $stmt = $conn->prepare("INSERT INTO user_languages (user_id, language) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $lang);
            $stmt->execute();
            $stmt->close();
        }

        echo "Данные успешно сохранены!";
    } else {
        echo "Ошибка: " . $stmt->error;
    }
}

$conn->close();
?>
