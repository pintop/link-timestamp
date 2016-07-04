<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://pintopsolutions.com
 * @since      1.0.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/admin
 * @author     Arelthia Phillips <Arelthia Phillips@gmail.com>
 */
class Link_Timestamp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Link_Timestamp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Link_Timestamp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/link-timestamp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Link_Timestamp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Link_Timestamp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/link-timestamp-admin.js', array( 'jquery' ), $this->version, false );

	}


	public function add_lts_menu(){
		add_options_page(
			'Link Timestamp',
			'Link Timestamp',
			'manage_options',
			'linktimestamp',
			array($this,'create_lts_settings_page')
		);
	}

	public function create_lts_settings_page(){
		require plugin_dir_path( __FILE__ ) . 'partials/link-timestamp-admin-display.php';
	}

	public function register_lts_options(){

		register_setting(
			'ps_lts_settings_group',
			'ps_lts_settings',
			array($this,'validate_lts_settings')
		);



		add_settings_section(
			'ps_lts_link_section',
			__('', 'link-timestamp'),
			array($this, 'render_link_section'),
			'linktimestamp'
		);

		add_settings_field(
			'auto_link',
			__('Link timestamps automatically', 'link-timestamp'),
			array($this, 'render_auto_link_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);



		add_settings_field(
			'link_audio',
			__('Link time for embedded audio', 'link-timestamp'),
			array($this, 'render_audio_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_video',
			__('Link time for embedded video', 'link-timestamp'),
			array($this, 'render_video_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_youtube',
			__('Link time for embedded Youtube video', 'link-timestamp'),
			array($this, 'render_youtube_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_vimeo',
			__('Link time for embedded Vimeo video', 'link-timestamp'),
			array($this, 'render_vimeo_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		register_setting(
			'ps_lts_settings_group',
			'ps_lts_link_on',
			array($this,'validate_link_on_settings')
		);

		add_settings_field(
			'ps_lts_link_on',
			__('Post types to auto link timestamps on (Single only)', 'link-timestamp'),
			array($this, 'render_link_on_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);
	}

	public function validate_link_on_settings($input){
        $post_types = get_post_types();
        $valid = array();
        foreach( $post_types as $type) {
           $valid[$type] = isset($input[$type]) && true == $input[$type] ? true : false;
        }

		return $valid;
	}

	public function validate_lts_settings($input){
		$valid = array(
			'link_audio'		 	=> isset($input['link_audio']) && true == $input['link_audio'] ? true : false,
			'link_video' 			=> isset($input['link_video']) && true == $input['link_video'] ? true : false,
			'link_youtube' 			=> isset($input['link_youtube']) && true == $input['link_youtube'] ? true : false,
			'link_vimeo' 			=> isset($input['link_vimeo']) && true == $input['link_vimeo'] ? true : false,
			'auto_link' 		=> isset($input['auto_link']) && true == $input['auto_link'] ? true : false
		);

		return $valid;
	}

	public function render_auto_link_field(){
        $options = get_option('ps_lts_settings');
        $auto_link = $options['auto_link'];
        echo "<input name='ps_lts_settings[auto_link]' type='checkbox'";
        if ($auto_link) echo ' checked ';
        echo "/>";
       echo '<p class="description">' . __('You can manually link timestamps in the editor. If you check this all text 
        formatted <br />like \'1:15\' or \'00:45\' in your posts will be automatically replaced with a link to the correct 
        timestamp.', 'link-timestamp' ) . '</p>';
	}

    public function render_link_on_field(){
        $post_types = get_post_types();
        $options = (array)get_option('ps_lts_link_on');



        foreach ( $post_types as $type ) {
            if( !isset($options[$type]) ){
                $options[$type] = 0;
            }
			echo '<label><input name="ps_lts_link_on['. $type .']" id="ps_lts_link_on['. $type .']" type="checkbox" value="1" class="code" ' . checked( 1, $options[$type], false ) . ' />'. $type .'</label><br />' ;

		}
    }

	public function render_audio_field(){
        $options = get_option('ps_lts_settings');
        $link_audio = $options['link_audio'];
        echo "<input name='ps_lts_settings[link_audio]' type='checkbox'";
        if ($link_audio) echo ' checked ';
        echo "/>";
        echo '<p class="description">' . __('Link Timestamp in audio embedded with the [audio] 
        shortcode or &lt;audio&gt; HTML5 tag.', 'link-timestamp' ) . '</p>';

	}


	public function render_video_field(){
        $options = get_option('ps_lts_settings');
        $link_video = $options['link_video'];
        echo "<input name='ps_lts_settings[link_video]' type='checkbox'";
        if ($link_video) echo ' checked ';
        echo "/>";
        echo '<p class="description">' . __('Link Timestamp in audio embedded with the [video] 
        shortcode or &lt;video&gt; HTML5 tag.', 'link-timestamp' ) . '</p>';
	}

	public function render_youtube_field(){
        $options = get_option('ps_lts_settings');
        $link_youtube = $options['link_youtube'];
        echo "<input name='ps_lts_settings[link_youtube]' type='checkbox'";
        if ($link_youtube) echo ' checked ';
        echo "/>";
        echo '<p class="description">' . __('Link Timestamp in embedded Youtube videos.', 'link-timestamp' ) . '</p>';

	}

	public function render_vimeo_field(){
        $options = get_option('ps_lts_settings');
        $link_vimeo = $options['link_vimeo'];
        echo "<input name='ps_lts_settings[link_vimeo]' type='checkbox'";
        if ($link_vimeo) echo ' checked ';
        echo "/>";
        echo '<p class="description">' . __('Link Timestamp in embedded Vimeo videos.', 'link-timestamp' ) . '</p>';
	}


    public function render_link_section(){

    }

}