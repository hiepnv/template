<script type="text/javascript">
(function($){
	$(document).ready(function () {
		$('#menu-item-list').treeview({
			persist: 'location',
			collapsed: true,
			unique: true
		});
		$(".linkimage").each(function(){
			$(this).click(function(){
				$("#article-list").find('.selected').removeClass('selected');
				$(this).addClass('selected');
				var link = $(this).attr('href');
				//parent = top.document.getElementById("image_link").value = link;
				//$("#savelink").removeAttr("disabled", "disabled");;
				document.getElementById("linkchild").value = link;
				//savelink
				$("#savelink").addClass('buttonpopup').removeAttr("disabled", "disabled");
				return false;
			});
		})
	});

})(jQuery);
</script>
<?php
echo $articleCate;
?>
