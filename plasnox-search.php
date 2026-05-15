<?php
/**
 * Plugin Name: Plasnox Search
 * Description: Busca unificada de produtos WooCommerce, categorias e páginas da linha industrial (Metais).
 * Version: 1.5.0
 * Author: Elisson Rodrigues
 * Text Domain: plasnox-search
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Plasnox_Search' ) ) :

class Plasnox_Search {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'plasnox_search', array( $this, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts',      array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_plasnox_search',        array( $this, 'handle_ajax' ) );
		add_action( 'wp_ajax_nopriv_plasnox_search', array( $this, 'handle_ajax' ) );

		// Garante que o shortcode funciona dentro do Elementor Text Editor
		add_filter( 'widget_text', 'do_shortcode' );
	}

	// ─────────────────────────────────────────────
	// Assets
	// ─────────────────────────────────────────────

	public function enqueue_assets() {
		$base = plugin_dir_url( __FILE__ );

		wp_enqueue_style(
			'plasnox-search',
			$base . 'assets/plasnox-search.css',
			array(),
			'1.5.0'
		);

		wp_enqueue_script(
			'plasnox-search',
			$base . 'assets/plasnox-search.js',
			array( 'jquery' ),
			'1.5.0',
			true
		);

		wp_localize_script( 'plasnox-search', 'PlasSearch', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'pls_nonce' ),
			'i18n'     => array(
				'products'    => 'Produtos',
				'categories'  => 'Categorias',
				'solutions'   => 'Soluções Industriais',
				'plastico'    => 'Linha Plástico',
				'no_results'  => 'Nenhum resultado encontrado.',
				'placeholder' => 'Buscar produtos, categorias e soluções...',
			),
		) );
	}

	// ─────────────────────────────────────────────
	// Shortcode HTML
	// ─────────────────────────────────────────────

	public function render_shortcode( $atts ) {
		ob_start();
		?>
		<div class="pls-wrapper" id="pls-wrapper">
			<div class="pls-expand" id="pls-expand">
				<input
					type="text"
					id="pls-input"
					class="pls-input"
					placeholder="Buscar produtos, categorias e soluções..."
					autocomplete="off"
					tabindex="-1"
				/>
				<span class="pls-spinner" id="pls-spinner"></span>
			</div>
			<button class="pls-toggle" id="pls-toggle" aria-label="Abrir busca" aria-expanded="false">
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
			<div class="pls-results" id="pls-results"></div>
		</div>
		<?php
		return ob_get_clean();
	}

	// ─────────────────────────────────────────────
	// AJAX
	// ─────────────────────────────────────────────

	public function handle_ajax() {
		if ( ! check_ajax_referer( 'pls_nonce', 'nonce', false ) ) {
			wp_die( '', '', array( 'response' => 403 ) );
		}

		$q = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';

		if ( mb_strlen( trim( $q ) ) < 2 ) {
			wp_send_json_success( array( 'products' => array(), 'categories' => array(), 'solutions' => array(), 'plastico' => array() ) );
		}

		wp_send_json_success( array(
			'products'   => $this->search_products( $q ),
			'categories' => $this->search_categories( $q ),
			'solutions'  => $this->search_solutions( $q ),
			'plastico'   => $this->search_plastico( $q ),
		) );
	}

	// ─────────────────────────────────────────────
	// Produtos WooCommerce
	// ─────────────────────────────────────────────

	private function search_products( $query ) {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return array();
		}

		$post_ids = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			's'              => $query,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'suppress_filters' => false,
		) );

		$results = array();

		foreach ( (array) $post_ids as $id ) {
			$id      = (int) $id;
			$product = wc_get_product( $id );
			if ( ! $product ) {
				continue;
			}

			$img_id = $product->get_image_id();
			$thumb  = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';

			$results[] = array(
				'id'    => $id,
				'title' => $product->get_name(),
				'url'   => get_permalink( $id ),
				'thumb' => $thumb ? $thumb : '',
				'meta'  => $product->get_sku() ? 'SKU: ' . $product->get_sku() : '',
			);
		}

		return $results;
	}

	// ─────────────────────────────────────────────
	// Categorias WooCommerce
	// ─────────────────────────────────────────────

	private function search_categories( $query ) {
		$terms = get_terms( array(
			'taxonomy'   => 'product_cat',
			'search'     => $query,
			'hide_empty' => false,
			'number'     => 6,
		) );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		$results = array();

		foreach ( $terms as $term ) {
			$link = get_term_link( $term );
			if ( is_wp_error( $link ) ) {
				continue;
			}

			$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			$thumb    = $thumb_id ? wp_get_attachment_image_url( (int) $thumb_id, 'thumbnail' ) : '';

			$results[] = array(
				'id'    => $term->term_id,
				'title' => $term->name,
				'url'   => $link,
				'thumb' => $thumb ? $thumb : '',
				'meta'  => $term->count . ( $term->count === 1 ? ' produto' : ' produtos' ),
			);
		}

		return $results;
	}

	// ─────────────────────────────────────────────
	// Páginas Metais (soluções industriais)
	// ─────────────────────────────────────────────

	private function search_solutions( $query ) {
		$metais_id = $this->get_metais_id();

		if ( ! $metais_id ) {
			return array();
		}

		// Filhos diretos do Metais usando get_posts (suporta fields=ids corretamente)
		$child_ids = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_parent'    => $metais_id,
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		) );

		// Escopo: Metais + todos os filhos
		$scope = array_merge( array( $metais_id ), (array) $child_ids );
		$scope = array_map( 'intval', $scope );
		$scope = array_unique( array_filter( $scope ) );

		if ( empty( $scope ) ) {
			return array();
		}

		// Filtra pelo título dentro do escopo
		$matched = array();
		foreach ( $scope as $id ) {
			$title = get_the_title( $id );
			if ( $title && false !== mb_stripos( $title, $query ) ) {
				$matched[] = $id;
			}
		}

		// Se não encontrou por título, tenta busca nativa (pega post_content também)
		if ( empty( $matched ) ) {
			$matched = get_posts( array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post__in'       => $scope,
				's'              => $query,
				'posts_per_page' => 8,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			) );
			$matched = (array) $matched;
		}

		$results = array();
		foreach ( array_slice( $matched, 0, 8 ) as $id ) {
			$id       = (int) $id;
			$thumb_id = get_post_thumbnail_id( $id );
			$thumb    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'thumbnail' ) : '';

			$results[] = array(
				'id'    => $id,
				'title' => get_the_title( $id ),
				'url'   => get_permalink( $id ),
				'thumb' => $thumb ? $thumb : '',
				'meta'  => 'Solução Industrial',
			);
		}

		return $results;
	}

	// ─────────────────────────────────────────────
	// Linha Plástico (itens fixos — todos apontam para /solucoes/plastico/)
	// ─────────────────────────────────────────────

	private function search_plastico( $query ) {
		$url = home_url( '/solucoes/plastico/' );

		$items = array(
			array(
				'title' => 'Plástico',
				'meta'  => 'Linha Plástico',
				'thumb' => '',
			),
			array(
				'title' => 'Preformas PET',
				'meta'  => 'Linha Plástico',
				'thumb' => 'https://plasnox.com.br/wp-content/uploads/2026/05/Imagem-destaque-Preformas-PET-300x200.jpg',
			),
			array(
				'title' => 'Resinas',
				'meta'  => 'Linha Plástico',
				'thumb' => 'https://plasnox.com.br/wp-content/uploads/2026/05/Imagem-destaque-Resinas-300x200.jpg',
			),
			array(
				'title' => 'Garrafas',
				'meta'  => 'Linha Plástico',
				'thumb' => 'https://plasnox.com.br/wp-content/uploads/2026/05/Imagem-destaque-Garrafas-300x200.jpg',
			),
			array(
				'title' => 'Tampas',
				'meta'  => 'Linha Plástico',
				'thumb' => 'https://plasnox.com.br/wp-content/uploads/2026/05/Imagem-destaque-Tampas-300x200.jpg',
			),
			array(
				'title' => 'Alças',
				'meta'  => 'Linha Plástico',
				'thumb' => 'https://plasnox.com.br/wp-content/uploads/2026/05/Imagem-destaque-Alcas-300x200.jpg',
			),
			array(
				'title' => 'Projetos',
				'meta'  => 'Linha Plástico',
				'thumb' => 'https://plasnox.com.br/wp-content/uploads/2026/05/Imagem-destaque-Projetos-300x200.jpg',
			),
		);

		$q_norm = $this->remove_accents_lower( $query );

		$results = array();
		foreach ( $items as $item ) {
			if ( mb_stripos( $this->remove_accents_lower( $item['title'] ), $q_norm ) !== false ) {
				$results[] = array(
					'title' => $item['title'],
					'url'   => $url,
					'thumb' => $item['thumb'],
					'meta'  => $item['meta'],
				);
			}
		}

		return $results;
	}

	private function remove_accents_lower( $str ) {
		return mb_strtolower( remove_accents( $str ) );
	}

	// ─────────────────────────────────────────────
	// Localiza a página Metais
	// ─────────────────────────────────────────────

	private function get_metais_id() {
		// Tenta pelo caminho hierárquico
		$page = get_page_by_path( 'solucoes/metais' );
		if ( $page ) {
			return (int) $page->ID;
		}

		// Fallback: slug 'metais' em qualquer posição
		$rows = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'name'           => 'metais',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		) );

		return ! empty( $rows ) ? (int) $rows[0] : 0;
	}
}

Plasnox_Search::get_instance();

// ── Elementor Widget ─────────────────────────────────────────────────────────
add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}
	require_once plugin_dir_path( __FILE__ ) . 'includes/widget-elementor.php';
	$widgets_manager->register( new Plasnox_Search_Elementor_Widget() );
} );

endif; // class_exists
