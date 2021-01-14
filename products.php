<?php
require(dirname(__FILE__) . '/../config/config.inc.php');
$id_lang = (int) $cookie->id_lang;
$all_products = Product::getProducts($id_lang, 1, 100000, 'id_product', 'DESC', false, true);

$_filename = 'exportproducts.csv';
$_chemin = dirname(__FILE__);
$_ExportFile = fopen($_filename, 'w+');
$delimiteur = ";";
ftruncate($_ExportFile, 0);
$arrayexport = array();
$i = 0;
$_colomns = array(
    'id_product',
    'name',
    'ean13',
    'reference',
    'price_ttc',
    'price_ht',
    'id_tax_rules_group',
    'id_category_default',
    'categories',
    'link_rewrite',
    'description',
    'quantity',
    'weight',
    'out_of_stock',
    'active',
    'id_lang',
    'description_short',
    'meta_description',
    'meta_keywords',
    'meta_title',
    'features',
);
fputcsv($_ExportFile, $_colomns, $delimiteur);
foreach ($all_products as $key => $product) {
    // get categories
    $product_obj = new Product((int) $product['id_product']);

    $categories = $product_obj->getProductCategories((int) $product['id_product']);
    $categories = implode(',', $categories);

    $features = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT name, value
    FROM ' . _DB_PREFIX_ . 'feature_product pf
    LEFT JOIN ' . _DB_PREFIX_ . 'feature_lang fl ON (fl.id_feature = pf.id_feature AND fl.id_lang = ' . (int) $cookie->id_lang . ')
    LEFT JOIN ' . _DB_PREFIX_ . 'feature_value_lang fvl ON (fvl.id_feature_value = pf.id_feature_value AND fvl.id_lang = ' . (int) $cookie->id_lang . ')
    LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON (f.id_feature = pf.id_feature AND fl.id_lang = ' . (int) $cookie->id_lang . ')
    ' . Shop::addSqlAssociation('feature', 'f') . '
    WHERE pf.id_product = ' . (int) $product['id_product'] . '
    ORDER BY f.position ASC');

    $featurevalue = '';

    foreach ($features as $feature) {
        if ($feature === end($features)) {
            $featurevalue .= $feature['name'] . ':' . $feature['value'];
        } else {
            $featurevalue .= $feature['name'] . ':' . $feature['value'] . ',';
        }
    }

    $arrayexport[$i]['id_product'] = utf8_decode($product['id_product']);
    $arrayexport[$i]['name'] = utf8_decode($product['name']);
    $arrayexport[$i]['ean13'] = $product['ean13'];
    $arrayexport[$i]['reference'] = $product['reference'];
    $arrayexport[$i]['price_ttc'] = $product['wholesale_price'];
    $arrayexport[$i]['price_ht'] = $product['price'];
    $arrayexport[$i]['id_tax_rules_group'] = $product['id_tax_rules_group'];
    $arrayexport[$i]['id_category_default'] = $product['id_category_default'];
    $arrayexport[$i]['categories'] = $categories;
    $arrayexport[$i]['link_rewrite'] = $product['link_rewrite'];
    $arrayexport[$i]['description'] = utf8_decode($product['description']);
    $arrayexport[$i]['quantity'] = $product['quantity'];
    $arrayexport[$i]['weight'] = $product['weight'];
    $arrayexport[$i]['out_of_stock'] = $product['out_of_stock'];
    $arrayexport[$i]['active'] = $product['active'];
    $arrayexport[$i]['id_lang'] = $product['id_lang'];
    $arrayexport[$i]['description_short'] = utf8_decode($product['description_short']);
    $arrayexport[$i]['meta_description'] = utf8_decode($product['meta_description']);
    $arrayexport[$i]['meta_keywords'] = $product['meta_keywords'];
    $arrayexport[$i]['meta_title'] = $product['meta_title'];
    $arrayexport[$i]['features'] = utf8_decode($featurevalue);
    fputcsv($_ExportFile, array_values($arrayexport[$i]), $delimiteur);
}
fclose($_ExportFile);
