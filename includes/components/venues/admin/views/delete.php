<?php
/**
 * Delete Venue Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use function Ensemble\Components\Venues\{get_type_label, get_status_label, get_venue};
use function Ensemble\{html, clean_admin_url};

$venue_id = absint( $_REQUEST['venue_id'] ?? 0 );
$venue    = get_venue( $venue_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Venue', 'ensemble' ); ?></h1>

<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'overview' ), clean_admin_url() ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Return to All Venues', 'ensemble' ); ?>
</a>

<hr class="wp-header-end" />

<?php
/** This action is documented in includes/core/admin/views/overview.php */
do_action( 'ensemble_admin_notices' );
?>

<div class="row">
	<div class="col-12 col-xl-8">
		<form id="ensemble-delete-venue" method="post">

			<?php if ( is_wp_error( $venue ) ) : ?>

				<?php foreach ( $venue->get_error_messages() as $message ) : ?>
					<p><?php echo esc_html( $message ); ?></p>
				<?php endforeach; ?>

			<?php else : ?>

				<div class="card mb-3 md-md-5 pt-4">
					<h5><?php esc_html_e( 'Venue Information', 'ensemble' ); ?></h5>

					<div class="table-responsive">
						<table class="table">
							<thead>
							<tr>
								<th><?php echo esc_html_x( 'Name', 'venue', 'ensemble' ); ?></th>
								<th><?php echo esc_html_x( 'Type', 'venue', 'ensemble' ); ?></th>
								<th><?php echo esc_html_x( 'Status', 'venue', 'ensemble' ); ?></th>
								<th><?php esc_html_e( 'Date Added', 'ensemble' ); ?></th>
							</tr>
							</thead>
							<tbody>

							<tr>
								<td><?php echo apply_filters( 'the_title', $venue->name ); ?></td>
								<td><?php echo get_type_label( $venue->type ); ?></td>
								<td><?php echo get_status_label( $venue->status ); ?></td>
								<td><?php echo $venue->get_date_added(); ?></td>
							</tr>

							</tbody>
						</table>
					</div>
				</div>

				<div class="card mb-3 md-md-5 pt-4">
					<h5><?php esc_html_e( 'Are you sure?', 'ensemble' ); ?></h5>

					<div class="form-group">
						<div class="form-check mb-3">
							<?php
							// Radio button: Yes.
							html()->radio( array(
								'id'          => 'venue-delete-yes',
								'name'        => 'venue-delete',
								'label'       => _x( 'Yes, delete', 'venue delete', 'ensemble' ),
								'label_class' => 'form-check-label',
								'value'       => 'yes',
								'class'       => array( 'form-check-input' ),
							) );
							?>
						</div>

						<div class="form-check">
							<?php
							// Radio button: No
							html()->radio( array(
								'id'          => 'venue-delete-no',
								'name'        => 'venue-delete',
								'label'       => _x( 'No, cancel', 'venue delete', 'ensemble' ),
								'label_class' => 'form-check-label',
								'class'       => array( 'form-check-input' ),
								'value'       => 'no',
								'checked'     => true,
							) );
							?>
						</div>
					</div>
				</div>
				<div class="pb-5">
					<?php
					// Venue ID (hidden).
					html()->hidden( array(
						'name'  => 'venue-id',
						'value' => $venue->id,
					) );

					wp_nonce_field( 'ensemble-delete-venue-nonce', 'ensemble-delete-venue-nonce' );

					// Submit button.
					html()->input( 'submit', array(
						'name'  => 'ensemble-delete-venue',
						'value' => 'Submit',
						'class' => array( 'btn-dark', 'btn', 'btn-primary' ),
					) );
					?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>
