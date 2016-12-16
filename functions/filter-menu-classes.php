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

?>