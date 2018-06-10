<?php
/**
 * Delete Contest Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Components\Venues;
use function Ensemble\Components\Contests\{get_contest, get_status_label};
use function Ensemble\{html, clean_admin_url};

$contest_id = absint( $_REQUEST['contest_id'] ?? 0 );
$contest    = get_contest( $contest_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Contest', 'ensemble' ); ?></h1>

<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'overview' ), clean_admin_url() ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Return to All Contests', 'ensemble' ); ?>
</a>

<hr class="wp-header-end" />

<?php
/** This action is documented in includes/core/admin/views/overview.php */
do_action( 'ensemble_admin_notices' );
?>

<div class="row">
	<div class="col-12 col-xl-8">
		<form id="ensemble-delete-contest" method="post">

			<?php if ( is_wp_error( $contest ) ) : ?>

				<?php foreach ( $contest->get_error_messages() as $message ) : ?>
					<p><?php echo esc_html( $message ); ?></p>
				<?php endforeach; ?>

			<?php else : ?>

				<div class="card mb-3 md-md-5 pt-4">
					<h5><?php esc_html_e( 'Contest Information', 'ensemble' ); ?></h5>

					<div class="table-responsive">
						<table class="table">
							<thead>
							<tr>
								<th><?php esc_html_e( 'Name', 'ensemble' ); ?></th>
								<th><?php esc_html_e( 'Venue(s)', 'ensemble' ); ?></th>
								<th><?php esc_html_e( 'Status', 'ensemble' ); ?></th>
								<th><?php esc_html_e( 'Start Date', 'ensemble' ); ?></th>
							</tr>
							</thead>
							<tbody>

							<tr>
								<td><?php echo apply_filters( 'the_title', $contest->name ); ?></td>
								<td>
									<?php
									// Convert to an array.
									$venue_ids = array_map( 'absint', explode( ',', $contest->venues ) );

									if ( ! empty( $venue_ids ) ) {
										$venues = ( new Venues\Database )->query( array(
											'id'     => $venue_ids,
											'fields' => 'name',
										) );

										if ( ! empty( $venues ) ) {
											echo implode( ', ', $venues );
										}
									}
									?>
								</td>
								<td><?php echo get_status_label( $contest->status ); ?></td>
								<td><?php echo $contest->get_start_date(); ?></td>
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
								'id'          => 'contest-delete-yes',
								'name'        => 'contest-delete',
								'label'       => __( 'Yes, delete', 'ensemble' ),
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
								'id'          => 'contest-delete-no',
								'name'        => 'contest-delete',
								'label'       => __( 'No, cancel', 'ensemble' ),
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
					// Contest ID (hidden).
					html()->hidden( array(
						'name'  => 'contest-id',
						'value' => $contest->id,
					) );

					wp_nonce_field( 'ensemble-delete-contest-nonce', 'ensemble-delete-contest-nonce' );

					// Submit button.
					html()->input( 'submit', array(
						'name'  => 'ensemble-delete-contest',
						'value' => 'Submit',
						'class' => array( 'btn-dark', 'btn', 'btn-primary' ),
					) );
					?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>
