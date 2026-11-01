$(document).ready(function(){

	// Navigation Slide Toggle
	$('#navbar-slide-toggler').click(function(){
		if (window.innerWidth > 767) {
			var menuSide = $('#menu-side');
			if (menuSide.hasClass('small-navbar')) {
				menuSide.children('.nav-menu').css('display', 'none');
				setTimeout(function(){
					menuSide.children('.nav-menu').fadeIn(250);
				}, 200);
				$('#menu-side').removeClass('small-navbar');
			} else {
				$('#menu-side').addClass('small-navbar');
			}
		} else {
			var menuSide = $('#menu-side');
			if (menuSide.hasClass('small-navbar')) {
				menuSide.removeClass('small-navbar');
				menuSide.children('.nav-menu').fadeOut(150, function(){
					setTimeout(function(){
						menuSide.children('.nav-menu').attr('style', '');
					}, 250);
				});
			} else {
				menuSide.addClass('small-navbar');
				menuSide.children('.nav-menu').css('display', 'none');
				setTimeout(function(){
					menuSide.children('.nav-menu').fadeIn(250, function(){
						menuSide.children('.nav-menu').attr('style', '');
					});
				}, 200);
			}
		}
	});

	// Navigation Menu Dropdown
	$('#nav-menus li.active').children('ul').addClass('show');
	$('#nav-menus > li > a').click(function(e){
		if ($(this).next('ul').length > 0) {
			e.preventDefault();
			if ($(this).parent('li').hasClass('active')) {
				$('#nav-menus > li').removeClass('active');
				$('#nav-menus > li > ul').slideUp(300, function(){
					$(this).removeClass('show');
					$(this).attr('style','');
				});
			} else {
				$('#nav-menus > li').removeClass('active');
				$('#nav-menus > li > ul').slideUp(300, function(){
					$(this).removeClass('show');
					$(this).attr('style', '');
				});
				$(this).parent('li').addClass('active');
				$(this).next('ul').slideDown(300, function(){
					$(this).addClass('show');
					$(this).attr('style','');
				});	
			}
		}
	});
	$('#nav-menus > li > ul a').click(function(e){
		if ($(this).next('ul').length > 0) {
			e.preventDefault();
			if ($(this).parent('li').hasClass('active')) {
				$(this).parent('li').removeClass('active');
				$(this).next('ul').slideUp(300, function(){
					$(this).removeClass('show');
					$(this).attr('style','');
				});
			} else {
				$(this).parent('li').addClass('active');
				$(this).next('ul').slideDown(300, function(){
					$(this).addClass('show');
					$(this).attr('style','');
				});
			}
		}
	});
});