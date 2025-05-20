<?php

// Подключаемся к базе данных
$host = '127.0.0.1'; // Замените на ваш хост
$database = '22064_agregator'; // Замените на имя вашей базы данных
$username = 'root'; // Замените на имя пользователя
$password = 'loubfe'; // Замените на пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Подключение к базе данных успешно установлено.\n";
    
    // Шаг 1: Находим имя внешнего ключа
    $stmt = $pdo->query("
        SELECT CONSTRAINT_NAME
        FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_SCHEMA = '$database'
        AND TABLE_NAME = 'booking_seats'
        AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        AND CONSTRAINT_NAME LIKE '%seat_id%'
    ");
    
    $foreignKeys = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($foreignKeys)) {
        echo "Внешний ключ для seat_id не найден.\n";
    } else {
        echo "Найдены следующие внешние ключи: " . implode(', ', $foreignKeys) . "\n";
        
        // Шаг 2: Удаляем внешние ключи
        foreach ($foreignKeys as $foreignKey) {
            $pdo->exec("ALTER TABLE booking_seats DROP FOREIGN KEY `$foreignKey`");
            echo "Внешний ключ $foreignKey удален.\n";
        }
    }
    
    // Шаг 3: Изменяем тип колонки seat_id
    $pdo->exec("ALTER TABLE booking_seats MODIFY seat_id VARCHAR(100)");
    echo "Тип колонки seat_id изменен на VARCHAR(100).\n";
    
    // Шаг 4: Добавляем колонку venue_zone_id, если её ещё нет
    $stmt = $pdo->query("SHOW COLUMNS FROM booking_seats LIKE 'venue_zone_id'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE booking_seats ADD COLUMN venue_zone_id BIGINT UNSIGNED NULL AFTER booking_id");
        echo "Колонка venue_zone_id добавлена.\n";
        
        // Добавляем внешний ключ для venue_zone_id
        $pdo->exec("ALTER TABLE booking_seats ADD CONSTRAINT booking_seats_venue_zone_id_foreign FOREIGN KEY (venue_zone_id) REFERENCES venue_zones(id) ON DELETE SET NULL");
        echo "Внешний ключ для venue_zone_id добавлен.\n";
    } else {
        echo "Колонка venue_zone_id уже существует.\n";
    }
    
    echo "Все операции выполнены успешно.\n";
    
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}