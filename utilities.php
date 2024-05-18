<?php 

function connectToDB(){
    return new mysqli('localhost', 'root', '', 'phone_store');
}

function buildTable($array) {
    $html = "";
    foreach ($array as $row) {
        $html .= "<tr> ";
        $html .= "<td>" . $row["name"] . "</td>";
        $html .= "<td>  <img src='images/" . $row["image"] . ".png'></td>";
        $html .= "<td>" . $row["company"] . "</td>";
        $html .= "<td>" . $row["color"] . "</td>";
        $html .= "<td id='price'>" . $row["price"] . "</td>";
        $html .= "<td><button class='removeFromCart' value=" . $row["id"] .">Remove</button></tr>";
    }
    return $html;
}

function buildTableNoButton($array) {
    $html = "";
    foreach ($array as $row) {
        $html .= "<tr> ";
        $html .= "<td>" . $row["name"] . "</td>";
        $html .= "<td>  <img src='images/" . $row["image"] . ".png'></td>";
        $html .= "<td>" . $row["company"] . "</td>";
        $html .= "<td>" . $row["color"] . "</td>";
        $html .= "<td id='price'>" . $row["price"] . "</td>";
        $html .= "<td></tr>";
    }
    return $html;
}

function buildStructure($array) {
    $html = "";
    foreach ($array as $row) {
        $html .= "<div class='phoneContainer'>";
        $html .= "<div class='phoneImage'><img src='images/" . $row["image"] . ".png'></div>";
        $html .= "<div class='phoneContent'> <h3 class='phoneName'>" . $row["name"] . "</h3>";
        $html .= "<h3>" . $row["company"] . "</h3>";
        $html .= "<h3>" . $row["color"] . "</h3>";
        $html .= "<h3 class='price'>" . $row["price"] . "</h3><button class='addToCartButton' value=" . $row["id"] .">Add to cart</button></div></div>";
    }
    return $html;
}

function getInfo($array , $table){
    while ($row = $table->fetch_assoc()) {
        $array []= $row;
    }
    return $array;
}

function searchBy($list , $needle = 'a') {
    $filter = [];
    for ($i=0; $i < count($list) ; $i++) { 
        if (strpos (strtolower($list[$i]['name']) , strtolower($needle)) !== false  
            || strpos(strtolower($list[$i]['address']) , strtolower($needle)) !== false 
            || strpos(strtolower($list[$i]['phone']) , strtolower($needle)) !== false  ) {
            array_push($filter, $list[$i]);
        }
    }
    return $filter;
}

function printList($list){
    $html = '<ul>';
    for ($i=0; $i < count($list) ; $i++) { 
        $html .= "<li>Name: {$list[$i]['name']} , phone: {$list[$i]['phone']}, address: {$list[$i]['address']} </li>";
    }
    $html .= '</ul>';
    echo "$html";
}

function checkCookie($needle){
    if (isset($needle)) {
        return 'true';
    } else {
        header('Location:index.php');
        return 'false';
    }
}
 ?>