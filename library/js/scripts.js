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

    /* menu scrollspy */
    jQuery('body').scrollspy({
        target: '#main-nav',
        offset: jQuery('.navbar').height() + 50 //somewhat arbitrary, might need to be adjusted for various setups
    });

    jQuery('#main-nav a').click(function() {

        var thetop;

        if (jQuery('#wpadminbar').css('position') == 'absolute' || jQuery('body').hasClass('navbar-no-offset')) {
            thetop = (jQuery(jQuery(this).attr('href')).offset().top - jQuery('.navbar').height());
        } else if (jQuery('#wpadminbar').css('position') == 'fixed' || jQuery('body').hasClass('navbar-fixed-offset')) {
            thetop = (jQuery(jQuery(this).attr('href')).offset().top);
        }

        jQuery(document.body).animate({
            scrollTop: thetop
        }, 500);
        return false;

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
        } else {
            jQuery('.navbar-brand').css('font-size','');
            console.log(jQuery('body').data('navpos'));
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
        }
    }).resize();

    /* end of as page load scripts */

});

/* additional tools */


/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w) {
    // This fix addresses an iOS bug, so return early if the UA claims it's something else.
    if (!(/iPhone|iPad|iPod/.test(navigator.platform) && navigator.userAgent.indexOf("AppleWebKit") > -1)) {
        return;
    }
    var doc = w.document;
    if (!doc.querySelector) {
        return;
    }
    var meta = doc.querySelector("meta[name=viewport]"),
        initialContent = meta && meta.getAttribute("content"),
        disabledZoom = initialContent + ",maximum-scale=1",
        enabledZoom = initialContent + ",maximum-scale=10",
        enabled = true,
        x, y, z, aig;
    if (!meta) {
        return;
    }

    function restoreZoom() {
        meta.setAttribute("content", enabledZoom);
        enabled = true;
    }

    function disableZoom() {
        meta.setAttribute("content", disabledZoom);
        enabled = false;
    }

    function checkTilt(e) {
        aig = e.accelerationIncludingGravity;
        x = Math.abs(aig.x);
        y = Math.abs(aig.y);
        z = Math.abs(aig.z);
        // If portrait orientation and in one of the danger zones
        if (!w.orientation && (x > 7 || ((z > 6 && y < 8 || z < 8 && y > 6) && x > 5))) {
            if (enabled) {
                disableZoom();
            }
        } else if (!enabled) {
            restoreZoom();
        }
    }
    w.addEventListener("orientationchange", restoreZoom, false);
    w.addEventListener("devicemotion", checkTilt, false);
})(this);
