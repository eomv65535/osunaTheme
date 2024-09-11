<?php
/**
 * Template for the account page
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/templates/account.php
 *
 * Page: "Account"
 *
 * @version 2.7.0
 *
 * @var string $mode
 * @var int    $form_id
 * @var array  $args
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bg-azulito container-utm align-items-center">
	<div class="cerrar_login" style="position:absolute"><a href="<?php echo site_url('/buscar'); ?>"class="btn-close btn-close-white" aria-label="Close"></a></div>
	<div class="custom-login-form w-100">
		<div class=" w-100 text-center"><img src="https://osuna.cbtpruebas.es/wp-content/uploads/2024/06/Osuna-Logo-Vector.png" style="width: 120px;"></div>
		<br>
		<p class="title"><?php echo  __('Profile', 'osunatheme'); ?></p>
		<h2>Revitaliza Osuna</h2>
		<br>
		<div class="p-4 rounded bg-white text-dark um <?php echo esc_attr( $this->get_class( $mode ) ); ?> um-<?php echo esc_attr( $form_id ); ?>">

			<div class="um-form">

				<form method="post" action="" id="micuentaform">
					<input type="hidden" name="action" value="mictaajax">
					<?php
					/**
					 * UM hook
					 *
					 * @type action
					 * @title um_account_page_hidden_fields
					 * @description Show hidden fields on account form
					 * @input_vars
					 * [{"var":"$args","type":"array","desc":"Account shortcode arguments"}]
					 * @change_log
					 * ["Since: 2.0"]
					 * @usage add_action( 'um_account_page_hidden_fields', 'function_name', 10, 1 );
					 * @example
					 * <?php
					 * add_action( 'um_account_page_hidden_fields', 'my_account_page_hidden_fields', 10, 1 );
					 * function my_account_page_hidden_fields( $args ) {
					 *     // your code here
					 * }
					 * ?>
					 */
					do_action( 'um_account_page_hidden_fields', $args );
					?>

					<div class="um-account-main w-100" data-current_tab="<?php echo esc_attr( UM()->account()->current_tab ); ?>">

						<?php
						/** This action is documented in includes/core/um-actions-profile.php */
						do_action( 'um_before_form', $args );

						foreach ( UM()->account()->tabs as $id => $info ) {
							$tab_enabled = UM()->options()->get( 'account_tab_' . $id );
							$current_tab = UM()->account()->current_tab;

							if ( isset( $info['custom'] ) || !empty( $tab_enabled ) || 'general' === $id) {
								?>
								<div class="um-account-nav w-100">
									<a href="javascript:void(0);" data-tab="<?php echo esc_attr( $id ); ?>" class="<?php if ( $id === $current_tab ) echo 'current'; ?>">
										<?php echo esc_html( $info['title'] ); ?>
										<span class="ico"><i class="<?php echo esc_attr( $info['icon'] ); ?>"></i></span>
										<span class="arr"><i class="um-faicon-angle-down"></i></span>
									</a>
								</div>

								<div class="um-account-tab um-account-tab-<?php echo esc_attr( $id ); ?>" data-tab="<?php echo esc_attr( $id  )?>">
									<?php  $info['with_header'] = true;
									UM()->account()->render_account_tab( $id, $info, $args ); ?>
								</div>
								<?php
							}
						}
						?>
					</div>
					<div class="um-clear"></div>
				</form>

				<?php
				/**
				 * UM hook
				 *
				 * @type action
				 * @title um_after_account_page_load
				 * @description After account form
				 * @change_log
				 * ["Since: 2.0"]
				 * @usage add_action( 'um_after_account_page_load', 'function_name', 10 );
				 * @example
				 * <?php
				 * add_action( 'um_after_account_page_load', 'my_after_account_page_load', 10 );
				 * function my_after_account_page_load() {
				 *     // your code here
				 * }
				 * ?>
				 */
				do_action( 'um_after_account_page_load' );
				?>
			</div>
		</div>
	</div>
</div>