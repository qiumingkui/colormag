<?php
/**
 * ColorMag Theme Review Notice Class.
 *
 * @author  ThemeGrill
 * @package ColorMag
 * @since   1.3.9
 */

// Exit if directly accessed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to display the theme review notice for this theme after certain period.
 *
 * Class ColorMag_Theme_Review_Notice
 */
class ColorMag_Theme_Review_Notice {

	/**
	 * Constructor function to include the required functionality for the class.
	 *
	 * ColorMag_Theme_Review_Notice constructor.
	 */
	public function __construct() {

		add_action( 'after_setup_theme', array( $this, 'colormag_theme_rating_notice' ) );

	}

	/**
	 * Set the required option value as needed for theme review notice.
	 */
	public function colormag_theme_rating_notice() {

		// Set the installed time in `colormag_theme_installed_time` option table.
		$option = get_option( 'colormag_theme_installed_time' );
		if ( ! $option ) {
			update_option( 'colormag_theme_installed_time', time() );
		}

		add_action( 'admin_notices', array( $this, 'colormag_theme_review_notice' ), 0 );
		add_action( 'admin_init', array( $this, 'colormag_ignore_theme_review_notice' ), 0 );
		add_action( 'admin_init', array( $this, 'colormag_ignore_theme_review_notice_partially' ), 0 );

	}

	/**
	 * Display the theme review notice.
	 */
	public function colormag_theme_review_notice() {

		global $current_user;
		$user_id                            = $current_user->ID;
		$ignored_notice_partially_activated = get_user_meta( $user_id, 'colormag_ignore_theme_review_notice_partially_activated', true );

		/**
		 * Return from notice display if:
		 * 1. The theme installed is less than 1 month ago.
		 * 2. If the user has ignored the message partially for 1 month.
		 */
		if ( ( get_option( 'colormag_theme_installed_time' ) > strtotime( '-1 month' ) ) || ( $ignored_notice_partially_activated > strtotime( '-1 month' ) ) ) {
			return;
		}
		?>

		<div class="notice updated theme-review-notice" style="position:relative;">
			<?php
			printf(
				/* Translators: %1$s current user display name. */
				esc_html__(
					'Howdy %1$s! It seems that you have been using this theme for more than 1 month. We hope you are happy with everything that the theme has to offer. If you can spare a mimute, please help us by leaving a 5-star review on WordPress.org.  By spreading the love, we can continue to develop new amazing features in the future, for free!', 'colormag'
				),
				wp_get_current_user()->display_name
			);
			?>

			<div class="links">
				<a href="https://wordpress.org/support/theme/colormag/reviews/?filter=5#new-post" class="btn button-primary" target="_blank">
					<span class="dashicons dashicons-thumbs-up"></span>
					<span><?php esc_html_e( 'Sure', 'colormag' ); ?></span>
				</a>

				<a href="?nag_colormag_ignore_theme_review_notice_partially=0" class="btn button-secondary">
					<span class="dashicons dashicons-calendar"></span>
					<span><?php esc_html_e( 'Maybe Later', 'colormag' ); ?></span>
				</a>

				<a href="#" class="btn button-secondary">
					<span class="dashicons dashicons-smiley"></span>
					<span><?php esc_html_e( 'I Already Did', 'colormag' ); ?></span>
				</a>

				<a href="<?php echo esc_url( 'https://themegrill.com/support-forum/forum/colormag-free/' ); ?>" class="btn button-secondary" target="_blank">
					<span class="dashicons dashicons-edit"></span>
					<span><?php esc_html_e( 'File A Query', 'colormag' ); ?></span>
				</a>
			</div>

			<a class="notice-dismiss" style="text-decoration:none;" href="?nag_colormag_ignore_theme_review_notice_partially=0"></a>
		</div>

		<?php
	}

	/**
	 * Function to remove the theme review notice permanently as requested by the user.
	 */
	public function colormag_ignore_theme_review_notice() {


	}

	/**
	 * Function to remove the theme review notice partially as requested by the user.
	 */
	public function colormag_ignore_theme_review_notice_partially() {

		global $current_user;
		$user_id = $current_user->ID;

		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET['nag_colormag_ignore_theme_review_notice_partially'] ) && '0' == $_GET['nag_colormag_ignore_theme_review_notice_partially'] ) {
			add_user_meta( $user_id, 'colormag_ignore_theme_review_notice_partially', 'true', true );

			add_user_meta( $user_id, 'colormag_ignore_theme_review_notice_partially_activated', time() );
		}

	}

}

new ColorMag_Theme_Review_Notice();
