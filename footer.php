<?php
/**
 *
 * @package Fluidity
 * @subpackage Main
 * @since 20150511
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/footer.php
 */
defined( 'ABSPATH' ) || exit; ?>

		<footer>

			<div id="fluid-footer" class="<?php e_esc_attr( container_type( 'footer' ) ); ?>" <?php microdata()->WPFooter(); ?>><?php
				$slug = get_page_slug();
				$dir  = apply_filters( 'fluid_footer_template_dir', 'template-parts', $slug );
				$root = apply_filters( 'fluid_footer_template_root', 'footer', $slug );
				get_template_part( "$dir/$root", $slug ); ?>
			</div>

			<?php do_action( 'fluid_php_error_messages' ); ?>

			<div id="wp-footer"><?php
				wp_footer(); ?>
			</div>

		</footer>

	</body>

</html>
