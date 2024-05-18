<?php 

include 'utilities.php';

include 'index.html';


if (!isset($_COOKIE['username'])) {

	header('Location:index.php');

}

$connection = connectToDB();
$get_clients = $connection->query("SELECT name,address,phone,password FROM clients");
$clients = getInfo([], $get_clients); // Избегание переопределения переменной $clients

$result = checkCookie($_COOKIE['username']);
$name = $_COOKIE['username'];

// Поиск информации о пользователе по имени пользователя
$user_info = [];
foreach ($clients as $client) {
    if ($client['name'] === $name) {
        $user_info = $client;
        break;
    }
}
?>

<main>
    <span id="welcomeMessage" data-username="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>"></span>
    <div id="userInfo">
        <div id="left">
            <div id="profilePic" style="background-image: url(profile_images/<?php echo $_COOKIE['username']; ?>.png);">
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <span>Select image to upload:</span>
                    <label>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                    </label>
                    <label>
                        <input type="submit" value="Upload Image" name="submit">
                    </label>
                </form>
            </div>
        </div>
        <div id="right">
            <div id="profileHeader">
                <h1><?php echo $_COOKIE['username']; ?></h1>
                <button class="privacyButtonName">🗝 Change user name</button>
            </div>
            <h2>Phone number : <?php echo $user_info['phone']; ?></h2>
            <h2>Address : <?php echo $user_info['address']; ?></h2>
            <h2>Password : <?php echo $user_info['password']; ?></h2>
        </div>
        <div id="privacy">
        </div>
    </div>
</main>

<script src="user_functions.js"></script>
<script type="text/javascript">
    var cookie = <?php echo '"' . $result . '"' ?>;
    createPrivacyButtonName();
    setupUserInterface(cookie);
</script>