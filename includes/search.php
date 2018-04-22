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
				fluid()->apply_attrs_element( 'label', $form['label'], $form['text']['label'] ); ?>
				<span class="block"><?php
					if ( isset( $form['hidden'] ) ) {
						fluid()->apply_attrs_element( 'input', $form['hidden'] );
					}
					fluid()->apply_attrs_element( 'input', $form['search'] ); ?>
					<span class="input-group-btn">
						<button <?php fluid()->apply_attrs( $form['button'] ); ?>>
							<?php fluid()->fawe( 'fa-search' ); ?>
							<span class="screen-reader-text">&nbsp;
								<?php esc_html( $form['text']['button'] ); ?>
							</span>
						</button>
					</span>
				</span>
			</div>
		</form><?php
	}
}
