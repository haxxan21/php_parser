<?php
require "vendor/autoload.php";
include_once('Curl_parser.php');

use PHPHtmlParser\Dom;

$url = 'file:///C:/Users/LC/Desktop/file.html';

$curl_parser = new Curl_parser();
$parsed_html = $curl_parser->Fetch_URL($url);

//output, you can also save it locally on the server
$output = fopen("testfile.html", "w");
fwrite($output, $parsed_html);
fclose($output);
// dom object
$dom = new Dom;
// load Dom from file
$dom->loadFromFile('testfile.html');
// reading content from file where class name is setDisplayWidth
$contents = $dom->find('.setDisplayWidth')[1];
//writing new file with new content
if (!empty($contents)) {
    $extracted_div = fopen("extracted.html", "w");
    fwrite($extracted_div, $contents->innerHtml);
    fclose($extracted_div);

    $dom->loadFromFile('extracted.html');
    $extracted_product = $dom->find('span')[1];
    $extracted_adjustment = $dom->find('span')[5];
    $extracted_reason = $dom->find('span')[6];
    $extracted_data = array(
        'product' > $extracted_product->innerHtml,
        'adjustment' => $extracted_adjustment->innerHtml,
        'reason' => $extracted_reason->innerHtml
    );
    print_r($extracted_data);
} else {
    echo "Response Failed or Query Not Found";
}
