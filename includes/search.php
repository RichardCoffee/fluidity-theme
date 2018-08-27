<?php
/**
 *  provide common search format
 *
 * @since 20180408
 * @param array $form
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'fluid_show_search_form' ) ) {
	function fluid_show_search_form( $form ) {
		$form = apply_filters( 'fluid_show_search_form', $form ); ?>
		<form <?php fluid()->apply_attrs( $form['form'] ); ?>>
			<div class="input-group"><?php
				fluid()->element( 'label', $form['label'], $form['text']['label'] );
				if ( isset( $form['hidden'] ) ) {
					fluid()->element( 'input', $form['hidden'] );
				}
				fluid()->element( 'input', $form['search'] ); ?>
				<span class="input-group-btn">
					<button <?php fluid()->apply_attrs( $form['button'] ); ?>>
						<?php fluid()->fawe( 'fa-search' ); ?>&nbsp;
						<span class="screen-reader-text">
							<?php esc_html( $form['text']['button'] ); ?>
						</span>
					</button>
				</span>
			</div>
		</form><?php
	}
}
