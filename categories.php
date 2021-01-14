<?php
require(dirname(__FILE__) . '/../config/config.inc.php');
$id_lang = (int) $cookie->id_lang;
$categories = Category::getCategories($id_lang, false, true);

$_filename = 'categories.csv';
$_chemin = dirname(__FILE__);
$_ExportFile = fopen($_filename, 'w+');
$delimiteur = ";";
ftruncate($_ExportFile, 0);
$arrayexport = array();
$i = 0;
$_colomns = array(
    'id_category',
    'name',
    'active',
);
fputcsv($_ExportFile, $_colomns, $delimiteur);
foreach ($categories as $key => $category) {
    foreach ($category as $cat) {
        var_dump($cat['infos']['id_category']);
        $arrayexport[$i]['id_attribute_group'] = $cat['infos']['id_category'];
        $arrayexport[$i]['attribute_group'] = utf8_decode($cat['infos']['name']);
        $arrayexport[$i]['id_attribute'] = $cat['infos']['active'];

        fputcsv($_ExportFile, array_values($arrayexport[$i]), $delimiteur);
    }
}
fclose($_ExportFile);
