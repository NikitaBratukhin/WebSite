<?php 
// Подключаем необходимые файлы
require_once 'utilities.php';
require_once 'index.html';

// Проверяем подключение к базе данных
$connection = connectToDB();
if ($connection->connect_error) {
    echo $connection->connect_error;
    die();
}

// Получаем информацию о продуктах из базы данных
$products = $connection->query("SELECT id, name, image, company, color, price FROM phones");
$phones = [];
$phones = getInfo($phones, $products);

// Проверяем наличие куки с именем пользователя
$result = "";
if (isset($_COOKIE['username'])) {
    $result = checkCookie($_COOKIE['username']);
}
?>

<header>
    <div id="storeHeader">
        <h1></h1>
		<span id="welcomeMessage" data-username="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>"></span>
        <img id="headerImage" src="https://www.allviewmobile.com/x2/assets/images/tel3.png">
        <div id="insideStoreHeader">
            <h3>K-mobile</h3>
        </div>
    </div>

    <!-- Форма фильтрации продуктов -->
    <?php include 'filter_form.php'; ?>
</header>
<main>
    <div id="mainTable">        
        <?php echo buildStructure($phones) ?>
    </div>
</main>
<script src="user_functions.js"></script>
<script type="text/javascript">
    var cookie = <?php echo json_encode($result); ?>;
    setupUserInterface(cookie);
</script>