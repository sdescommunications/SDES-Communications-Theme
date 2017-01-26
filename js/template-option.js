(function($){
$(document).ready(function() {

    var $page_template = $('#page_template'),
        $side = $('#custom_page_metabox'),
        $billboard = $('#billboard-meta-box'),
        $service = $('#service-meta-box'); 

    $page_template.change(function() {
        if ($(this).val() == 'content-right-sidecol.php') {
            $side.show();
            $billboard.hide();
            $service.hide();
            

        }else if($(this).val() == 'content-billboard.php'){
        	$billboard.show();
			$side.show();
            $service.hide();
           
        }else if($(this).val() == 'content-services.php'){
            $service.show();
            $side.hide();
            $billboard.hide();
           
        }
        else {
            $side.hide();
            $billboard.hide();
            $service.hide();
           
        }
    }).change();

});
})(jQuery);