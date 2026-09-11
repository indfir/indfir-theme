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

	$choices = array( 0 => __( '— All categories —', 'indfir' ) );

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
			'label'       => __( 'Tagline next to logo', 'indfir' ),
			'description' => __( 'Example: Elevating Your Future. Leave empty to hide.', 'indfir' ),
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
				'label'       => __( 'Primary color (navbar)', 'indfir' ),
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
				'label'   => __( 'Accent color (categories, links)', 'indfir' ),
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
			'title'    => __( 'Typography & Layout', 'indfir' ),
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
			'label'       => __( 'Load Google Fonts (Bitter + Jost)', 'indfir' ),
			'description' => __( 'Turn off to use system fonts only for faster performance.', 'indfir' ),
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
			'label'       => __( 'Heading font (CSS font stack)', 'indfir' ),
			'description' => __( "Leave empty for default. Example: 'Playfair Display', Georgia, serif", 'indfir' ),
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
			'label'   => __( 'Body font (CSS font stack)', 'indfir' ),
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
			'label'       => __( 'Container width (px)', 'indfir' ),
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
			'label'       => __( 'Excerpt length (words)', 'indfir' ),
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
			'label'   => __( 'Show top bar', 'indfir' ),
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
			'label'       => __( 'Top bar left text', 'indfir' ),
			'description' => __( 'Example: Jakarta. Today\'s date is displayed automatically next to it.', 'indfir' ),
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
			'label'   => __( 'Show today\'s date', 'indfir' ),
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
			'title'    => __( 'Social Media', 'indfir' ),
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
			'title'       => __( 'Homepage: Content Blocks', 'indfir' ),
			'description' => __( 'Set the title and category for each block on the front page. Leave the title empty to hide the block.', 'indfir' ),
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
			'label'   => __( 'Main block label', 'indfir' ),
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
			'label'   => __( 'Popular column label', 'indfir' ),
			'section' => 'indfir_homepage',
			'type'    => 'text',
		)
	);

	// Four configurable category blocks.
	$blocks = array(
		1 => array( __( 'Block 1 — 3 columns', 'indfir' ), 'General' ),
		2 => array( __( 'Block 2 — beside block 1', 'indfir' ), 'Data/AI' ),
		3 => array( __( 'Block 3 — beside block 1', 'indfir' ), 'Astronomy' ),
		4 => array( __( 'Block 4 — large feature', 'indfir' ), 'Technology' ),
		5 => array( __( 'Block 5 — block 4 sidebar', 'indfir' ), 'Science' ),
		6 => array( __( 'Block 6 — full width', 'indfir' ), 'Trading & Crypto' ),
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
				'label'   => $data[0] . ' — ' . __( 'title', 'indfir' ),
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
				'label'   => $data[0] . ' — ' . __( 'category', 'indfir' ),
				'section' => 'indfir_homepage',
				'type'    => 'select',
				'choices' => indfir_category_choices(),
			)
		);
	}

	$wp_customize->add_setting(
		'indfir_latest_title',
		array(
			'default'           => __( 'Latest', 'indfir' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'indfir_latest_title',
		array(
			'label'       => __( 'Latest articles list title', 'indfir' ),
			'description' => __( 'This is the list that carries page numbering on the homepage.', 'indfir' ),
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
			'title'    => __( 'Article Page', 'indfir' ),
			'priority' => 49,
		)
	);

	$toggles = array(
		'indfir_show_share'     => array( __( 'Show share buttons', 'indfir' ), true ),
		'indfir_show_related'   => array( __( 'Show related articles', 'indfir' ), true ),
		'indfir_show_author'    => array( __( 'Show author box', 'indfir' ), true ),
		'indfir_show_prevnext'  => array( __( 'Show previous/next navigation', 'indfir' ), true ),
		'indfir_show_crumbs'    => array( __( 'Show breadcrumbs', 'indfir' ), true ),
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
			'label'   => __( 'First column title', 'indfir' ),
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
			'label'   => __( 'Footer description', 'indfir' ),
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
			'label'       => __( 'Copyright text', 'indfir' ),
			'description' => __( 'Leave empty to use "© year site name".', 'indfir' ),
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
