myvars.forEach(function(slideroptions) {
var some_variable_value = slideroptions.sliderid;
var slide_num = slideroptions.slide_num;
var autoplay = slideroptions.autoplay;
var margin = slideroptions.margin;
var loop = slideroptions.loop;
var slide_move_by = slideroptions.slide_move_by;
var slide_moving_speed = slideroptions.speed;
var pauseonhover = slideroptions.pauseonhover;
var navigation_arrows = slideroptions.navigation_arrows;
var navigation_dots = slideroptions.navigation_dots;
var slideWidth = slideroptions.slideWidth;
if (jQuery(window).width() > 460) {
var slideWidth = slideroptions.slideWidth;
if(!slideWidth){
var divWidth = jQuery("#"+some_variable_value).width(); 
	slideWidth = parseInt( parseInt(divWidth)/parseInt(slide_num));
}
}else{
	slideWidth = 0;

}
jQuery("#"+some_variable_value).bxSlider({
    auto: JSON.parse(autoplay),
    speed: parseInt(slide_moving_speed),
    minSlides: 1,
    maxSlides: parseInt(slide_num),
    //moveSlides:parseInt(slide_move_by),
    slideWidth: parseInt(slideWidth),
	slideMargin: parseInt(margin),
	infiniteLoop:JSON.parse(loop),
	autoHover:JSON.parse(pauseonhover),
	controls:JSON.parse(navigation_arrows),
	pager:JSON.parse(navigation_dots),
	
	
});
});