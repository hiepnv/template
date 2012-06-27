<script type="text/javascript">
(function($){
$(document).ready(function(){
	$(".linkimage").each(function(){
		$(this).click(function(){
			$("#article-cate-list").find('.selected').removeClass('selected');
			$(this).addClass('selected');
			var link = $(this).attr('href');
			//parent = top.document.getElementById("image_link").value = link;
			document.getElementById("linkchild").value = link;
			$("#savelink").removeAttr("disabled", "disabled");
			return false;
		});
	})
});
})(jQuery);
</script>
<ul class="treeview">
<?php
$i=1;
foreach($articleCate as $art){
	if($i==count($articleCate)){
		$last = 'class="last"';
	}else{
		$last = '';
	}
	echo '<li '.$last.'>';
	echo '<a class="linkimage" href="'.$art->link.'"><span>'.$art->title.'</span></a>';
	echo '</li>';
	$i++;
}
?>
</ul>
