<?php
/**
 * Customizer settings.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Category dropdown choices, cached per request.
 */
function indfir_category_choices() {
	static $choices = null;

	if ( null !== $choices ) {
		return $choices;
	}

	$choices = array( 0 => __( '— Semua kategori —', 'indfir' ) );

	$terms = get_categories(
		array(
			'hide_empty' => false,
			'orderby'    => 'name',
		)
	);

	foreach ( $terms as $term ) {
		$choices[ $term->term_id ] = $term->name;
	}

	return $choices;
}

function indfir_sanitize_checkbox( $value ) {
	return (bool) $value;
}

function indfir_sanitize_select( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	$choices = $control ? $control->choices : array();

	return array_key_exists( $value, $choices ) ? $value : $setting->default;
}

function indfir_sanitize_intval( $value ) {
	return absint( $value );
}

/**
 * Register everything.
 */
function indfir_customize_register( $wp_customize ) {

	/* --------------------------------------------------------------
	 * Site identity extras
	 * ----------------------------------------------------------- */
	$wp_customize->add_setting(
		'indfir_header_tagline',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'indfir_header_tagline',
		array(
			'label'       => __( 'Tagline di samping logo', 'indfir' ),
			'description' => __( 'Contoh: Elevating Your Future. Kosongkan untuk menyembunyikan.', 'indfir' ),
			'section'     => 'title_tagline',
			'type'        => 'text',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'indfir_header_tagline',
			array(
				'selector'        => '.if-brand__tagline',
				'render_callback' => function () {
					return get_theme_mod( 'indfir_header_tagline', '' );
				},
			)
		);
	}

	/* --------------------------------------------------------------
	 * Colours
	 * ----------------------------------------------------------- */
	$wp_customize->add_setting(
		'indfir_color_primary',
		array(
			'default'           => '#141e6e',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'indfir_color_primary',
			array(
				'label'       => __( 'Warna utama (navbar)', 'indfir' ),
				'section'     => 'colors',
			)
		)
	);

	$wp_customize->add_setting(
		'indfir_color_accent',
		array(
			'default'           => '#e3a21c',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'indfir_color_accent',
			array(
				'label'   => __( 'Warna aksen (kategori, link)', 'indfir' ),
				'section' => 'colors',
			)
		)
	);

	/* --------------------------------------------------------------
	 * Typography & layout
	 * ----------------------------------------------------------- */
	$wp_customize->add_section(
		'indfir_typography',
		array(
			'title'    => __( 'Tipografi & Layout', 'indfir' ),
			'priority' => 45,
		)
	);

	$wp_customize->add_setting(
		'indfir_google_fonts',
		array(
			'default'           => true,
			'sanitize_callback' => 'indfir_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'indfir_google_fonts',
		array(
			'label'       => __( 'Muat Google Fonts (Bitter + Jost)', 'indfir' ),
			'description' => __( 'Matikan bila ingin memakai font sistem saja agar lebih cepat.', 'indfir' ),
			'section'     => 'indfir_typography',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'indfir_font_heading',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_font_heading',
		array(
			'label'       => __( 'Font judul (CSS font stack)', 'indfir' ),
			'description' => __( "Kosongkan untuk bawaan. Contoh: 'Playfair Display', Georgia, serif", 'indfir' ),
			'section'     => 'indfir_typography',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'indfir_font_body',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_font_body',
		array(
			'label'   => __( 'Font isi (CSS font stack)', 'indfir' ),
			'section' => 'indfir_typography',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'indfir_container_width',
		array(
			'default'           => 1230,
			'sanitize_callback' => 'indfir_sanitize_intval',
		)
	);
	$wp_customize->add_control(
		'indfir_container_width',
		array(
			'label'       => __( 'Lebar kontainer (px)', 'indfir' ),
			'section'     => 'indfir_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1600,
				'step' => 10,
			),
		)
	);

	$wp_customize->add_setting(
		'indfir_excerpt_length',
		array(
			'default'           => 22,
			'sanitize_callback' => 'indfir_sanitize_intval',
		)
	);
	$wp_customize->add_control(
		'indfir_excerpt_length',
		array(
			'label'       => __( 'Panjang ringkasan (kata)', 'indfir' ),
			'section'     => 'indfir_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 8,
				'max'  => 60,
				'step' => 1,
			),
		)
	);

	/* --------------------------------------------------------------
	 * Header / top bar
	 * ----------------------------------------------------------- */
	$wp_customize->add_section(
		'indfir_header',
		array(
			'title'    => __( 'Header & Top Bar', 'indfir' ),
			'priority' => 46,
		)
	);

	$wp_customize->add_setting(
		'indfir_show_topbar',
		array(
			'default'           => true,
			'sanitize_callback' => 'indfir_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'indfir_show_topbar',
		array(
			'label'   => __( 'Tampilkan top bar', 'indfir' ),
			'section' => 'indfir_header',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'indfir_topbar_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_topbar_text',
		array(
			'label'       => __( 'Teks kiri top bar', 'indfir' ),
			'description' => __( 'Contoh: Jakarta. Tanggal hari ini ditampilkan otomatis di sebelahnya.', 'indfir' ),
			'section'     => 'indfir_header',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'indfir_show_date',
		array(
			'default'           => true,
			'sanitize_callback' => 'indfir_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'indfir_show_date',
		array(
			'label'   => __( 'Tampilkan tanggal hari ini', 'indfir' ),
			'section' => 'indfir_header',
			'type'    => 'checkbox',
		)
	);

	/* --------------------------------------------------------------
	 * Social
	 * ----------------------------------------------------------- */
	$wp_customize->add_section(
		'indfir_social',
		array(
			'title'    => __( 'Media Sosial', 'indfir' ),
			'priority' => 47,
		)
	);

	$socials = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'x'         => 'X / Twitter',
		'youtube'   => 'YouTube',
	);

	foreach ( $socials as $key => $label ) {
		$wp_customize->add_setting(
			'indfir_social_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'indfir_social_' . $key,
			array(
				'label'   => $label,
				'section' => 'indfir_social',
				'type'    => 'url',
			)
		);
	}

	/* --------------------------------------------------------------
	 * Homepage blocks
	 * ----------------------------------------------------------- */
	$wp_customize->add_section(
		'indfir_homepage',
		array(
			'title'       => __( 'Beranda: Blok Konten', 'indfir' ),
			'description' => __( 'Atur judul dan kategori tiap blok di halaman depan. Kosongkan judul untuk menyembunyikan blok.', 'indfir' ),
			'priority'    => 48,
		)
	);

	$wp_customize->add_setting(
		'indfir_hero_label',
		array(
			'default'           => __( 'Breaking news:', 'indfir' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_hero_label',
		array(
			'label'   => __( 'Label blok utama', 'indfir' ),
			'section' => 'indfir_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'indfir_popular_label',
		array(
			'default'           => __( 'Popular:', 'indfir' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_popular_label',
		array(
			'label'   => __( 'Label kolom populer', 'indfir' ),
			'section' => 'indfir_homepage',
			'type'    => 'text',
		)
	);

	// Four configurable category blocks.
	$blocks = array(
		1 => array( __( 'Blok 1 — 3 kolom', 'indfir' ), 'General' ),
		2 => array( __( 'Blok 2 — samping blok 1', 'indfir' ), 'Data/AI' ),
		3 => array( __( 'Blok 3 — samping blok 1', 'indfir' ), 'Astronomi' ),
		4 => array( __( 'Blok 4 — sorotan besar', 'indfir' ), 'Teknologi' ),
		5 => array( __( 'Blok 5 — sidebar blok 4', 'indfir' ), 'Sains' ),
		6 => array( __( 'Blok 6 — lebar penuh', 'indfir' ), 'Trading & Kripto' ),
	);

	foreach ( $blocks as $i => $data ) {
		$wp_customize->add_setting(
			'indfir_block' . $i . '_title',
			array(
				'default'           => $data[1],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'indfir_block' . $i . '_title',
			array(
				'label'   => $data[0] . ' — ' . __( 'judul', 'indfir' ),
				'section' => 'indfir_homepage',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'indfir_block' . $i . '_cat',
			array(
				'default'           => 0,
				'sanitize_callback' => 'indfir_sanitize_intval',
			)
		);
		$wp_customize->add_control(
			'indfir_block' . $i . '_cat',
			array(
				'label'   => $data[0] . ' — ' . __( 'kategori', 'indfir' ),
				'section' => 'indfir_homepage',
				'type'    => 'select',
				'choices' => indfir_category_choices(),
			)
		);
	}

	$wp_customize->add_setting(
		'indfir_latest_title',
		array(
			'default'           => __( 'Terbaru', 'indfir' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_latest_title',
		array(
			'label'       => __( 'Judul daftar artikel terbaru', 'indfir' ),
			'description' => __( 'Daftar ini yang membawa penomoran halaman di beranda.', 'indfir' ),
			'section'     => 'indfir_homepage',
			'type'        => 'text',
		)
	);

	/* --------------------------------------------------------------
	 * Single post
	 * ----------------------------------------------------------- */
	$wp_customize->add_section(
		'indfir_single',
		array(
			'title'    => __( 'Halaman Artikel', 'indfir' ),
			'priority' => 49,
		)
	);

	$toggles = array(
		'indfir_show_share'     => array( __( 'Tampilkan tombol berbagi', 'indfir' ), true ),
		'indfir_show_related'   => array( __( 'Tampilkan artikel terkait', 'indfir' ), true ),
		'indfir_show_author'    => array( __( 'Tampilkan kotak penulis', 'indfir' ), true ),
		'indfir_show_prevnext'  => array( __( 'Tampilkan navigasi sebelum/berikutnya', 'indfir' ), true ),
		'indfir_show_crumbs'    => array( __( 'Tampilkan breadcrumb', 'indfir' ), true ),
	);

	foreach ( $toggles as $key => $data ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $data[1],
				'sanitize_callback' => 'indfir_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $data[0],
				'section' => 'indfir_single',
				'type'    => 'checkbox',
			)
		);
	}

	/* --------------------------------------------------------------
	 * Footer
	 * ----------------------------------------------------------- */
	$wp_customize->add_section(
		'indfir_footer',
		array(
			'title'    => __( 'Footer', 'indfir' ),
			'priority' => 50,
		)
	);

	$wp_customize->add_setting(
		'indfir_footer_about_title',
		array(
			'default'           => __( 'Website', 'indfir' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_footer_about_title',
		array(
			'label'   => __( 'Judul kolom pertama', 'indfir' ),
			'section' => 'indfir_footer',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'indfir_footer_about',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'indfir_footer_about',
		array(
			'label'   => __( 'Deskripsi footer', 'indfir' ),
			'section' => 'indfir_footer',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'indfir_footer_copyright',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'indfir_footer_copyright',
		array(
			'label'       => __( 'Teks hak cipta', 'indfir' ),
			'description' => __( 'Kosongkan untuk memakai "© tahun nama situs".', 'indfir' ),
			'section'     => 'indfir_footer',
			'type'        => 'text',
		)
	);

	// Live preview for core fields.
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'indfir_customize_register' );

/**
 * Live preview script.
 */
function indfir_customize_preview_js() {
	wp_enqueue_script(
		'indfir-customizer',
		get_template_directory_uri() . '/assets/js/customizer.js',
		array( 'customize-preview' ),
		INDFIR_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'indfir_customize_preview_js' );
