<?php

/*
 *  fluidity/footer.php
 *
 */

require_once('includes/footer.php'); ?>

<footer>
	<div id="fluid-footer" class="<? echo container_type('footer'); ?>" <?php microdata()->WPFooter(); ?>>
		<div class="row"><?php
			get_template_part('template-parts/footer',get_page_slug());
			wp_footer(); ?>
		</div>
	</div>
</footer>

</body>

</html>
