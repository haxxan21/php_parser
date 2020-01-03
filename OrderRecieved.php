<?php
include 'vendor/autoload.php';
require_once('Curl_parser.php');

use PHPHtmlParser\Dom;
$url = 'file:///C:/Users/LC/Desktop/OrderRecieved.html';

$curl_parser = new Curl_parser();
$parsed_html = $curl_parser->Fetch_URL($url);

$output = fopen("testfile.html", "w");
fwrite($output, $parsed_html);
fclose($output);

$dom = new Dom();

$dom->loadFromFile("testfile.html");
$delivery_date = $dom->find('.setDisplayWidthInner')[2]->find('span')[0]->innerHtml;
$delivery_name = $dom->find('.setDisplayWidthInner')[2]->find('span')[2]->innerHtml;
$delivery_address = $dom->find('.setDisplayWidthInner')[2]->find('span')[3]->innerHtml;
$delivery_address =  preg_replace('/<br \/>/iU', '', $delivery_address );
// $contents = $contents->find('.greyText')->innerHtml;
$item = $dom->find('.setDisplayWidthInner')[3]->find('span')[1]->innerHtml;
$quantity = $dom->find('.setDisplayWidthInner')[3]->find('span')[2]->innerHtml;
$total = $dom->find('.setDisplayWidthInner')[3]->find('span')[3]->innerHtml;

$sub_total = $dom->find('.setDisplayWidthInner')[4]->find('span')[2]->innerHtml;
$shipping_cost = $dom->find('.setDisplayWidthInner')[4]->find('span')[4]->innerHtml;
$net_total = $dom->find('.setDisplayWidthInner')[4]->find('span')[6]->innerHtml;

$extracted_data = array (
    'delivery_date'  => $delivery_date,
    'delivery_name' => $delivery_name,
    'delivery_address' => $delivery_address,
    'item' => $item,
    'quantity' => $quantity,
    'total' => $total,
    'sub_total' =>  $sub_total,
    'shipping_cost' => $shipping_cost,
    'net_total' => $net_total,
);
print_r($extracted_data);
?>