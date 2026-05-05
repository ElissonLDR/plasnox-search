<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

class Plasnox_Search_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name()        { return 'plasnox_search'; }
	public function get_title()       { return 'Plasnox Search'; }
	public function get_icon()        { return 'eicon-search'; }
	public function get_categories()  { return [ 'general' ]; }
	public function get_keywords()    { return [ 'busca', 'pesquisa', 'search', 'plasnox', 'produtos' ]; }

	public function get_style_depends()  { return [ 'plasnox-search' ]; }
	public function get_script_depends() { return [ 'plasnox-search' ]; }

	// ════════════════════════════════════════════════
	// CONTROLS
	// ════════════════════════════════════════════════

	protected function register_controls() {

		// ── ABA: CONTEÚDO ────────────────────────────

		$this->start_controls_section( 'section_settings', [
			'label' => 'Configurações',
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

			$this->add_responsive_control( 'display_mode', [
				'label'          => 'Modo de exibição',
				'type'           => Controls_Manager::SELECT,
				'options'        => [
					'closed' => 'Ícone (expandir ao clicar)',
					'open'   => 'Sempre aberto',
				],
				'default'        => 'closed',
				'tablet_default' => 'closed',
				'mobile_default' => 'closed',
				'description'    => 'Escolha como o campo de busca aparece em cada dispositivo.',
			] );

			$this->add_responsive_control( 'field_width', [
				'label'      => 'Largura do campo (px)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 140, 'max' => 800, 'step' => 10 ] ],
				'default'         => [ 'unit' => 'px', 'size' => 400 ],
				'tablet_default'  => [ 'unit' => 'px', 'size' => 300 ],
				'mobile_default'  => [ 'unit' => 'px', 'size' => 240 ],
				'selectors'  => [
					'{{WRAPPER}} .pls-wrapper' => '--pls-field-w: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pls-input'   => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pls-results' => 'width: calc({{SIZE}}{{UNIT}} + var(--pls-btn-w, 46px)); max-width: 96vw;',
				],
			] );

			$this->add_control( 'placeholder', [
				'label'       => 'Placeholder',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Buscar produtos, categorias e soluções...',
				'label_block' => true,
			] );

		$this->end_controls_section();

		// ── ABA: ESTILO — BOTÃO / ÍCONE ──────────────

		$this->start_controls_section( 'section_style_btn', [
			'label' => 'Botão / Ícone',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

			$this->add_responsive_control( 'pls_btn_size', [
				'label'      => 'Tamanho do botão (px)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 32, 'max' => 80 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 46 ],
				'selectors'  => [
					'{{WRAPPER}} .pls-toggle'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pls-wrapper' => '--pls-btn-w: {{SIZE}}{{UNIT}};',
				],
			] );

			$this->add_responsive_control( 'icon_size', [
				'label'      => 'Tamanho do ícone',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 12, 'max' => 40 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 20 ],
				'selectors'  => [
					'{{WRAPPER}} .pls-toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			] );

			$this->start_controls_tabs( 'tabs_btn' );

				$this->start_controls_tab( 'tab_btn_normal', [ 'label' => 'Normal' ] );

					$this->add_control( 'icon_color', [
						'label'     => 'Cor do ícone',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#00609F',
						'selectors' => [ '{{WRAPPER}} .pls-toggle' => 'color: {{VALUE}};' ],
					] );

					$this->add_control( 'btn_bg', [
						'label'     => 'Cor de fundo',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => [ '{{WRAPPER}} .pls-toggle' => 'background-color: {{VALUE}};' ],
					] );

					$this->add_group_control( Group_Control_Border::get_type(), [
						'name'     => 'btn_border',
						'selector' => '{{WRAPPER}} .pls-toggle',
						'fields_options' => [
							'border' => [ 'default' => 'solid' ],
							'width'  => [ 'default' => [ 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2', 'isLinked' => true ] ],
							'color'  => [ 'default' => '#d0d8e4' ],
						],
					] );

					$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
						'name'     => 'btn_shadow',
						'selector' => '{{WRAPPER}} .pls-toggle',
						'fields_options' => [
							'box_shadow_type' => [ 'default' => 'yes' ],
							'box_shadow'      => [
								'default' => [
									'horizontal' => 0, 'vertical' => 2, 'blur' => 8,
									'spread' => 0, 'color' => 'rgba(0,14,39,0.08)',
								],
							],
						],
					] );

				$this->end_controls_tab();

				$this->start_controls_tab( 'tab_btn_hover', [ 'label' => 'Hover' ] );

					$this->add_control( 'icon_color_hover', [
						'label'     => 'Cor do ícone',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#00609F',
						'selectors' => [ '{{WRAPPER}} .pls-toggle:hover' => 'color: {{VALUE}};' ],
					] );

					$this->add_control( 'btn_bg_hover', [
						'label'     => 'Cor de fundo',
						'type'      => Controls_Manager::COLOR,
						'selectors' => [ '{{WRAPPER}} .pls-toggle:hover' => 'background-color: {{VALUE}};' ],
					] );

					$this->add_control( 'btn_border_color_hover', [
						'label'     => 'Cor da borda',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#00609F',
						'selectors' => [ '{{WRAPPER}} .pls-toggle:hover' => 'border-color: {{VALUE}};' ],
					] );

					$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
						'name'     => 'btn_shadow_hover',
						'selector' => '{{WRAPPER}} .pls-toggle:hover',
					] );

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control( 'btn_border_radius', [
				'label'      => 'Raio da borda',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px', 'isLinked' => true ],
				'selectors'  => [
					'{{WRAPPER}} .pls-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			] );

		$this->end_controls_section();

		// ── ABA: ESTILO — CAMPO DE BUSCA ─────────────

		$this->start_controls_section( 'section_style_input', [
			'label' => 'Campo de Busca',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .pls-input',
			] );

			$this->add_control( 'input_color', [
				'label'     => 'Cor do texto',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000E27',
				'selectors' => [ '{{WRAPPER}} .pls-input' => 'color: {{VALUE}};' ],
			] );

			$this->add_control( 'input_placeholder_color', [
				'label'     => 'Cor do placeholder',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#aab4c0',
				'selectors' => [
					'{{WRAPPER}} .pls-input::placeholder'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .pls-input::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			] );

			$this->add_control( 'input_bg', [
				'label'     => 'Cor de fundo',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [ '{{WRAPPER}} .pls-input' => 'background-color: {{VALUE}};' ],
				'separator' => 'before',
			] );

			$this->add_control( 'input_border_color', [
				'label'     => 'Cor da borda',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00609F',
				'selectors' => [ '{{WRAPPER}} .pls-input' => 'border-color: {{VALUE}};' ],
			] );

			$this->add_responsive_control( 'input_border_width', [
				'label'      => 'Espessura da borda (px)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 8 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 2 ],
				'selectors'  => [ '{{WRAPPER}} .pls-input' => 'border-width: {{SIZE}}{{UNIT}};' ],
			] );

			$this->add_responsive_control( 'input_border_radius', [
				'label'      => 'Raio da borda',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'top' => '8', 'right' => '0', 'bottom' => '0', 'left' => '8', 'unit' => 'px', 'isLinked' => false ],
				'selectors'  => [
					'{{WRAPPER}} .pls-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			] );

			$this->add_responsive_control( 'input_height', [
				'label'      => 'Altura (px)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 32, 'max' => 80 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 46 ],
				'selectors'  => [
					'{{WRAPPER}} .pls-input'  => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pls-toggle' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			] );

			$this->add_responsive_control( 'input_padding', [
				'label'      => 'Padding interno',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [ 'top' => '0', 'right' => '16', 'bottom' => '0', 'left' => '16', 'unit' => 'px', 'isLinked' => false ],
				'selectors'  => [ '{{WRAPPER}} .pls-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			] );

		$this->end_controls_section();

		// ── ABA: ESTILO — DROPDOWN ───────────────────

		$this->start_controls_section( 'section_style_results', [
			'label' => 'Resultados — Dropdown',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

			$this->add_control( 'results_bg', [
				'label'     => 'Cor de fundo',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [ '{{WRAPPER}} .pls-results' => 'background-color: {{VALUE}};' ],
			] );

			$this->add_control( 'results_border_color', [
				'label'     => 'Cor da borda',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d0d8e4',
				'selectors' => [ '{{WRAPPER}} .pls-results' => 'border-color: {{VALUE}};' ],
			] );

			$this->add_responsive_control( 'results_border_radius', [
				'label'      => 'Raio da borda',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px', 'isLinked' => true ],
				'selectors'  => [
					'{{WRAPPER}} .pls-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			] );

			$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
				'name'     => 'results_shadow',
				'selector' => '{{WRAPPER}} .pls-results',
				'fields_options' => [
					'box_shadow_type' => [ 'default' => 'yes' ],
					'box_shadow'      => [
						'default' => [
							'horizontal' => 0, 'vertical' => 8, 'blur' => 32,
							'spread' => 0, 'color' => 'rgba(0,14,39,0.13)',
						],
					],
				],
			] );

			$this->add_responsive_control( 'results_max_height', [
				'label'      => 'Altura máxima (px)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 150, 'max' => 800 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 460 ],
				'selectors'  => [ '{{WRAPPER}} .pls-results' => 'max-height: {{SIZE}}{{UNIT}};' ],
				'separator'  => 'before',
			] );

		$this->end_controls_section();

		// ── ABA: ESTILO — GRUPOS ─────────────────────

		$this->start_controls_section( 'section_style_groups', [
			'label' => 'Resultados — Rótulos de Grupo',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'     => 'group_typography',
				'selector' => '{{WRAPPER}} .pls-group',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [ 'default' => [ 'unit' => 'px', 'size' => 10 ] ],
					'font_weight' => [ 'default' => '700' ],
					'letter_spacing' => [ 'default' => [ 'unit' => 'em', 'size' => 0.1 ] ],
					'text_transform' => [ 'default' => 'uppercase' ],
				],
			] );

			$this->add_control( 'group_color', [
				'label'     => 'Cor do texto',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00609F',
				'selectors' => [ '{{WRAPPER}} .pls-group' => 'color: {{VALUE}};' ],
			] );

			$this->add_control( 'group_bg', [
				'label'     => 'Cor de fundo',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f4f8fc',
				'selectors' => [ '{{WRAPPER}} .pls-group' => 'background-color: {{VALUE}};' ],
			] );

			$this->add_responsive_control( 'group_padding', [
				'label'      => 'Padding',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [ 'top' => '9', 'right' => '16', 'bottom' => '6', 'left' => '16', 'unit' => 'px', 'isLinked' => false ],
				'selectors'  => [ '{{WRAPPER}} .pls-group' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			] );

		$this->end_controls_section();

		// ── ABA: ESTILO — CARDS ──────────────────────

		$this->start_controls_section( 'section_style_items', [
			'label' => 'Resultados — Cards',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

			$this->start_controls_tabs( 'tabs_items' );

				$this->start_controls_tab( 'tab_item_normal', [ 'label' => 'Normal' ] );

					$this->add_control( 'item_bg', [
						'label'     => 'Cor de fundo',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => [ '{{WRAPPER}} a.pls-item' => 'background-color: {{VALUE}};' ],
					] );

					$this->add_control( 'item_title_color', [
						'label'     => 'Cor do título',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#000E27',
						'selectors' => [ '{{WRAPPER}} .pls-title' => 'color: {{VALUE}};' ],
					] );

					$this->add_control( 'item_meta_color', [
						'label'     => 'Cor da meta (SKU, qtd)',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#7A7A7A',
						'selectors' => [ '{{WRAPPER}} .pls-meta' => 'color: {{VALUE}};' ],
					] );

					$this->add_control( 'item_arrow_color', [
						'label'     => 'Cor da seta',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#c8d4de',
						'selectors' => [ '{{WRAPPER}} .pls-arr' => 'color: {{VALUE}};' ],
					] );

				$this->end_controls_tab();

				$this->start_controls_tab( 'tab_item_hover', [ 'label' => 'Hover' ] );

					$this->add_control( 'item_bg_hover', [
						'label'     => 'Cor de fundo',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#eef5fb',
						'selectors' => [ '{{WRAPPER}} a.pls-item:hover, {{WRAPPER}} a.pls-item.pls-focused' => 'background-color: {{VALUE}};' ],
					] );

					$this->add_control( 'item_title_color_hover', [
						'label'     => 'Cor do título',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#00609F',
						'selectors' => [
							'{{WRAPPER}} a.pls-item:hover .pls-title' => 'color: {{VALUE}};',
							'{{WRAPPER}} a.pls-item.pls-focused .pls-title' => 'color: {{VALUE}};',
						],
					] );

					$this->add_control( 'item_arrow_color_hover', [
						'label'     => 'Cor da seta',
						'type'      => Controls_Manager::COLOR,
						'default'   => '#FFC212',
						'selectors' => [
							'{{WRAPPER}} a.pls-item:hover .pls-arr' => 'color: {{VALUE}};',
							'{{WRAPPER}} a.pls-item.pls-focused .pls-arr' => 'color: {{VALUE}};',
						],
					] );

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'     => 'item_title_typography',
				'label'    => 'Tipografia do título',
				'selector' => '{{WRAPPER}} .pls-title',
				'separator' => 'before',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ],
					'font_weight' => [ 'default' => '600' ],
				],
			] );

			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'     => 'item_meta_typography',
				'label'    => 'Tipografia da meta',
				'selector' => '{{WRAPPER}} .pls-meta',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [ 'default' => [ 'unit' => 'px', 'size' => 12 ] ],
					'font_weight' => [ 'default' => '400' ],
				],
			] );

			$this->add_responsive_control( 'item_padding', [
				'label'      => 'Padding do card',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [ 'top' => '10', 'right' => '16', 'bottom' => '10', 'left' => '16', 'unit' => 'px', 'isLinked' => false ],
				'selectors'  => [ '{{WRAPPER}} a.pls-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
				'separator'  => 'before',
			] );

			$this->add_responsive_control( 'thumb_size', [
				'label'      => 'Tamanho da imagem (px)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 24, 'max' => 80 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 44 ],
				'selectors'  => [
					'{{WRAPPER}} .pls-thumb'    => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pls-thumb-ph' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			] );

			$this->add_responsive_control( 'thumb_border_radius', [
				'label'      => 'Raio da imagem',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [ 'top' => '6', 'right' => '6', 'bottom' => '6', 'left' => '6', 'unit' => 'px', 'isLinked' => true ],
				'selectors'  => [
					'{{WRAPPER}} .pls-thumb'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .pls-thumb-ph' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			] );

		$this->end_controls_section();
	}

	// ════════════════════════════════════════════════
	// RENDER
	// ════════════════════════════════════════════════

	protected function render() {
		$s = $this->get_settings_for_display();

		$mode_d = ! empty( $s['display_mode'] )         ? $s['display_mode']         : 'closed';
		$mode_t = ! empty( $s['display_mode_tablet'] )  ? $s['display_mode_tablet']  : $mode_d;
		$mode_m = ! empty( $s['display_mode_mobile'] )  ? $s['display_mode_mobile']  : $mode_t;

		$placeholder = ! empty( $s['placeholder'] ) ? esc_attr( $s['placeholder'] ) : 'Buscar produtos, categorias e soluções...';

		$this->add_render_attribute( 'wrapper', [
			'class'        => 'pls-wrapper',
			'id'           => 'pls-wrapper-' . $this->get_id(),
			'data-mode-d'  => esc_attr( $mode_d ),
			'data-mode-t'  => esc_attr( $mode_t ),
			'data-mode-m'  => esc_attr( $mode_m ),
			'data-wid'     => esc_attr( $this->get_id() ),
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="pls-expand" id="pls-expand-<?php echo esc_attr( $this->get_id() ); ?>">
				<input
					type="text"
					id="pls-input-<?php echo esc_attr( $this->get_id() ); ?>"
					class="pls-input"
					placeholder="<?php echo $placeholder; ?>"
					autocomplete="off"
					tabindex="-1"
				/>
				<span class="pls-spinner"></span>
			</div>

			<button class="pls-toggle"
				id="pls-toggle-<?php echo esc_attr( $this->get_id() ); ?>"
				aria-label="Abrir busca"
				aria-expanded="false"
			>
				<span class="pls-icon-search" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
					</svg>
				</span>
				<span class="pls-icon-close" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
					</svg>
				</span>
			</button>

			<div class="pls-results" id="pls-results-<?php echo esc_attr( $this->get_id() ); ?>"></div>
		</div>
		<?php
	}
}
