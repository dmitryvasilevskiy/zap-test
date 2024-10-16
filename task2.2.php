<?php
// Подключение к базе данных
$host = 'localhost';
$db = 'zap';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
    exit;
}

//Вводные данные
$orderId = 1;
$paidProducts = [
    ['product_id' => 1, 'quantity' => 1],
    ['product_id' => 2, 'quantity' => 2]
];

// Расчет стоимости оплаты
$totalPayment = 0;
foreach ($paidProducts as $paidProduct) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :product_id");
    $stmt->execute(['product_id' => $paidProduct['product_id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalPayment += $product['price'] * $paidProduct['quantity'];
}

// Расчет оставшихся товаров в заказе
$remainingProducts = [];
$stmt = $pdo->prepare("SELECT product_id, quantity FROM order_products WHERE order_id = :order_id");
$stmt->execute(['order_id' => $orderId]);
$orderProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($orderProducts as $orderProduct) {
    $remainingQuantity = $orderProduct['quantity'];
    foreach ($paidProducts as $paidProduct) {
        if ($orderProduct['product_id'] == $paidProduct['product_id']) {
            $remainingQuantity -= $paidProduct['quantity'];
        }
    }
    if ($remainingQuantity > 0) {
        $remainingProducts[] = [
            'product_id' => $orderProduct['product_id'],
            'remaining_quantity' => $remainingQuantity
        ];
    }
}

echo "Стоимость оплаты: $totalPayment\n";
echo "Оставшиеся товары в заказе:\n";

foreach ($remainingProducts as $remainingProduct) {
    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = :product_id");
    $stmt->execute(['product_id' => $remainingProduct['product_id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $remainingCost = $product['price'] * $remainingProduct['remaining_quantity'];
    echo "{$product['name']}: {$remainingProduct['remaining_quantity']} шт. (стоимость: $remainingCost)\n";
}