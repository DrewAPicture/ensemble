<?php
/**
 * Venue Overview Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use function Ensemble\{clean_admin_url};
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Venues', 'ensemble' ); ?></h1>
<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'add' ), clean_admin_url() ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Add New', 'ensemble' ); ?>
</a>

<hr class="wp-header-end" />

<?php
/** This action is documented in includes/core/admin/views/overview.php */
do_action( 'ensemble_admin_notices' );
?>

<?php
$list_table = new List_Table();
$list_table->prepare_items();
?>
<form id="ensemble-venues" method="get">
	<?php
	$list_table->search_box( __( 'Search', 'ensemble' ), 'ensemble-contests-search' );
	$list_table->views();
	$list_table->display();
	?>
</form>
