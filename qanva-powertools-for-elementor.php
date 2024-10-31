<?php
/**
 * Plugin Name: Qanva Powertools for Elementor
 * Description: Add special settings, cloning of pages,posts/templates in Elementor
 * Plugin URI:  https://qanva.tech/qanva-powertools-for-elementor
 * Version:     2.2.2
 * Author:      ukischkel, fab22
 * Author URI:  https://qanva.tech
 * License:		 GPL v2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: qanva-powertools-for-elementor
 * Domain Path: languages
 * Elementor tested up to: 3.15.3
 * Elementor Pro tested up to: 3.15.1
**/

	 if (!defined('ABSPATH')) {
		 exit;
	 }

	 define('MAKEPOWERSETTINGSVERSION', '2.2.2');

	 if (!get_option('qanva_buttons_for_elementor')) {
		 add_option('qanva_buttons_for_elementor', '');
	 }
	 if (!get_option('qanva_buttons_for_elementor_font')) {
		 add_option('qanva_buttons_for_elementor_font', [0, 0]);
	 }
	 if (!get_option('qanva_buttons_for_elementor_fontaw')) {
		 add_option('qanva_buttons_for_elementor_fontaw', [0, 0]);
	 }

	 $name = __('Qanva Powertools for Elementor', 'qanva-powertools-for-elementor');
	 $desc = __('Add special settings, cloning of pages,posts/templates in Elementor', 'qanva-powertools-for-elementor');

final class MAKEPOWERSETTINGSELEMENTOR {
   const MINIMUM_ELEMENTOR_VERSION = '3.5.0';
   const MINIMUM_PHP_VERSION = '7.0';
   private static $_instance = null;
   public static function instance() {
      if (is_null(self::$_instance)) {
         self::$_instance = new self();
      }
      return self::$_instance;
   }

   public function __construct() {
      add_action('plugins_loaded', [$this, 'ladesprachdateifuerpowersettingsforelementor']);
      add_action('plugins_loaded', [$this, 'onpluginsloaded']);
      if (1 == get_option('qanva_buttons_for_elementor_font')) {
         add_filter('elementor/frontend/print_google_fonts', '__return_false');
      }
   }

   public function ladesprachdateifuerpowersettingsforelementor() {
      $pfad = dirname(plugin_basename(__FILE__)) . '/languages/';
      load_plugin_textdomain('qanva-powertools-for-elementor', false, $pfad);
   }

   public function onpluginsloaded() {
      if ($this->is_compatible()) {
         add_action('elementor/init', [$this, 'init']);
      }
   }

   /** Link im Plugin-Eintrag Plugin Seite **/
   public function addextrapowertoolslinks(array $links) {
      $url = get_admin_url() . 'options-general.php?page=qanva-powertools';
      $teamlinks = '<a href="' . $url . '" >' . __("Settings", "qanva-powertools-for-elementor") . '</a> | ';
      $links['qanva-powertools-for-elementor-link'] = $teamlinks;
      return $links;
   }

   public function addextrapowertoolspage() {
      add_submenu_page('', '', '', 'manage_options', 'qanva-powertools', [$this, 'extrapowertoolscontent']);
   }

   public function extrapowertoolscontent() {
      include_once 'settings.php';
   }

   public function extra_elementormenu_css() {
      wp_enqueue_style('qanva-powertools-for-elementor-admin', plugin_dir_url(__FILE__) . 'css/qanva-powertools-for-elementor-admin.css', true, MAKEPOWERSETTINGSVERSION, 'all');
   }

   /** Check required min versions **/
   public function is_compatible() {
      if (!did_action('elementor/loaded')) {
         add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
         return false;
      }
      if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
         add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
         return false;
      }
      if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
         add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
         return false;
      }
      return true;
   }

   public function admin_notice_missing_main_plugin() {
      if (isset($_GET['activate'])) unset($_GET['activate']);
      $message = sprintf(esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'qanva-powertools-for-elementor') , '<strong>' . esc_html__('POWER SETTINGS for Elementor', 'qanva-powertools-for-elementor') . '</strong>', '<strong>' . esc_html__('Elementor', 'qanva-powertools-for-elementor') . '</strong>');
      printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
   }

   public function admin_notice_minimum_elementor_version() {
      if (isset($_GET['activate'])) unset($_GET['activate']);
      $message = sprintf(esc_html__('"%1$s" requires min version "%2$s" of Elementor to be installed.', 'qanva-powertools-for-elementor') , '<strong>' . esc_html__('POWER SETTINGS for Elementor', 'qanva-powertools-for-elementor') . '</strong>', '<strong>' . MINIMUM_ELEMENTOR_VERSION . '</strong>');
      printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
   }

   public function admin_notice_minimum_php_version() {
      if (isset($_GET['activate'])) unset($_GET['activate']);
      $message = sprintf(esc_html__('"%1$s" requires min PHP version "%2$s" running.', 'qanva-powertools-for-elementor') , '<strong>' . esc_html__('POWER SETTINGS for Elementor', 'qanva-powertools-for-elementor') . '</strong>', '<strong>' . MINIMUM_PHP_VERSION . '</strong>');
      printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
   }

   public function init() {
      add_filter('plugin_action_links_' . plugin_basename(__FILE__) , [$this, 'addextrapowertoolslinks'], 10, 1);
      add_action('admin_menu', [$this, 'addextrapowertoolspage']);
      add_action('admin_footer', [$this, 'extra_elementormenu_css']);
      add_action('elementor/editor/after_enqueue_scripts', [$this, 'extra_elementormenu_buttons_admin']);
      add_action('elementor/editor/after_enqueue_styles', [$this, 'extra_elementormenu_buttons_admin_css']);
      add_action('wp_ajax_setnewpermaname', [$this, 'setnewpermaname']);
      add_action('wp_ajax_getqptefavorites', [$this, 'getqptefavorites']);
      add_action('elementor/editor/after_enqueue_scripts', [$this, 'powersettings_scripts']);
      add_action('elementor/controls/controls_registered', [$this, 'init_controls']);
      add_action('elementor/frontend/after_register_styles', [$this, 'remove_fontawsome']);
      add_action('wp_footer', [$this, 'shortcutpreview']);
   }

   /** Elementor controls **/
   public function init_controls() {
      require_once (__DIR__ . '/controls/powertoolcontrols.php');
      Qanvapowerusertoolscontrols::start();
   }

   /** read all posts, pages, landing-pages and create option-list **/
   public function eebaoptionmaker($place) {
      global $wpdb;
      $linkval = '';
      $linkvalb = '';
      $allposts = get_posts();
      $allpages = get_pages(['post_type' => 'page', 'sort_column' => 'ID', 'post_status' => 'publish,draft', ]);
      $alllandingpages = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type='e-landing-page'");
      $alltemplates = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type='elementor_library'");
      $pagetypes = ['page', 'post', 'e-landing-page'];
      $isaktid = get_the_ID();
      if (!empty(get_option('elementor_cpt_support'))) {
         $pagetypes = get_option('elementor_cpt_support');
      }
      if (in_array('post', $pagetypes)) {
         $linkval = '<optgroup label="Post">';
         $linkvalb = '<optgroup label="Post">';
         for ($x = 0;$x < count($allposts);$x++) {
            $adda = '';
            if ($allposts[$x]->ID == $isaktid && 2 == $place) {
               $adda = 'selected';
            }
            $linkval .= '<option value="' . $allposts[$x]->ID . '">Post-Title: ' . $allposts[$x]->post_title . ' - (' . __($allposts[$x]->post_name) . ')</option>';
            $linkvalb .= '<option value="' . $allposts[$x]->ID . '" ' . $adda . '>Post-Title: ' . $allposts[$x]->post_title . ' - (' . __($allposts[$x]->post_name) . ')</option>';
         }
         $linkval .= '</optgroup>';
         $linkvalb .= '</optgroup>';
      }
      if (in_array('page', $pagetypes)) {
         $linkval .= '<optgroup label="Page">';
         $linkvalb .= '<optgroup label="Page">';
         for ($y = 0;$y < count($allpages);$y++) {
            $pname = $allpages[$y]->post_name;
            if ('' == $allpages[$y]->post_name) {
               $pname = $allpages[$y]->post_title;
            }
            $addb = '';
            if ($allpages[$y]->ID == $isaktid && 2 == $place && 2 == $place) {
               $addb = 'selected';
            }
            $linkval .= '<option value="' . $allpages[$y]->ID . '">Page-Title: ' . $allpages[$y]->post_title . ' - (' . $pname . ')</option>';
            $linkvalb .= '<option value="' . $allpages[$y]->ID . '" ' . $addb . '>Page-Title: ' . $allpages[$y]->post_title . ' - (' . $pname . ')</option>';
         }
         $linkval .= '</optgroup>';
         $linkvalb .= '</optgroup>';
      }
      if (in_array('e-landing-page', $pagetypes)) {
         $linkval .= '<optgroup label="Landingpage">';
         $linkvalb .= '<optgroup label="Landingpage">';
         for ($q = 0;$q < count($alllandingpages);$q++) {
            $addc = '';
            if ($alllandingpages[$q]->ID == $isaktid && 2 == $place) {
               $addc = 'selected';
            }
            $linkval .= '<option value="' . $alllandingpages[$q]->ID . '">Landingpage: ' . $alllandingpages[$q]->post_name . ' - (' . $alllandingpages[$q]->post_title . ')</option>';
            $linkvalb .= '<option value="' . $alllandingpages[$q]->ID . '" ' . $addc . '>Landingpage: ' . $alllandingpages[$q]->post_name . ' - (' . $alllandingpages[$q]->post_title . ')</option>';
         }
         $linkval .= '</optgroup>';
         $linkvalb .= '</optgroup>';
      }
      for ($z = 0;$z < count($alltemplates);$z++) {
         if ('standard-kit' != $alltemplates[$z]->post_name) {
            $linkval .= '<optgroup label="Templates">';
            $linkvalb .= '<optgroup label="Templates">';
            $addd = '';
            if ($alltemplates[$z]->ID == $isaktid && 2 == $place) {
               $addd = 'selected';
            }
            $linkval .= '<option value="' . $alltemplates[$z]->ID . '">Templates: ' . $alltemplates[$z]->post_name . ' - (' . $alltemplates[$z]->post_title . ')</option>';
            $linkvalb .= '<option value="' . $alltemplates[$z]->ID . '" ' . $addd . '>Templates: ' . $alltemplates[$z]->post_name . ' - (' . $alltemplates[$z]->post_title . ')</option>';
         }
         $linkval .= '</optgroup>';
         $linkvalb .= '</optgroup>';
      }
      return [$linkval, $linkvalb];
   }

   /** clonen **/
   public function extra_elementormenu_buttons_admin() {
      global $wpdb;

      if (isset($_POST['openmyclone'])) {
         $id = sanitize_text_field($_POST['qanvaeebcloneselect']);
         $p = get_post($id);
         $pagename = $p->post_name;
         if ('' != $_POST['qanvaeebperma']) {
            $pagename = strtolower(sanitize_text_field($_POST['qanvaeebperma']));
         }
         if ($p == null) return false;
         $newpost = array(
            'post_name' => $pagename,
            'post_type' => $p->post_type,
            'ping_status' => $p->ping_status,
            'post_parent' => $p->post_parent,
            'menu_order' => $p->menu_order,
            'post_password' => $p->post_password,
            'post_excerpt' => $p->post_excerpt,
            'comment_status' => $p->comment_status,
            'post_title' => ucfirst($pagename) ,
            'post_content' => $p->post_content,
            'post_author' => $p->post_author,
            'to_ping' => $p->to_ping,
            'pinged' => $p->pinged,
            'post_content_filtered' => $p->post_content_filtered,
            'post_category' => $p->post_category,
            'tags_input' => $p->tags_input,
            'tax_input' => $p->tax_input,
            'page_template' => $p->page_template,
            'post_status' => 'publish',
            'post_date' => $p->post_date,
            'post_date_gmt' => $p->post_date_gmt,
         );

         $newid = wp_insert_post($newpost);
         $format = get_post_format($id);
         set_post_format($newid, $format);

         /** copy taxonomies  **/
         $taxonomies = get_object_taxonomies($p->post_type);
         if (!empty($taxonomies) && is_array($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
               $terms = wp_get_object_terms($p->ID, $taxonomy, ['fields' => 'slugs']);
               wp_set_object_terms($newid, $terms, $taxonomy, false);
            }
         }

         /** copy meta **/
         $entries = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", $id));

         if (is_array($entries)) {
            $query = "INSERT INTO {$wpdb->postmeta} ( post_id, meta_key, meta_value ) VALUES ";
            $records = [];
            foreach ($entries as $entry) {
               $value = wp_slash($entry->meta_value);
               $records[] = "( $newid, '{$entry->meta_key}', '{$value}' )";
            }
            $query .= implode(', ', $records) . ';';
            $wpdb->query($query);

         }
         /** open clone in Elementor **/
         echo '<meta http-equiv="refresh" content="0; url=post.php?post=' . esc_attr($newid) . '&action=elementor">';
         exit;

      }

      $clonetext = __('Clone and open', 'qanva-powertools-for-elementor');

      echo '<style>';
      echo "#qanvaeebselect,#qanvaeebcloneselect,#qanvaeebselectmodal{border:none;appearance: none;-webkit-appearance: none;-moz-appearance: none;cursor: pointer;height:34px;background:white url(\"data:image/svg+xml;utf8,<svg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M0 0h24v24H0z' fill='white'/><path d='M7 10l5 5 5-5z'/></svg>\") no-repeat 97%;border: 1px solid #e6e9ec;padding: 3px 35px 3px 15px;border-radius:0;margin:0 0 10px 0;color:dimgrey}";
      echo '</style>';

?>
<!-- modal -->			
<div id="qanvaeeboverlay">
<div class="qanvaeebinfo" id="qanvaeebinfo">
<form method="post" action="">
<?php _e('Select to open', 'qanva-powertools-for-elementor'); ?>
<br>
<select name="qanvaeebselectmodal" id="qanvaeebselectmodal" autocomplete="off" >
<option><?php _e('Select to open', 'qanva-powertools-for-elementor'); ?></option>
<?php echo $this->eebaoptionmaker(1) [1]; ?>
</select>
<br>
<input type="button" class="qanvaquicknew" onclick="window.open('post.php?post=' + document.getElementById('qanvaeebselectmodal').value + '&action=elementor');document.getElementById('qanvaeeboverlay').style.display = 'none';" value="<?php _e('New Window', 'qanva-powertools-for-elementor') ?>">
<input type="button" class="qanvaquicksame" onclick="window.location = 'post.php?post=' + document.getElementById('qanvaeebselectmodal').value + '&action=elementor'" value="<?php _e('Same Window', 'qanva-powertools-for-elementor') ?>">
<?php _e('Select to clone', 'qanva-powertools-for-elementor'); ?>
<br>
<select name="qanvaeebcloneselect" id="qanvaeebcloneselect" autocomplete="off">
<?php echo $this->eebaoptionmaker(2) [1]; ?>
</select><br>
<?php _e('Set new name of clone (optional)', 'qanva-powertools-for-elementor') ?><br>
<input name="qanvaeebperma" type="text" pattern="[a-z0-9]{2,}[a-z0-9-]{1,}" autocomplete="off" title="<?php _e('Only small letters, numbers and dash allowed, begin with min 4 characters', 'qanva-powertools-for-elementor') ?>"><br>
<input type="submit" name="openmyclone" value="<?php echo esc_attr($clonetext); ?>">
<input type="button" onclick="document.getElementById('qanvaeeboverlay').style.display='none';" value="<?php _e('Cancel', 'qanva-powertools-for-elementor') ?>">
</form>
</div>
</div>
<?php

   }

   /** react to saved values and send to JS **/
   public function powersettings_scripts() {
      $savevalues = [];
      if (!empty(get_option('qanva_buttons_for_elementor'))) {
         $savevalues = get_option('qanva_buttons_for_elementor');
      }

      $buttonwerte = array_reverse($savevalues);

      $clonetext = __('Clone and open', 'qanva-powertools-for-elementor');

      $linkurl = [];
      $target = [];
      $linkname = [];
      if (!empty($buttonwerte)) {
         foreach ($buttonwerte as $key => $val) {
            array_push($linkurl, $val[0]);
            array_push($target, $val[1]);
            array_push($linkname, $val[2]);
         }
      }

      wp_enqueue_script('qanva_powertools', plugins_url('js/qanvapower_back.js', __FILE__) , ['jquery'], MAKEPOWERSETTINGSVERSION);
      wp_localize_script('qanva_powertools', 'qanvapowertoolsvals', ['seltext' => __('Select to open', 'qanva-powertools-for-elementor') , 'linkliste' => $this->eebaoptionmaker(1) [0], 'qanva_extrabutton_url' => $linkurl, 'qanva_extrabutton_target' => $target, 'qanva_extrabutton_text' => $linkname, 'qanva_extrabutton_self' => __('Open in same window', 'qanva-powertools-for-elementor') , 'qanva_extrabutton_new' => __('Open in new window', 'qanva-powertools-for-elementor') , 'qanva_extrabutton_clone' => esc_attr($clonetext) , 'qanva_save_perma' => __('Save', 'qanva-powertools-for-elementor') , 'qanva_done' => __('Done', 'qanva-powertools-for-elementor') , ]);
   }

   public function extra_elementormenu_buttons_admin_css() {
      wp_enqueue_style('qanva_pt_style', plugins_url('css/qanvapower_back.css', __FILE__) , true, MAKEPOWERSETTINGSVERSION, 'all');
   }

   public function remove_fontawsome() {
      if (1 == get_option('qanva_buttons_for_elementor_fontaw')) {
         foreach (['solid', 'regular', 'brands'] as $style) {
            wp_deregister_style('elementor-icons-fa-' . $style);
         }
      }
   }

   /** Values by AJAX for new post_name **/
   function setnewpermaname() {
      global $wpdb;
      $data = ['post_name' => sanitize_text_field($_POST['newname']) ];
      $where = ['id' => sanitize_text_field($_POST['postid']) ];
      if ($wpdb->update($wpdb->prefix . 'posts', $data, $where) == 1) {
         wp_die(__('Done', 'qanva-powertools-for-elementor'));
      }
   }

   /** Values for favorite widgets **/
   function getqptefavorites() {
      global $wpdb;
      $data = hash('ripemd256', sanitize_text_field($_POST['qanvausermail']) . sanitize_text_field($_POST['qanvauserpw']));
      if ('getfavorites' == $_POST['todo']) {
         $remote = wp_remote_get('https://qanva.tech/wp-content/plugins/qanvauser/qanvauser.php?getuserdata=' . $data);
         #	$remote = wp_remote_get('http://localhost/develop/wp-content/plugins/qanvauser/qanvauser.php?getuserdata=' . $data );
         if (update_user_meta(get_current_user_id() , $wpdb->prefix . 'elementor_editor_user_favorites', unserialize($remote['body']))) {
            echo "OK";
         }
      }
      if ('setfavorites' == $_POST['todo']) {
         $favs = serialize(get_user_meta(get_current_user_id() , $wpdb->prefix . 'elementor_editor_user_favorites', true));
         $remote = wp_remote_get('https://qanva.tech/wp-content/plugins/qanvauser/qanvauser.php?setuserdata=' . $data . '&favi=' . $favs);
         #	$remote = wp_remote_get('http://localhost/develop/wp-content/plugins/qanvauser/qanvauser.php?setuserdata=' . $data  . '&favi=' . $favs);
         echo "OK";
      }

   }

   /** shortcut im Previewfenster **/
   public function shortcutpreview() {
      if (\Elementor\Plugin::$instance
         ->preview
         ->is_preview_mode()) {
         wp_register_script('qanvapowershortcut', plugin_dir_url(__FILE__) . 'js/qanvapowershortcut.js', [], MAKEPOWERSETTINGSVERSION, true);
         wp_enqueue_script('qanvapowershortcut');
      }
   }
}

MAKEPOWERSETTINGSELEMENTOR::instance();

