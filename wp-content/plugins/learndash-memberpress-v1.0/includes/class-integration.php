<?php
/**
* Integration class
*/
class Learndash_Memberpress_Integration
{
	
	public function __construct()
	{
		add_action( 'mepr-product-options-tabs', array( $this, 'learndash_tab' ) );
		add_action( 'mepr-product-options-pages', array( $this, 'learndash_tab_page' ) );
		add_action( 'mepr-membership-save-meta', array( $this, 'save_post_meta' ) );

		// Associate or disasociate course when MP transaction status is changed
		add_action( 'mepr-txn-transition-status', array( $this, 'transition_status' ), 10, 3 );
		// Disassociate course when MP transaction is expired
		add_action( 'mepr-transaction-expired', array( $this, 'transaction_expired' ), 10, 2 );
		// Disassociate course when MP transaction is deleted
		add_action( 'mepr_pre_delete_transaction', array( $this, 'delete_transaction' ), 10, 1 );
	}

	/**
	 * Output new tab for LearnDash on MemberPress membership edit screen
	 * 
	 * @param  array  $product MemberPress product information
	 */
	public function learndash_tab( $product )
	{
		?>

		<a class="nav-tab main-nav-tab" href="#" id="learndash"><?php _e( 'LearnDash', 'learndash-memberpress' ); ?></a>

		<?php
	}

	/**
	 * Output tab content for LearnDash tab on MemberPress membership edit screen
	 * 
	 * @param  array  $product MemberPress product information
	 */
	public function learndash_tab_page( $product )
	{
		$courses = $this->get_learndash_courses();
		$saved_courses = maybe_unserialize( get_post_meta( $product->rec->ID, '_learndash_memberpress_courses', true ) );
		?>
		
		<div class="product_options_page learndash">
			<div class="product-options-panel">
				<div class="ld-memberpress-options">
					<p><strong><?php _e( 'Courses', 'learndash-memberpress' ); ?></strong></p>
					<div class="ld-memberpress-course-options">
					<?php foreach ( $courses as $course ): ?>
						<label for="<?php echo esc_attr( $course->ID ); ?>">
							<input type="checkbox" name="_learndash_memberpress_courses[]" id="<?php echo esc_attr( $course->ID ); ?>" value="<?php echo esc_attr( $course->ID ); ?>" <?php $this->checked_course( $course->ID, $saved_courses ); ?>>
							<?php echo esc_attr( $course->post_title ); ?>
						</label><br>
					<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Save LearnDash post meta for MemberPress membership post object
	 * 
	 * @param  array  $product MemberPress product information
	 */
	public function save_post_meta( $product )
	{
		update_post_meta( $product->rec->ID, '_learndash_memberpress_courses', array_map( 'sanitize_text_field', $_POST['_learndash_memberpress_courses'] ) );
	}

	/**
	 * Change LearnDash course status if MemberPress txn status is changed
	 *
	 * @param  string $old_status 	Old status of a transaction
	 * @param  string $new_status 	New status of a transaction
	 * @param  array  $txn 		  	Transaction data	 
	 */
	public function transition_status( $old_status, $new_status, $txn )
	{
		$ld_courses = maybe_unserialize( get_post_meta( $txn->rec->product_id, '_learndash_memberpress_courses', true ) );

		// If no LearnDash course associated, exit
		if ( empty( $ld_courses ) ) {
			return;
		}

		if ( $old_status != 'complete' && $new_status == 'complete' ) {
			foreach ( $ld_courses as $course_id ) {
				$this->add_course_access( $course_id, $txn->rec->user_id );
			}
		} elseif ( $old_status == 'complete' && $new_status != 'complete' ) {
			foreach ( $ld_courses as $course_id ) {
				$this->remove_course_access( $course_id, $txn->rec->user_id );
			}
		}
	}

	/**
	 * Fired when a MP transaction is expired
	 * 
	 * @param  object $txn        MP transaction object
	 * @param  string $sub_status Subscription status
	 */
	public function transaction_expired( $txn, $sub_status )
	{
		$ld_courses = maybe_unserialize( get_post_meta( $txn->rec->product_id, '_learndash_memberpress_courses', true ) );

		// If no LearnDash course associated, exit
		if ( empty( $ld_courses ) ) {
			return;
		}

		foreach ( $ld_courses as $course_id ) {
			$this->remove_course_access( $course_id, $txn->rec->user_id );
		}
	}

	/**
	 * Delete LearnDash course association if transaction is deleted
	 * 
	 * @param  int|bool $query Result of $wpdb->query
	 * @param  array 	$args  Args of transaction
	 */
	public function delete_transaction( $txn )
	{
		$ld_courses = maybe_unserialize( get_post_meta( $txn->product_id, '_learndash_memberpress_courses', true ) );

		// If no LearnDash course associated, exit
		if ( empty( $ld_courses ) ) {
			return;
		}
		
		foreach ( $ld_courses as $course_id ) {
			$this->remove_course_access( $course_id, $txn->user_id );
		}
	}

	/**
	 * Add course access
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function add_course_access( $course_id, $user_id )
	{
		$this->increment_course_access_counter( $course_id, $user_id );
		ld_update_course_access( $user_id, $course_id );
	}

	/**
	 * Add course access
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function remove_course_access( $course_id, $user_id )
	{
		$this->decrement_course_access_counter( $course_id, $user_id );
		$counter = $this->get_courses_access_counter( $user_id );

		if ( ( isset( $counter[ $course_id ] ) && $counter[ $course_id ] < 1 ) || 
			empty( $counter ) 
		) {
			ld_update_course_access( $user_id, $course_id, $remove = true );
		}
	}

	/**
	 * Get all LearnDash courses
	 * 
	 * @return object LearnDash course
	 */
	private function get_learndash_courses()
	{
		global $wpdb;
		$query = "SELECT posts.* FROM $wpdb->posts posts WHERE posts.post_type = 'sfwd-courses' AND posts.post_status = 'publish' ORDER BY posts.post_title";

		return $wpdb->get_results( $query, OBJECT );
	}

	/**
	 * Check if a course belong to a courses array
	 * If true, output HTML attribute checked="checked"
	 * 
	 * @param  int    $course_id     Course ID
	 * @param  array  $courses_array Course IDs array
	 */
	private function checked_course( $course_id, $courses_array )
	{
		if ( in_array( $course_id, $courses_array ) ) {
			echo 'checked="checked"';
		}
	}

	/**
	 * Add enrolled course record to a user
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function increment_course_access_counter( $course_id, $user_id )
	{
		$courses = $this->get_courses_access_counter( $user_id );

		if ( isset( $courses[ $course_id ] ) ) {
			$courses[ $course_id ] += 1;
		} else {
			$courses[ $course_id ] = 1;
		}

		update_user_meta( $user_id, '_learndash_memberpress_enrolled_courses_access_counter', $courses );
	}

	/**
	 * Delete enrolled course record from a user
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	private function decrement_course_access_counter( $course_id, $user_id )
	{
		$courses = $this->get_courses_access_counter( $user_id );

		if ( isset( $courses[ $course_id ] ) && $courses[ $course_id ] > 0 ) {
			$courses[ $course_id ] -= 1;
		}

		update_user_meta( $user_id, '_learndash_memberpress_enrolled_courses_access_counter', $courses );
	}

	/**
	 * Check if a course user access is empty
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 * @return boolean       True if empty|false otherwise
	 */
	private function is_course_user_access_empty( $course_id, $user_id )
	{
		$courses = $this->get_courses_access_counter( $user_id );

		if ( $courses[ $courses_id ] < 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * Get user enrolled course access counter
	 * 
	 * @param  int $user_id ID of a user
	 * @return array        Course access counter array
	 */
	private function get_courses_access_counter( $user_id )
	{
		$courses = get_user_meta( $user_id, '_learndash_memberpress_enrolled_courses_access_counter', true );

		if ( ! empty( $courses ) ) {
			$courses = maybe_unserialize( $courses );
		} else {
			$courses = array();
		}
		
		return $courses;
	}
}

new Learndash_Memberpress_Integration();