<?php

/*
 *  fluidity/footer.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

		<footer>

			<div id="fluid-footer" class="<?php e_esc_attr( container_type( 'footer' ) ); ?>" <?php microdata()->WPFooter(); ?>><?php
				$slug = get_page_slug();
				$dir  = apply_filters( 'fluid_footer_template_dir', 'template-parts', $slug );
				$root = apply_filters( 'fluid_footer_template_root', 'footer', $slug );
				get_template_part( "$dir/$root", $slug ); ?>
			</div>

			<div id="wp-footer"><?php
				wp_footer(); ?>
			</div>

		</footer>

	</body>

</html>
