<?php
/**
 * Edit Contest Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Components\Venues\Database as Venues;
use function Ensemble\Components\Contests\{get_contest, get_allowed_statuses, get_allowed_types, get_type_label};
use function Ensemble\{html};

$contest_id = $_REQUEST['contest_id'] ?? 0;
$contest    = get_contest( $contest_id );
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Contest', 'ensemble' ); ?></h1>

<div class="row">
	<div class="col-12 col-xl-8">

		<?php if ( is_wp_error( $contest ) ) : ?>

			<?php foreach ( $contest->get_error_messages() as $message ) : ?>
				<p><?php echo esc_html( $message ); ?></p>
			<?php endforeach; ?>

		<?php else : ?>

			<form method="post">
				<div class="card mb-3 md-md-5 pt-4">
					<div class="form-group">
						<?php
						html()->text( array(
							'id'    => 'contest-name',
							'label' => __( 'Name', 'ensemble' ),
							'class' => array( 'form-control' ),
							'value' => $contest->name,
						) );
						?>
					</div>

					<div class="form-group">
						<?php
						html()->editor( array(
							'id'      => 'contest-desc',
							'label'   => __( 'Description', 'ensemble' ),
							'context' => 'add',
							'class'   => array( 'form-control' ),
							'value'   => $contest->description,
						) );
						?>
					</div>
				</div>

				<div class="card mb-3 md-md-5 pt-4">
					<div class="form-group">
						<?php
						$venues = ( new Venues )->query( array(
							'fields' => array( 'id', 'name' ),
							'number' => 500,
						) );

						if ( ! empty( $venues ) ) :
							foreach ( $venues as $venue ) {
								$options[ $venue->id ] = $venue->name;
							}
						else :
							$options = array();
						endif;

						html()->select( array(
							'id'               => 'contest-venues',
							'name'             => 'contest-venues[]',
							'label'            => __( 'Venue(s)', 'ensemble' ),
							'class'            => array( 'form-control' ),
							'multiple'         => true,
							'selected'         => explode( ',', $contest->venues ),
							'options'          => $options,
							'show_option_all'  => false,
							'show_option_none' => false,
						) );
						?>
					</div>

					<div class="form-row form-group">
						<div class="col">
							<?php
							html()->text( array(
								'id'    => 'contest-start-date',
								'label' => __( 'Start Date', 'ensemble' ),
								'class' => array( 'form-control', 'date' ),
								'value' => $contest->start_date,
							) );
							?>
						</div>
						<div class="col">
							<?php
							html()->text( array(
								'id'    => 'contest-end-date',
								'label' => __( 'End Date', 'ensemble' ),
								'class' => array( 'form-control', 'date' ),
								'value' => $contest->end_date,
								'desc'  => __( 'Leave blank to default to the same date as Start Date.', 'ensemble' ),
							) );
							?>
						</div>
					</div>
				</div>

				<div class="card mb-3 md-md-5 pt-4">
					<div class="form-row form-group">
						<div class="col">
							<?php
							$types = get_allowed_types();

							if ( count( $types ) > 1 || ! array_key_exists( $contest->type, $types ) ) :
								html()->select( array(
									'id'               => 'contest-type',
									'label'            => __( 'Type', 'ensemble' ),
									'class'            => array( 'form-control' ),
									'selected'         => $contest->type,
									'options'          => $types,
									'show_option_all'  => false,
									'show_option_none' => false,
								) );
							else :
								html()->text( array(
									'id'       => 'contest-type',
									'label'    => __( 'Type', 'ensemble' ),
									'class'    => array( 'form-control' ),
									'value'    => get_type_label( $contest->type ),
								) );
							endif;
							?>
						</div>
						<div class="col">
							<?php
							html()->select( array(
								'id'               => 'contest-status',
								'label'            => __( 'Status', 'ensemble' ),
								'class'            => array( 'form-control' ),
								'selected'         => $contest->status,
								'options'          => get_allowed_statuses(),
								'show_option_all'  => false,
								'show_option_none' => false,
							) );
							?>
						</div>
					</div>

					<div class="form-row form-group">
						<?php
						html()->input( 'url', array(
							'id'    => 'contest-external',
							'label' => __( 'External Contest URL', 'ensemble' ),
							'class' => array( 'form-control' ),
							'value' => $contest->external,
						) );
						?>
					</div>
				</div>
				<div class="pb-5">
					<?php
					wp_nonce_field( 'ensemble-update-contest-nonce', 'ensemble-update-contest-nonce' );

					// Contest ID (hidden).
					html()->hidden( array(
						'name'  => 'contest-id',
						'value' => $contest->id,
					) );
					?>
					<div class="pb-5 d-flex justify-content-between">

						<?php
						// Update Contest button
						html()->input( 'submit', array(
							'name'  => 'ensemble-update-contest',
							'value' => 'Update Contest',
							'class' => array( 'btn-dark', 'btn', 'btn-primary' )
						) );

						$base_url = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

						// Delete link.
						html()->button( array(
							'url'   => add_query_arg( array( 'ensbl-view' => 'delete', 'contest_id' => $contest->id ), $base_url ),
							'class' => array( 'btn', 'btn-link', 'btn-link-delete', 'text-danger' ),
							'value' => _x( 'Delete', 'contest', 'ensemble' ),
							'aria'  => array(
								'label' => __( 'Delete contest', 'ensemble' ),
							),
						) );
						?>
					</div>
				</div>
			</form>

		<?php endif; ?>
	</div>
</div>
