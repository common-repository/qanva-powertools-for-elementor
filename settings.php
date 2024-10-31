 <?php
		wp_enqueue_style( 'uikitcss', plugin_dir_url( __FILE__ ) . 'css/uikit.min.css', true, '3.6.3', 'all' );
		wp_register_script( 'quikitjs', plugin_dir_url( __FILE__ ) . 'js/uikit.min.js', ['jquery'], '3.6.3', true );
		wp_enqueue_script('quikitjs');
		
?>
	<script type="text/javascript">
	window.addEventListener( 'load', function(){
		document.getElementById( 'wpfooter' ).remove();
		var notice = document.getElementsByClassName( 'fs-notice' );
		for(var i = 0;i < notice.length;i++){
			notice[i].remove();
		}
	});
	</script>
	<style>
html, body{
    height:100%;
    background:rgb(241, 241, 241) !important;
}
.qanva .logo{
	width: 40px;
margin: 0 10px 5px 0;
}
</style>
<div class="wrap">
<?php
global $wpdb;

	/** sanitize alle $_POST variables **/
	$cleanedpost = [];
	if ( isset( $_POST ) ) {
		foreach( $_POST AS $key => $val ){
			$cleanedpost[ $key ] = sanitize_text_field( $val );
		}
	}
	
	if ( isset( $cleanedpost[ 'qanvasubmit' ] ) ) {
	}
	
			/* speichern */
   if ( isset( $cleanedpost[ 'qanvasubmit' ]  ) && wp_verify_nonce( $_POST[ 'qanvaproofone' ], 'qanvasubmit' ) ) {
					/** Fontawsome speichern **/
						if( isset( $cleanedpost[ 'nofontaw' ] ) && 1 == $cleanedpost[ 'nofontaw' ] ){
							update_option( 'qanva_buttons_for_elementor_fontaw',1 );
						}
						if( !isset( $cleanedpost[ 'nofontaw' ] ) ){
							update_option( 'qanva_buttons_for_elementor_fontaw',0 );
						}
					/** Google Fonts speichern **/
						if( isset( $cleanedpost[ 'nofont' ] ) && 1 == $cleanedpost[ 'nofont' ] ){
							update_option( 'qanva_buttons_for_elementor_font',1 );
						}
						if( !isset( $cleanedpost[ 'nofont' ] ) ){
							update_option( 'qanva_buttons_for_elementor_font',0 );
						}
						
				/** Links speichern **/
    $newarr = [];
				$suche = [ 'Ü','ü','Ö','ö', 'Ä', 'ä', 'ß', '*'  ];
				$ersatz = [ '&Uuml;','&uuml;','&Ouml;','&uuml;', '&Auml;', '&auml;', 'ss', '<br />' ];
						if( ( !empty( $cleanedpost[ 'pagetarget' ] ) || !empty( $cleanedpost[ 'pagetargettext' ] ) ) && !empty( $cleanedpost[ 'pagename' ] ) ){
							$new_arr[] = [ $cleanedpost[ 'pagetarget' ] . $cleanedpost[ 'pagetargettext' ] , $cleanedpost[ 'linktarget' ] , str_replace( $suche, $ersatz, $cleanedpost[ 'pagename' ] ) ] ;
																								
								$old_arr = get_option( 'qanva_buttons_for_elementor' );
								if( !empty( $old_arr ) ){
												$old_arr = array_merge( $old_arr, $new_arr );
								}
								else{
												$old_arr = $new_arr;
								}
									update_option( 'qanva_buttons_for_elementor', $old_arr );
				}
	}
	
	/* Links löschen */
 if ( isset( $cleanedpost[ 'deleter' ]  ) && wp_verify_nonce( $_POST[ 'qanvaprooftwo' ], 'qanvasubmitb' ) ) {
		$old_arr = get_option( 'qanva_buttons_for_elementor' ); 
			unset( $old_arr[ $cleanedpost[ 'deleter' ][0] ] );
				update_option( 'qanva_buttons_for_elementor', $old_arr );
	}
		
	if( '' != get_option( 'qanva_buttons_for_elementor' ) ){
		$savevalues =  get_option( 'qanva_buttons_for_elementor' );
	}
	
	$clonesel = '';
	$cloneop = '';
	if( '' != get_option( 'qanva_buttons_for_elementor_clone' ) ){
		$clonevalues =  get_option( 'qanva_buttons_for_elementor_clone' );
		if(1 == $clonevalues[0]){
			$clonesel = 'checked';
		}
		if(1 == $clonevalues[1]){
			$cloneop = 'checked';
		}
	}
	
	$nogfont = '';
	if( 1 == get_option( 'qanva_buttons_for_elementor_font' ) ){
		$nogfont = 'checked';
	}
	
	$nofawsome = '';
	if( 1 == get_option( 'qanva_buttons_for_elementor_fontaw' ) ){
		$nofawsome = 'checked';
	}
	
	
	/** verlinkbare Optionen **/
		function qanvaebe_get_links(){
				global $menu;
				global $submenu;
						$reval = '';
						$allposts = get_posts();
						$allpages = get_pages( [ 'post_type' => 'page', 'sort_column' => 'ID','post_status' => 'publish,draft' ] );
						$alltemplates = new WP_Query( ['post_type' => 'elementor_library' ] ); 
						$mainlinks = [];
						$mainname = [];
						foreach( $menu AS $key => $vala ){
							if( '' != $vala[ 0 ] ){
								array_push( $mainlinks, $vala[ 2 ] );
								array_push( $mainname, preg_replace( '/[0-9]+/', '', $vala[ 0 ] ) );
							}
						}
		for( $i = 0; $i < count( $mainlinks ); $i++ ){;
			$name = $mainname[ $i ]; 
			if( array_key_exists( $mainlinks[ $i ], $submenu ) ){
				foreach( $submenu[ $mainlinks[ $i ] ] AS $key => $val ){ 
					$subname = $val[ 0 ];
					$target = $val[ 2 ];
					if( '' != $subname ){
						$reval .= '<option value="' . $target . '">' . __( $name ) . ' &rarr; ' . __( preg_replace( '/[0-9]+/', '', $subname ) ) . '</option>'; 
					}
					if( $target == 'edit.php' ){
						for( $x = 0; $x < count( $allposts ); $x++ ){
							$reval .= '<option value="post.php?post=' . $allposts[ $x ] -> ID . '&action=elementor">' . __( $name ) . ' &rarr; '  . ucfirst( __( 'post' ) ) . '-' . __( 'Name' ) . ' &rarr; ' . ucfirst( __( $allposts[ $x ] -> post_name ) ) . '</option>'; 
						}
					}
					if( $target == 'edit.php?post_type=page' ){
						for( $y = 0; $y < count( $allpages ); $y++ ){ 
							$reval .= '<option value="post.php?post=' . $allpages[ $y ] -> ID . '&action=elementor">' . __( $name ) . ' &rarr; '  . ucfirst( __( 'page' ) ) . '-'  . __( 'Name' ) . ' &rarr; ' . ucfirst( __( $allpages[ $y ] -> post_name ) ) . '</option>'; 
						}
					}
					if( strpos( $target, 'tabs_group=library' ) !== false ){
						for( $z = 0; $z < count( $alltemplates -> posts ); $z++ ){ 
							if( 'standard-kit' != $alltemplates -> posts[ $z ] -> post_name ){
								$reval .= '<option value="post.php?post=' . $alltemplates -> posts[ $z ] -> ID . '&action=elementor">' . __( $name ) . ' &rarr; '  . ucfirst( __( 'templates' ) ) . '-'  . __( 'Name' ) . ' &rarr; ' . ucfirst( __( $alltemplates -> posts[ $z ] -> post_name ) ) . '</option>'; 
							}
						}
					}
				}
			}
			else{
				$target = $mainlinks[ $i ];
				if( strpos( $target, 'php' ) < 1 ){
					$target = './admin.php?page=' . preg_replace( '/[0-9]+/', '', $name );
				}
				if( $target == 'edit-comments.php' ){
					$name = __( 'Comments' );
				}
				$reval .= '<option value="' . $target . '">' . __( $name ) .'</option>'; 
			}
					
		}		

		return $reval;
	}

			/* reset favorites */
   if ( isset( $cleanedpost[ 'qanvafavreset' ]  ) && wp_verify_nonce( $_POST[ 'qanvaproofthree' ], 'qanvafavreset' ) ) {
				update_user_meta(get_current_user_id(), $wpdb->prefix . 'elementor_editor_user_favorites','');
			}
?>

	<script type="text/javascript">
	window.addEventListener( 'load', function(){
		document.getElementById( 'wpfooter' ).remove();
		document.getElementsByTagName("title")[0].text = 'Powertools Settings';
	});
	
	jQuery( document ).ready( function( $ ) {
		$( 'select[name=pagetarget]' ).on( 'change', function(){
			if( $( this ).val() != '' ){
				$( 'input[name=pagetargettext]' ).hide();
			}
			else{
				$( 'input[name=pagetargettext]' ).show();
			}
		});
		
		$( 'input[name=pagetargettext]' ).on( 'change keyup', function(){
			if( $( this ).val() != '' ){
				$( 'select[name=pagetarget]' ).hide();
			}
			else{
				$( 'select[name=pagetarget]' ).show();
			}
		});

	});
	</script>

<form id="qanvaebeform" method="post" action="">
<?php wp_nonce_field( 'qanvasubmit', 'qanvaproofone' ); ?>
</form>
<form id="qanvaebeformb" method="post" action="">
<?php wp_nonce_field( 'qanvasubmitb', 'qanvaprooftwo' ); ?>
</form>
<form id="qanvaeceform" method="post" action="">
<?php wp_nonce_field( 'qanvafavreset', 'qanvaproofthree' ); ?>
</form>
<div class="qanva qanvasetting uk-container-center uk-margin-top uk-margin-large-bottom">
<h1><img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/qanvalogo.svg" class="logo">Qanva Powertools - <?php _e( "Page cloning and special settings for Elementor", "qanva-powertools-for-elementor" ); ?>*</h1>
        <div class="uk-grid uk-margin-remove-left uk-margin-right" data-uk-grid-margin>
            <div class="uk-width-1-3 uk-card uk-card-default uk-card-body">
<h4><?php _e( "Settings", "qanva-powertools-for-elementor" ); ?></h4>
<hr>
		<h5><?php _e( "Remove Google-Fonts from frontend", "qanva-powertools-for-elementor" ); ?></h5>
		<div class="switchbox  uk-width-1-1">
		<label for="switch-4" class="switch">
   <input type="checkbox" id="switch-4" name="nofont" class="uk-switch"  form="qanvaebeform" value="1" autocomplete="off" <?php echo esc_attr($nogfont);?>>
			<span class="slider round"></span>
   </label>
		</div>
<hr>
		<h5><?php _e( "Remove Fontawsome from frontend", "qanva-powertools-for-elementor" ); ?></h5>
		<div class="switchbox  uk-width-1-1">
		<label for="switch-5" class="switch">
   <input type="checkbox" id="switch-5" name="nofontaw" class="uk-switch"  form="qanvaebeform" value="1" autocomplete="off" <?php echo esc_attr($nofawsome);?>>
			<span class="slider round"></span>
   </label>
		</div>
<hr>
		<p>
		<button type="submit" name="qanvasubmit" form="qanvaebeform"  class="uk-button uk-button-primary uk-form-small uk-width-1-1" ><?php _e( "Save", "qanva-powertools-for-elementor" ); ?></button>
		</p>
		<?php if(!empty(get_user_meta(get_current_user_id(), $wpdb->prefix . 'elementor_editor_user_favorites')[0])){ ?>
<hr>
	<h5><?php _e( "In case loaded favorites let Elementor crash, you can delete them here", "qanva-powertools-for-elementor" ); ?>.</h5>
		<p>
		<button type="submit" name="qanvafavreset" form="qanvaeceform"  class="uk-button uk-button-danger uk-form-small uk-width-1-1" ><?php _e( "Reset Favorite Widgets", "qanva-powertools-for-elementor" ); ?></button>
		</p>
		<?php } ?>
* Elementor is the trademark of elementor.com  This project is <strong class="red">NOT</strong> affiliated with Elementor!
<style>

</style>
</div>  

		    
				<div class="uk-width-2-3 uk-card uk-card-default uk-card-body qanvaexample">
					<!-- content right -->
				</div>
		</div>  
		<div class="uk-text-right uk-text-meta uk-text-small uk-margin-right"><small>&copy; <?php echo date( "Y");?> <a href="https://qanva.tech" target="_blank" class="uk-link-text" >QANVA.TECH</a> All rights reserved.</small></div>
</div>
</div>

