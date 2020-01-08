<?php
    require 'vendor/autoload.php';
    include_once('Curl_parser.php');
    use PHPHtmlParser\Dom;

    // $url = 'file:///D:/xampp/htdocs/untitled.html';
    $url = 'https://mail.google.com/mail/u/0/#spam/FMfcgxwGCbLTzBBVRdjzfQjTQZDxLCJJ';
    $curl_parser = new Curl_parser();
    $parsed_html = $curl_parser->Fetch_URL($url);

    $output = fopen("testfile.html", "w");
    fwrite($output, $parsed_html);
    fclose($output);
    // dom object
    $dom = new Dom;
    // load Dom from file
    $dom->loadFromFile('testfile.html');
    // reading content from file where class name is MsoNormalTable
    // $contents = $dom->find('div');
    $contents = $dom->find('body');

    // $subject = $dom->find('table')->find('h2');
    print_r($contents->innerHtml); exit;
    if (!empty($contents)) {
            //writing new file with new content
            $extracted_div = fopen("extracted.html", "w");
            fwrite($extracted_div, $contents->innerHtml);
            fclose($extracted_div);
        
        $dom->loadFromFile('extracted.html');
        $extracted_product = $dom->find('span')[4];
        // print_r($extracted_product);
        $extracted_adjustment = $dom->find('span')[5];
        $extracted_reason = $dom->find('span')[6];
        $extracted_data = array(
            'product' => $extracted_product->innerHtml,
            'adjustment' => $extracted_adjustment->innerHtml,
            'reason' => $extracted_reason->innerHtml
        );

        print_r($extracted_data);
    } else {
        echo 'Response Failed Or Required Query Not Found';
    }

?>