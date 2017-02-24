<?php

$uniqid   =  uniqid();
$form_id  = 'searchform-' . $uniqid;
$field_id = 's-' . $uniqid; ?>

<form role="search" method="get" id="<?php e_esc_attr( $form_id ); ?>" action="<?php echo home_url( '/' ); ?>" role="form">
	<div class="input-group">
		<label class="screen-reader-text" for="s">
			<?php esc_html_e( 'Search field', 'tcc-fluid' ); ?>
		</label>
		<input type="text" value="" name="s" id="<?php e_esc_attr( $field_id ); ?>" class="form-control searchform-input" placeholder="<?php esc_attr_e( 'Search', 'tcc-fluid' ); ?>" />
		<span class="input-group-btn">
			<button class="btn btn-fluidity" type="submit">
				<i class="fa fa-search" aria-hidden="true"></i>
				<span class="screen-reader-text">
					<?php esc_html_e( 'Submit search terms', 'tcc-fluid' ); ?>
				</span>
			</button>
		</span>
	</div>
</form>
