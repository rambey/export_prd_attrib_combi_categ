<?php
require(dirname(__FILE__) . '/../config/config.inc.php');
$id_lang = (int) $cookie->id_lang;

$sql = 'select id_product from ' . _DB_PREFIX_ . 'product';
$resultats = Db::getInstance()->ExecuteS($sql);
$attributsproduct = array();
foreach ($resultats as $key => $value) {
    if ($value['id_product']) {
        $producttmp = new Product($value['id_product']);
        $attributsproduct[] = $producttmp->getAttributeCombinations($id_lang);
    }
}

$_filename = 'declinaisons.csv';
$_chemin = dirname(__FILE__);
$_ExportFile = fopen($_filename, 'w+');
$delimiteur = ";";
ftruncate($_ExportFile, 0);
$arrayexport = array();
$i = 0;
$_colomns = array(
    "id_product_attribute",
    "id_product",
    "reference",
    "id_attribute",
    "attribute_name",
    "id_attribute_group",
    "group_name",
    "is_color_group",
    "price",
    "quantity",
    "default_on",
);
fputcsv($_ExportFile, $_colomns, $delimiteur);
foreach ($attributsproduct as $productall) {

    foreach ($productall as $product) {
        $arrayexport[$i]['id_product_attribute'] = $product['id_product_attribute'];
        $arrayexport[$i]['id_product'] = $product['id_product'];
        $arrayexport[$i]['reference'] = utf8_decode($product['reference']);
        $arrayexport[$i]['id_attribute'] = $product['id_attribute'];
        $arrayexport[$i]['attribute_name'] = utf8_decode($product['attribute_name']);
        $arrayexport[$i]['id_attribute_group'] = $product['id_attribute_group'];
        $arrayexport[$i]['group_name'] = utf8_decode($product['group_name']);
        $arrayexport[$i]['is_color_group'] = $product['is_color_group'];
        $arrayexport[$i]['quantity'] = $product['quantity'];
        $arrayexport[$i]['minimal_quantity'] = $product['minimal_quantity'];
        $arrayexport[$i]['default_on'] = $product['default_on'];
        fputcsv($_ExportFile, array_values($arrayexport[$i]), $delimiteur);
    }
}

fclose($_ExportFile);






