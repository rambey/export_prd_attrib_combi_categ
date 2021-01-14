<?php
require(dirname(__FILE__) . '/../config/config.inc.php');
$id_lang = (int) $cookie->id_lang;
$attributes = Attribute::getAttributes($id_lang, false);

$_filename = 'attributes.csv';
$_chemin = dirname(__FILE__);
$_ExportFile = fopen($_filename, 'w+');
$delimiteur = ";";
ftruncate($_ExportFile, 0);
$arrayexport = array();
$i = 0;
$_colomns = array(
    'id_attribute_group',
    'attribute_group',
    'id_attribute',
    'name',
    'group_type',
    'is_color_group',
);
fputcsv($_ExportFile, $_colomns, $delimiteur);
foreach ($attributes as $key => $attribute) {

    $arrayexport[$i]['id_attribute_group'] = $attribute['id_attribute_group'];
    $arrayexport[$i]['attribute_group'] = utf8_decode($attribute['attribute_group']);
    $arrayexport[$i]['id_attribute'] = $attribute['id_attribute'];
    $arrayexport[$i]['name'] = utf8_decode($attribute['name']);
    $arrayexport[$i]['group_type'] = $attribute['group_type'];
    $arrayexport[$i]['is_color_group'] = $attribute['is_color_group'];

    fputcsv($_ExportFile, array_values($arrayexport[$i]), $delimiteur);
}
fclose($_ExportFile);
