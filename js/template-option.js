(function($){
$(document).ready(function() {

    var $page_template = $('#page_template'),
        $metabox_side = $('#custom_page_metabox'),
        $metabox_billboard = $('#billboard-meta-box'); // For example

    $page_template.change(function() {
        if ($(this).val() == 'content-left-sidecol.php' || $(this).val() == 'content-right-sidecol.php') {
            $metabox_side.show();
            $metabox_billboard.hide();

        }else if($(this).val() == 'content-billboard.php'){
        	$metabox_billboard.show();
			$metabox_side.show();
        }
        else {
            $metabox_side.hide();
            $metabox_billboard.hide();
        }
    }).change();

});
})(jQuery);