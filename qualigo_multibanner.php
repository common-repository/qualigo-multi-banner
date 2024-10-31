<?php
/*
Plugin Name: Qualigo Multi Banner
Description: Anzeige der Qualigo Banner
Version: 1.0.0
Author: Qualigo - info@qualigo.com
Author URI: https://qualigo.com
*/

require_once(ABSPATH .'wp-includes/pluggable.php');

// SETP 1 Plugin Setup
// SETP 1 Plugin Setup
function qualigo_activate() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE `".$wpdb->prefix."qualigo_banner` (
        `bid` int(15) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `format` varchar(32) NOT NULL,
        `ds` varchar(10) NOT NULL,
        `dssub` varchar(255) NOT NULL,
        `search` varchar(255) NOT NULL,
        `headline` varchar(10) NOT NULL,
        `text` varchar(10) NOT NULL,
        `url` varchar(10) NOT NULL,
        `background` varchar(10) NOT NULL,
        `border` varchar(10) NOT NULL, 
        PRIMARY KEY  (bid)
    ) ENGINE=InnoDB ".$charset_collate;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'qualigo_activate' );
// SETP 1 Plugin Setup
// SETP 1 Plugin Setup

// Backend Setting Menu
// Backend Setting Menu
add_action('admin_menu', 'qualigo_menu');
function qualigo_menu() {
    add_menu_page('Qualigo Banner', 'Qualigo Banner', 'manage_options', 'qualigo-setup', 'qualigo_setup', '' );
}
// Backend Setting Menu
// Backend Setting Menu

// Setting Mask
// Setting Mask
function qualigo_setup(){
    global $wpdb;
    $rows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."qualigo_banner WHERE 1 ORDER BY bid ASC" );
    $_acc_bid = -1;
    if ( isset($_REQUEST['bid']) ) $_acc_bid = sanitize_text_field($_REQUEST['bid']);
    if ( isset($_POST['acc_bid']) ) $_acc_bid = sanitize_text_field($_POST['acc_bid']);
    if ( isset($_POST['bid']) ) $_acc_bid = sanitize_text_field($_POST['bid']);
    if ( $_acc_bid > 0 ) $row = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."qualigo_banner WHERE bid='".$_acc_bid."'" );
    if ( !isset($row->headline) || strlen($row->headline) < 4 ) $row->headline = "#0B0B61";
    if ( !isset($row->text) || strlen($row->text) < 4 ) $row->text = "#848484";
    if ( !isset($row->url) || strlen($row->url) < 4 ) $row->url = "#848484";
    if ( !isset($row->background) || strlen($row->background) < 4 ) $row->background = "#FFFFFF";
    if ( !isset($row->border) || strlen($row->border) < 4 ) $row->border = "#FF8000";
    $row->format = strtolower($row->format);
    print '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div><h2>Qualigo Banner2</h2></div>';
    print '<h2>Publisher/Banner Einstellungen</h2>';
    print '<form action="?page=qualigo-setup" method="POST" enctype="multipart/form-data">
    <select name="acc_bid" onchange="this.form.submit()">
    <option '.(($_acc_bid=="-1") ? 'selected=selected' : '').' value="-1">neu anlegen</option>';
    if ( count( $rows ) > 0 ) {
        foreach ( $rows AS $row_list ) print '<option '.(($_acc_bid==$row_list->bid) ? 'selected=selected' : '').' value="'.$row_list->bid.'">'.$row_list->bid.' ('.$row_list->format.') '.$row_list->name.'</option>';
    }
    print '</select>';
    if ($_acc_bid > 0 ) print 'Aktuelles Banner : <strong>[QUALIGOBANNER bid="'.$_acc_bid.'"]</strong>';
    print '</form>';
    if ($_acc_bid > 0 ) print '<br>Um ein Banner im Layout einzusetzen, erstellen Sie an der gew&uuml;nschten Position einen Text-Block und setzen in den Contentbereich folgenden Test  :<br><h2><strong>[QUALIGOBANNER bid="'.$_acc_bid.'"]</strong></h2><br>';
    print '<form action="'.esc_attr('admin-post.php').'" method="POST" enctype="multipart/form-data">'
    //print '<form action="?page=qualigo-setup" method="POST" enctype="multipart/form-data">'
		.wp_nonce_field( 'qualigo_to_save', 'qualigo_field' ).
		'<input type="hidden" name="bid" value="'.$_acc_bid.'">
		<input type="hidden" name="action" value="setup_save">
        <table class="form-table" style="width:50%;">
            <tr>
                <td>Interner Titel</td>
                <td colspan="3"><input type="text" style="width:100%;" name="name" value="'.$row->name.'" class="regular-text" ></td>
            </tr>
            <tr>
                <td style="width:25%;">Banner Format</td>
                <td colspan="3">
                <select style="width:100%;" name="format" class="regular-text">
                    <option '.( ($row->format == "") ? 'selected=selected' : '').' value="">Bitte ausw&auml;hlen</option>
                    <option '.( ($row->format == "docking_728x90") ? 'selected=selected' : '').' value="docking_728x90">728x90 Sticky Leaderboard Top</option>
                    <option '.( ($row->format == "docking_728x91") ? 'selected=selected' : '').' value="docking_728x91">728x90 Sticky Leaderboard Bottom</option>
                    <option '.( ($row->format == "docking_120x600") ? 'selected=selected' : '').' value="docking_120x600">160x600Sticky Skyscraper Right</option>
                    <option '.( ($row->format == "slider_200x200") ? 'selected=selected' : '').' value="slider_200x200">200x200 Sider Ad Small Square</option>
                    <option '.( ($row->format == "slider_250x250") ? 'selected=selected' : '').' value="slider_250x250">250x250 Sider Ad Square</option>
                    <option '.( ($row->format == "slider_300x250") ? 'selected=selected' : '').' value="slider_300x250">300x250 Slider Ad Medium Rectangle</option>
                    <option '.( ($row->format == "slider_336x280") ? 'selected=selected' : '').' value="slider_336x280">336x280 Slider Ad Large Rectangle</option>
                    <option '.( ($row->format == "ad_120x600") ? 'selected=selected' : '').' value="ad_120x600">120x600 Skyscraper</option>
                    <option '.( ($row->format == "ad_125x125") ? 'selected=selected' : '').' value="ad_125x125">125x125 Button</option>
                    <option '.( ($row->format == "ad_160x300") ? 'selected=selected' : '').' value="ad_160x300">160x300 Wide Skyscraper (half)</option>
                    <option '.( ($row->format == "ad_160x600") ? 'selected=selected' : '').' value="ad_160x600">160x600 Wide Skyscraper</option>
                    <option '.( ($row->format == "ad_200x200") ? 'selected=selected' : '').' value="ad_200x200">200x200 Small Square</option>
                    <option '.( ($row->format == "ad_234x60") ? 'selected=selected' : '').' value="ad_234x60">234x60 Half Banner</option>
                    <option '.( ($row->format == "ad_250x250") ? 'selected=selected' : '').' value="ad_250x250">250x250 Square</option>
                    <option '.( ($row->format == "ad_300x250") ? 'selected=selected' : '').' value="ad_300x250">300x250 Medium Rectangle</option>
                    <option '.( ($row->format == "ad_336x280") ? 'selected=selected' : '').' value="ad_336x280">336x280 Large Rectangle</option>
                    <option '.( ($row->format == "ad_468x60") ? 'selected=selected' : '').' value="ad_468x60">468x60 Banner</option>
                    <option '.( ($row->format == "ad_600x505") ? 'selected=selected' : '').' value="ad_600x505">600x505 Banner</option>
                    <option '.( ($row->format == "ad_728x90") ? 'selected=selected' : '').' value="ad_728x90">728x90 Leaderboard</option>
                    <option '.( ($row->format == "ad_728x200") ? 'selected=selected' : '').' value="ad_728x200">728x200 Large Leaderboard</option>
                    <option '.( ($row->format == "ad_728x310") ? 'selected=selected' : '').' value="ad_728x310">728x310 Large Leaderboard</option>
                    <option '.( ($row->format == "ad_728x595") ? 'selected=selected' : '').' value="ad_728x595">728x595 Banner</option>
                </select></td>
            </tr>
            <tr>
                <td width="25%">Ihre Publisher-ID</td>
                <td width="25%"><input type="text" style="width:100%;" name="ds" value="'.$row->ds.'" class="regular-text" ></td>
                <td width="25%">Ihre Publisher-SUB-ID</td>
                <td width="25%"><input type="text" style="width:100%;" name="dssub" value="'.$row->dssub.'" class="regular-text" ></td>
            </tr>
            <tr>
                <td colspan="4">WICHTIG: Für die Zuordnung der Banner muss Ihre Publisher-ID nach der Registrierung auf qualigo.com immer eingetragen werden! Als SUB-ID k&ouml;nnen Sie einen eignen Text, ohne Lerrzeichen und Sonderzeichen, w&auml;hlen.</td>
            </tr>
            <tr>
                <td>Keyword</td>
                <td colspan="3"><input type="text" style="width:100%;" name="search" value="'.$row->search.'" class="regular-text" ></td>
            </tr>
            <tr>
                <td>Farbe Titelzeile</td>
                <td><input type="text" style="width:100%;" name="headline" value="'.$row->headline.'" class="color-field" ></td>
                <td>Farbe Text</td>
                <td><input type="text" style="width:100%;" name="text" value="'.$row->text.'" class="color-field" ></td>
            </tr>
            <tr>
                <td>Farbe URL-Adresse</td>
                <td><input type="text" style="width:100%;" name="url" value="'.$row->url.'" class="color-field" ></td>
                <td>Farbe Hintergrund</td>
                <td><input type="text" style="width:100%;" name="background" value="'.$row->background.'" class="color-field" ></td>
            </tr>
            <tr>
                <td>Farbe Rahmen</td>
                <td colspan="3"><input type="text" style="width:100%;" name="border" value="'.$row->border.'" class="color-field"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3"><input name="save_qualigo_setup" id="submit" class="button button-primary" value="Einstellungen speichern" type="submit"></td>
            </tr>
        </table>
    </form>';
}
// Setting Mask
// Setting Mask

wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

// Setting save
// Setting save
add_action( 'admin_post_setup_save' ,'qualigo_setup_save');
add_action( 'admin_post_nopriv_setup_save' ,'qualigo_setup_save');
function qualigo_setup_save() {
	//if ( !wp_verify_nonce( $_POST['qualigo_field'], 'qualigo_to_save' ) ) print "FEHLER2";
	if ( !isset($_POST['qualigo_field'] ) || ! wp_verify_nonce( $_POST['qualigo_field'], 'qualigo_to_save' ) ) {
	}
	else if( !current_user_can('editor') && !current_user_can('administrator') ) {
		
	}
	else {
		global $wpdb;
		$_POST["dssub"] = str_replace(" ", "", sanitize_text_field($_POST["dssub"]));
		$_POST["format"] = strtolower(sanitize_text_field($_POST["format"]));
		if ( $_POST["bid"] < 1 ) {
			$aw = $wpdb->insert( 
				$wpdb->prefix."qualigo_banner", 
				array( 
					'name' => sanitize_text_field($_POST["name"]),
					'format' => sanitize_text_field($_POST["format"]),
					'ds' => sanitize_text_field($_POST["ds"]),
					'dssub' => sanitize_text_field($_POST["dssub"]),
					'search' => sanitize_text_field($_POST["search"]),
					'headline' => sanitize_text_field($_POST["headline"]),
					'text' => sanitize_text_field($_POST["text"]),
					'url' => sanitize_text_field($_POST["url"]),
					'background' => sanitize_text_field($_POST["background"]),
					'border' => sanitize_text_field($_POST["border"])
				), 
				array( 
					'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'
				)
			);
			$_POST["bid"] = $wpdb->insert_id;
		}
		else {
			$wpdb->update( 
				$wpdb->prefix.'qualigo_banner',
				array( 
					'name' => sanitize_text_field($_POST["name"]),
					'format' => sanitize_text_field($_POST["format"]),
					'ds' => sanitize_text_field($_POST["ds"]),
					'dssub' => sanitize_text_field($_POST["dssub"]),
					'search' => sanitize_text_field($_POST["search"]),
					'headline' => sanitize_text_field($_POST["headline"]),
					'text' => sanitize_text_field($_POST["text"]),
					'url' => sanitize_text_field($_POST["url"]),
					'background' => sanitize_text_field($_POST["background"]),
					'border' => sanitize_text_field($_POST["border"]),
				), 
				array( 'bid' => sanitize_text_field($_POST["bid"]) ),
				array( 
					'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'
				), 
				array( '%d' )
			);
		}
	}
	//return qualigo_setup();
	wp_redirect(admin_url('admin.php?page=qualigo-setup&bid='.$_POST["bid"]));
}
// Setting save
// Setting save

// Banner show
// Banner show
add_shortcode("QUALIGOBANNER", "show_qualigo_banner");
function show_qualigo_banner( $argv ){
    global $wpdb;
    if ( !isset($argv['bid'])) return;
    $bid = $argv['bid'];
    $row = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."qualigo_banner WHERE bid='".$bid."' "  );
    print '<script type="text/javascript">
        var QualiGOAdOptions = {
        ad_ds : "'.$row->ds.'",
        ad_subds : "'.$row->dssub.'",
        ad_cat : "",
        ad_search : "'.$row->search.'",
        ad_wo : "de",
        ad_m : "de",
        ad_erotic : "0",
        ad_name : "'.$row->format.'",
        ad_target : "0",
        ad_track : "WP01",
        ad_trackingurl : "",
        ad_color_headline : "'.str_replace("#", "", $row->headline).'",
        ad_color_text : "'.str_replace("#", "", $row->text).'",
        ad_color_url : "'.str_replace("#", "", $row->url).'",
        ad_color_background : "'.str_replace("#", "", $row->background).'",
        ad_color_border : "'.str_replace("#", "", $row->border).'",
        ad_start : 1,
        };
        (function(src,params) {
        var position = document.getElementsByTagName("script");
        position = position[position.length-1];
        qi=document.createElement("script");
        qi.async="async";
        qi.src=src;
        qi.onload = (function() { displaynow(params,position); });
        position.parentNode.insertBefore(qi,position);
        }) ( "//qualigo.com/doks/ad.js", QualiGOAdOptions );
    </script>';
}
// Banner show
// Banner show

// Widget show
// Widget show
class Qualigo_Banner_Widget extends WP_Widget {
    // Frontend-Design Funktionen
    public function __construct(){
        $this->var_sTextdomain = 'Qualigo-Banner-Widget';
        $widget_options = array(
            'classname' => 'Qualigo_Banner_Widget',
            'description' => __('Gespeicherte Banner im Layout anzeigen.', $this->var_sTextdomain)
        );
        $control_options = array();
        $this->WP_Widget('Qualigo_Banner_Widget', __('Qualigo Banner', $this->var_sTextdomain), $widget_options, $control_options);
    }
    public function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;
        $bid = (empty($instance['qualigo-bid'])) ? '' : apply_filters('qualigo-bid', $instance['qualigo-bid']);
        if(!empty($bid)) {
            echo $before_title .$after_title;
        }
		global $wpdb;
        $row = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."qualigo_banner WHERE bid='".$bid."' "  );
        $output = '<script type="text/javascript">
        var QualiGOAdOptions = {
        ad_ds : "'.$row->ds.'",
        ad_subds : "'.$row->dssub.'",
        ad_cat : "",
        ad_search : "'.$row->search.'",
        ad_wo : "de",
        ad_m : "de",
        ad_erotic : "0",
        ad_name : "'.$row->format.'",
        ad_target : "0",
        ad_trackingurl : "",
        ad_color_headline : "'.str_replace("#", "", $row->headline).'",
        ad_color_text : "'.str_replace("#", "", $row->text).'",
        ad_color_url : "'.str_replace("#", "", $row->url).'",
        ad_color_background : "'.str_replace("#", "", $row->background).'",
        ad_color_border : "'.str_replace("#", "", $row->border).'",
        ad_start : 1,
        };
        (function(src,params) {
        var position = document.getElementsByTagName("script");
        position = position[position.length-1];
        qi=document.createElement("script");
        qi.async="async";
        qi.src=src;
        qi.onload = (function() { displaynow(params,position); });
        position.parentNode.insertBefore(qi,position);
        }) ( "//qualigo.com/doks/ad.js", QualiGOAdOptions );
        </script>';

        if ( strlen($row->ds) < 1 || strlen($row->format) < 1 ) {
			$output = "";
		}
        echo $output . '</ul>';
		echo $after_widget;
    }
    public function form( $instance ) {
        $instance = wp_parse_args((array) $instance, array(
            'qualigo-bid' => '0'
        ));
        $bid = (empty($instance['qualigo-bid'])) ? '' : apply_filters('qualigo-bid', $instance['qualigo-bid']);
        global $wpdb;
        $rows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."qualigo_banner WHERE 1 ORDER BY bid ASC" );
        ?>
            <p>
Bevor sie ein Banner im Widget auswählen können, müssen Sie dieses bitte im Plugin anlegen.
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('qualigo-bid'); ?>"><?php echo 'Multi Banner:'; ?></label>
                <select id="<?php echo $this->get_field_id('qualigo-bid'); ?>" name="<?php echo $this->get_field_name('qualigo-bid'); ?>">
		<?php
        if ( count( $rows ) > 0 ) {
            foreach ( $rows AS $row_list ) print '<option '.(($bid==$row_list->bid) ? 'selected=selected' : '').' value="'.$row_list->bid.'">'.$row_list->bid.' ('.$row_list->format.') '.$row_list->name.'</option>';
        }
		?>	
				</select>
            </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array(
            'qualigo-bid' => '0'
        ));
        $instance['qualigo-bid'] = (string) strip_tags($new_instance['qualigo-bid']);
        return $instance;
    }
}
// Die Registrierung unseres Widgets
function qualigo_banner_widget_init() {
    register_widget('Qualigo_Banner_Widget');
}
add_action('widgets_init', 'qualigo_banner_widget_init');
// Widget show
// Widget show


