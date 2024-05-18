<?php 
include 'utilities.php';
include 'index.html';

$connection = connectToDB();

// Поиск по выбранным параметрам
if (isset($_POST['selectColor']) && isset($_POST['selectComp'])) {
    $color = $_POST['selectColor'];
    $company = $_POST['selectComp'];
    $result = [];
    
    // Формирование запроса в зависимости от выбранных параметров
    if ($color === 'All' && $company !== 'All') {
        $products = $connection->query("SELECT id, name, image, company, color, price FROM phones WHERE company ='$company'");
    } elseif ($color !== 'All' && $company === 'All') {
        $products = $connection->query("SELECT id, name, image, company, color, price FROM phones WHERE color='$color'");
    } elseif ($color === 'All' && $company === 'All') {
        $products = $connection->query("SELECT id, name, image, company, color, price FROM phones");
    } else {
        $products = $connection->query("SELECT id, name, image, company, color, price FROM phones WHERE color='$color' and company ='$company'");
    }

    $result = getInfo($result, $products);
}

// Проверка наличия куки и получение имени пользователя
$username = '';
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
    $resultCookie = checkCookie($username);
}
?>

<main>
    <span id="welcomeMessage" data-username="<?php echo $username; ?>"></span>
    <h1>K-mobile</h1>
    <h2>Search result ..</h2>
    <div id="mainTable">
        <table>
            <thead>
                <th>Name</th>
                <th>Image</th>
                <th>Brand</th>
                <th>Color</th>
                <th>Price</th>
            </thead>
            <tbody>
                <?php echo buildTableNoButton($result) ?>
            </tbody>
        </table>
    </div>	
</main>
<script src="user_functions.js"></script>
<script type="text/javascript">
    var cookie = <?php echo '"' . $resultCookie . '"' ?>;;
    setupUserInterface(cookie);
</script>