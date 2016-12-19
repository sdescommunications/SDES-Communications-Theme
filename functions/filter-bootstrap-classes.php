<?php

//Adds class to menu anchor
function add_menuclass($ulclass) {
   return preg_replace('/<li /', '<li class="nav-item"', $ulclass);
}
add_filter('wp_nav_menu','add_menuclass');

function add_menuclassB($ulclass) {
   return preg_replace('/<a /', '<a class="nav-link"', $ulclass);
}
add_filter('wp_nav_menu','add_menuclassB');

//Adds responsive class to images
function image_tag_class($class) {
    $class .= ' img-fluid';
    return $class;
}
add_filter('get_image_tag_class', 'image_tag_class' );

?>