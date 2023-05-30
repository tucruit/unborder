<script>
$(function(){
	var dontTouchMeContentIds = [<?php echo h(implode(', ', Configure::read('DontTouchMe.Contents'))); ?>];
    $($.bcTree).bind('loaded', function (e) {
        if ($.bcTree.treeDom) {
            $.bcTree.treeDom.on("ready.jstree", function(){
                $("li.jstree-node").each(function (i) {
                    var node = $.bcTree.jsTree.get_node(this);
                    if($.inArray(parseInt(node.data.jstree.contentId), dontTouchMeContentIds) !== -1) {
                        $(this).find('div').addClass('bc-plugin-dont-touch-me-content');
                    }
                });
            });
        }
    });
});
</script>
<style type="text/css">
	div.jstree-wholerow.bc-plugin-dont-touch-me-content {
		background-color: #E0E7AA;
	}
</style>
	