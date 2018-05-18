<?php
/**
 * Add Contest Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Components\Contests\Database;
use Ensemble\Components\Venues\Database as Venues;
use Ensemble\Components\Seasons\Setup as Seasons;
use function Ensemble\Components\Contests\{get_allowed_types, get_allowed_statuses};
use function Ensemble\{html};

?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Add a Contest', 'ensemble' ); ?></h1>
<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'overview' ) ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Return to All Contests', 'ensemble' ); ?>
</a>

<div class="row">
	<div class="col-12 col-xl-8">
		<?php if ( 0 === ( new Database )->count() ) : ?>
			<div class="alert alert-info" role="alert">
				<h5><?php esc_html_e( 'Howdy!', 'ensemble' ); ?></h5>
				<?php esc_html_e( 'It looks like you&#8217;re adding your first contest. To get started, just choose a name and venue, and tell us when you want your contest to start and end. If you haven&#8217;t created any venues yet, not to worry, you can always come back and set one later, ', 'ensemble' ); ?>
			</div>
		<?php endif; ?>

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

				<div class="form-row form-group">
					<div class="col">
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
					<div class="col">
						<?php
						$seasons = get_terms( array(
							'taxonomy'   => ( new Seasons )->get_taxonomy_slug(),
							'hide_empty' => false,
							'fields'     => 'id=>name',
							'number'     => 250,
						) );

						// Season.
						html()->select( array(
							'id'              => 'contest-season',
							'label'           => _x( 'Season', 'contest', 'ensemble' ),
							'options'         => $seasons,
							'show_option_all' => false,
						) );
						?>
					</div>
				</div>
			</div>

			<div class="card mb-3 md-md-5 pt-4">
				<div class="form-row form-group">
					<div class="col">
						<?php
						html()->text( array(
							'id'    => 'contest-start-date',
							'label' => __( 'Start Date', 'ensemble' ),
							'class' => array( 'form-control', 'date', 'allow-past-dates' ),
						) );
						?>
					</div>
					<div class="col">
						<?php
						html()->text( array(
							'id'    => 'contest-end-date',
							'label' => __( 'End Date', 'ensemble' ),
							'class' => array( 'form-control', 'date', 'allow-past-dates' ),
							'desc'  => __( 'Leave blank to default to the same date as Start Date.', 'ensemble' ),
						) );
						?>
					</div>
				</div>

				<div class="form-group">
					<?php
					html()->editor( array(
						'id'      => 'contest-desc',
						'label'   => __( 'Description', 'ensemble' ),
						'context' => 'add',
						'class'   => array( 'form-control' ),
					) );
					?>
				</div>
			</div>

			<div class="card mb-3 md-md-5 pt-4">
				<div class="form-row form-group">
					<div class="col">
						<?php
						$types = get_allowed_types();

						// If only one type, don't bother with a select.
						if ( 1 === count( $types ) ) :
							$first = key( $types );
							html()->text( array(
								'id'       => 'contest-type',
								'label'    => __( 'Type', 'ensemble' ),
								'class'    => array( 'form-control', 'form-control-plaintext' ),
								'value'    => $types[ $first ],
								'readonly' => true,
							) );
						else :
							html()->select( array(
								'id'               => 'contest-type',
								'label'            => __( 'Type', 'ensemble' ),
								'class'            => array( 'form-control' ),
								'selected'         => 'standard',
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
							'id'               => 'contest-status',
							'label'            => __( 'Status', 'ensemble' ),
							'class'            => array( 'form-control' ),
							'selected'         => 'draft',
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
					) );
					?>
				</div>
			</div>

			<div class="pb-5">
				<?php
				wp_nonce_field( 'ensemble-add-contest-nonce', 'ensemble-add-contest-nonce' );

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
