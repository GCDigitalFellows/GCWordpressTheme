// as the page loads, call these scripts
jQuery(window).load(function() {

    // parallax scrolling
    jQuery('.parallax-gc').each(function() {
        var bgobj = jQuery(this); // assigning the object

        jQuery(window).scroll(function() {
            var yPos = -(jQuery(window).scrollTop() / bgobj.data('bg-speed'));

            // Put together our final background position
            var coords = '50% ' + yPos + 'px';
            // Move the background
            bgobj.css({
                backgroundPosition: coords
            });
        });
    });

    // menu scrollspy
    jQuery('body').scrollspy({
        target: '#main-nav',
        offset: 0
    });

    // Setup waypoints for triggering events on scroll

    /* sticky sections
     *
     * waypoint-trigger is a class applied to block elements like divs.
     * there is a waypoint attached to the top and bottom of that element.
     * when the top waypoint is hit, the "waypoint-sticky" element gets stuck to the top of the screen
     * when the bottom waypoint is hit, the waypoint-sticky element is unstuck
     * when the element becomes sticky, a replacement div with the same height is put in place so that the page layout isn't affected.
     */

    jQuery('.waypoint-trigger').each(function() {
        jQuery(this).waypoint(function(direction) {
            if (jQuery(document).width() > 768) {
                var $id = jQuery(this).attr('id');
                var $nextElement = jQuery('.waypoint-sticky-' + jQuery(this).waypoint('next').attr('id'));
                if (direction === 'up') {
                    var scrollH = (jQuery.waypoints('viewportHeight') - jQuery('.navbar').height() - jQuery(this).outerHeight() + 160);
                    if (scrollH < 0) {
                        jQuery('.waypoint-sticky-' + $id).css('width',jQuery('.waypoint-sticky-' + $id).parent().width());
                        jQuery('.waypoint-sticky-' + $id).addClass('waypoint-stuck');
                        jQuery('.waypoint-sticky-' + $id + '.waypoint-stuck').css('top',jQuery('.navbar').height() + 40); //a little margin to breathe
                        if ($nextElement.hasClass('waypoint-stuck')) {
                            $nextElement.removeClass('waypoint-stuck');
                        }
                    }
                } else {
                    jQuery('.waypoint-sticky-' + $id).removeClass('waypoint-stuck');
                }
            }
        },{
          offset: 'bottom-in-view'
        });

        jQuery(this).waypoint(function(direction) {
            if (jQuery(document).width() > 768) {
                var $id = jQuery(this).attr('id');
                var $prevElement = jQuery('.waypoint-sticky-' + jQuery(this).waypoint('prev').attr('id'));
                var $nextElement = jQuery('.waypoint-sticky-' + jQuery(this).waypoint('next').attr('id'));
                if (direction === 'up') {
                    jQuery('.waypoint-sticky-' + $id).removeClass('waypoint-stuck');
                } else {
                    var scrollH = (jQuery.waypoints('viewportHeight') - jQuery('.navbar').height() - jQuery(this).outerHeight() + 160);
                    if (scrollH < 0) {
                        jQuery('.waypoint-sticky-' + $id).css('width',jQuery('.waypoint-sticky-' + $id).parent().width());
                        jQuery('.waypoint-sticky-' + $id).addClass('waypoint-stuck');
                        jQuery('.waypoint-sticky-' + $id + '.waypoint-stuck').css('top',jQuery('.navbar').height() + 40);
                        if ($prevElement.hasClass('waypoint-stuck')) {
                            $prevElement.removeClass('waypoint-stuck');
                        }
                    }
                }
            }
        },{
          offset: jQuery('.navbar').height() - 40 // account for padding
        });
    })

    // hacked up waypoints version of scrollspy since bootstrap only lets you use one per page...

    jQuery('.waypoint-scrollspy').each(function() {
        jQuery(this).waypoint(function(direction) {
            if (jQuery(document).width() > 768) {
                var $currentElement = jQuery(this);
                var $id = $currentElement.attr('id');
                var $prevElement = jQuery('.waypoint-scrollspy-' + $currentElement.waypoint('prev').attr('id'));
                var $nextElement = jQuery('.waypoint-scrollspy-' + $currentElement.waypoint('next').attr('id'));
                if (direction === 'up') {
                    if ($prevElement.hasClass('hidden')) {
                        jQuery('.waypoint-scrollspy-' + $id).addClass('hidden');
                        jQuery('.waypoint-scrollspy-' + $id).removeClass('visible');

                        $prevElement.removeClass('hidden');
                        $prevElement.addClass('visible');
                    }
                } else {
                    jQuery('.waypoint-scrollspy-' + $id).removeClass('hidden');
                    jQuery('.waypoint-scrollspy-' + $id).addClass('visible');
                    if ($prevElement.hasClass('visible')) {
                        $prevElement.removeClass('visible');
                        $prevElement.addClass('hidden');
                    }
                }
            }
        },{
          offset: jQuery('.navbar').height()
        });
    })


    // animate scrolling within a page on menu clicks

    jQuery('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = jQuery(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        var thetop;
        if (jQuery('body').hasClass('navbar-no-offset')) {
            thetop = target.offset().top + 1;
        } else if (jQuery('body').hasClass('navbar-fixed-offset')) {
            thetop = target.offset().top - jQuery('.navbar').height() + 1;
        }
        jQuery('html,body').animate({
          scrollTop: thetop
        }, 1000);
        return false;
      }
    }
  });

    jQuery(window).resize(function() {
        if (jQuery(document).width() < 768) {
            jQuery('.navbar-brand').css('font-size', 58+(80-58)*(jQuery(document).width()-400)/(768-400));
            jQuery('body').addClass('navbar-no-offset');
            jQuery('header.navbar').addClass('navbar-static-top');
            jQuery('header.navbar').removeClass('navbar-fixed-bottom');
            jQuery('header.navbar').removeClass('navbar-fixed-top');
            jQuery('body').removeClass('navbar-fixed-offset');
            jQuery('#content').css('margin-top', 0);
            jQuery.waypoints('disable');
        } else {
            jQuery('.navbar-brand').css('font-size','');
            if (jQuery('body').data('navpos') == 'fixed') {
                jQuery('#content').css('margin-top', jQuery('.navbar').height());
                if (jQuery('body').hasClass('navbar-no-offset')) {
                    jQuery('body').removeClass('navbar-no-offset');
                    jQuery('body').addClass('navbar-fixed-offset');
                    jQuery('header.navbar').removeClass('navbar-static-top');
                    jQuery('header.navbar').addClass('navbar-fixed-top');
                }
            }
            if (jQuery('body').data('navpos') == 'fixed-bottom' && jQuery('navbar').hasClass('navbar-static-top')) {
                jQuery('header.navbar').removeClass('navbar-static-top');
                jQuery('header.navbar').addClass('navbar-fixed-bottom');
            }
            jQuery.waypoints('enable');
        }
        jQuery.waypoints('refresh');
    }).resize();

    // end of as page load scripts

});
