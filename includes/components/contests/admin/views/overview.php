<?php
/**
 * Contest Overview Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Contests', 'ensemble' ); ?></h1>
<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'add' ) ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Add New', 'ensemble' ); ?>
</a>

<?php do_action( 'admin_notices' ); ?>

<?php
$list_table = new List_Table();
$list_table->prepare_items();
?>
<form id="ensemble-contests" method="get">
	<?php
	$list_table->views();
	$list_table->display();
	?>
</form>
