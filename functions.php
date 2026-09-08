<?php
/**
 * Indfir theme bootstrap.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

define( 'INDFIR_VERSION', '1.5.1' );

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */

function indfir_setup() {
	load_theme_textdomain( 'indfir', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu Utama (navbar)', 'indfir' ),
			'topbar'  => __( 'Menu Atas (top bar)', 'indfir' ),
			'footer'  => __( 'Menu Footer', 'indfir' ),
		)
	);

	// Image sizes used across the layout blocks.
	add_image_size( 'indfir-lead', 800, 500, true );   // lead / hero.
	add_image_size( 'indfir-card', 520, 320, true );   // standard card.
	add_image_size( 'indfir-hero', 700, 560, true );   // tall overlay hero.
	add_image_size( 'indfir-thumb', 150, 120, true );  // list thumbnail.

	// Content width for oEmbeds.
	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 820;
	}
}
add_action( 'after_setup_theme', 'indfir_setup' );

function indfir_image_size_names( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'indfir-lead' => __( 'Indfir Lead', 'indfir' ),
			'indfir-card' => __( 'Indfir Card', 'indfir' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'indfir_image_size_names' );

/* -------------------------------------------------------------------------
 * Assets
 * ---------------------------------------------------------------------- */

function indfir_scripts() {
	$theme_uri = get_template_directory_uri();

	if ( get_theme_mod( 'indfir_google_fonts', true ) ) {
		wp_enqueue_style(
			'indfir-fonts',
			'https://fonts.googleapis.com/css2?family=Bitter:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Jost:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap',
			array(),
			null
		);
	}

	wp_enqueue_style( 'indfir-style', get_stylesheet_uri(), array(), INDFIR_VERSION );
	wp_add_inline_style( 'indfir-style', indfir_inline_css() );

	wp_enqueue_script( 'indfir-theme', $theme_uri . '/assets/js/theme.js', array(), INDFIR_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'indfir_scripts' );

/**
 * Customizer values are injected as CSS custom properties.
 */
function indfir_inline_css() {
	$vars = array(
		'--if-navy'      => get_theme_mod( 'indfir_color_primary', '#141e6e' ),
		'--if-navy-dark' => indfir_darken( get_theme_mod( 'indfir_color_primary', '#141e6e' ), 22 ),
		'--if-accent'    => get_theme_mod( 'indfir_color_accent', '#e3a21c' ),
		'--if-wrap'      => absint( get_theme_mod( 'indfir_container_width', 1230 ) ) . 'px',
	);

	$head = get_theme_mod( 'indfir_font_heading', '' );
	$body = get_theme_mod( 'indfir_font_body', '' );
	if ( $head ) {
		$vars['--if-font-head'] = $head;
	}
	if ( $body ) {
		$vars['--if-font-body'] = $body;
	}

	$css = ':root{';
	foreach ( $vars as $key => $value ) {
		$css .= $key . ':' . $value . ';';
	}
	$css .= '}';

	$css .= '.if-card__thumb--placeholder{display:block;background:var(--if-bg-soft,#f3f4f6);text-decoration:none;overflow:hidden}';
	$css .= '.if-placeholder{display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);color:#fff;border-radius:4px;transition:transform .2s ease}';
	$css .= '.if-card:not(.if-card--row) .if-placeholder{min-height:160px}';
	$css .= '.if-card--row .if-placeholder{aspect-ratio:4/3;min-height:75px}';
	$css .= '.if-card:hover .if-placeholder{transform:scale(1.02)}';
	$css .= '.if-placeholder__mark{font-family:var(--if-font-head,serif);font-weight:700;font-size:18px;letter-spacing:2px;text-transform:uppercase;color:#fff;opacity:.7}';
	$css .= '.if-card--row .if-placeholder__mark{font-size:12px;letter-spacing:1px}';

	return $css;
}

/**
 * Darken a hex colour by a percentage. Used for nav dropdown / search bar shades.
 */
function indfir_darken( $hex, $percent ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
		return '#0d1550';
	}

	$out = '#';
	for ( $i = 0; $i < 3; $i++ ) {
		$channel = hexdec( substr( $hex, $i * 2, 2 ) );
		$channel = max( 0, (int) round( $channel * ( 100 - $percent ) / 100 ) );
		$out    .= str_pad( dechex( $channel ), 2, '0', STR_PAD_LEFT );
	}

	return $out;
}

/* -------------------------------------------------------------------------
 * Widget areas
 * ---------------------------------------------------------------------- */

function indfir_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar Utama', 'indfir' ),
			'id'            => 'sidebar-main',
			'description'   => __( 'Tampil di samping artikel dan arsip.', 'indfir' ),
			'before_widget' => '<section id="%1$s" class="if-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="if-widget__title">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Kolom %d', 'indfir' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => __( 'Kosongkan untuk memakai tampilan footer bawaan.', 'indfir' ),
				'before_widget' => '<section id="%1$s" class="if-widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="if-widget__title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'indfir_widgets_init' );

/* -------------------------------------------------------------------------
 * Excerpts
 * ---------------------------------------------------------------------- */

function indfir_excerpt_length() {
	return (int) get_theme_mod( 'indfir_excerpt_length', 22 );
}
add_filter( 'excerpt_length', 'indfir_excerpt_length', 999 );

function indfir_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'indfir_excerpt_more' );

/* -------------------------------------------------------------------------
 * Body classes
 * ---------------------------------------------------------------------- */

function indfir_body_classes( $classes ) {
	if ( ! is_active_sidebar( 'sidebar-main' ) ) {
		$classes[] = 'if-no-sidebar';
	}
	if ( is_singular() && has_post_thumbnail() ) {
		$classes[] = 'if-has-thumb';
	}
	return $classes;
}
add_filter( 'body_class', 'indfir_body_classes' );

/* -------------------------------------------------------------------------
 * Post views — powers the "Popular" blocks without a plugin.
 * ---------------------------------------------------------------------- */

function indfir_track_views() {
	if ( ! is_singular( 'post' ) || is_user_logged_in() ) {
		return;
	}

	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		return;
	}

	$views = (int) get_post_meta( $post_id, '_indfir_views', true );
	update_post_meta( $post_id, '_indfir_views', $views + 1 );
}
add_action( 'wp_head', 'indfir_track_views' );

/* -------------------------------------------------------------------------
 * Includes
 * ---------------------------------------------------------------------- */

require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/tagdiv-compat.php';

/* -------------------------------------------------------------------------
 * Ads.txt handler
 * ---------------------------------------------------------------------- */

function indfir_handle_ads_txt() {
	if ( isset( $_SERVER['REQUEST_URI'] ) && '/ads.txt' === strtok( $_SERVER['REQUEST_URI'], '?' ) ) {
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo "google.com, pub-4663577290986895, DIRECT, f08c47fec0942fa0\n";
		exit;
	}
}
add_action( 'init', 'indfir_handle_ads_txt' );

/* -------------------------------------------------------------------------
 * Optimize LiteSpeed Cache settings to avoid burst 520 / 500 errors
 * ---------------------------------------------------------------------- */

function indfir_optimize_litespeed_settings() {
	$current_ver = get_option( 'indfir_last_purged_ver' );
	if ( $current_ver !== INDFIR_VERSION ) {
		update_option( 'indfir_last_purged_ver', INDFIR_VERSION );
		do_action( 'litespeed_purge_all' );
	}

	// Disable JS-based lazy loading in favor of native browser loading="lazy"
	if ( '0' !== (string) get_option( 'litespeed.conf.media-lazy' ) ) {
		update_option( 'litespeed.conf.media-lazy', '0' );
	}
	// Disable delayed JS (which causes sudden burst requests when unpaused)
	if ( '2' === (string) get_option( 'litespeed.conf.optm-js_defer' ) ) {
		update_option( 'litespeed.conf.optm-js_defer', '0' );
	}
}
add_action( 'init', 'indfir_optimize_litespeed_settings' );

/* -------------------------------------------------------------------------
 * Robust Image Asset Delivery
 * - Strips non-existent legacy thumbnails from srcset (prevents 404 / 520)
 * - Adds cache-buster parameter to bypass poisoned edge CDN 403 blocks
 * ---------------------------------------------------------------------- */

function indfir_filter_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	if ( empty( $sources ) || ! is_array( $sources ) ) {
		return $sources;
	}

	$upload_dir = wp_upload_dir();
	$basedir    = $upload_dir['basedir'];
	$baseurl    = $upload_dir['baseurl'];

	$filtered = array();
	foreach ( $sources as $width => $source ) {
		$url = $source['url'];

		// Verify thumbnail file exists on disk if within uploads dir.
		if ( 0 === strpos( $url, $baseurl ) ) {
			$rel_path  = substr( $url, strlen( $baseurl ) );
			$rel_path  = strtok( $rel_path, '?' );
			$file_path = $basedir . $rel_path;

			if ( ! file_exists( $file_path ) ) {
				continue; // Skip ghost/missing thumbnail from old themes.
			}
		}

		$source['url']      = add_query_arg( 'v', '2', $url );
		$filtered[ $width ] = $source;
	}

	return $filtered;
}
add_filter( 'wp_calculate_image_srcset', 'indfir_filter_image_srcset', 99, 5 );

function indfir_filter_attachment_image_src( $image, $attachment_id, $size, $icon ) {
	if ( $image && ! empty( $image[0] ) ) {
		$image[0] = add_query_arg( 'v', '2', $image[0] );
	}
	return $image;
}
add_filter( 'wp_get_attachment_image_src', 'indfir_filter_attachment_image_src', 99, 4 );

/* -------------------------------------------------------------------------
 * Default Navigation Menu Setup
 * Ensures core editorial categories are assigned to 'primary' and legal pages
 * to 'topbar' and 'footer'.
 * ---------------------------------------------------------------------- */

function indfir_setup_default_menus() {
	if ( get_option( 'indfir_menus_v2_initialized' ) ) {
		return;
	}

	$locations = get_nav_menu_locations();
	$categories = array(
		'Berita'       => 'berita',
		'Teknologi'    => 'teknologi',
		'Ekonomi'      => 'ekonomi',
		'Olahraga'     => 'olahraga',
		'Sains'        => 'sains',
		'Data & AI'    => 'data-ai',
		'Gaya Hidup'   => 'gaya-hidup',
	);

	// Check or create "Kategori Utama" menu
	$cat_menu = wp_get_nav_menu_object( 'Kategori Utama' );
	if ( ! $cat_menu ) {
		$cat_menu_id = wp_create_nav_menu( 'Kategori Utama' );
		if ( ! is_wp_error( $cat_menu_id ) ) {
			// Add Home
			wp_update_nav_menu_item( $cat_menu_id, 0, array(
				'menu-item-title'  => __( 'Home', 'indfir' ),
				'menu-item-url'    => home_url( '/' ),
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
			) );

			// Add each category
			foreach ( $categories as $title => $slug ) {
				$term = get_category_by_slug( $slug );
				if ( $term ) {
					wp_update_nav_menu_item( $cat_menu_id, 0, array(
						'menu-item-title'     => $term->name,
						'menu-item-object'    => 'category',
						'menu-item-object-id' => $term->term_id,
						'menu-item-type'      => 'taxonomy',
						'menu-item-status'    => 'publish',
					) );
				}
			}
		}
	} else {
		$cat_menu_id = $cat_menu->term_id;
	}

	if ( ! empty( $cat_menu_id ) ) {
		$prev_primary = ! empty( $locations['primary'] ) ? $locations['primary'] : 0;

		$locations['primary'] = $cat_menu_id;

		if ( $prev_primary && empty( $locations['topbar'] ) ) {
			$locations['topbar'] = $prev_primary;
		}
		if ( $prev_primary && empty( $locations['footer'] ) ) {
			$locations['footer'] = $prev_primary;
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}

	update_option( 'indfir_menus_v2_initialized', 1 );
}
add_action( 'init', 'indfir_setup_default_menus' );

/* -------------------------------------------------------------------------
 * Grid 3-column posts per page (9 posts per page)
 * ---------------------------------------------------------------------- */

function indfir_adjust_posts_per_page( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( $query->is_front_page() || $query->is_home() || $query->is_archive() || $query->is_search() ) {
			$query->set( 'posts_per_page', 9 );
		}
	}
}
add_action( 'pre_get_posts', 'indfir_adjust_posts_per_page' );

/* -------------------------------------------------------------------------
 * AdSense Compliance & Housekeeping Automation
 * ---------------------------------------------------------------------- */

function indfir_setup_adsense_compliance() {
	$executed = get_option( 'indfir_adsense_compliance_v6' );
	if ( $executed ) {
		return;
	}

	// 1. Trash broken demo TagDiv pages
	$demo_slugs = array(
		'tds-switching-plans-wizard',
		'tds-login-register',
		'my-account-downtown_pro',
		'login-register-downtown_pro',
		'home-mobile',
		'tds-checkout',
	);
	foreach ( $demo_slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			wp_trash_post( $page->ID );
		}
	}

	// 2. Fix the contact page text if it exists
	$contact_page = get_page_by_path( 'contact' );
	if ( $contact_page ) {
		$content = $contact_page->post_content;
		$new_phrase = 'Untuk saat ini, seluruh korespondensi dan pengiriman pesan dilayani secara langsung melalui email resmi kami di <strong>admin@indfir.com</strong>. Tim redaksi kami berkomitmen untuk merespons semua pesan dalam 1-2 hari kerja.';
		if ( strpos( $content, 'formulir kontak di bawah ini' ) !== false ) {
			$content = preg_replace(
				'/<p>Selain email, Anda juga dapat menggunakan formulir kontak di bawah ini[^<]*<\/p>/i',
				'<p>' . $new_phrase . '</p>',
				$content
			);
			wp_update_post( array(
				'ID'           => $contact_page->ID,
				'post_content' => $content,
			) );
		}
	}

	// 3. Create 'Pedoman Pemberitaan Media Siber' page if not exists
	$pedoman_page = get_page_by_path( 'pedoman-media-siber' );
	if ( ! $pedoman_page ) {
		$pedoman_content = '<h2>Pedoman Pemberitaan Media Siber Indfir.com</h2>
<p>Kemerdekaan berpendapat, kemerdekaan berekspresi, dan kemerdekaan pers adalah hak asasi manusia yang dilindungi oleh Pancasila, Undang-Undang Dasar 1945, dan Deklarasi Universal Hak Asasi Manusia PBB. Keberadaan media siber di Indonesia juga merupakan bagian dari kemerdekaan berpendapat, kemerdekaan berekspresi, dan kemerdekaan pers.</p>
<p>Indfir.com mematuhi dan menjalankan <strong>Pedoman Pemberitaan Media Siber</strong> yang ditetapkan oleh Dewan Pers dan komunitas pers di Jakarta pada 3 Februari 2012 sebagai berikut:</p>
<hr>
<h3>1. Ruang Lingkup</h3>
<p>Media Siber adalah segala bentuk media yang menggunakan wahana internet dan melaksanakan kegiatan jurnalistik, serta memenuhi persyaratan Undang-Undang Pokok Pers dan Standar Perusahaan Pers yang ditetapkan Dewan Pers. Isi Buatan Pengguna (User Generated Content) adalah segala isi yang dibuat dan atau dipublikasikan oleh pengguna media siber, antara lain artikel, gambar, komentar, suara, video dan berbagai bentuk unggahan yang melekat pada media siber.</p>
<hr>
<h3>2. Verifikasi dan Keberimbangan Berita</h3>
<ul>
<li>Setiap berita harus melalui proses verifikasi fakta dan konfirmasi kepada pihak-pihak terkait sebelum dipublikasikan.</li>
<li>Berita yang dapat merugikan pihak lain memerlukan verifikasi pada berita yang sama untuk memenuhi prinsip akurasi dan keberimbangan (cover both sides).</li>
<li>Setiap berita harus mencantumkan sumber informasi secara jelas dan dapat dipertanggungjawabkan demi transparansi publik.</li>
</ul>
<hr>
<h3>3. Isi Buatan Pengguna (User Generated Content)</h3>
<ul>
<li>Indfir.com mewajibkan setiap pengguna yang menyampaikan komentar atau tulisan untuk tunduk pada etika dan hukum yang berlaku.</li>
<li>Indfir.com berhak menyunting, menghapus, atau tidak menayangkan komentar yang mengandung unsur fitnah, kebencian berbasis SARA, pornografi, ujaran provokatif, atau pelanggaran privasi.</li>
<li>Redaksi menyediakan mekanisme pelaporan terhadap isi buatan pengguna yang dinilai melanggar ketentuan hukum.</li>
</ul>
<hr>
<h3>4. Ralat, Koreksi, dan Hak Jawab</h3>
<ul>
<li>Ralat, koreksi, dan hak jawab mengacu pada Undang-Undang Pers, Kode Etik Jurnalistik, dan Pedoman Pemberitaan Media Siber yang ditetapkan Dewan Pers.</li>
<li>Ralat, koreksi, dan atau hak jawab wajib ditautkan pada berita yang diralat, dikoreksi atau yang diberi hak jawab.</li>
<li>Pada setiap berita ralat, koreksi, dan hak jawab wajib dicantumkan waktu pemuatan ralat, koreksi, dan atau hak jawab tersebut.</li>
</ul>
<hr>
<h3>5. Pencabutan Berita</h3>
<ul>
<li>Berita yang sudah dipublikasikan tidak dapat dicabut karena alasan penyensoran dari pihak luar redaksi, kecuali terkait masalah SARA, kesusilaan, masa depan anak, pengalaman traumatik korban atau berdasarkan pertimbangan khusus lain yang ditetapkan Dewan Pers.</li>
<li>Pencabutan berita wajib disertai dengan alasan pencabutan dan diumumkan kepada publik secara transparan.</li>
</ul>
<hr>
<h3>6. Hak Cipta dan Kutipan</h3>
<p>Indfir.com menghormati hak cipta dan kekayaan intelektual pihak lain. Seluruh kutipan informasi, data riset, dan gambar yang berasal dari pihak ketiga selalu dicantumkan sumber dan kredit atribusinya secara jelas.</p>
<hr>
<h3>7. Mekanisme Pengaduan Publik</h3>
<p>Masyarakat atau pihak yang merasa dirugikan oleh materi pemberitaan di Indfir.com dapat menyampaikan pengaduan, ralat, atau hak jawab secara tertulis kepada tim redaksi melalui:</p>
<ul>
<li><strong>Email:</strong> admin@indfir.com</li>
<li><strong>Subjek Email:</strong> Hak Jawab / Pengaduan Pemberitaan</li>
<li><strong>Alamat Redaksi:</strong> Jakarta, Indonesia</li>
</ul>
<p style="text-align: center;"><em><strong>Indfir.com – Elevating Your Future</strong></em></p>';

		wp_insert_post( array(
			'post_title'   => 'Pedoman Media Siber',
			'post_name'    => 'pedoman-media-siber',
			'post_content' => $pedoman_content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		) );
	}

	// 4. Create 'Disclaimer' page if not exists
	$disclaimer_page = get_page_by_path( 'disclaimer' );
	if ( ! $disclaimer_page ) {
		$disclaimer_content = '<h2>Disclaimer (Sanggahan) Indfir.com</h2>
<p><strong>Terakhir Diperbarui:</strong> September 2026</p>
<p>Selamat datang di Indfir.com. Dengan mengakses dan menggunakan situs ini, Anda menyatakan setuju dan terikat oleh syarat dan ketentuan dalam Disclaimer ini. Harap membaca informasi berikut dengan saksama.</p>
<hr>
<h3>1. Akurasi dan Tujuan Informasi</h3>
<p>Seluruh materi, artikel, data, dan opini yang dipublikasikan di <strong>Indfir.com</strong> disajikan semata-mata untuk tujuan informasi umum dan edukasi. Meskipun tim redaksi kami berusaha menyajikan informasi yang akurat, mutakhir, dan berdasarkan sumber yang kredibel, Indfir.com tidak memberikan jaminan atau garansi dalam bentuk apa pun, baik tersurat maupun tersirat, mengenai kelengkapan, ketepatan, keandalan, atau kesesuaian informasi yang tersedia di situs ini.</p>
<hr>
<h3>2. Sanggahan Finansial, Investasi, dan Pasar Modal</h3>
<p>Konten yang berkaitan dengan pasar saham, ekonomi, aset kripto, keuangan terdesentralisasi (DeFi), dan instrumen investasi lainnya di Indfir.com <strong>BUKAN merupakan nasihat keuangan, rekomendasi beli/jual, atau ajakan investasi finansial resmi</strong>.</p>
<p>Setiap keputusan keuangan atau investasi yang Anda ambil sepenuhnya merupakan tanggung jawab dan risiko pribadi Anda. Kami sangat menyarankan pembaca untuk selalu melakukan riset mandiri (<em>Do Your Own Research - DYOR</em>) dan berkonsultasi dengan penasihat keuangan profesional berlisensi sebelum mengambil keputusan investasi apa pun.</p>
<hr>
<h3>3. Tautan ke Pihak Ketiga (External Links)</h3>
<p>Indfir.com mungkin memuat tautan menuju situs web eksternal yang dioperasikan oleh pihak ketiga. Kami tidak memiliki kendali atas isi, kebijakan privasi, atau praktik situs web pihak ketiga tersebut. Penyertaan tautan eksternal tidak serta merta mencerminkan rekomendasi atau dukungan terhadap pandangan yang diungkapkan di dalamnya.</p>
<hr>
<h3>4. Kebijakan Periklanan dan Google AdSense</h3>
<p>Indfir.com menggunakan layanan periklanan pihak ketiga, termasuk <strong>Google AdSense</strong>, untuk menampilkan iklan saat Anda mengunjungi situs kami. Vendor pihak ketiga, termasuk Google, menggunakan cookie (seperti cookie DART) untuk menayangkan iklan berdasarkan kunjungan pengguna ke situs ini dan situs web lain di internet. Indfir.com tidak bertanggung jawab atas produk, layanan, atau klaim yang ditawarkan oleh pengiklan pihak ketiga.</p>
<hr>
<h3>5. Hak Cipta dan Kekayaan Intelektual</h3>
<p>Seluruh teks, tata letak, grafis orisinal, dan materi editorial di Indfir.com dilindungi oleh undang-undang hak cipta Republik Indonesia. Pengambilan atau penyalinan sebagian konten untuk keperluan wajar diperbolehkan dengan syarat mencantumkan atribusi yang jelas serta tautan aktif kembali menuju artikel sumber di Indfir.com.</p>
<hr>
<h3>6. Kontak dan Klarifikasi</h3>
<p>Jika Anda memiliki pertanyaan, memerlukan klarifikasi, atau ingin menyampaikan tanggapan terkait isi Disclaimer ini, silakan hubungi kami di:</p>
<ul>
<li><strong>Email:</strong> admin@indfir.com</li>
<li><strong>Halaman Kontak:</strong> <a href="' . esc_url( home_url( '/contact/' ) ) . '">https://indfir.com/contact/</a></li>
</ul>
<p style="text-align: center;"><em><strong>Indfir.com – Elevating Your Future</strong></em></p>';

		wp_insert_post( array(
			'post_title'   => 'Disclaimer',
			'post_name'    => 'disclaimer',
			'post_content' => $disclaimer_content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		) );
	}

	// 5. Build/Update dedicated Topbar Menu & Footer Menu
	$topbar_menu = wp_get_nav_menu_object( 'Topbar Menu' );
	if ( ! $topbar_menu ) {
		$topbar_menu_id = wp_create_nav_menu( 'Topbar Menu' );
	} else {
		$topbar_menu_id = $topbar_menu->term_id;
	}

	if ( ! empty( $topbar_menu_id ) ) {
		$existing_tb = wp_get_nav_menu_items( $topbar_menu_id, array( 'post_status' => 'any' ) );
		if ( ! empty( $existing_tb ) ) {
			foreach ( $existing_tb as $tb_it ) {
				wp_delete_post( $tb_it->ID, true );
			}
		}

		$topbar_links = array(
			'About'               => home_url( '/about/' ),
			'Contact'             => home_url( '/contact/' ),
			'Pedoman Media Siber' => home_url( '/pedoman-media-siber/' ),
			'Privacy Policy'      => home_url( '/privacy-policy/' ),
		);
		$tb_order = 1;
		foreach ( $topbar_links as $title => $url ) {
			wp_update_nav_menu_item( $topbar_menu_id, 0, array(
				'menu-item-title'    => $title,
				'menu-item-url'      => $url,
				'menu-item-status'   => 'publish',
				'menu-item-position' => $tb_order++,
			) );
		}
	}

	$footer_menu = wp_get_nav_menu_object( 'Footer Menu' );
	if ( ! $footer_menu ) {
		$footer_menu_id = wp_create_nav_menu( 'Footer Menu' );
	} else {
		$footer_menu_id = $footer_menu->term_id;
	}

	if ( ! empty( $footer_menu_id ) ) {
		$existing_f = wp_get_nav_menu_items( $footer_menu_id, array( 'post_status' => 'any' ) );
		if ( ! empty( $existing_f ) ) {
			foreach ( $existing_f as $it ) {
				wp_delete_post( $it->ID, true );
			}
		}

		$compliance_links = array(
			'About'               => home_url( '/about/' ),
			'Contact'             => home_url( '/contact/' ),
			'Editorial Policy'    => home_url( '/editorial-policy/' ),
			'Pedoman Media Siber' => home_url( '/pedoman-media-siber/' ),
			'Disclaimer'          => home_url( '/disclaimer/' ),
			'Privacy Policy'      => home_url( '/privacy-policy/' ),
			'Terms of Service'    => home_url( '/terms-of-service/' ),
		);

		$order = 1;
		foreach ( $compliance_links as $title => $url ) {
			wp_update_nav_menu_item( $footer_menu_id, 0, array(
				'menu-item-title'    => $title,
				'menu-item-url'      => $url,
				'menu-item-status'   => 'publish',
				'menu-item-position' => $order++,
			) );
		}
	}

	$locations = get_nav_menu_locations();
	if ( ! empty( $topbar_menu_id ) ) {
		$locations['topbar'] = $topbar_menu_id;
	}
	if ( ! empty( $footer_menu_id ) ) {
		$locations['footer'] = $footer_menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// 6. Clear Yoast SEO sitemap cache and indexables
	global $wpdb;
	if ( isset( $wpdb ) ) {
		$wpdb->query( "DELETE FROM {$wpdb->prefix}yoast_indexable WHERE object_type = 'post' AND object_sub_type = 'page' AND post_status != 'publish'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_yst_sm_%' OR option_name LIKE '_transient_timeout_yst_sm_%' OR option_name LIKE '%wpseo_sitemap%'" );
	}
	if ( class_exists( 'WPSEO_Sitemaps_Cache' ) ) {
		WPSEO_Sitemaps_Cache::clear();
	}
	$p_about = get_page_by_path( 'about' );
	if ( $p_about ) {
		clean_post_cache( $p_about->ID );
		wp_update_post( array( 'ID' => $p_about->ID ) );
	}

	update_option( 'indfir_adsense_compliance_v6', 1 );
}
add_action( 'init', 'indfir_setup_adsense_compliance' );

/* -------------------------------------------------------------------------
 * Remove XSL stylesheet from Yoast sitemaps to prevent GSC "Sitemap is HTML" error
 * ---------------------------------------------------------------------- */

add_filter( 'wpseo_stylesheet_url', '__return_empty_string' );
add_filter( 'wpseo_enable_xml_sitemap_transient_caching', '__return_false' );

/* -------------------------------------------------------------------------
 * Disable LiteSpeed & CDN cache on all XML sitemaps
 * ---------------------------------------------------------------------- */

add_action( 'init', function() {
	if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( (string) $_SERVER['REQUEST_URI'], 'sitemap' ) !== false ) {
		if ( ! defined( 'LSCACHE_NO_CACHE' ) ) {
			define( 'LSCACHE_NO_CACHE', true );
		}
		do_action( 'litespeed_control_set_nocache', 'Yoast Sitemap XML' );
		header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'Cloudflare-CDN-Cache-Control: no-cache, no-store, max-age=0' );
		header( 'CDN-Cache-Control: no-cache, no-store, max-age=0' );
		header( 'X-Accel-Expires: 0' );
	}
}, 1 );

add_filter( 'litespeed_can_cache', function( $can_cache ) {
	if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( (string) $_SERVER['REQUEST_URI'], 'sitemap' ) !== false ) {
		return false;
	}
	return $can_cache;
}, 999 );






