<?php

/*
 *  fluidity/footer.php
 *
 */

 ?>

<footer>
	<div id="fluid-footer" class="<? echo container_type('footer'); ?>" <?php microdata()->WPFooter(); ?>>
<?php log_entry(get_page_slug());
		<?php get_template_part('template-parts/footer',get_page_slug()); ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>

</html>
