<?php
/**
 * People Overview Template
 *
 * @package   Ensemble\Admin\Templates
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Admin;

use function Ensemble\{get_current_tab};
?>
<div class="bootstrap-iso">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'People', 'ensemble' ); ?></h1>

	<?php
	$tabs        = get_tabs();
	$current_tab = get_current_tab( key( $tabs ) );

	if ( ! empty( $tabs ) ) :
		?>
		<div class="d-flex flex-row h-100 mt-2">
			<ul class="nav nav-tabs nav-tabs--vertical nav-tabs--left mr-0 ml-0 w-15 text-left" role="navigation">
				<?php foreach ( $tabs as $slug => $label ) : ?>
					<li class="nav-item">
						<a href="#<?php echo esc_attr( $slug ); ?>" class="nav-link btn text-left bg-light<?php echo $slug === $current_tab ? ' active' : ''; ?>" data-toggle="tab" role="tab" aria-controls="<?php echo esc_attr( $slug ); ?>">
							<?php echo esc_html( $label ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="tab-content bg-white d-flex flex-fill h-100">
				<?php foreach ( $tabs as $slug => $label ) : ?>
					<div class="tab-pane fade p-4<?php echo $slug === $current_tab ? ' active show' : ''; ?>" id="<?php echo esc_attr( $slug ); ?>" role="tabpanel">
						<h2 class="sr-only"><?php echo esc_html( $label ); ?></h2>

						<?php render_tab_contents( $slug ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
