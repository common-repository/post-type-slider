<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
/* Plugin custom post type creation for slider */
add_action( 'init', 'pts_custom_post_type' );

// add custom post type with all terms related to slider only
function pts_custom_post_type(){
	$labels = array(
	'name'               => _x( 'Post Type Slider', 'post type general name', 'post-type-slider' ),
	'singular_name'      => _x( 'Post Type Slider', 'post type singular name', 'post-type-slider' ),
	'menu_name'          => _x( 'Post Type Slider', 'admin menu', 'post-type-slider' ),
	'name_admin_bar'     => _x( 'Post Type Slider', 'add new on admin bar', 'post-type-slider' ),
	'add_new'            => _x( 'Add Slider', 'book', 'post-type-slider' ),
	'add_new_item'       => __( 'Add New Slider', 'post-type-slider' ),
	'new_item'           => __( 'New Slider', 'post-type-slider' ),
	'edit_item'          => __( 'Edit Slider', 'post-type-slider' ),
	'view_item'          => __( 'View Slider', 'post-type-slider' ),
	'all_items'          => __( 'All Slider', 'post-type-slider' ),
	'search_items'       => __( 'Search Slider', 'post-type-slider' ),
	'parent_item_colon'  => __( 'Parent Slider:', 'post-type-slider' ),
	'not_found'          => __( 'No Slider found.', 'post-type-slider' ),
	'not_found_in_trash' => __( 'No Slider found in Trash.', 'post-type-slider' )
	);
	
	$args1 = array(
	'public' => true,
	'label'  => 'Post Type Slider',
	'labels' => $labels,
	'supports' => array('title')
	);
	register_post_type('post_type_slider', $args1 );
	
	// removing content editor
	remove_post_type_support( 'post_type_slider', 'editor' );
}

// Adding custom boxes
add_action( 'add_meta_boxes', 'pts_custom_fieldsservices' );
function pts_custom_fieldsservices() {
	add_meta_box(
	'pts_menu_slider_custombox1', // $id
	'Post Type', // $title
	'pts_post_types_available_listing', // $callback
	'post_type_slider', // post type
	'normal', // $context
	'high' // $priority
	);
	add_meta_box(
	'pts_menu_slider_custombox2', // $id
	'Slider Shortcode', // $title
	'pts_post_types_shorcode_display', // $callback
	'post_type_slider', // post type
	'side', // $context
	'high' // $priority
	);
	add_meta_box(
	'pts_menu_slider_custombox3', // $id
	'Slider Options', // $title
	'pts_slider_options', // $callback
	'post_type_slider', // post type
	'normal', // $context
	'high' // $priority
	);
	add_meta_box(
	'pts_menu_slider_custombox4', // $id
	'Slider Contents', // $title
	'pts_slider_contents', // $callback
	'post_type_slider', // post type
	'normal', // $context
	'high' // $priority
	);
}
//Slider Shortcode display
function pts_post_types_shorcode_display(){
	global $post;
	$postid = $post->ID;
	echo "[pts_slideshow postid='".$postid."']";
}
// meta box function callback
function pts_post_types_available_listing($post_id){
	global $post;
	$selected = get_post_meta($post->ID, 'pts_slider', true); // selected slider
	
	// Getting all public post type in wordpress
	$args       = array(
	'public' => true,
	);
	$post_types = get_post_types( $args, 'objects' );
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
?>
<div>
		<select id="pts_slider" name="pts_slider" >
			<?php foreach ( $post_types as $post_type_obj ):
				// Exclude Media type
				if( $post_type_obj->name == "attachment" || $post_type_obj->name == "post_type_slider"){
					continue;
				}
				$labels = get_post_type_labels( $post_type_obj );
			?>
			<option value="<?php echo esc_attr( $post_type_obj->name ); ?>" <?php if($post_type_obj->name==$selected) echo "selected"; ?> > <?php echo esc_html( $labels->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<input name="pts_settings_non" type="hidden" value="<?php echo wp_create_nonce('pts_settings_non_num'); ?>" />
</div> 
<?php 
}
// Slider options save
function pts_slider_options(){
	global $post;
?>
	<div class="formfields">
		<label>Number of slides : </label>
		<input name="pts_num_slides" type="number" value="<?php if(!empty($pts_num_slides= get_post_meta($post->ID, 'pts_num_slides', true))){ echo $pts_num_slides; } ?>" />
	</div>
	<div class="formfields">
		<label>Auto play (ON by default) : </label>
		<select id="autoplay" name="autoplay" >
			<option value="true" selected <?php if((get_post_meta($post->ID, 'autoplay', true)) == "true"){ echo "selected"; } ?> >ON</option>
			<option value="false" <?php if((get_post_meta($post->ID, 'autoplay', true)) == "false"){ echo "selected"; } ?>>OFF</option>
		</select>
	</div>
	<div class="formfields">
		<label>Margin : </label>
		<input name="margin" type="number" value="<?php if(!empty($pts_num_slides= get_post_meta($post->ID, 'margin', true))){ echo $pts_num_slides; } ?>" />
	</div>
	<div class="formfields">
		<label>Looping ON (Last slide will lead to first slide) : </label>
		<select id="loop" name="loop" >
			<option value="true" selected <?php if((get_post_meta($post->ID, 'loop', true)) == "true"){ echo "selected"; } ?> >ON</option>
			<option value="false" <?php if((get_post_meta($post->ID, 'loop', true)) == "false"){ echo "selected"; } ?> >OFF</option>
		</select>
	</div>
	<div class="formfields">
		<label>SlideWidth (Each slides width) : </label>
		<input name="slideWidth" type="number" value="<?php if(!empty($slideWidth= get_post_meta($post->ID, 'slideWidth', true))){ echo $slideWidth; } ?>" />
		<label>If empty, we will take based on the widow size and number of slides per load</label>
	</div>
	<div class="formfields">
		<label>Slide Move By : </label>
		<input name="slide_move_by" type="number" value="<?php if(!empty($pts_num_slides= get_post_meta($post->ID, 'slide_move_by', true))){ echo $pts_num_slides; } ?>" />
	</div>
	<div class="formfields">
		<label>Slide Moving Speed (default 300) : </label>
		<input name="slide_moving_speed" type="number" value="<?php if(!empty($pts_num_slides= get_post_meta($post->ID, 'slide_moving_speed', true))){ echo $pts_num_slides; } ?>" />
	</div>
	<div class="formfields">
		<label>Pause on Hover : </label>
		<select id="pauseonhover" name="pauseonhover" >
			<option value="true" selected <?php if((get_post_meta($post->ID, 'pauseonhover', true)) == "true"){ echo "selected"; } ?> >Yes</option>
			<option value="false" <?php if((get_post_meta($post->ID, 'pauseonhover', true)) == "false"){ echo "selected"; } ?> >No</option>
		</select>
	</div>
	<div class="formfields">
		<label>Navigation Arrows: </label>
		<select id="navigation_arrows" name="navigation_arrows" >
			<option value="true" selected <?php if((get_post_meta($post->ID, 'navigation_arrows', true)) == "true"){ echo "selected"; } ?> >Yes</option>
			<option value="false" <?php if((get_post_meta($post->ID, 'navigation_arrows', true)) == "false"){ echo "selected"; } ?> >No</option>
		</select>
	</div>
	<div class="formfields">
		<label>Navigation Dots: </label>
		<select id="navigation_dots" name="navigation_dots" >
			<option value="true" selected <?php if((get_post_meta($post->ID, 'navigation_dots', true)) == "true"){ echo "selected"; } ?> >Yes</option>
			<option value="false" <?php if((get_post_meta($post->ID, 'navigation_dots', true)) == "false"){ echo "selected"; } ?> >No</option>
		</select>
	</div>
<?php
}
// Slider Contents selections

function pts_slider_contents(){
	global $post;
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
?>
	<div class="formfields">
	<h4>Slider contents to be displayed:</h4>
	</div>
	<div class="formfields">
	
		<input type="checkbox" name="bx_image" value="true"  <?php if((get_post_meta($post->ID, 'bx_image', true)) == "true"){ echo "checked"; } ?> />
		<label class="custom-control-label" for="defaultUnchecked">Show image</label>
		
		<input type="checkbox" name="bx_title" value="true" <?php if((get_post_meta($post->ID, 'bx_title', true)) == "true"){ echo "checked"; } ?> />
		<label class="custom-control-label" for="defaultUnchecked">Show Title</label>
		
		<input type="checkbox" name="bx_content" value="true" <?php if((get_post_meta($post->ID, 'bx_content', true)) == "true"){ echo "checked"; } ?> />
		<label class="custom-control-label" for="defaultUnchecked">Show Content</label>
		
		<input type="checkbox" name="bx_read_more" value="true" <?php if((get_post_meta($post->ID, 'bx_read_more', true)) == "true"){ echo "checked"; } ?> />
		<label class="custom-control-label" for="defaultUnchecked">Show Read More</label>
		
	</div>
	<div class="formfields">
		<label>Content on slider: </label>
		<select id="content_type" name="content_type" >
			<option value="content" selected <?php if((get_post_meta($post->ID, 'content_type', true)) == "content"){ echo "selected"; } ?> >Content</option>
			<option value="excerpt" <?php if((get_post_meta($post->ID, 'content_type', true)) == "excerpt"){ echo "selected"; } ?> >Excerpt</option>
		</select>
	</div>
	<div class="formfields">
		<label>Content character limit : </label>
		<input name="content_chara" type="number" value="<?php if(!empty($content_chara= get_post_meta($post->ID, 'content_chara', true))){ echo $content_chara; } ?>" />
		<label>Default is 50 </label>
	</div>
	<div class="formfields">
		<label>Read More Text : </label>
		<input name="read_more_text" type="text" value="<?php if(!empty($read_more_text= get_post_meta($post->ID, 'read_more_text', true))){ echo $read_more_text; } ?>" />
	</div>
<?php 
}

// save post type post_type_slider
add_action( 'save_post_post_type_slider', 'pts_save_custom_field_posttypeslider' );
function pts_save_custom_field_posttypeslider( $post_id ) {
	
 if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
	return $post_id;
	$pts_slider = sanitize_text_field($_POST['pts_slider']); // sanitize data
	if (metadata_exists( 'post', $post_id, 'pts_slider' ) ) {
	update_post_meta(
	$post_id,
	'pts_slider',
	$pts_slider
	);
	}else {
	add_post_meta(
	$post_id,
	'pts_slider',
	$pts_slider
	);
	}
	// number of slider
	$pts_num_slides = sanitize_text_field($_POST['pts_num_slides']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'pts_num_slides' ) ) {
	update_post_meta(
	$post_id,
	'pts_num_slides',
	$pts_num_slides
	);
	}else {
	add_post_meta(
	$post_id,
	'pts_num_slides',
	$pts_num_slides
	);
	}
	// autoplay
	$autoplay = sanitize_text_field($_POST['autoplay']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'autoplay' ) ) {
	update_post_meta(
	$post_id,
	'autoplay',
	$autoplay
	);
	}else {
	add_post_meta(
	$post_id,
	'autoplay',
	$autoplay
	);
	}
	// margin
	$margin = sanitize_text_field($_POST['margin']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'margin' ) ) {
	update_post_meta(
	$post_id,
	'margin',
	$margin
	);
	}else {
	add_post_meta(
	$post_id,
	'margin',
	$margin
	);
	}
	// loop
	$loop = sanitize_text_field($_POST['loop']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'loop' ) ) {
	update_post_meta(
	$post_id,
	'loop',
	$loop
	);
	}else {
	add_post_meta(
	$post_id,
	'loop',
	$loop
	);
	}
	// slideWidth
	$slideWidth = sanitize_text_field($_POST['slideWidth']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'slideWidth' ) ) {
	update_post_meta(
	$post_id,
	'slideWidth',
	$slideWidth
	);
	}else {
	add_post_meta(
	$post_id,
	'slideWidth',
	$slideWidth
	);
	}
	// slide_move_by
	$slide_move_by = sanitize_text_field($_POST['slide_move_by']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'slide_move_by' ) ) {
	update_post_meta(
	$post_id,
	'slide_move_by',
	$slide_move_by
	);
	}else {
	add_post_meta(
	$post_id,
	'slide_move_by',
	$slide_move_by
	);
	}
	// slide_moving_speed
	$slide_moving_speed = sanitize_text_field($_POST['slide_moving_speed']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'slide_moving_speed' ) ) {
	update_post_meta(
	$post_id,
	'slide_moving_speed',
	$slide_moving_speed
	);
	}else {
	add_post_meta(
	$post_id,
	'slide_moving_speed',
	$slide_moving_speed
	);
	}
	// pauseonhover
	$pauseonhover = sanitize_text_field($_POST['pauseonhover']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'pauseonhover' ) ) {
	update_post_meta(
	$post_id,
	'pauseonhover',
	$pauseonhover
	);
	}else {
	add_post_meta(
	$post_id,
	'pauseonhover',
	$pauseonhover
	);
	}
	// navigation_arrows
	$navigation_arrows = sanitize_text_field($_POST['navigation_arrows']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'navigation_arrows' ) ) {
	update_post_meta(
	$post_id,
	'navigation_arrows',
	$navigation_arrows
	);
	}else {
	add_post_meta(
	$post_id,
	'navigation_arrows',
	$navigation_arrows
	);
	}
	// navigation_dots
	$navigation_dots = sanitize_text_field($_POST['navigation_dots']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'navigation_dots' ) ) {
	update_post_meta(
	$post_id,
	'navigation_dots',
	$navigation_dots
	);
	}else {
	add_post_meta(
	$post_id,
	'navigation_dots',
	$navigation_dots
	);
	}
	// content_type
	$content_type = sanitize_text_field($_POST['content_type']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'content_type' ) ) {
	update_post_meta(
	$post_id,
	'content_type',
	$content_type
	);
	}else {
	add_post_meta(
	$post_id,
	'content_type',
	$content_type
	);
	}
	// content_chara
	$content_chara = sanitize_text_field($_POST['content_chara']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'content_chara' ) ) {
	update_post_meta(
	$post_id,
	'content_chara',
	$content_chara
	);
	}else {
	add_post_meta(
	$post_id,
	'content_chara',
	$content_chara
	);
	}
	// bx_image
	$bx_image = sanitize_text_field($_POST['bx_image']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'bx_image' ) ) {
	update_post_meta(
	$post_id,
	'bx_image',
	$bx_image
	);
	}else {
	add_post_meta(
	$post_id,
	'bx_image',
	$bx_image
	);
	}
	
	// bx_title
	$bx_title = sanitize_text_field($_POST['bx_title']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'bx_title' ) ) {
	update_post_meta(
	$post_id,
	'bx_title',
	$bx_title
	);
	}else {
	add_post_meta(
	$post_id,
	'bx_title',
	$bx_title
	);
	}
	
	// bx_content
	$bx_content = sanitize_text_field($_POST['bx_content']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'bx_content' ) ) {
	update_post_meta(
	$post_id,
	'bx_content',
	$bx_content
	);
	}else {
	add_post_meta(
	$post_id,
	'bx_content',
	$bx_content
	);
	}
	
	// read more
	$bx_read_more = sanitize_text_field($_POST['bx_read_more']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'bx_read_more' ) ) {
	update_post_meta(
	$post_id,
	'bx_read_more',
	$bx_read_more
	);
	}else {
	add_post_meta(
	$post_id,
	'bx_read_more',
	$bx_read_more
	);
	}
	
	// read_more_text
	$read_more_text = sanitize_text_field($_POST['read_more_text']); // sanitize data
	if ( metadata_exists( 'post', $post_id, 'read_more_text' ) ) {
	update_post_meta(
	$post_id,
	'read_more_text',
	$read_more_text
	);
	}else {
	add_post_meta(
	$post_id,
	'read_more_text',
	$read_more_text
	);
	}

}
// enqueue scripts	
add_action('wp_enqueue_scripts', 'pts_enqueue_scripts');
function pts_enqueue_scripts() {
	wp_register_script( 'bxslider', PTS_JS_URL.'/jquery.bxslider.js', array( 'jquery' ), array(), false, true);
	wp_register_script( 'myscript', PTS_JS_URL.'/myscript.js', array( 'jquery' ) , array(), false, true);
	wp_register_style( 'bxstyle_css', PTS_CSS_URL.'/jquery.bxslider.css');
}
// shortcode to display slider 	
add_shortcode('pts_slideshow',"pts_showslides");
function pts_showslides($atts){
	global $post;
	wp_enqueue_script( 'bxslider' );
	wp_enqueue_style( 'bxstyle_css' );
	$postid = $atts['postid'];
	$meta_key = 'pts_slider';
	$meta_ket = get_post_meta($postid, $meta_key, true); 
	$meta_ketid = 'pts_slideshow'.strtolower(str_replace(" ", "",$meta_ket));
	
	if(get_post_meta($postid, 'pts_num_slides', true)){
		$pts_num_slides = get_post_meta($postid, 'pts_num_slides', true);
	}else $pts_num_slides =1;

	if(get_post_meta($postid, 'autoplay', true)){
		$autoplay = get_post_meta($postid, 'autoplay', true);
	}else $autoplay =true;

	if(get_post_meta($postid, 'margin', true)){
		$margin = get_post_meta($postid, 'margin', true);
	}else $margin = 0;

	if(get_post_meta($postid, 'loop', true)){
		$loop = get_post_meta($postid, 'loop', true);
	}else $loop = true;

	if(get_post_meta($postid, 'slide_move_by', true)){
		$slide_move_by = get_post_meta($postid, 'slide_move_by', true);
	}else $slide_move_by = 1;

	if(get_post_meta($postid, 'slide_moving_speed', true)){
		$slide_moving_speed = get_post_meta($postid, 'slide_moving_speed', true);
	}else $slide_moving_speed = 500;

	if(get_post_meta($postid, 'pauseonhover', true)){
		$pauseonhover = get_post_meta($postid, 'pauseonhover', true);
	}else $pauseonhover = true;

	if(get_post_meta($postid, 'navigation_arrows', true)){
		$navigation_arrows = get_post_meta($postid, 'navigation_arrows', true);
	}else $navigation_arrows = true;

	if(get_post_meta($postid, 'navigation_dots', true)){
		$navigation_dots = get_post_meta($postid, 'navigation_dots', true);
	}else $navigation_dots = true;

	if(get_post_meta($postid, 'slideWidth', true)){
		$slideWidth = get_post_meta($postid, 'slideWidth', true);
	}else $slideWidth = '';

	if(get_post_meta($postid, 'content_type', true)){
		$content_type = get_post_meta($postid, 'content_type', true);
	}else $slideWidth = "content";

	if(get_post_meta($postid, 'content_chara', true)){
		$content_chara = get_post_meta($postid, 'content_chara', true);
	}else $content_chara = 50;


	if(get_post_meta($postid, 'read_more_text', true)){
		$read_more_text = get_post_meta($postid, 'read_more_text', true);
	}else $read_more_text = "Read More";


	  if ( !isset($GLOBALS['slideroptions']) ) {
    $GLOBALS['slideroptions'] = array();
  }
 $GLOBALS['slideroptions'][] = array (
    'sliderid' => $meta_ketid,
		'slide_num' => $pts_num_slides,
		'autoplay' => $autoplay,
		'margin' => $margin,
		'loop' => $loop,
		'slide_move_by' => $slide_move_by,
		'speed' => $slide_moving_speed,
		'pauseonhover'=>$pauseonhover,
		'navigation_arrows' => $navigation_arrows,
		'navigation_dots' => $navigation_dots,
		'slideWidth' => $slideWidth
  );

	wp_enqueue_script( 'myscript' );
	wp_localize_script('myscript','myvars',$GLOBALS['slideroptions']);

	$data = '<div id="'.$meta_ketid.'" class="slider '.$meta_ketid.'">';
	$my_query = new WP_Query( 'post_type='.$meta_ket );
	while ( $my_query->have_posts() ) : $my_query->the_post();
	$posturl = get_the_post_thumbnail_url();
	if($content_type == "content"){
		$content = get_the_content();
	}else  $content = get_the_excerpt();
	$trimmed_content = substr(strip_tags($content), 0, $content_chara);
	$title = get_the_title();
	$data .= '<div class="eachslider_bx">';
	if(get_post_meta($postid, 'bx_image', false)){
	$data .= '<img src="'.$posturl.'" alt="'.$title.'" class="bx_image" style="width:100%;">';
	} 
	if(get_post_meta($postid, 'bx_title', true)){
	$data .= '<h4 class="bx_title">'.$title.'</h4>';
	}
	if(get_post_meta($postid, 'bx_content', true)){
	$data .= '<p class="bx_content">'.$trimmed_content.'</p>';
	}
	if(get_post_meta($postid, 'bx_read_more', true)){
	$data .= '<a href="'.get_the_permalink().'"class="read_more_bx">'.$read_more_text.'</a>';
	}
	$data .= '</div>';
	endwhile; 
	wp_reset_postdata(); // reset the query 
	$data .= '</div>'; 
	return $data;
}	