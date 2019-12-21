<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package StudioPress\Genesis
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

namespace StudioPress\Genesis\Upgrade;

/**
 * Upgrade class. Called when `db_version` Genesis setting is below 3200.
 *
 * @since 3.2.0
 */
class Upgrade_DB_3200 implements Upgrade_DB_Interface {
	/**
	 * The Genesis Simple Edits option key constant value.
	 *
	 * @var string $gse_settings_field
	 *
	 * @since 3.2.0
	 */
	public $gse_settings_field;

	/**
	 * Constructor.
	 *
	 * @since 3.2.0
	 */
	public function __construct() {
		$this->gse_settings_field = defined( 'GSE_SETTINGS_FIELD' ) ? GSE_SETTINGS_FIELD : null;
	}

	/**
	 * Upgrade method.
	 *
	 * @since 3.2.0
	 */
	public function upgrade() {
		$this->create_entry_meta_settings();
	}

	/**
	 * Create the entry meta settings with proper default. Use values from Genesis Simple Edits, if plugin is active.
	 *
	 * @since 3.2.0
	 */
	public function create_entry_meta_settings() {
		$before_content_default = is_null( $this->gse_settings_field ) ? '[post_date] ' . __( 'by', 'genesis' ) . ' [post_author_posts_link] [post_comments] [post_edit]' : genesis_get_option( 'post_info', $this->gse_settings_field );
		$after_content_default  = is_null( $this->gse_settings_field ) ? '[post_categories] [post_tags]' : genesis_get_option( 'post_meta', $this->gse_settings_field );

		genesis_update_settings(
			[
				'entry_meta_before_content' => $before_content_default,
				'entry_meta_after_content'  => $after_content_default,
			]
		);
	}
}
