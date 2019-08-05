var containerHeight = function () {
	var availableHeight = $(window).height() - $('body > .navbar').outerHeight() - $('body > .navbar-fixed-top:not(.navbar)').outerHeight() - $('body > .navbar-fixed-bottom:not(.navbar)').outerHeight() - $('body > .navbar + .navbar').outerHeight() - $('body > .navbar + .navbar-collapse').outerHeight() - $('.page-header').outerHeight();
	$('.content-wrapper').attr('style', 'min-height:' + availableHeight + 'px');
	
	
	// Fixing header right module on mobile landscape
	if($(window).width() < 991) {
		var moduleRightHight = $(window).height()-(55+$('.module-group.module-center').outerHeight());
		$('.nav-bar .module-group.right').css({
			'max-height': moduleRightHight,
			'overflow-y': 'auto'
		})
	} else {
		$('.nav-bar .module-group.right').css({
			'max-height': '',
			'overflow-y': ''
		})
	}
	//end
        /*this class (.selfContainer) is use to auto height the page container section or division it over rides the height property of .page-container*/
				//$('.page-container.selfContainer').attr('style', 'min-height:auto');
}
window.addEventListener('resize', containerHeight);
$(function() {
var containerHeight = function () {
	var availableHeight = $(window).height() - $('body > .navbar').outerHeight() - $('body > .navbar-fixed-top:not(.navbar)').outerHeight() - $('body > .navbar-fixed-bottom:not(.navbar)').outerHeight() - $('body > .navbar + .navbar').outerHeight() - $('body > .navbar + .navbar-collapse').outerHeight() - $('.page-header').outerHeight();
	$('.content-wrapper').attr('style', 'min-height:' + availableHeight + 'px');
	
	
	// Fixing header right module on mobile landscape
	if($(window).width() < 991) {
		var moduleRightHight = $(window).height()-(55+$('.module-group.module-center').outerHeight());
		$('.nav-bar .module-group.right').css({
			'max-height': moduleRightHight,
			'overflow-y': 'auto'
		})
	} else {
		$('.nav-bar .module-group.right').css({
			'max-height': '',
			'overflow-y': ''
		})
	}
	//end
        /*this class (.selfContainer) is use to auto height the page container section or division it over rides the height property of .page-container*/
				//$('.page-container.selfContainer').attr('style', 'min-height:auto');
}


    // ========================================
    //
    // Layout
    //
    // ========================================


    // Calculate page container height
    // -------------------------

    // Window height - navbars heights
    




    // ========================================
    //
    // Heading elements
    //
    // ========================================


    // Heading elements toggler
    // -------------------------

    // Add control button toggler to page and panel headers if have heading elements
    $('.panel-heading, .page-header-content, .panel-body').has('> .heading-elements').append('<a class="heading-elements-toggle"><i class="icon-menu"></i></a>');


    // Toggle visible state of heading elements
    $('.heading-elements-toggle').on('click', function() {
        $(this).parent().children('.heading-elements').toggleClass('visible');
    });



    // Breadcrumb elements toggler
    // -------------------------

    // Add control button toggler to breadcrumbs if has elements
    $('.breadcrumb-line').has('.breadcrumb-elements').append('<a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>');


    // Toggle visible state of breadcrumb elements
    $('.breadcrumb-elements-toggle').on('click', function() {
        $(this).parent().children('.breadcrumb-elements').toggleClass('visible');
    });




    // ========================================
    //
    // Navbar
    //
    // ========================================


    // Navbar navigation
    // -------------------------

    // Prevent dropdown from closing on click
    $(document).on('click', '.dropdown-content', function (e) {
        e.stopPropagation();
    });

    // Disabled links
    $('.navbar-nav .disabled a').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Show tabs inside dropdowns
    $('.dropdown-content a[data-toggle="tab"]').on('click', function (e) {
        $(this).tab('show');
    });




    // ========================================
    //
    // Element controls
    //
    // ========================================


    // Reload elements
    // -------------------------

    // Panels
    $('.panel [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function () {
           $(block).unblock();
        }, 2000); 
    });



    // Collapse elements
    // -------------------------

    //
    // Sidebar categories
    //

  
    //
    // Panels
    //

    // Hide if collapsed by default
    $('.panel-collapsed').children('.panel-heading').nextAll().hide();


    // Rotate icon if collapsed by default
    $('.panel-collapsed').find('[data-action=collapse]').children('i').addClass('');

    // Collapse on click
    $(document).on("click",'.panel [data-action=collapse]',function (e) {
        e.preventDefault();
        var $panelCollapse = $(this).parent().parent().parent().parent().nextAll();
        $(this).parents('.panel').toggleClass('panel-collapsed');
        $(this).toggleClass('');
        containerHeight(); // recalculate page height
        $panelCollapse.slideToggle(150);
    });



    // Remove elements
    // -------------------------

    // Panels
    $('.panel [data-action=close]').click(function (e) {
        e.preventDefault();
        var $panelClose = $(this).parent().parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $panelClose.slideUp(150, function() {
            $(this).remove();
        });
    });


    // Sidebar categories
    $('.category-title [data-action=close]').click(function (e) {
        e.preventDefault();
        var $categoryClose = $(this).parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $categoryClose.slideUp(150, function() {
            $(this).remove();
        });
    });




    // ========================================
    //
    // Main navigation
    //
    // ========================================


    // Main navigation
    // -------------------------

    // Add 'active' class to parent list item in all levels
    $('.navigation').find('li.active').parents('li').addClass('active');

    // Hide all nested lists
    $('.navigation').find('li').not('.active, .category-title').has('ul').children('ul').addClass('hidden-ul');

    // Highlight children links
    $('.navigation').find('li').has('ul').children('a').addClass('has-ul');

    // Add active state to all dropdown parent levels
    $('.dropdown-menu:not(.dropdown-content), .dropdown-menu:not(.dropdown-content) .dropdown-submenu').has('li.active').addClass('active').parents('.navbar-nav .dropdown:not(.language-switch), .navbar-nav .dropup:not(.language-switch)').addClass('active');

    

    // Main navigation tooltips positioning
    // -------------------------

    // Left sidebar
    $('.navigation-main > .navigation-header > i').tooltip({
        placement: 'right',
        container: 'body'
    });



    // Collapsible functionality
    // -------------------------

    // Main navigation
    $('.navigation-main').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).toggleClass('active').children('ul').slideToggle(250);

        // Accordion
        if ($('.navigation-main').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(250);
        }
    });

        
    // Alternate navigation
    $('.navigation-alt').find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').toggleClass('active').children('ul').slideToggle(200);

        // Accordion
        if ($('.navigation-alt').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(200);
        }
    }); 




    // ========================================
    //
    // Sidebars
    //
    // ========================================


    // Mini sidebar
    // -------------------------

    // Toggle mini sidebar
    $('.sidebar-main-toggle').on('click', function (e) {
        e.preventDefault();

        // Toggle min sidebar class
        $('body').toggleClass('sidebar-xs');
    });



    // Sidebar controls
    // -------------------------

    // Disable click in disabled navigation items
    $(document).on('click', '.navigation .disabled a', function (e) {
        e.preventDefault();
    });


    // Adjust page height on sidebar control button click
    $(document).on('click', '.sidebar-control', function (e) {
        containerHeight();
    });


    // Hide main sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-main-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-main-hidden');
    });


    // Toggle second sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-secondary-hide', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-secondary-hidden');
    });


    // Hide all sidebars
    $(document).on('click', '.sidebar-all-hide', function (e) {
        e.preventDefault();

        $('body').toggleClass('sidebar-all-hidden');
    });






    // Mobile sidebar controls
    // -------------------------

    // Toggle main sidebar
    $('.sidebar-mobile-main-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite');
    });


    // Toggle secondary sidebar
    $('.sidebar-mobile-secondary-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-secondary').removeClass('sidebar-mobile-main sidebar-mobile-opposite');
    });


    // Toggle opposite sidebar
    $('.sidebar-mobile-opposite-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-opposite').removeClass('sidebar-mobile-main sidebar-mobile-secondary');
    });



    // Mobile sidebar setup
    // -------------------------

    $(window).on('resize', function() {
        setTimeout(function() {
            // containerHeight();

            // Course module page
            if($('.media-section').length) $('.media-section').css('max-height', $(window).height()-(60+62));
            
            if($(window).width() <= 768) {

                // Add mini sidebar indicator
                $('body').addClass('sidebar-xs-indicator');

                // Place right sidebar before content
                $('.sidebar-opposite').prependTo('.page-content');
            }
            else {               

                // Remove mini sidebar indicator
                $('body').removeClass('sidebar-xs-indicator');

                // Revert back right sidebar
                $('.sidebar-opposite').insertAfter('.content-wrapper');

                // Remove all mobile sidebar classes
                $('body').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-opposite');
                
            }

            if($(window).width() >= 990) {
                $('.nav-bar.nav-open').removeClass('nav-open');
            }
        }, 100);
    }).resize();




    // ========================================
    //
    // Other code
    //
    // ========================================


    // Plugins
    // -------------------------

    // Popover
    $('[data-popup="popover"]').popover();


    // Tooltip
    $('[data-popup="tooltip"]').tooltip();

});




/*
	****** Panel Scroll 
*/
$(function() {
	var $panelScroll = $('.panel-scroll');
	if($panelScroll.length) {
		panelScroll();
		$(window).resize(function(){
				setTimeout(function(){
					panelScroll();
				}, 100)
		})
	}
	function panelScroll(){
		$panelScroll.find('.panel-body').css('max-height', $(window).height()-$panelScroll.offset().top-$panelScroll.find('.panel-heading').height()-$panelScroll.find('.panel-footer').height());
		$panelScroll.hover(function(){
			$('body').addClass('scroll-disabled');
		}, function(){
			$('body').removeClass('scroll-disabled');
		})
	}
});


/**
 * @desc: Search Drawer functions. There are three function to get loding design, result and hide drawer.
 * @dev: Sagar Saini
 * @author: ITC Labs.
 */

function loadSearchDrawer(target) {
    openDrawer(target);   
    let html = '<div class="loader"><img src="'+basepath+'/images/search-drawer-loader.gif" alt="loader" /></div>';
    $('.search-drawer-wrapper').html(html);
}
function showSearchDrawer(target, html) {
    openDrawer(target);
    $('.search-drawer-wrapper').html(html);
}
function hideSearchDrawer() {
    $('.search-drawer-wrapper').remove();
}
function openDrawer(target) {
    hideSearchDrawer();
    let x = target.offset().left;
    let y = (target.outerHeight() + target.offset().top) - $(window).scrollTop();
    let width = target.width();
    let html = '<div class="search-drawer-wrapper hide-x" style="top:'+y+'px; left:'+x+'px;width:'+width+'px"></div>';
    $('body').append(html);

    
}
$(document).on('keydown', '#search', function (e) {
    switch (e.keyCode) {
        case 38:
            navigate('up');
            break;
        case 40:
            navigate('down');
            break;
        case 9:
            navigate('tab');
            e.preventDefault();
            break;
        case 13:
            if (currentUrl1 != "") {
                e.preventDefault();
                e.stopPropagation();
                window.location.href = currentUrl1;
            }
            break;
    }
});
function navigate(direction) {
    if ($(".search-drawer .selected").size() == 0) {
        currentSelection = -1;
    }
    if (direction == 'up' && currentSelection != -1) {
        if (currentSelection != 0) {
            currentSelection--;
        }
    } else if (direction == 'down') {
        if (currentSelection != $(".search-drawer li").size() - 1) {
            currentSelection++;
        }
    } else if (direction == 'tab') {
        currentSelection = currentSelection;
    }
    setSelected(currentSelection, direction);
}
function setSelected(menuitem, direction) {
    if (direction == 'up' || direction == 'down') {
        $(".search-drawer").find("li:hover").removeClass('selected');
        $(".search-drawer li").removeClass("selected");
        $(".search-drawer li").eq(menuitem).addClass("selected");
        let drawerY = $('.search-drawer-wrapper').offset().top;
        let drawerH = $('.search-drawer-wrapper').outerHeight();
        let itemY = $(".search-drawer li").eq(menuitem).offset().top - (drawerY + 386);
        if(itemY > -50) {
            $('.search-drawer-wrapper').scrollTop($('.search-drawer-wrapper').scrollTop() + 50);
        } else if(itemY < -370) {
            $('.search-drawer-wrapper').scrollTop($('.search-drawer-wrapper').scrollTop() - 50);
        }
        currentUrl1 = $(".search-drawer li a").eq(menuitem).attr("href");
    }
    if (direction == 'tab') {
        lisearchhtml = $(".search-drawer .jstabval").eq(menuitem).text();
        if (lisearchhtml != "") {
            $("#search").val(lisearchhtml);
            $(".search-drawer").slideUp("fast");
            $("#search").focus();
        }
    }
}
$(window).resize(function(){
    hideSearchDrawer();
});
$(document).on('click', 'body', function (e) {
    if (!$(e.target).parents('.search-box').length && !$(e.target).parents('.search-drawer-wrapper').length && !$(e.target).hasClass('.search-drawer-wrapper')) {
        hideSearchDrawer();
    }
});
