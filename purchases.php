<?php 

include 'utilities.php';

include 'index.html';

$username = $_COOKIE['username'];

// Проверка наличия куки и перенаправление на index.php, если она отсутствует
if (!isset($username)) {
    header('Location:index.php');
    exit(); // Добавление выхода из скрипта после перенаправления
}

$result = checkCookie($username);
$connection = connectToDB();

// Получение ID клиента по его имени
$user_id = getClientId($username, $connection);

// Добавление покупки в корзину, если была отправлена форма с новой покупкой
if (isset($_POST['new_purchase'])) {
    $new_phone = $_POST['new_purchase'];
    addPurchaseToCart($user_id, $new_phone, $connection);
}

// Получение списка последних покупок клиента
$last_purchases = getLastPurchases($user_id, $connection);

// Удаление покупки из корзины, если была отправлена форма на удаление
if (isset($_POST['remove_purchase'])) {
    $remove_purchase = $_POST['remove_purchase'];
    removePurchaseFromCart($remove_purchase, $connection);
}

function getClientId($username, $connection)
{
    $purchases = $connection->query("SELECT id FROM clients WHERE name='$username'");
    $find_purchases = getInfo([], $purchases);
    return $find_purchases[0]['id'];
}

function addPurchaseToCart($user_id, $new_phone, $connection)
{
    $add_purchase_to_database = $connection->query("INSERT INTO purchases (user_id,phone_id) VALUES ('$user_id','$new_phone')");
}

function getLastPurchases($user_id, $connection)
{
    $find_purchases = $connection->query("SELECT `phone_id` FROM `purchases` WHERE `user_id` = '$user_id'");
    $find_new_purchaes = getInfo([], $find_purchases);

    $last_result = [];
    foreach ($find_new_purchaes as $row) {
        $number = $row['phone_id'];
        $last_purchases = $connection->query("SELECT p.id, p.name, p.image, p.company, p.color, p.price FROM phones p WHERE p.id='$number'");
        $last_result = getInfo($last_result, $last_purchases);
    }
    return $last_result;
}

function removePurchaseFromCart($remove_purchase, $connection)
{
    $add_purchase_to_database =  $connection->query("DELETE FROM `purchases` WHERE `purchases`.`phone_id` = '$remove_purchase'");
    header('Location: purchases.php');
    exit(); // Добавление выхода из скрипта после перенаправления
}
?>

<main>
    <span id="welcomeMessage" data-username="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>"></span>
    <h2>Hello <?php echo $username; ?> , your last purchases :</h2>
    <div id="mainTable">
        <table>
            <thead>
                <th>Name</th>
                <th>Image</th>
                <th>Brand</th>
                <th>Color</th>
                <th>Payed</th>
            </thead>
            <tbody>
                <?php echo buildTable($last_purchases); ?>
            </tbody>
        </table>
    </div>
</main>

<script src="user_functions.js"></script>
<script type="text/javascript">
    var cookie = <?php echo '"' . $result . '"' ?>;
    setupUserInterface(cookie);
    createRemoveFromCart();
</script>