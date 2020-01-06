<?php
require 'vendor/autoload.php';
include_once('Curl_parser.php');

use PHPHtmlParser\Dom;

class EmailParser
{

    private $dom;

    public function index($url)
    {
        $this->dom   = new Dom();
        // Custom class Curl_Parser using Curl to fetch Document Object Model from live URl or local HTML file
        $curl_parser = new Curl_parser();
        $parsed_html = $curl_parser->Fetch_URL($url);
 
        $output = fopen("testfile.html", "w");
        fwrite($output, $parsed_html);
        fclose($output);
        
        $this->dom->loadFromFile('testfile.html');
        $subject = $this->dom->find('table')->find('h2')->innerHtml;
        
        switch ($subject) {
            case (strpos($subject, 'An adjustment for your recent Walmart.com order') == true):
                return $this->RecentAdjustment();
                break;
            case (strpos($subject, 'Your order has been delivered:') == true):
                return $this->OrderDelivery();
                break;
            case (strpos($subject, 'Refund notice for your recent Walmart.com order ') == true):
                return $this->RefundNotice();
                break;
            case (strpos($subject, 'Order received. Arrives') == true):
                return $this->OrderReceived();
                break;
            default:
                echo 'Response Failed or Query Not Found';
        }
    }


    private function RecentAdjustment()
    {
        $this->dom->loadFromFile('testfile.html');
        $contents = $this->dom->find('table')->find('table')[4];
        if (!empty($contents)) {
            
            $extracted_div = fopen("extracted.html", "w");
            fwrite($extracted_div, $contents->innerHtml);
            fclose($extracted_div);

            $this->dom->loadFromFile('extracted.html');
            $extracted_product    = $this->dom->find('span')[4]->innerHtml;
            $extracted_adjustment = $this->dom->find('span')[5]->innerHtml;
            $extracted_reason     = $this->dom->find('span')[6]->innerHtml;
            $extracted_data       = array(
                'product'       => $extracted_product,
                'adjustment'    => $extracted_adjustment,
                'reason'        => $extracted_reason
            );
            return $this->print($extracted_data);
        } else {
            echo 'Response Failed Or Required Query Not Found';
        }
    }


    private function OrderDelivery()
    {
        $this->dom->loadFromFile('testfile.html');

        $contents               = $this->dom->find('table')->find('table')[7];
        $address                = $this->dom->find('table')->find('table')[6]->find('p')[2];
        $tracking_info_company  = $this->dom->find('table')->find('table')[6]->find('p')[3]->find('span');
        $tracking_info          = $this->dom->find('table')->find('table')[6]->find('p')[3]->find('span')[1];

        $address                =  preg_replace('/<br \/>/iU', '', $address->innerHtml);
        $tracking               = $tracking_info_company . ' ' . $tracking_info->innerHtml;
        
        if (!empty($contents)) {
            $extracted_div = fopen("extracted.html", "w");
            fwrite($extracted_div, $contents->innerHtml);
            fclose($extracted_div);

            $this->dom->loadFromFile("extracted.html");
            $extracted_product  = $this->dom->find('span')[6]->innerHtml;
            $extracted_price    = $this->dom->find('span')[5]->innerHtml;
            $extracted_price    = str_replace("<b>", "", $extracted_price);
            $extracted_quantity = $this->dom->find('span')[7]->innerHtml;
            $extracted_total    = $this->dom->find('span')[8]->innerHtml;

            $extracted_data = array(
                'product'       => $extracted_product,
                'unitprice'     => $extracted_price,
                'quantity'      => $extracted_quantity,
                'total'         => $extracted_total,
                'address'       => $address,
                'tracking_id'   => $tracking
            );
            return $this->print($extracted_data);
        } else {
            echo "Response Failed or Query Not Found";
        }
    }


    private function RefundNotice()
    {
        $contents = $this->dom->find('table')->find('table')[4];

        if (!empty($contents)) {

            $extracted_div = fopen("extracted.html", "w");
            fwrite($extracted_div, $contents->innerHtml);
            fclose($extracted_div);

            $this->dom->loadFromFile( "extracted.html" );
            $extracted_price     = $this->dom->find('p')[1]->find('span')->innerHtml;
            $extracted_orderNo   = $this->dom->find('span')[3]->innerHtml;
            $extracted_orderDate = $this->dom->find('span')[5]->innerHtml;
            $extracted_price     = (float) filter_var($extracted_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $extracted_price     = '$' . $extracted_price;

            $extracted_data = array(
                'price'          => $extracted_price,
                'orderNo'        => $extracted_orderNo,
                'orderDate'      => $extracted_orderDate
            );
            return $this->print($extracted_data);
        } else {
            echo "Response Failed or Query Not Found";
        }
    }


    private function OrderReceived()
    {
        $this->dom->loadFromFile("testfile.html");
        $delivery_date      = $this->dom->find('table')->find('table')[5]->find('span')[1]->innerHtml;
        $delivery_name      = $this->dom->find('table')->find('table')[5]->find('span')[5]->innerHtml;
        $delivery_address   = $this->dom->find('table')->find('table')[5]->find('span')[6] . ' ' .
                              $this->dom->find('table')->find('table')[5]->find('span')[7] . ' ' .
                              $this->dom->find('table')->find('table')[5]->find('span')[8];
        $item               = $this->dom->find('table')->find('table')[6]->find('span')[4]->innerHtml;
        $quantity           = $this->dom->find('table')->find('table')[6]->find('span')[5]->innerHtml;
        $unit_price         = str_replace("<b>","",$this->dom->find('table')->find('table')[6]->find('span')[3]->innerHtml);
        $total              = $this->dom->find('table')->find('table')[6]->find('span')[6]->innerHtml;
        $shipping           = $this->dom->find('table')->find('table')[7]->find('span')[4]->innerHtml;
        $net_total          = str_replace("<b>","",$this->dom->find('table')->find('table')[7]->find('span')[6]->innerHtml);
        $extracted_data     = array(
            'delivery_date'      => $delivery_date,
            'delivery_name'      => $delivery_name,
            'delivery_address'   => $delivery_address,
            'item'               => $item,
            'quantity'           => $quantity,
            'unit_price'         => $unit_price,
            'total'              => $total,
            'shipping_cost'      => $shipping,
            'net_total'          => $net_total,
        );
        return $this->print($extracted_data);
    }

    
    private function print($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
