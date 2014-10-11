jQuery(document).ready(function($){

    // position static header at the top of the screen, accounting for admin bar
    $('#main_header.navbar-fixed-top').css('top',$('#wpadminbar').outerHeight());

    // force footer to bottom of page
    var height_diff = $(window).height() - $('body').height();
    if ( height_diff > 0 ) {
        $('#page-footer').css( 'margin-top', height_diff );
    }

    // parallax scrolling
    $('.parallax-gc').each(function() {
        var bgobj = $(this); // assigning the object

        $(window).scroll(function() {
            var yPos = -($(window).scrollTop() / bgobj.data('bg-speed'));

            // Put together our final background position
            var coords = '50% ' + yPos + 'px';
            // Move the background
            bgobj.css({
                backgroundPosition: coords
            });
        });
    });

    // menu scrollspy
    $('body').scrollspy({
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

    $('.waypoint-trigger').each(function() {
        if ($(document).width() > 768) {
            $(this).waypoint(function(direction) {
                var $id = $(this).attr('id');
                var $nextElement = $('.waypoint-sticky-' + $(this).waypoint('next').attr('id'));
                if (direction === 'up') {
                    var scrollH = ($.waypoints('viewportHeight') - $('.navbar').height() - $(this).outerHeight() + 160);
                    if (scrollH < 0) {
                        $('.waypoint-sticky-' + $id).css('width',$('.waypoint-sticky-' + $id).parent().width());
                        $('.waypoint-sticky-' + $id).addClass('waypoint-stuck');
                        $('.waypoint-sticky-' + $id + '.waypoint-stuck').css('top',$('.navbar').height() + 40); //a little margin to breathe
                        if ($nextElement.hasClass('waypoint-stuck')) {
                            $nextElement.removeClass('waypoint-stuck');
                        }
                    }
                } else {
                    $('.waypoint-sticky-' + $id).removeClass('waypoint-stuck');
                }
            },{
                continuous: true,
                offset: 'bottom-in-view'
            });

            $(this).waypoint(function(direction) {
                var $id = $(this).attr('id');
                var $prevElement = $('.waypoint-sticky-' + $(this).waypoint('prev').attr('id'));
                var $nextElement = $('.waypoint-sticky-' + $(this).waypoint('next').attr('id'));
                if (direction === 'up') {
                    $('.waypoint-sticky-' + $id).removeClass('waypoint-stuck');
                } else {
                    var scrollH = ($.waypoints('viewportHeight') - $('.navbar').height() - $(this).outerHeight() + 160);
                    if (scrollH < 0) {
                        $('.waypoint-sticky-' + $id).css('width',$('.waypoint-sticky-' + $id).parent().width());
                        $('.waypoint-sticky-' + $id).addClass('waypoint-stuck');
                        $('.waypoint-sticky-' + $id + '.waypoint-stuck').css('top',$('.navbar').height() + 40);
                        if ($prevElement.hasClass('waypoint-stuck')) {
                            $prevElement.removeClass('waypoint-stuck');
                        }
                    }
                }
            },{
                continuous: true,
                offset: $('.navbar').height() - 40 // account for padding
            });
        }
    });

    // hacked up waypoints version of scrollspy since bootstrap only lets you use one per page...

    $('.waypoint-scrollspy').each(function() {
        if ($(document).width() > 768) {
            $(this).waypoint(function(direction) {
                var $currentElement = $(this);
                var $id = $currentElement.attr('id');
                var $prevElement = $('.waypoint-scrollspy-' + $currentElement.waypoint('prev').attr('id'));
                var $nextElement = $('.waypoint-scrollspy-' + $currentElement.waypoint('next').attr('id'));
                if (direction === 'up') {
                    if ($prevElement.hasClass('hidden')) {
                        $('.waypoint-scrollspy-' + $id).addClass('hidden');
                        $('.waypoint-scrollspy-' + $id).removeClass('visible');

                        $prevElement.removeClass('hidden');
                        $prevElement.addClass('visible');
                    }
                } else {
                    $('.waypoint-scrollspy-' + $id).removeClass('hidden');
                    $('.waypoint-scrollspy-' + $id).addClass('visible');
                    if ($prevElement.hasClass('visible')) {
                        $prevElement.removeClass('visible');
                        $prevElement.addClass('hidden');
                    }
                }
            },{
              continuous: false,
              offset: $('.navbar').height()
            });
        }
    })


    // animate scrolling within a page on menu clicks

    $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        var thetop;
        if ($('body').hasClass('navbar-no-offset')) {
            thetop = target.offset().top + 1;
        } else if ($('body').hasClass('navbar-fixed-offset')) {
            thetop = target.offset().top - $('.navbar').height() + 1;
        }
        $('html,body').animate({
          scrollTop: thetop
        }, 1000);
        return false;
      }
    }
  });

    $(window).resize(function() {
        if ( $(document).width() < 768) {
            $('.navbar-brand').css('font-size', 58+(80-58)*($(document).width()-400)/(768-400));
            $('body').addClass('navbar-no-offset');
            $('header.navbar').addClass('navbar-static-top');
            $('header.navbar').removeClass('navbar-fixed-bottom');
            $('header.navbar').removeClass('navbar-fixed-top');
            $('body').removeClass('navbar-fixed-offset');
            $('#content').css('margin-top', 0);
            $.waypoints('disable');
        } else {
            $('.navbar-brand').css('font-size','');
            if ($('body').data('navpos') == 'fixed') {
                $('#content').css('margin-top', $('.navbar').height());
                if ($('body').hasClass('navbar-no-offset')) {
                    $('body').removeClass('navbar-no-offset');
                    $('body').addClass('navbar-fixed-offset');
                    $('header.navbar').removeClass('navbar-static-top');
                    $('header.navbar').addClass('navbar-fixed-top');
                }
            }
            if ($('body').data('navpos') == 'fixed-bottom' && $('navbar').hasClass('navbar-static-top')) {
                $('header.navbar').removeClass('navbar-static-top');
                $('header.navbar').addClass('navbar-fixed-bottom');
            }
            $.waypoints('enable');
        }
        $.waypoints('refresh');
    }).resize();

    // end of as page load scripts

});