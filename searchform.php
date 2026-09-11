<?php
/**
 * Search form.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

$if_id = 'if-search-' . wp_unique_id();
?>
<form role="search" method="get" class="if-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $if_id ); ?>">
		<?php esc_html_e( 'Search articles', 'indfir' ); ?>
	</label>
	<input type="search" id="<?php echo esc_attr( $if_id ); ?>" name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search articles…', 'indfir' ); ?>">
	<button type="submit"><?php esc_html_e( 'Search', 'indfir' ); ?></button>
</form>
