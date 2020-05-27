/*
Bones Scripts File
Author: Emil Broll
*/

// Warn old browsers to update/switch
var $buoop = {}; 
$buoop.ol = window.onload; 
window.onload=function(){ 
	try {if ($buoop.ol) $buoop.ol();}catch (e) {} 
	var e = document.createElement("script"); 
	e.setAttribute("type", "text/javascript"); 
	e.setAttribute("src", "//browser-update.org/update.js"); 
	document.body.appendChild(e); 
}
(function($){

if ($('.singlepaged').length !== 0){
	// Set up iframes
	$('iframe').each(function(){
		//$(this).attr('store', $(this).attr('src'));
		//$(this).attr('src', '');
	});
}

}(jQuery));


// as the page loads, call these scripts
jQuery(document).ready(function($) {
	/* getting viewport width */
	var responsive_viewport = $(window).width();

	/*
	*
	* Front-page specific scripts
	*
	*/
	if ($('.grid').length > 0){
		var msnry = 0;
		
		// Lazy-load images
		$('img.lazy').unveil(500, function() {
			if (msnry === 0) return;
			setTimeout(function(){
				msnry.layout();
			}, 500);
		});
		
		// IE<=8 fix for lazy images and grid loading (re-layout every 3 secs)
		var ieVersion = /*@cc_on (function() {switch(@_jscript_version) {case 1.0: return 3; case 3.0: return 4; case 5.0: return 5; case 5.1: return 5; case 5.5: return 5.5; case 5.6: return 6; case 5.7: return 7; case 5.8: return 8; case 9: return 9; case 10: return 10;}})() || @*/ 0;
		if (ieVersion !== 0 && ieVersion <= 8){
			setInterval(function (){
				if (msnry === 0) return;
				msnry.layout();
			}, 3000);
		}
		
		// If desktop 
		if (responsive_viewport >= 768) {
			// When images are ready, layout grid (masonry is redundant on mobile)
			imagesLoaded(container, function() {
				jQuery('.grid').removeClass('nomasonry');
				var $grid = $('#main');
				$grid.height( $grid.height() ); // fix the height at its current height
				var container = document.querySelector('#main');
				msnry = new Masonry( container, {
					itemSelector: ".grid",
					gutter: "#gutter"
				});
			});
			
			// Set up hover events
			var hover = function(id, hovering) {
				var series = $(id).attr('series');
				if (hovering){
					$(id).find('.entry-content').fadeIn(100);
					$('article[series="'+series+'"] #article-image').fadeTo(100, 0.5);
				} else {
					$(id).find('.entry-content').fadeOut(100);
					$('article[series="'+series+'"] #article-image').fadeTo(100, 1);
				}
			};
			$('.grid').hover(function(id) {
				hover($(this), true);
			}, function(id) {
				hover($(this), false);
			});
		}
		
		// Handles clicks on infobutton, show/hide infopanel
		var infotoggle = function(hide) {
			if (hide === true && infotoggle.on !== true) return;
			if (infotoggle.on === true || hide === true){
				$('#inner-info').slideUp();
				if (Modernizr.rgba){
					$('.header').css('background', 'rgba(255,255,255,0.5)');
				} else {
					$('.header').css('background', 'url("../images/black06.png") repeat');
				}
				$('.header').css('position', 'fixed');
				$('#infobutton .genericon').hide();
				$('#infobutton #infospan').show();
				if ($('.singlepaged').length !== 0) $.fn.fullpage.setAutoScrolling(true);
				infotoggle.on = false;
			} else {
				$('.header').css('background', '#f9cdb7');
				$('.header').css('position', 'absolute');
				$('#infobutton .genericon').show();
				$('#infobutton #infospan').hide();
				if ($('.singlepaged').length !== 0){
					$.fn.fullpage.moveTo(1);
				if ($('.singlepaged').length !== 0) $.fn.fullpage.setAutoScrolling(false);
				} else {
					$('html,body').animate({scrollTop:0}, 300);
				}
				$('#inner-info').slideDown();
				infotoggle.on = true;
			}
		};
		$('#infobutton').click(infotoggle);
		
		// Catch clicks outside of infopanel when active
		$(document).mouseup(function (e) {
			if (infotoggle.on !== true) return;
		    var container = $(".header");
		    if (!container.is(e.target) && container.has(e.target).length === 0){
		    	infotoggle(true);
		    }
		});

	}
	
	/*
	*
	* Single-series specific scripts
	*
	*/
	// Called when new series is presented
	var onLoad = function(article) {
		// Global variables
		var active = false;
		var hovering = false;

		// Fade out/in functions
		var fadeIn = function (){
		    article.find('figure, .video-overlay').addClass('overlay');
		    article.find('.entry-content').fadeIn();
			article.find('.fp-controlArrow').fadeTo(400, 1);
		};
		var fadeOut = function(){
		    if (!active){
			    article.find('.fp-controlArrow').fadeTo(400, 0);
			}
			if (!hovering){
			    article.find('.entry-content').fadeOut();
			    article.find('figure, .video-overlay').delay(200).removeClass('overlay');
			}
		};
		fadeIn();
		// Fade back after 2000 ms
	    setTimeout(function() {
	    	fadeOut();
	    }, 2000);
	    
	    // Only load hover stuff when desktop
	    if (responsive_viewport >= 768) {
	    	// On image hover, show info overlay
		    article.find('figure, .video-overlay').eq(0).hover(function(){
			    hovering = true;
			    $(this).data('hovering', true);
			    if ($(this).hasClass('overlay')) return;
			    fadeIn();
		    });
			
			// If close to arrows, show them (hide them if not)
			var nearId = 0;
			var notNear = function() {
				$('.fp-controlArrow').fadeTo(400, 0);
				clearTimeout(nearId);
				nearId = 0;
				active = false;
			};
			
			$(article).mousemove(function(event) {
				if (isNear(article.find('.fp-controlArrow.fp-prev'), $(window).width()/5, event) ||
					isNear(article.find('.fp-controlArrow.fp-next'), $(window).width()/5, event)){
					if (nearId === 0 && !active){
						$('.fp-controlArrow').fadeTo(400, 1);
						active = true;
					} else {
						clearTimeout(nearId);
						nearId = 0;
					}
				} else {
					if (active && nearId === 0){
						//clearTimeout(nearId);
						nearId = setTimeout(notNear, 500);
					}
				}
				// If hovering and mouse isn't over, hide overlay
				article.find('figure, .video-overlay').eq(0).each(function(index){
					if (!isNear($(this), 1, event)){
						if ($(this).data('hovering') === true){
							var glob = $(this);
   							glob.data('hovering', false);
   							hovering = false;
						    setTimeout(function() {
						    	fadeOut();
						    }, 800);
						}
					}
				});
			});
		}
	};
	
	if ($('.singlepaged').length !== 0){
		// Set up url-changes on switch (does not work in IE<=8)
	    Backbone.$ = $;
	    var path = window.location.pathname.split('/');
	    path.splice(-2,2);
	    Backbone.history.start({pushState: Modernizr.history, root: (path.length > 1)?path.join('/'):'/'});
	    if (!Modernizr.history){
	        var rootLength = Backbone.history.options.root.length;
		    var fragment = window.location.pathname.substr(rootLength);
		    Backbone.history.navigate(fragment, { trigger: true });
	    }
	    
	    // Set up display script
	    $('#main').fullpage({
		    resize: false,
		    css3: true,
		    fixedElements: ".header",
		    autoScrolling: true,
		    paddingTop: $('.header').height() +'px',
		    paddingBottom: $('.header').height() +'px',
		    afterLoad: function(anchor, index){
		    	// Tell lazyload to load images
		   		//$(window).trigger( "scroll" );
		   		
			    // Update url and head title
			    var article = $('article:eq('+ (index-1) +')');
				
				article.find('iframe').each(function(){
			   		$(this).attr('src', $(this).attr('data'));
		   		});

				/*$('article').find('iframe').each(function(){
					if (article.has($(this)).length <= 0){
			   			$(this).attr('src', '');
			   		}
		   		});*/
		   		
/*
				article.find('iframe').each(function(){
					if $('article:eq('+ (fromIndex-1) +')').find('iframe').each(function(){
		   			$(this).attr('src', '');
		   		});
			   		console.log('Found something');
			   		$(this).attr('src', $(this).attr('data'));
		   		});
*/


		    	var slug = article.attr('slug');
			 	Backbone.history.navigate(slug, {trigger: true});
				document.title = 'Thomas Brun • '+ article.attr('title');
			    // Do setup of slide
			    onLoad(article);
		    }, 
		    onLeave: function(fromIndex, direction) {
		    	// Tell lazyload to load images
		   		//$(window).trigger( "scroll" );
		   		var article = $('article:eq('+ (fromIndex-1) +')');
				
				article.find('iframe').each(function(){
					$(this).delay(1000).queue(function() {
						$(this).attr('src', '');
						$(this).dequeue();
					});
		   		});

		    },
		    afterRender: function() {
		    	// Initial article setup
		    	var article = $('article').eq(0);
		    	onLoad(article);
		    	// Start loading images (after fullpage is initialised)
		    	//$('img.lazy').unveil($(window).height()*2);

				/*article.find('iframe').each(function(){
			   		console.log('Found something');
			   		$(this).attr('src', $(this).attr('data'));
		   		});*/


		    }
	    });
		// Switch slide when image is clicked
	    $('figure').click(function() {
		    console.log()
		    $('.section.active').find('.fp-controlArrow.next:visible').trigger('click');
	    });
	    
	}
 
}); /* end of as page load scripts */


/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w){
	// This fix addresses an iOS bug, so return early if the UA claims it's something else.
	if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ){ return; }
	var doc = w.document;
	if( !doc.querySelector ){ return; }
	var meta = doc.querySelector( "meta[name=viewport]" ),
		initialContent = meta && meta.getAttribute( "content" ),
		disabledZoom = initialContent + ",maximum-scale=1",
		enabledZoom = initialContent + ",maximum-scale=10",
		enabled = true,
		x, y, z, aig;
	if( !meta ){ return; }
	function restoreZoom(){
		meta.setAttribute( "content", enabledZoom );
		enabled = true; }
	function disableZoom(){
		meta.setAttribute( "content", disabledZoom );
		enabled = false; }
	function checkTilt( e ){
		aig = e.accelerationIncludingGravity;
		x = Math.abs( aig.x );
		y = Math.abs( aig.y );
		z = Math.abs( aig.z );
		// If portrait orientation and in one of the danger zones
		if( !w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ){
			if( enabled ){ disableZoom(); } }
		else if( !enabled ){ restoreZoom(); } }
	w.addEventListener( "orientationchange", restoreZoom, false );
	w.addEventListener( "devicemotion", checkTilt, false );
})( this );
// IE8 ployfill for GetComputed Style (for Responsive Script below)
if (!window.getComputedStyle) {
	window.getComputedStyle = function(el, pseudo) {
		this.el = el;
		this.getPropertyValue = function(prop) {
			var re = /(\-([a-z]){1})/g;
			if (prop == 'float') prop = 'styleFloat';
			if (re.test(prop)) {
				prop = prop.replace(re, function () {
					return arguments[2].toUpperCase();
				});
			}
			return el.currentStyle[prop] ? el.currentStyle[prop] : null;
		}
		return this;
	}
}
// Check if event is near element a the distance
function isNear( element, distance, event ) {
    var left = element.offset().left - distance,
        top = element.offset().top - distance,
        right = left + element.width() + 2*distance,
        bottom = top + element.height() + 2*distance,
        x = event.pageX,
        y = event.pageY;
    return ( x > left && x < right && y > top && y < bottom );
};
