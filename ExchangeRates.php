<?php
/*
Plugin Name: курсы валют в админ
Plugin URI:
Description: При нажатии на кнопку переводит на страницу с отображением курса валют в реальном времени (по обновлению страницы)
Version: для теста от работадателя
Author: Leeroyel
Author URI: https://vk.com/id90629680
*/
add_action( 'admin_menu', 'register_my_menu_item' );

function register_my_menu_item() {
    add_menu_page( 'Курс Валют', 'Курс валют', 'manage_options', 'query-string-parameter', 'my_menu_item');
}

function my_menu_item() {
    $date = date("d/m/Y");
    $link = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$date"; // ссылка на xml с курсами валют
    $content = file_get_contents($link); // скачиваем содержимое
    $dom = new domDocument("1.0", "cp1251"); // создаем DOM
    $dom->loadXML($content); // загружаем в DOM xml
    $root = $dom->documentElement; 
    $childs = $root->childNodes; 
    $data = array(); 
    for ($i = 0; $i < $childs->length; $i++) {
      $childs_new = $childs->item($i)->childNodes; 
      for ($j = 0; $j < $childs_new->length; $j++) {
        /* ищем интересующие */
        $el = $childs_new->item($j);
        $code = $el->nodeValue;
        if (($code == "USD") || ($code == "EUR") || ($code == "AZN") || ($code == "GBP") || ($code == "AMD") 
        || ($code == "BYN") || ($code == "BGN") || ($code == "BRL") || ($code == "HUF") || ($code == "HKD") 
        || ($code == "DKK") || ($code == "INR") || ($code == "KZT") || ($code == "CAD") || ($code == "KGS") 
        || ($code == "CNY") || ($code == "MDL") || ($code == "NOK") || ($code == "PLN") || ($code == "RON") 
        || ($code == "XDR") || ($code == "SGD") || ($code == "TJS") || ($code == "TRY") || ($code == "TMT") 
        || ($code == "UZS") || ($code == "UAH") || ($code == "CZK") || ($code == "SEK") || ($code == "CHF") 
        || ($code == "ZAR") || ($code == "KRW") || ($code == "JPY")) $data[] = $childs_new; 
      }
    }
    /* перебор массива с данными о валютах */
    for ($i = 0; $i < count($data); $i++) {
      $list = $data[$i];
      for ($j = 0; $j < $list->length; $j++) {
        $el = $list->item($j);
        /* вывод */
        if ($el->nodeName == "Name") echo $el->nodeValue." - ";
        elseif ($el->nodeName == "Value") echo $el->nodeValue." рублей<br />";
      }
    }
}
    ?>