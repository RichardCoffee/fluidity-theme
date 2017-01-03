<?php

/*
 *  fluidity/footer.php
 *
 */

 ?>

<footer>
	<div id="fluid-footer" class="<? echo container_type('footer'); ?>" <?php microdata()->WPFooter(); ?>>
		<?php get_template_part('template-parts/footer',get_page_slug()); ?>
	</div>
	<?php wp_footer(); ?>
</footer>

</body>

</html>
