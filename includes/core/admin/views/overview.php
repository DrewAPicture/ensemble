<?php
/**
 * Ensemble Overview Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Components\Contests;
use Ensemble\Components\Venues;
use function Ensemble\load_view;
?>
<div class="bootstrap-iso">
	<hr class="wp-header-end" />

	<?php
	/**
	 * Fires in the head of custom Ensemble views to display admin notices.
	 *
	 * This would be the preferred method for showing notices vs using the
	 * standard 'admin_notices' hook because of the wonky placement above
	 * the header.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ensemble_admin_notices' );
	?>

	<div class="text-center py-5">
		<div class="container">
			<div class="row">
				<div class="col-md-9">
					<h1><?php esc_html_e( 'Welcome to Ensemble!', 'ensemble' ); ?></h1>
					<p class="lead text-muted">
						<?php esc_html_e( 'Ensemble is a game-changing solution for running a color guard or other alternative sports circuit using WordPress. Finally, all of the data you need in one place, and it&#8217;s all tied together: venues, contests, seasons, units, unit directors, and more.', 'ensemble' ); ?>
 					</p>
					<a href="<?php echo esc_url( add_query_arg( 'page', 'ensemble-admin-venues', admin_url( 'admin.php' ) ) ); ?>" class="btn btn-info mr-2 mt-2 mb-2">
						<?php
						if ( 0 === ( new Venues\Database )->count() ) :
							esc_html_e( 'Create Your First Venue', 'ensemble' );
						else :
							esc_html_e( 'Manage Venues', 'ensemble' );
						endif;
						?>
					</a>
					<a href="<?php echo esc_url( add_query_arg( 'page', 'ensemble-admin-venues', admin_url( 'admin.php' ) ) ); ?>" class="btn btn-secondary m-2">
						<?php
						if ( 0 === ( new Contests\Database )->count() ) :
							esc_html_e( 'Create Your First Contest', 'ensemble' );
						else :
							esc_html_e( 'Manage Contests', 'ensemble' );
						endif;
						?>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="py-4 bg-light">
		<div class="container ensemble-welcome">
			<div class="row">
				<div class="col-md-4 p-3">
					<div class="card box-shadow">
						<?php $contests_url = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) ); ?>
						<a href="<?php echo esc_url( $contests_url ); ?>">
							<span class="dashicons dashicons-awards card-img-top justify-content-center"></span>
						</a>
						<div class="card-body">
							<p class="card-text">
								<?php esc_html_e( 'Contests are central to how Ensemble works. They&#8217;re tied to venues and seasons, which are in-turn tied to everything else.', 'ensemble' ); ?>
							</p>
							<div class="d-flex justify-content-center align-items-center">
								<div class="btn-group">
									<a href="<?php echo esc_url( $contests_url ); ?>" role="button" class="btn btn-sm btn-outline-info">
										<?php esc_html_e( 'Contests', 'ensemble' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 p-3">
					<div class="card box-shadow">
						<?php $venues_url = add_query_arg( 'page', 'ensemble-admin-venues', admin_url( 'admin.php' ) ); ?>
						<a href="<?php echo esc_url( $venues_url ); ?>" aria-label="<?php esc_attr_e( 'Manage Venues', 'ensemble' ); ?>">
							<span class="dashicons dashicons-location-alt card-img-top justify-content-center"></span>
						</a>
						<div class="card-body">
							<p class="card-text">
								<?php esc_html_e( 'Being able to manage canonical venue data is what ultiamtely allows for so much work to be done once instead of once every season.', 'ensemble' ); ?>
							</p>
							<div class="d-flex justify-content-center align-items-center">
								<div class="btn-group">
									<a href="<?php echo esc_url( $venues_url ); ?>" role="button" class="btn btn-sm btn-outline-info">
										<?php esc_html_e( 'Venues', 'ensemble' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 p-3">
					<div class="card box-shadow">
						<?php $director_url = add_query_arg( 'page', 'ensemble-admin-people-directors', admin_url( 'admin.php' ) ); ?>
						<a href="<?php echo esc_url( $director_url ); ?>" aria-label="<?php esc_attr_e( 'Manage Unit Directors', 'ensemble' ); ?>">
							<span class="dashicons dashicons-admin-users card-img-top justify-content-center"></span>
						</a>
						<div class="card-body">
							<p class="card-text">
								<?php esc_html_e( 'People management is important too &emdash; this first version of Ensemble has a Unit Director component baked in from the start.', 'ensemble' ); ?>
							</p>
							<div class="d-flex justify-content-center align-items-center">
								<div class="btn-group">
									<a href="<?php echo esc_url( $director_url ); ?>" role="button" class="btn btn-sm btn-outline-info">
										<?php esc_html_e( 'Unit Directors', 'ensemble' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 p-3">
					<div class="card box-shadow">
						<?php $season_url = add_query_arg( 'taxonomy', 'ensemble_season', admin_url( 'edit-tags.php' ) ); ?>
						<a href="<?php echo esc_url( $season_url ); ?>" aria-label="<?php esc_attr_e( 'Manage Unit Directors', 'ensemble' ); ?>">
							<span class="dashicons dashicons-calendar-alt card-img-top justify-content-center"></span>
						</a>
						<div class="card-body">
							<p class="card-text">
								<?php esc_html_e( 'Season creation with start and end dates means no more confusion about which contests are current and which have already passed.', 'ensemble' ); ?>
							</p>
							<div class="d-flex justify-content-center align-items-center">
								<div class="btn-group">
									<a href="<?php echo esc_url( $season_url ); ?>" role="button" class="btn btn-sm btn-outline-info">
										<?php esc_html_e( 'Seasons', 'ensemble' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 p-3">
					<div class="card box-shadow">
						<?php $units_url = add_query_arg( 'taxonomy', 'ensemble_unit', admin_url( 'edit-tags.php' ) ); ?>
						<a href="<?php echo esc_url( $units_url ); ?>" aria-label="<?php esc_attr_e( 'Manage Unit Directors', 'ensemble' ); ?>">
							<span class="dashicons dashicons-groups card-img-top justify-content-center"></span>
						</a>
						<div class="card-body">
							<p class="card-text">
								<?php esc_html_e( 'Competing units are the heart and soul of any sports activity, and Ensemble has designed them to be super easy to tie in to everything.', 'ensemble' ); ?>
							</p>
							<div class="d-flex justify-content-center align-items-center">
								<div class="btn-group">
									<a href="<?php echo esc_url( $units_url ); ?>" role="button" class="btn btn-sm btn-outline-info">
										<?php esc_html_e( 'Competing Units', 'ensemble' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 p-3">
					<div class="card box-shadow">
						<span class="dashicons dashicons-editor-expand card-img-top justify-content-center"></span>
						<div class="card-body">
							<p class="card-text">
								<?php esc_html_e( 'Coming Soon: Integrations in the form of sister theme for the front-end and support for popular calendar & eCommerce solutions.', 'ensemble' ); ?>
							</p>
							<div class="d-flex justify-content-center align-items-center">
								<div class="btn-group">
									<a href="https://github.com/DrewAPicture/ensemble/issues" role="button" class="btn btn-sm btn-outline-info">
										<?php esc_html_e( 'Request a Feature', 'ensemble' ); ?>
									</a>
									<a href="https://github.com/DrewAPicture/ensemble/issues" role="button" class="btn btn-sm btn-outline-secondary">
										<?php esc_html_e( 'Report a Bug', 'ensemble' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
