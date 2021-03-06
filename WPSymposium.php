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
			'to_fieldname'    => 'post_name'
		);

		// Forum description.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'cat_desc',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_content'
		);

		// Forum status (Open or Closed)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_cats',
			'from_fieldname'  => 'allow_new',
			'to_type'         => 'forum',
			'to_fieldname'    => '_bbp_status',
			'callback_method' => 'callback_forum_status'
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

		/** Group Forum Section ***********************************************/
/*
		// Group Forum id (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_groups',
			'from_fieldname'  => 'gid',
			'to_type'         => 'forum',
			'to_fieldname'    => '_bbp_forum_id'
		);
		
		// Groups forums have no parent cat
		// Group Forum parent id (If no parent, then 0. Stored in postmeta)
		// $this->field_map[] = array(
			// 'from_tablename'  => 'symposium_groups',
			// 'from_fieldname'  => 'cat_parent',
			// 'to_type'         => 'forum',
			// 'to_fieldname'    => '_bbp_forum_parent_id'
		// );

		// Group Forum title.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_groups',
			'from_fieldname'  => 'name',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_title'
		);

		// Group Forum slug (Clean name to avoid confilcts)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_groups',
			'from_fieldname'  => 'name',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_name',
			'callback_method' => 'callback_forum_slug'
		);

		// Group Forum description.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_groups',
			'from_fieldname'  => 'description',
			'to_type'         => 'forum',
			'to_fieldname'    => 'post_content'
		);

		// Group Forum status (Open or Closed)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_groups',
			'from_fieldname'  => 'allow_new_topics',
			'to_type'         => 'forum',
			'to_fieldname'    => '_bbp_status',
			'callback_method' => 'callback_forum_status'
		);
		
		// Groups Forums have no order (one forum per group)
		// Group Forum display order (Starts from 1)
		// $this->field_map[] = array(
			// 'from_tablename'  => 'symposium_groups',
			// 'from_fieldname'  => 'listorder',
			// 'to_type'         => 'forum',
			// 'to_fieldname'    => 'menu_order'
		// );
		
		// Also TODO: mark as private / hidden

		// Group Forum dates.
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

		// Topic id (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'tid',
			'from_expression' => 'WHERE symposium_topics.topic_parent = 0 AND symposium_topics.topic_group = 0',
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
			'join_tablename'  => 'symposium_topics',
			'join_table_as'   => 't',
			'join_type'       => 'INNER',
			'join_expression' => 'ON symposium_topics.topic_parent = t.tid WHERE symposium_topics.topic_parent != 0 AND symposium_topics.topic_group = 0 AND t.topic_parent = 0',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_post_id'
		);

		// Reply parent forum id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_category',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_forum_id',
			'callback_method' => 'callback_forumid'
		);

		// Reply parent topic id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_parent',
			'to_type'         => 'reply',
			'to_fieldname'    => '_bbp_topic_id',
			'callback_method' => 'callback_topicid'
		);

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

		/** Comment Section (second-level replies) ***************************/

		// Comment id (Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'tid',
			'join_tablename'  => 'symposium_topics',
			'join_table_as'   => 't',
			'join_type'       => 'INNER',
			'join_expression' => 'ON symposium_topics.topic_parent = t.tid WHERE symposium_topics.topic_parent != 0 AND symposium_topics.topic_group = 0 AND t.topic_parent != 0',
			'to_type'         => 'comment',
			'to_fieldname'    => '_bbp_post_id'
		);

		// Comment parent forum id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_category',
			'to_type'         => 'comment',
			'to_fieldname'    => '_bbp_forum_id',
			'callback_method' => 'callback_forumid'
		);

		// Comment parent topic id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_table_as'   => 't',
			'from_fieldname'  => 'topic_parent',
			'to_type'         => 'comment',
			'to_fieldname'    => '_bbp_topic_id',
			'callback_method' => 'callback_replytoid_to_topicid'
		);

		// Comment author.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_owner',
			'to_type'         => 'comment',
			'to_fieldname'    => 'post_author',
			'callback_method' => 'callback_userid'
		);

		// Comment content.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_post',
			'to_type'         => 'comment',
			'to_fieldname'    => 'post_content',
			'callback_method' => 'callback_html'
		);

		// Comment parent topic id (If no parent, then 0)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_table_as'   => 't',
			'from_fieldname'  => 'topic_parent',
			'to_type'         => 'comment',
			'to_fieldname'    => 'post_parent',
			'callback_method' => 'callback_replytoid_to_topicid'
		);

		// Comment parent reply id (If no parent, then 0. Stored in postmeta)
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_parent',
			'to_type'         => 'comment',
			'to_fieldname'    => '_bbp_reply_to',
			'callback_method' => 'callback_reply_to'
		);

		// Comment dates.
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'comment',
			'to_fieldname'    => 'post_date',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'comment',
			'to_fieldname'    => 'post_date_gmt',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'comment',
			'to_fieldname'    => 'post_modified',
			'callback_method' => 'callback_datetime'
		);
		$this->field_map[] = array(
			'from_tablename'  => 'symposium_topics',
			'from_fieldname'  => 'topic_started',
			'to_type'         => 'comment',
			'to_fieldname'    => 'post_modified_gmt',
			'callback_method' => 'callback_datetime'
		);

		/** User Section ******************************************************/

		// Store old User id (Stored in usermeta)
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'ID',
			'to_type'        => 'user',
			'to_fieldname'   => '_bbp_user_id'
		);

		// Store old User password (Stored in usermeta)
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'user_pass',
			'to_type'        => 'user',
			'to_fieldname'   => '_bbp_password'
		);

		// User name.
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'user_login',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_login'
		);

		// User nice name.
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'user_nicename',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_nicename'
		);

		// User email.
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'user_email',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_email'
		);

		// User homepage.
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'user_url',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_url'
		);

		// User registered.
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'user_registered',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_registered'
		);

		// User status.
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'user_status',
			'to_type'        => 'user',
			'to_fieldname'   => 'user_status'
		);

		// User display name.
		$this->field_map[] = array(
			'from_tablename' => 'users',
			'from_fieldname' => 'display_name',
			'to_type'        => 'user',
			'to_fieldname'   => 'display_name'
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
	 * Translate the forum status from WP Symposium strings to bbPress strings.
	 *
	 * @param string $status WP Symposium string forum status, 'on' or ''
	 * @return string WordPress safe
	 *
	 */
	public function callback_forum_status( $status = 'on' ) {
		switch ( $status ) {
			case '' :
				$status = 'closed';    // WP Symposium closed forum "allow_new = ''"
				break;

			case 'on' :
			default :
				$status = 'open';      // WP Symposium forum open to new topics "allow_new = 'on'"
				break;
		}
		return $status;
	}

	/**
	 * Translate the topic status from WP Symposium strings to bbPress strings.
	 *
	 * @param string $status WP Symposium string topic status, 'on' or ''
	 * @return string WordPress safe
	 */
	public function callback_topic_status( $status = 'on' ) {
		switch ( $status ) {
			case '' :
				$status = 'closed';    // WP Symposium closed topic "allow_replies = ''"
				break;

			case 'on' :
			default :
				$status = 'publish';   // WP Symposium Normal Topic "allow_replies = 'on'"
				break;
		}
		return $status;
	}

	/**
	 * Translate the topic sticky status type from WP Symposium numeric's to bbPress strings.
	 *
	 * @param int $status WP Symposium numeric forum type
	 * @return string WordPress safe
	 */
	public function callback_sticky_status( $status = 0 ) {
		switch ( $status ) {
			case 1 :
				$status = 'sticky';    // WP Symposium Sticky topic 'topic_sticky = 1'
				break;

			case 0  :
			default :
				$status = 'normal';    // WP Symposium normal topic 'topic_sticky = 0'
				break;
		}
		return $status;
	}

	/**
	 * Convert the WP Symposium secondary reply parent id to the topic id
	 *
	 * @param string $field
	 * @return string
	 */
	protected function callback_replytoid_to_topicid( $field ) {
		if ( !isset( $this->map_topicid[$field] ) ) {
			if ( !empty( $this->sync_table ) ) {
				$row = $this->wpdb->get_row( $this->wpdb->prepare( 'SELECT value_id, meta_value FROM ' . $this->sync_table_name . ' WHERE meta_key = "_bbp_old_topic_id" AND meta_value = "%s" LIMIT 1', $field ) );
			} else {
				$row = $this->wpdb->get_row( $this->wpdb->prepare( 'SELECT pm2.meta_value AS value_id FROM ' . $this->wpdb->postmeta . ' AS pm1 JOIN ' . $this->wpdb->postmeta . ' AS pm2 ON pm2.post_id = pm1.post_id AND pm2.meta_key = "_bbp_topic_id" WHERE pm1.meta_key = "_bbp_post_id" AND pm1.meta_value = "%s" LIMIT 1', $field ) );
			}
			if ( !is_null( $row ) ) {
				$this->map_topicid[$field] = $row->value_id;
			} else {
				$this->map_topicid[$field] = 0;
			}
		}
		return $this->map_topicid[$field];
	}

	/**
	 * This callback processes any custom parser.php attributes and custom code with preg_replace
	 */
	protected function callback_html( $field ) {

		// Strips WP Symposium custom HTML first from $field before parsing $field to parser.php
		$wps_markup = $field;
		$wps_markup = html_entity_decode( $wps_markup );

		// Replace '[youtube]$1[/youtube]' with 'https://youtu.be/$1"
		$wps_markup = preg_replace( '/\[youtube\](.*?)\[\/youtube\]/', 'https://youtu.be/$1', $wps_markup );

		// Now that WP Symposium custom HTML has been stripped put the cleaned HTML back in $field
		$field = $wps_markup;

		// Parse out any bbCodes in $field with the BBCode 'parser.php'
		require_once( bbpress()->admin->admin_dir . 'parser.php' );
		$bbcode = BBCode::getInstance();
		$bbcode->enable_smileys = false;
		$bbcode->smiley_regex   = false;
		return html_entity_decode( $bbcode->Parse( $field ) );
	}
}
