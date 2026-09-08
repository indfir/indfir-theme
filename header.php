<?php
/**
 * Site header.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>
	(function(){
		try {
			var s = localStorage.getItem('indfir_theme');
			var p = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
			if (s === 'dark' || (!s && p)) {
				document.documentElement.setAttribute('data-theme', 'dark');
				document.documentElement.classList.add('dark-mode');
			}
		} catch (e) {}
	})();
	</script>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4663577290986895" crossorigin="anonymous"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( is_singular( 'post' ) ) : ?>
	<div class="if-read-progress" id="if-read-progress" aria-hidden="true"></div>
<?php endif; ?>

<a class="skip-link screen-reader-text" href="#if-content"><?php esc_html_e( 'Lompat ke konten', 'indfir' ); ?></a>

<?php if ( get_theme_mod( 'indfir_show_topbar', true ) ) : ?>
	<div class="if-topbar">
		<div class="if-wrap">
			<div class="if-topbar__left">
				<?php if ( get_theme_mod( 'indfir_topbar_text', '' ) ) : ?>
					<span><?php echo esc_html( get_theme_mod( 'indfir_topbar_text', '' ) ); ?></span>
				<?php endif; ?>

				<?php if ( get_theme_mod( 'indfir_show_date', true ) ) : ?>
					<span class="if-topbar__date">
						<?php echo esc_html( wp_date( 'l, j F Y' ) ); ?>
					</span>
				<?php endif; ?>
			</div>

			<div class="if-topbar__right">
				<?php
				if ( has_nav_menu( 'topbar' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'topbar',
							'menu_class'     => 'if-topbar__menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				}

				indfir_social_links();
				?>
			</div>
		</div>
	</div>
<?php endif; ?>

<header class="if-header">
	<div class="if-wrap">
		<div class="if-brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<div>
					<div class="if-brand__text">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</div>
					<?php if ( get_bloginfo( 'description' ) ) : ?>
						<div class="if-brand__tagline"><?php bloginfo( 'description' ); ?></div>
					<?php endif; ?>
				</div>
				<?php
			}

			$if_tagline = get_theme_mod( 'indfir_header_tagline', '' );
			if ( $if_tagline && has_custom_logo() ) :
				?>
				<div class="if-brand__tagline"><?php echo esc_html( $if_tagline ); ?></div>
			<?php endif; ?>
		</div>

		<button class="if-nav-toggle" aria-controls="if-primary-nav" aria-expanded="false">
			<span class="screen-reader-text"><?php esc_html_e( 'Buka menu', 'indfir' ); ?></span>
			<?php echo indfir_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>

		<nav class="if-nav" id="if-primary-nav" aria-label="<?php esc_attr_e( 'Menu utama', 'indfir' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'if-nav__list',
					'depth'          => 3,
					'fallback_cb'    => 'indfir_menu_fallback',
				)
			);
			?>
		</nav>

		<button class="if-theme-toggle" id="if-theme-toggle" aria-label="<?php esc_attr_e( 'Ganti tema gelap/terang', 'indfir' ); ?>" title="<?php esc_attr_e( 'Ganti tema gelap/terang', 'indfir' ); ?>">
			<span class="if-theme-toggle__icon if-theme-toggle__icon--moon"><?php echo indfir_icon( 'moon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span class="if-theme-toggle__icon if-theme-toggle__icon--sun"><?php echo indfir_icon( 'sun' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</button>

		<button class="if-search-toggle" aria-controls="if-searchbar" aria-expanded="false">
			<span class="screen-reader-text"><?php esc_html_e( 'Cari', 'indfir' ); ?></span>
			<?php echo indfir_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>

	<div class="if-searchbar" id="if-searchbar">
		<div class="if-wrap">
			<?php get_search_form(); ?>
		</div>
	</div>
</header>

<?php
if ( is_front_page() || is_home() || is_singular( 'post' ) || is_archive() ) {
	indfir_breaking_ticker();
}
?>
