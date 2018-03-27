<?php

/*
 *  fluidity/footer.php
 *
 */

 ?>

		<footer>

			<div id="fluid-footer" class="<?php echo container_type( 'footer' ); ?>" <?php microdata()->WPFooter(); ?>>
				<?php get_template_part( 'template-parts/footer', get_page_slug() ); ?>
			</div>

			<div id="wp-footer">
				<?php wp_footer(); ?>
			</div>

		</footer>

	</body>

</html>
