<?php

$uniqid   =  uniqid();
$form_id  = 'searchform-' . $uniqid;
$field_id = 's-' . $uniqid;
$form_attrs = array(
	'id'     => $form_id,
	'method' => 'get',
	'action' => home_url( '/' ),
	'role'   => 'search',
);
$input_attrs = array(
	'type' => 'text',
	'id' => $field_id,
	'class' => 'form-control searchform-input',
	'value' => '',
	'name' => 's',
	'placeholder' => __( 'Search', 'tcc-fluid' ),
); ?>

<form <?php fluid_library()->apply_attrs( $form_attrs ); ?>>
	<div class="input-group">
		<label class="screen-reader-text" for="s">
			<?php esc_html_e( 'Search field', 'tcc-fluid' ); ?>
		</label>
		<input <?php fluid_library()->apply_attrs( $input_attrs ); ?> />
		<span class="input-group-btn">
			<button class="btn btn-fluidity" type="submit">
				<?php fluid_library()->fawe( 'fa-search' ); ?>
				<span class="screen-reader-text">&nbsp;
					<?php esc_html_e( 'Submit search terms', 'tcc-fluid' ); ?>
				</span>
			</button>
		</span>
	</div>
</form>
