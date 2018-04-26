<?php

/*
 *  fluidity/footer.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

		<footer>

			<div id="fluid-footer" class="<?php e_esc_attr( container_type( 'footer' ) ); ?>" <?php microdata()->WPFooter(); ?>>
				<?php get_template_part( 'template-parts/footer', get_page_slug() ); ?>
			</div>

			<div id="wp-footer">
				<?php wp_footer(); ?>
			</div>

		</footer>

	</body>

</html>
