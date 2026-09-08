<?php
/**
 * Main sidebar.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-main' ) ) {
	return;
}
?>
<aside class="if-sidebar" role="complementary">
	<?php dynamic_sidebar( 'sidebar-main' ); ?>
</aside>
