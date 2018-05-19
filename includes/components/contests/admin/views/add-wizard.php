<?php
/**
 * Add Contest Template for the Setup Wizard
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Components\Contests\Database;
use Ensemble\Components\Venues\Database as Venues;
use function Ensemble\Components\Contests\{get_allowed_types, get_allowed_statuses};
use function Ensemble\{html};

?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Add Your First Contest', 'ensemble' ); ?></h1>

<div class="row">
	<div class="col-12 col-xl-8">
		<div class="alert alert-info" role="alert">
			<?php esc_html_e( 'To add a contest, just choose a name and venue, and tell us when you want your contest to start and end. You can even use the venue you created in the first step of this guide.', 'ensemble' ); ?>
		</div>

		<form method="post">
			<div class="card mb-3 md-md-5 pt-4">
				<div class="form-group">
					<?php
					html()->text( array(
						'id'    => 'contest-name',
						'label' => __( 'Name', 'ensemble' ),
						'class' => array( 'form-control' ),
					) );
					?>
				</div>

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
						) );
						?>
					</div>
					<div class="col">
						<?php
						html()->text( array(
							'id'    => 'contest-end-date',
							'label' => __( 'End Date', 'ensemble' ),
							'class' => array( 'form-control', 'date' ),
							'desc'  => __( 'Leave blank to default to the same date as Start Date.', 'ensemble' ),
						) );
						?>
					</div>
				</div>
			</div>

			<div class="pb-5">
				<?php
				wp_nonce_field( 'ensemble-add-contest-nonce', 'ensemble-add-contest-nonce' );

				// Set the status to publish via a hidden field.
				html()->hidden( array(
					'id'    => 'contest-status',
					'value' => 'published',
				) );

				// Add Contest button
				html()->input( 'submit', array(
					'name'  => 'ensemble-add-contest',
					'value' => 'Add Contest',
					'class' => array( 'btn-dark', 'btn', 'btn-primary' )
				) );
				?>
			</div>
		</form>
	</div>
</div>
