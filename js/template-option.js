// template-option.js
jQuery(function($){
  var postId  = parseInt(window.data && data.postID, 10) || 0;
  var isFront = parseInt(window.data && data.isFrontPage, 10) || 0;

  // meta box elements (adjust IDs if they differ)
  var $side = $('#custom_page_metabox');      // side column box
  var $bill = $('#billboard-meta-box');       // billboard box
  var $svc  = $('#service-meta-box');         // services box
  var $bt   = $('#billTag'),  $btb = $('#billTagb'); // tag fields
  var $bu   = $('#billUrl'),  $bub = $('#billUrlb'); // url fields

  function hideAll(){ $side.hide(); $bill.hide(); $svc.hide(); }

  function applyFor(template){
    // Show/hide EXACTLY as theme expects:
    if (template === 'content-right-sidecol.php' || template === 'content-left-sidecol.php') {
      $side.show(); $bill.hide(); $svc.hide();
    } else if (template === 'content-billboard.php') {
      $bill.show(); $side.show(); $svc.hide(); $bt.show(); $btb.show(); $bu.hide(); $bub.hide();
    } else if (template === 'content-billboard-full.php') {
      $bill.show(); $side.hide(); $svc.hide(); $bt.show(); $btb.show(); $bu.hide(); $bub.hide();
    } else if (template === 'content-billboard-video.php' && isFront === postId) {
      $bill.show(); $side.show(); $svc.hide(); $bt.hide(); $btb.hide(); $bu.show(); $bub.show();
    } else if (template === 'content-billboard-video.php') {
      $bill.show(); $side.hide(); $svc.hide(); $bt.hide(); $btb.hide(); $bu.show(); $bub.show();
    } else if (template === 'content-services.php' || template === 'content-services-right-sidecol.php') {
      $svc.show(); $side.hide(); $bill.hide();
    } else {
      hideAll();
    }
  }

  // 1) React to changes (when you pick a template)
  $(document).on('change', '#page_template', function(){
    applyFor(this.value || '');
  });

  // 2) Initialize once on load (when editing an existing page)
  function initOnce(){
    var el = document.getElementById('page_template');
    if (!el) return;
    applyFor(el.value || '');
  }

  // Run after DOM ready and after all meta boxes are in the DOM
  initOnce();
  $(window).on('load', initOnce);
  setTimeout(initOnce, 0); // in case meta boxes mount async
});
