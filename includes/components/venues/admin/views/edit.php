<?php
/**
 * Edit Venue Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use function Ensemble\Components\Venues\{get_venue, get_allowed_statuses, get_allowed_types, get_type_label};
use function Ensemble\{html};

$venue_id = absint( $_REQUEST['venue_id'] ?? 0 );
$venue    = get_venue( $venue_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Venue', 'ensemble' ); ?></h1>
<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'overview' ) ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Return to All Venues', 'ensemble' ); ?>
</a>

<?php
/** This action is documented in includes/core/admin/views/overview.php */
do_action( 'ensemble_admin_notices' );
?>

<div class="row">
	<div class="col-12 col-xl-8">

		<?php if ( is_wp_error( $venue ) ) : ?>

			<?php foreach ( $venue->get_error_messages() as $message ) : ?>
				<p><?php echo esc_html( $message ); ?></p>
			<?php endforeach; ?>

		<?php else : ?>

			<form method="post">
				<div class="card mb-3 md-md-5 pt-4">
					<div class="form-group">
						<?php
						html()->text( array(
							'id'    => 'venue-name',
							'label' => __( 'Name', 'ensemble' ),
							'class' => array( 'form-control' ),
							'value' => $venue->name,
						) );
						?>
					</div>

					<div class="form-row form-group">
						<div class="col">
							<?php
							$types = get_allowed_types();

							// If only one type, don't bother with a select.
							if ( 1 === count( $types ) ) :
								html()->text( array(
									'id'       => 'venue-type',
									'label'    => _x( 'Type', 'venue', 'ensemble' ),
									'class'    => array( 'form-control', 'form-control-plaintext' ),
									'value'    => $venue->type,
									'readonly' => true,
								) );
							else :
								html()->select( array(
									'id'               => 'venue-type',
									'label'            => _x( 'Type', 'venue', 'ensemble' ),
									'class'            => array( 'form-control' ),
									'selected'         => $venue->type,
									'options'          => $types,
									'show_option_all'  => false,
									'show_option_none' => false,
								) );
							endif;
							?>
						</div>
						<div class="col">
							<?php
							html()->select( array(
								'id'               => 'venue-status',
								'label'            => __( 'Status', 'ensemble' ),
								'class'            => array( 'form-control' ),
								'selected'         => $venue->status,
								'options'          => get_allowed_statuses(),
								'show_option_all'  => false,
								'show_option_none' => false,
							) );
							?>
						</div>
					</div>

					<div class="form-group">
						<?php
						// Address.
						html()->textarea( array(
							'id'    => 'venue-address',
							'label' => __( 'Address', 'ensemble' ),
							'class' => array( 'form-control', 'form-textarea' ),
							'value' => $venue->address,
						) );
						?>
					</div>
				</div>

				<div class="pb-5">
					<?php
					wp_nonce_field( 'ensemble-update-venue-nonce', 'ensemble-update-venue-nonce' );

					// Venue ID (hidden).
					html()->hidden( array(
						'name'  => 'venue-id',
						'value' => $venue->id,
					) );
					?>
					<div class="pb-5 d-flex justify-content-between">
						<?php
						// Update Venue button
						html()->input( 'submit', array(
							'name'  => 'ensemble-update-venue',
							'value' => 'Update Venue',
							'class' => array( 'btn-dark', 'btn', 'btn-primary' )
						) );

						$base_url = add_query_arg( 'page', 'ensemble-admin-venues', admin_url( 'admin.php' ) );

						// Delete link.
						html()->button( array(
							'url'   => add_query_arg( array( 'ensbl-view' => 'delete', 'venue_id' => $venue->id ), $base_url ),
							'class' => array( 'btn', 'btn-link', 'btn-link-delete', 'text-danger' ),
							'value' => _x( 'Delete', 'venue', 'ensemble' ),
							'aria'  => array(
								'label' => __( 'Delete venue', 'ensemble' ),
							),
						) );
						?>
					</div>
				</div>
			</form>

		<?php endif; ?>
	</div>
</div>
