<?php
/**
 * Add Director Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors\Admin;

use Ensemble\Components\People\Directors\Database;
use Ensemble\Components\Units\Setup as Units;
use function Ensemble\{html};

?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Add a Unit Director', 'ensemble' ); ?></h1>
<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'overview' ) ) ); ?>" class="page-title-action" role="button">
	<?php esc_html_e( 'Return to All Directors', 'ensemble' ); ?>
</a>

<?php do_action( 'admin_notices' ); ?>

<div class="row">
	<div class="col-12 col-xl-8">
		<?php if ( 0 === ( new Database )->count() ) : ?>
			<div class="alert alert-info" role="alert">
				<h5><?php esc_html_e( 'Howdy!', 'ensemble' ); ?></h5>
				<?php esc_html_e( 'It looks like you&#8217;re adding your first unit director. To get started, just choose a username, enter an email, select a unit and that&#8217;s it. If you haven&#8217;t created any units yet, not to worry, you can always come back and it later, ', 'ensemble' ); ?>
			</div>
		<?php endif; ?>

		<form method="post">
			<div class="card mb-3 md-md-5 pt-4">
				<div class="form-group">
					<?php
					html()->text( array(
						'id'    => 'director-name',
						'label' => __( 'Name', 'ensemble' ),
						'class' => array( 'form-control' ),
					) );
					?>
				</div>

				<div class="form-row form-group">
					<div class="col">
						<?php
						html()->input( 'email', array(
							'id'    => 'director-email',
							'label' => __( 'Email', 'ensemble' ),
							'class' => array( 'form-control' ),
						) );
						?>
					</div>
					<div class="col">
						<?php
						$units = get_terms( array(
							'taxonomy'   => ( new Units )->get_taxonomy_slug(),
							'hide_empty' => false,
							'fields'     => 'id=>name',
							'number'     => 500,
						) );

						html()->select( array(
							'id'               => 'director-units',
							'name'             => 'director-units[]',
							'label'            => __( 'Competing Unit(s)', 'ensemble' ),
							'class'            => array( 'form-control' ),
							'multiple'         => true,
							'options'          => $units,
							'show_option_all'  => false,
							'show_option_none' => false,
						) );
						?>
					</div>
				</div>

			</div>

			<div class="pb-5">
				<?php
				wp_nonce_field( 'ensemble-add-director-nonce', 'ensemble-add-director-nonce' );

				// Add Director button
				html()->input( 'submit', array(
					'name'  => 'ensemble-add-director',
					'value' => __( 'Add Unit Director', 'ensemble' ),
					'class' => array( 'btn-dark', 'btn', 'btn-primary' )
				) );
				?>
			</div>
		</form>
	</div>
</div>
