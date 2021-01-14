<?php
@ini_set('max_execution_time', -1);
@ini_set('memory_limit', '2G');

include('../config/config.inc.php');
include('../init.php');


$id_lang = Context::getContext()->language->id;
$start = 0;
$limit = 100000;
$order_by = 'id_product';
$order_way = 'DESC';
$id_category = false;
$only_active = true;
$context = null;

$products = Product::getProducts($id_lang, $start, $limit, $order_by, $order_way,$id_category,$only_active);
$elements = array();
foreach ($products as $product) {
    $product['link'] = Context::getContext()->link->getProductLink((int)$product['id_product'], $product['link_rewrite'], $product['id_category_default']);
    $elements[] = $product;
}
fwrite($f, "ID PRODUIT;NOM PRODUIT;URL PRODUIT;URL Image principale;URL Image 2;URL Image 3,URL Image 4 \r\n");
        $data[0]=array("ID PRODUIT","NOM PRODUIT","URL PRODUIT","URL Image principale","URL Image 2","URL Image 3","URL Image 4");
        

foreach ($elements as $key => $element){
    
	$id_product=$element['id_product'];
	$product_name=$element['name'];
        $product_url=$element['link'];
        
        /** get all images */
            $productObj = new Product((int)$element['id_product'] ,$id_lang , 1 );
            $imgs = $productObj->getImages(Context::getContext()->language->id  , null);
            $img  = $productObj->getCover($product['id_product']);
            $link = new Link();
           
            $img_url = $link->getImageLink(isset($productObj->link_rewrite) ? $productObj->link_rewrite : $productObj->name , (int)$img['id_image']);
            
            $image_list = $img_url ;

            foreach($imgs  as $image){
                $img_url2 = $link->getImageLink(isset($productObj->link_rewrite) ? $productObj->link_rewrite : $productObj->name, (int)$image['id_image']);
                  if($img_url !== $img_url2 ){
                    $image_list .=",".$img_url2 ;
                  }
            }
            $all_images = explode(",",$image_list);
            $image_principale = $all_images[0] ; 
            $image_1 = $all_images[1] ; 
            $image_2 = $all_images[2] ; 
            $image_3 = $all_images[3] ; 
           
$data[$productObj->id] = 
                array($id_product,$product_name,$product_url,$image_principale,$image_1,$image_2,$image_3);
}
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=products-link.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

 foreach ($data as $fields) {
                if(is_array($fields)){
                    fputcsv($output, $fields);
                }
            }

fclose($output);