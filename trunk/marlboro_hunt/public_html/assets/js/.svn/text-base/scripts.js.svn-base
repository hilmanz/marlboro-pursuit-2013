
$(function() {   
	var theWindow        = $(window),
		$bg              = $("#bg"),
		aspectRatio      = $bg.width() / $bg.height();
								
	function resizeBg() {
		
		if ( (theWindow.width() / theWindow.height()) < aspectRatio ) {
			$bg
				.removeClass()
				.addClass('bgheight');
		} else {
			$bg
				.removeClass()
				.addClass('bgwidth');
		}
					
	}    			
	theWindow.resize(function() {
		resizeBg();
	}).trigger("resize");

});
// DETECT BROWSER
var BrowserDetect = {
init: function () {
	this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
	this.version = this.searchVersion(navigator.userAgent)
		|| this.searchVersion(navigator.appVersion)
		|| "an unknown version";
	this.OS = this.searchString(this.dataOS) || "an unknown OS";
},
searchString: function (data) {
	for (var i=0;i<data.length;i++)    {
		var dataString = data[i].string;
		var dataProp = data[i].prop;
		this.versionSearchString = data[i].versionSearch ||
		data[i].identity;
					if (dataString) {
						if (dataString.indexOf(data[i].subString) != -1)
							return data[i].identity;
					}
					else if (dataProp)
						return data[i].identity;
				}
			},
			searchVersion: function (dataString) {
				var index = dataString.indexOf(this.versionSearchString);
				if (index == -1) return;
				return
		parseFloat(dataString.substring(index+this.versionSearchString.length+1));
			},
			dataBrowser: [
				{
					string: navigator.userAgent,
					subString: "Chrome",
					identity: "Chrome"
				},
				{
					string: navigator.userAgent,
					subString: "OmniWeb",
					versionSearch: "OmniWeb/",
					identity: "OmniWeb"
				},
				{
					string: navigator.vendor,
					subString: "Apple",
					identity: "Safari",
					versionSearch: "Version"
				},
				{
					prop: window.opera,
					identity: "Opera"
				},
				{
					string: navigator.vendor,
					subString: "iCab",
					identity: "iCab"
				},
				{
					string: navigator.vendor,
					subString: "KDE",
					identity: "Konqueror"
				},
				{
					string: navigator.userAgent,
					subString: "Firefox",
					identity: "Firefox"
				},
				{
					string: navigator.vendor,
					subString: "Camino",
					identity: "Camino"
				},
				{        // for newer Netscapes (6+)
					string: navigator.userAgent,
					subString: "Netscape",
					identity: "Netscape"
				},
				{
					string: navigator.userAgent,
					subString: "MSIE",
					identity: "Explorer",
					versionSearch: "MSIE"
				},
				{
					string: navigator.userAgent,
					subString: "Gecko",
					identity: "Mozilla",
					versionSearch: "rv"
				},
				{         // for older Netscapes (4-)
					string: navigator.userAgent,
					subString: "Mozilla",
					identity: "Netscape",
					versionSearch: "Mozilla"
				}
			],
			dataOS : [
				{
					string: navigator.platform,
					subString: "Win",
					identity: "Windows"
				},
				{
					string: navigator.platform,
					subString: "Mac",
					identity: "Mac"
				},
				{
					string: navigator.platform,
					subString: "Linux",
					identity: "Linux"
				}
			]
		 
		};
		BrowserDetect.init();
$(function() {
	$(".theForms,.theForm").validate()
});
	
$(document).ready(function() { 
	jQuery("a.showPopups").click(function(){
		var targetID = jQuery(this).attr('href');
		jQuery("#fancybox-wrap,.imhide,.popup2").fadeOut();
		jQuery(targetID).fadeIn();
		jQuery(targetID).addClass('visible');
		jQuery("#fancybox-overlay").fadeIn();
		jQuery(".bgpopup2").fadeOut();
	});
	jQuery("a.showPopup").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
	});
	jQuery("a.icon_message").click(function(){
			$("#fancybox-outer").addClass('min10');
	});
	jQuery("a.closePopup,#fancybox-overlay").click(function(){
		jQuery(".popup").fadeOut();
		jQuery("#fancybox-overlay").fadeOut();
		jQuery('#landingPage').fadeIn();
	});
	jQuery("a.showPopup2").click(function(){
		var targetID = jQuery(this).attr('href');
		jQuery(targetID).fadeIn();
		jQuery('#landingPage').fadeOut();
		jQuery(targetID).addClass('visible');
	});
	jQuery("a#closewelcome").click(function(){
		jQuery(".bgpopup,.centercontent,.fakeheader").fadeOut();
	});
  /*------------ADD CLASS DETECT BROWSER------------*/ 
	$("body").addClass(BrowserDetect.browser); 
	//flip
	$('#sticker').fold();
	// Drop Down Menu
	$('ul#main-menu,ul#topNav').superfish({ 
        delay:       600,
        animation:   {opacity:'show',height:'show'},
        speed:       'fast',
        autoArrows:  true,
        dropShadows: false
    });

	// Accordion
	$( ".accordion" ).accordion( { autoHeight: false } );
	$( ".datepicker" ).datepicker({
		changeMonth: true,
        changeYear: true,
		 yearRange: "1933:2023"
	});

	// Toggle
	$( ".toggle > .inner" ).hide();
	$(".toggle .title").toggle(function(){
		$(this).addClass("active").closest('.toggle').find('.inner').slideDown(200, 'easeOutCirc');
	}, function () {
		$(this).removeClass("active").closest('.toggle').find('.inner').slideUp(200, 'easeOutCirc');
	});
	jQuery("body").mouseover(function(){
			jQuery(this).addClass("controlActive");	
	});
	jQuery("body").mouseout(function(){
			jQuery(this).removeClass("controlActive");	
	});
	
	$("a.getfeed").click(function(){
		$("#newsfeed").addClass("newsfeedactive")
		$('.fakeheader').fadeIn();
	});
	
	$("a.closefeed,#closewelcome").click(function(){
		$("#newsfeed").removeClass("newsfeedactive")
		$('.fakeheader').fadeOut();
	});

	// Tabs
	$(function() {
		$( "#tabs" ).tabs();
	});
	
	// Gallery Hover Effect
	$(".showPopup").fancybox({
		'titlePosition'		: 'inside',
		'transitionIn'		: 'none',
		'transitionOut'		: 'none'
	});
	// DYO SHIRT
	$('.perspective').perspective({
		scrollingSpeed : 8000,
		slidingSpeed : 400,
		depth :60,
		flightPoint : 'top',
		hoverGap : 5,
		maxDarkening : 1,
		timerPers : 'hide',
		playPers : 'hide'
	});	
	$('.newsfeedlist').jScrollPane(
		{
			showArrows: true,
			arrowScrollOnHover: true
		}
	);	
});

$(window).load(function() {
  $('#newslist,#gamelist').flexslider({
    animation: "slide",
    animationLoop: false,
	itemWidth: 200,
	itemMargin: 15,
	minItems: 1,
	maxItems: 1
  });
  
  $('#historyItemDate').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 210,
    itemMargin: 5,
    asNavFor: '#historyItem'
  });
   
  $('#historyItem').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#historyItemDate"
  });
});

//scrollpanescript
$(document).ready(function() {	
	$(".readMechanicFwd").click(function(event) {
		event.preventDefault();
		$('.scroll-paneMoveFwd').jScrollPane(
			{
				showArrows: true,
				arrowScrollOnHover: true
			}
		);
		$("#fancybox-close").addClass("fancybox-close-tnc");
		$(".fancybox-close-tnc").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-tnc");	
		});	
	});
	
	$(".popuptnc,.popupprivacy").click(function(event) {
		event.preventDefault();
		$('.scroll-pane').jScrollPane(
			{
				showArrows: true,
				arrowScrollOnHover: true
			}
		);
		$("#fancybox-close").addClass("fancybox-close-tnc");
		$(".fancybox-close-tnc").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-tnc");	
		});	
	});
	$(".popupNews").click(function(event) {
		event.preventDefault();
		$('.scroll-pane').jScrollPane(
			{
				showArrows: true,
				arrowScrollOnHover: true
			}
		);
		$("#fancybox-close").addClass("fancybox-close-news");
		$(".fancybox-close-news").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-news");	
		});	
	});
	
	$(".getEvent").click(function(event) {
		event.preventDefault();
		$('.scroll-pane').jScrollPane(
			{
				showArrows: true,
				arrowScrollOnHover: true,
				autoReinitialise: true
			}
		);
		$('#carousel').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: true,
			directionNav: true,
			smoothHeight: true,
			slideshow: false,
			itemWidth: 210,
			minItems: 3,
			maxItems: 3,
			asNavFor: '#slider'
		});
   		 $('#mycarousel').jcarousel();
		
		$('#slider').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			sync: "#carousel"
		});
		$("#fancybox-close").addClass("fancybox-close-event");	
		$(".fancybox-close-event").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-event");	
		});
	});
	
	$(".getInbox").click(function(event) {
		event.preventDefault();
		$("#fancybox-close").addClass("fancybox-close-inbox");	
		$(".fancybox-close-inbox").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-inbox");	
		});
	});
	
	$(".getGIID").click(function(event) {
		event.preventDefault();
		$("#fancybox-close").addClass("fancybox-close-validGiid");	
		$(".fancybox-close-validGiid").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-validGiid");	
		});
	});
	$(".popupcontact").click(function(event) {
		event.preventDefault();
		$("#fancybox-close").addClass("fancybox-close-contus");	
		$(".fancybox-close-contus").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-contus");	
		});
	});
	$(".commentSubmitPost").click(function(event) {
		event.preventDefault();
		$("#fancybox-close").addClass("fancybox-close-postsucces");	
		$(".fancybox-close-postsucces").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-postsucces");	
		});
	});
	/*
	$(".getInbox").click(function(event) {
		event.preventDefault();
		$("#fancybox-close").addClass("fancybox-close-birthday");	
		$(".fancybox-close-birthday").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-birthday");	
		});
	});
	*/
});

jQuery(document).ready(function($) { 
    var shadowify = function (e) {
        var color = jQuery(e).css('color'),
            size  = jQuery(e).css('font-size');

        // Got Hex color?  Modify with: http://stackoverflow.com/questions/1740700/get-hex-value-rather-than-rgb-value-using-jquery
        if ( color.search('rgb') == -1 )
            return;

        var rgba = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
        jQuery(e).css('text-shadow', '0 0 1px rgba('+rgba[1]+','+rgba[2]+','+rgba[3]+',1)');

    // To use calculated shadow of say, 1/15th of the font height 
    //var fsize = size.match(/(\d+)px/);
    //jQuery(e).css('text-shadow', '0 0 '+(fsize[1]/15)+'px rgba('+rgba[1]+','+rgba[2]+','+rgba[3]+',0.3)')
    }

    if(navigator.platform.indexOf('Win') != -1)
        $('.menu a,#header_right a,.event_title a, h1 a, h2 a').each(function(){shadowify(this)});
        //^ Your appropriately targeted list of elements here ^
});

$(function(){ 
				$('img.changeletter').aToolTip();
					
				
				
			}); 
// Can also be used with $(document).ready()



function reinitpopupevent(){
 
		$('.scroll-pane').jScrollPane(
			{
				showArrows: true,
				arrowScrollOnHover: true,
				autoReinitialise: true
			}
		);
		$('#carousel').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: true,
			directionNav: true,
			smoothHeight: true,
			slideshow: false,
			itemWidth: 210,
			minItems: 3,
			maxItems: 3,
			asNavFor: '#slider'
		});
   		 $('#mycarousel').jcarousel();
		
		$('#slider').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			sync: "#carousel"
		});
		$("#fancybox-close").addClass("fancybox-close-event");	
		$(".fancybox-close-event").click(function(event) {
			event.preventDefault();
			$(this).removeClass("fancybox-close-event");	
		});
}

