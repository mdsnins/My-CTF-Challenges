<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
libxml_disable_entity_loader(false);

$obj = simplexml_load_file("php://input", "SimpleXMLElement", LIBXML_NOENT);

$suit = (string) $obj->suit;
$rank = (string) $obj->rank;
$width = (int) $obj->meta->width;
$height = (int) $obj->meta->height;
$type = (string) $obj->meta->type;
$mime = (string) $obj->meta->mime;

$svg = '<?xml version="1.0"?>
<svg width="600" height="800">
    <rect x="0" y="0" width="600" height="800" rx="20" ry="20" color="#000000" display="block" fill="#fff" stroke="#000" stroke-width="3"/>
    <text x="10" y="80" fill="#000000" font-family="Sans Serif" font-size="90">
        '.$suit.'-'.$rank.'
    </text>
</svg>
';

$magick = new Imagick();
$magick->readImageBlob($svg);
$magick->setImageFormat($type);
$magick->resizeImage($width, $height, imagick::FILTER_LANCZOS, 1);

header("Content-Type: $mime");
echo $magick;

$magick->clear();
$magick->destroy();
