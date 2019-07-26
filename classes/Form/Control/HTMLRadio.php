<?php
/**
 * classes/Form/Control/HTMLRadio.php
 *
 */
/**
 *  display radio, showing raw html choices
 *
 */
class TCC_Form_Control_HTMLRadio extends TCC_Form_Control_Control {

	public $type = 'radio';

	public function render_content() {

		if ( empty( $this->choices ) ) {
			return;
		}

		$description_id = '_customize-description-' . $this->id;

		if ( isset( $this->label ) ) {
			$this->element( 'span', [ 'class' => 'customize-control-title', ], $this->label );
		}

		if ( ! empty( $this->description ) ) {
			$attrs = array(
				'id'    => $description_id,
				'class' => 'description customize-control-description'
			);
			$this->element( 'span', $attrs, $this->description );
		}

		foreach ( $this->choices as $value => $label ) { ?>

			<span class="customize-inside-control-row"><?php

				$attrs = array(
					'id'    => '_customize-input-' . $this->id . '-radio-' . $value,
					'type'  => 'radio',
					'value' => $value,
					'name'  => '_customize-radio-' . $this->id,
				);
				if ( ! empty( $this->description ) ) {
					$attrs['aria-describedby'] = $description_id;
				}
				$attrs = $this->setting_link( $attrs );
				$this->checked( $attrs, $this->value(), $value );
				$this->element( 'input', $attrs );

				$this->tag( 'label', [ 'for' => $attrs['id'] ] );
					echo wp_kses( $label, fluid()->kses() ); ?>
				</label>

			</span><?php

		}

	}


}
