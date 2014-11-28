<?php
// [search]
function search_shortcode() {
    ob_start();

 	get_product_search_form();
 	
 	$content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode("search", "search_shortcode");