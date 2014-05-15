<?php

/**
 * Implementation of WP Symposium converter
 *
 * @since bbPress (rXXXX)
 * @link Codex Docs http://codex.bbpress.org/import-forums/wp-symposium
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
			'from_fieldname'  => 'stub',
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

		/** Topic Section *****************************************************/

		// Setup table joins for the topic section at the base of this section

		// Topic id (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'tid',
			'from_expression' => 'WHERE symposium_topics.topic_parent = 0',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_topic_id'
		);

		// Topic parent forum id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_category',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_forum_id',
			'callback_method' => 'callback_forumid'
		);

		// Topic author.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_owner',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_author',
			'callback_method' => 'callback_userid'
		);

		/* This looks like IPv6, we don't support IPv6 in the importers yet
		// Topic author ip (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'remote_addr',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_author_ip'
		);
		*/

		// Topic content.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_post',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_content',
			'callback_method' => 'callback_html'
		);

		// Topic title.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_subject',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_title'
		);

		// Topic slug (Clean name to avoid conflicts)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'stub',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_name',
			'callback_method' => 'callback_slug'
		);

		// Topic status (Open or Closed)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'allow_replies',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_status',
			'callback_method' => 'callback_topic_status'
		);

		// Topic parent forum id (If no parent, then 0)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_category',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_parent',
			'callback_method' => 'callback_forumid'
		);

		// Sticky status (Stored in postmeta))
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_sticky',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_old_sticky_status',
			'callback_method' => 'callback_sticky_status'
		);

		// Topic dates.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_date',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_date_gmt',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_date',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_modified',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_date',
			'to_type'         => 'topic',
			'to_fieldname'    => 'post_modified_gmt',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_date',
			'to_type'         => 'topic',
			'to_fieldname'    => '_bbp_last_active_time',
			'callback_method' => 'callback_datetime'
		);

		/** Reply Section *****************************************************/

		// Reply id (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'tid',
			'from_expression' => 'WHERE symposium_topics.topic_parent != 0',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_post_id'
		);

		// Reply parent forum id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_category',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_forum_id',
			'callback_method' => 'callback_topicid_to_forumid'
		);

		// Reply parent topic id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_parent',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_topic_id',
			'callback_method' => 'callback_topicid'
		);

		/* This looks like IPv6, we don't support IPv6 in the importers yet
		// Reply author ip (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'remote_addr',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_author_ip'
		);
		*/

		// Reply author.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_owner',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_author',
			'callback_method' => 'callback_userid'
		);

		// Reply content.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_post',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_content',
			'callback_method' => 'callback_html'
		);

		// Reply parent topic id (If no parent, then 0)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_parent',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_parent',
			'callback_method' => 'callback_topicid'
		);

		// Reply dates.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_date',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_date_gmt',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_modified',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'reply',
			'to_fieldname'    => 'post_modified_gmt',
			'callback_method' => 'callback_datetime'
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

	/**
	 * Translate the forum type from WP Symposium numeric's to WordPress's strings.
	 *
	 * @param int $status WP Symposium numeric forum type
	 * @return string WordPress safe
	 *
	 * This can be included when more info on how WP Symposium handles forum types
	public function callback_forum_type( $status = 1 ) {
		switch ( $status ) {
			case 0 :
				$status = 'category';
				break;

			case 1  :
			default :
				$status = 'forum';
				break;
		}
		return $status;
	}
	*/

	/**
	 * Translate the forum status from WP Symposium numeric's to WordPress's strings.
	 *
	 * @param int $status WP Symposium numeric forum status
	 * @return string WordPress safe
	 *
	 * This can be included when more info on how WP Symposium handles forum status
	public function callback_forum_status( $status = 0 ) {
		switch ( $status ) {
			case 1 :
				$status = 'closed';
				break;

			case 0  :
			default :
				$status = 'open';
				break;
		}
		return $status;
	}
	*/

	/**
	 * Translate the topic status from WP Symposium strings to WordPress's strings.
	 *
	 * @param $status WP Symposium string topic status
	 * @return string WordPress safe
	 */
	public function callback_topic_status( $status = 0 ) {
		switch ( $status ) {
			case '' :
				$status = 'closed';       // WP Symposium closed topic "allow_replies = ''"
				break;

			case 'on'  :
			default :
				$status = 'publish';       // WP Symposium Normal Topic "allow_replies = 'on'"
				break;
		}
		return $status;
	}

	/**
	 * Translate the topic sticky status type from WP Symposium numeric's to WordPress's strings.
	 *
	 * @param int $status WP Symposium numeric forum type
	 * @return string WordPress safe
	 */
	public function callback_sticky_status( $status = 0 ) {
		switch ( $status ) {
			case 1 :
				$status = 'sticky';       // WP Symposium Sticky topic 'topic_sticky = 1'
				break;

			case 0  :
			default :
				$status = 'normal';       // WP Symposium normal topic 'topic_sticky = 0'
				break;
		}
		return $status;
	}
}
