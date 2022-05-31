<?php

namespace AwwwesomeEEP\Modules\ACF\Widgets;

use Elementor\Controls_Manager;

class Repeater_Image extends Repeater {

	public function get_name() {
		return 'acf-repeater-image';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'ACF Repeater Image', 'aw3sm-eep' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-image-box';
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'acf_sub_group_description',
			[
				'raw' => __( 'ACF Group Items must have "select" type with complete Image link as value [image-link : Image Name]', 'aw3sm-eep' ),
			]
		);
	}

	protected function register_style_controls() {
		$this->add_control(
			'image_background_color',
			[
				'label'     => esc_html__( 'Image background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_weight',
			[
				'label' => esc_html__( 'Image Weight', 'aw3sm-eep' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 400,
					],
				],
				/*'condition' => [
					'separator_style!' => 'none',
				],*/
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'size' => 30,
				],
			]
		);
		$this->add_control(
			'image_padding',
			[
				'label' => esc_html__( 'Image Padding', 'aw3sm-eep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'text_image_gap',
			[
				'label' => esc_html__( 'Text Image Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 5,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 10,
				],
			]
		);

		$this->add_control(
			'image_background_border_radius',
			[
				'label' => esc_html__( 'Image Background Border Radius', 'aw3sm-eep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list .acf-repeater-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		parent::register_style_controls();

		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_html__( 'Text Alignment', 'aw3sm-eep' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'aw3sm-eep' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'aw3sm-eep' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'aw3sm-eep' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list' => 'text-align: {{VALUE}}',
				],
			]);
	}

	protected function render() {
		// get settings
		$acf_group     = $this->get_settings( 'acf_group' );
		$acf_sub_group = $this->get_settings( 'acf_sub_group' );

		if ( '' === $acf_group || '' === $acf_sub_group ) {
			return;
		}

		$_finalContent = '';

		if ( have_rows( $acf_group ) ) {
			$_finalContent .= "<div class=\"acf-repeater-list\" style='display: flex'>";
			while ( have_rows( $acf_group ) ) {
				the_row();
				$subField       = get_sub_field_object( trim( $acf_sub_group ) );
				$subFieldValues = get_sub_field( trim( $acf_sub_group ) );
				foreach ( $subFieldValues as $value ) {
					$name      = $subField['choices'][ $value ]; // Get name
					$image_src = $value;

					$_finalContent .= "<div class=\"acf-repeater-item\">";
					$_finalContent .= "<div class=\"acf-repeater-image\">";
					$_finalContent .= sprintf( '<img src="%s" alt="%s" style="display: block;">', $image_src, $name );
					$_finalContent .= "</div>";
					$_finalContent .= "<span>$name</span>";
					$_finalContent .= "</div>";
				}

			}
			$_finalContent .= "</div>";
		} else {
			$_finalContent = "$acf_group does not have any rows";
		}

		echo $_finalContent;
	}
}