<?php

/**
 * Implementation of WP Symposium converter
 *
 * @since bbPress (r4689)
 * @link Codex Docs http://codex.bbpress.org/import-forums/custom-import
 */
class WPSymposium extends BBP_Converter_Base {

	/**
	 * Main Constructor
	 *
	 * @uses WPSymposium::setup_globals()
	 */
	function __construct() {
		parent::__construct();
		$this->setup_globals();
	}

	/**
	 * Sets up the field mappings
	 */
	public function setup_globals() {

		/** Forum Section *****************************************************/

		// Setup table joins for the forum section at the base of this section

		// Forum id (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'cid',
			'to_type'         => 'forum',
			'to_fieldname'    => '_bbp_forum_id'
		);

		// Forum parent id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'cat_parent',
			'to_type'         => 'forum',
			'to_fieldname'    => '_bbp_forum_parent_id'
		);

		// Forum title.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'title',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_title'
		);

		// Forum slug (Clean name to avoid confilcts)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'slug',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_name',
		);

		// Forum description.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'cat_desc',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_content',
		);

		// Forum display order (Starts from 1)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'listorder',
			'to_type'         => 'forum',
			'to_fieldname'    => 'menu_order'
		);

		// Forum dates.
		$this->field_map[] = array(
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_date',
			'default' => date('Y-m-d H:i:s')
		);
		$this->field_map[] = array(
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_date_gmt',
			'default' => date('Y-m-d H:i:s')
		);
		$this->field_map[] = array(
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_modified',
			'default' => date('Y-m-d H:i:s')
		);
		$this->field_map[] = array(
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_modified_gmt',
			'default' => date('Y-m-d H:i:s')
		);
	}

	/**
	 * This method allows us to indicates what is or is not converted for each
	 * converter.
	 */
	public function info() {
		return '';
	}

	/**
	 * This method is to save the salt and password together.  That
	 * way when we authenticate it we can get it out of the database
	 * as one value. Array values are auto sanitized by WordPress.
	 */
	public function callback_savepass( $field, $row ) {
		$pass_array = array( 'hash' => $field, 'salt' => $row['salt'] );
		return $pass_array;
	}

	/**
	 * This method is to take the pass out of the database and compare
	 * to a pass the user has typed in.
	 */
	public function authenticate_pass( $password, $serialized_pass ) {
		$pass_array = unserialize( $serialized_pass );
		return ( $pass_array['hash'] == md5( md5( $password ). $pass_array['salt'] ) );
	}
}
