<?php

/*
 *  fluidity/footer.php
 *
 */

 ?>

		<footer>
			<div id="fluid-footer" class="<?php echo container_type('footer'); ?>" <?php microdata()->WPFooter(); ?>>
<p>before footer</p>
				<?php get_template_part('template-parts/footer',get_page_slug()); ?>
<p>after footer</p>
			</div>
		</footer>

		<div id="wp-footer">
			<?php wp_footer(); ?>
		</div>

	</body>
</html>
