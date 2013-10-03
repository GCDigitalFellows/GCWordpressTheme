/*! 
CUNY GC Javascript
2011
Authors: Nick Evans & Philipp C. Adrian

*/

/* place all functions within cuny namespace */
var tryCalendarSetup = false;
var cuny = {
    // Check deisgn placement on the grid. 
    // Can be run easily from the console to toggle on and off.
    showGrid: function () {
        return $('.page').toggleClass("showgrid");
    },

    // behaviors() runs on document ready and sets up main behaviors.
    // can be applied to any content loaded after this (by ajax) 
    // by passing the selector as an argumnet
    behaviors: function (context) {

        /*
        * Browser detection script from http://www.quirksmode.org/js/detect.html
        * we're just doing this to prompt upgrades for browsers below the minium spec
        * In all other cases we're using feature sniffing rather than browser sniffing.
        * See http://www.quirksmode.org/js/support.html for why. Actual detection happens
        * in plugins.js
        */
        BrowserDetect.init();

        if ( // deliberatly not worrying about  Camino & Konqueror etc.
			BrowserDetect.browser == "Explorer" && BrowserDetect.version < 7 || // Internet explorer
			BrowserDetect.browser == "MSIE" && BrowserDetect.version < 7 || // Internet explorer
			BrowserDetect.browser == "Firefox" && BrowserDetect.version < 3 || // Firefox
			BrowserDetect.browser == "Netscape" && BrowserDetect.version <= 9 || // Netscape, you never know...
			BrowserDetect.browser == "Safari" && BrowserDetect.version < 2 || // Safari
			BrowserDetect.browser == "Opera" && BrowserDetect.version < 6  // Opera							
		) {

            var browserWarning = "";
            browserWarning += "<div style='background:#fff; padding:10px; width:auto; z-index:999; margin-bottom:10px;'>";
            browserWarning += "<h3>You appear to be running a browser unsupported by this website</h3>";
            browserWarning += "<p>We reccomend using Google Chrome for the best browsing experience. </p>";
            browserWarning += "<p><a href='http://www.google.com/chrome' target='_blank'>Click here to upgrade</a></p>";
            browserWarning += "</div>"
            $('#main-content').prepend(browserWarning);
        }


        // share link functionality.
        window.addthis_config = {
            services_compact: 'email, facebook, twitter, more',
            ui_click: true
        };

        //initialize fallback for placeholders, see search in the nav
        if (!Modernizr.input.placeholder) {
            $('input[placeholder], textarea[placeholder]').placeholder();
        }

        // initialize any menu-accordions on the page.
        $('.accordion-aside .accordion, .accordion-aside .accordion-expandable', context).expandableAccordion({
            firstOpen: true,
            toggleControls: false,
            multiple: false
        });

        // elements with the .accordion tag will have toggle controls and their first panel will be open
        $('.accordion-menu .bd', context).accordion({ autoHeight: false, active: false, collapsible: true });

        // initialize pseudo accordions
        // even though we've implemented some of jQuery UI, we're not using their Accordion.
        // If you want multiple sections open at once, don't use an accordion
        // see http://docs.jquery.com/UI/API/1.8/Accordion

        // elements with the .accordion tag will have toggle controls and their first panel will be open
        $('.accordion', context).expandableAccordion({ firstOpen: true, toggleControls: true });

        // elements with .accordion-closed tag will have all items closed with toggle controls.
        $('.accordion-closed', context).expandableAccordion({ toggleControls: true });

        //		Accordions Keyboard accessible.
        /*
        $(".accordion h3 a, .accordion-menu h3 a", context).bind("keydown", function (e) {
        if (e.which !== "13" && e.which !== "32") {
        return;
        }
        $(this).closest(".accordion, .bd").accordion("activate", $(this).parent().index() / 2);
        });
        */

        // style any select menus on the page.
        $('select', context).selectmenu();

        // style any radios on the page.
        $('.ui-buttonset', context).buttonset();

        if ($('.ui-buttonset', context).length) {
            $('.ui-buttonset-grid', context).button("option", "icons", { primary: 'ui-icon-grid' });
            $('.ui-buttonset-list', context).button("option", "icons", { primary: 'ui-icon-list' });
            $('.ui-buttonset-day', context).button("option", "icons", { primary: 'ui-icon-day' });
            $('.ui-buttonset-week', context).button("option", "icons", { primary: 'ui-icon-week' });
            $('.ui-buttonset-month', context).button("option", "icons", { primary: 'ui-icon-month' });
        }

        // find elements to truncate
        $('.truncate', context).truncate({ max_length: 300, more: 'Read more' });




        // initialize mini-site behaviour 
        cuny.miniSites.header.init();

        // initialize bookshelf on frontpage
        if ($('.bookshelfModule').length) { // ... but only if there is one
            cuny.bookshelf.init();
        } else {

            // we're not on the front page, so try some other behaviours

            // initialize any carousels
            cuny.gScarousel.init(context);
            cuny.multiCarousel.init(context);

            // Moved calendar pop up initialization to fire after page is completed loading
            // Robert Tyska, July 5, 2011
            // Set tryCalendarSetup to true and then we'll know to run the check in bmm.js
            tryCalendarSetup = true;

        }
        // initialize search filter
        cuny.searchbox.init();

        // DROP DOWNS	 
        cuny.mainNav.init();

        // handling humanitiesPopup
        cuny.humanitiesPopup.init();

        // handling arcMenu
        cuny.arcMenu.init();
    }, // end behaviors

    bookshelf: {
        init: function () {
            var cb = cuny.bookshelf; // local variable to improve minification

            //			Initialise frontpage slideshow.
            if ($(".bookshelf") && $().greenishSlides) {
                if ($(".bookshelf").children().length < 8) {
                    $(".bookshelf").parent().addClass('no-news'); // fallback in case .no-news class cannot be added.
                }
                $(".bookshelf").greenishSlides({
                    stayOpen: false,
                    transitionSpeed: "400",
                    easing: "easeInOutQuad",
                    handle: ".activater",
                    resizable: false,
                    cache: true,
                    events: {
                        activate: "mouseover",
                        deactivate: "mouseout"
                    },
                    limits: {
                        min: 20,
                        7: {
                            min: 140
                        }
                    },
                    hooks: {
                        //				Resets autoscrolling that happens because of the focus.
                        preDeactivate: function () {
                            cb.resetAutoscroll(".contentWrap");
                        },
                        preActivate: function () {
                            cb.resetAutoscroll(".contentWrap");
                        }
                    }
                });
            }
        },
        resetAutoscroll: function (element) {
            if ($(this).find(element).length) {
                $(this).find(element).scrollLeft(0);
            }
        }
    },

    humanitiesPopup: {
        init: function () {
            var chp = cuny.humanitiesPopup; // local variable for minification
            $(".humanitiesLink")
				.bind("click", function (e) {
				    $(".areasOfInterest-menu").focus();
				})
				.hover(function () {
				    cuny.mouseOverHumanities = true;
				}, function () {
				    cuny.mouseOverHumanities = false;
				})
				.live("focusin.humanitiesPopup", chp.show)
				.bind("focusout.humanitiesPopup", chp.hide);
        },
        hide: function (e) {
            if (cuny.mouseOverHumanities) {
                return;
            }
            $("#humanitiesPopup")
				.stop()
				.fadeOut();
        },
        show: function (e) {
            $("#humanitiesPopup")
				.stop()
				.animate({ opacity: 1 }, 0)
				.fadeIn()
				.focus();
        }
    },

    arcMenu: {
        init: function () {
            var chp = cuny.arcMenu; // local variable for minification
            $(".arcLink")
        },
    },

    // this controls the main drop down navigation
    mainNav: {
        // attaches main behaviors for the nav.
        init: function () {
            $("#main-nav li.nav").each(function () {
                this.margin = $(this).find(".drop-down").css("margin-left");
                $(this).find(".drop-down .line.mainNav").fadeOut(0);
            });
            $("#main-nav li.nav")
				.hover(cuny.mainNav.hoverIn, cuny.mainNav.hoverOut)
				.bind("focusin", cuny.mainNav.hoverIn)
				.bind("focusout", cuny.mainNav.hoverOut)
				.trigger("focusout");

        },

        // what happens when user hovers
        hoverIn: function () {
            var margin = this.margin,
				$dropDown = $(this).find(".drop-down"),
				maxWidth = $dropDown.css("max-width"),
				minWidth = $dropDown.css("min-width"),
				maxHeight = $dropDown.css("max-height");

            $(this).css("background-color", $(".background", this).css("background-color"));
            $(this).siblings().trigger("focusout");

            $(this).css("z-index", 10).find(".drop-down").stop().animate({ "height": maxHeight }, "fast", "swing", function () {
                $(this).animate({ "width": maxWidth, "margin-left": margin }, "fast", "swing", function () {
                    $(this).find(".line.mainNav").fadeIn("fast");
                });
            });
        },

        // what happens when the user moves out.
        hoverOut: function () {
            $(this).css("background-color", "transparent");
            var minWidth = $(this).find(".drop-down").css("min-width");
            $(this).css("z-index", 2).find(".drop-down").stop().animate({ "height": 0 }, 400, function () {
                $(this).parent().css("z-index", 1);
                $(this).css({ "margin-left": 0, "width": minWidth }).find(".line.mainNav").fadeOut(0);
            });
        }
    },
    calendar: {
        init: function () {
            var cc = cuny.calendar; // local variable to improve minification
            //		for IE (no animation and dropshadow with divs)
            //alert('in');
            if (!Modernizr.boxshadow) {
                $(".event .popupContent")
					.before("<div class='shadowPopup'></div>")
					.find(".content")
					.before("<div class='shadowPopupContent'></div>");

                $("article#main-content .unit .event")
					.hoverIntent({ timeout: 0, over: cc.overIE, out: cc.outIE })
					.bind("focusin", cc.overIE)
					.bind("focusout", cc.outIE);
            }
            else { // for not IE
                $("article#main-content .unit .event")
					.hoverIntent({ timeout: 0, over: cc.over, out: cc.out })
					.bind("focusin", cc.over)
					.bind("focusout", cc.out);
            }

            //			even out calendar columns			
            $("article#main-content .line").each(function () {
                var t = {
                    cols: $(this).find(".unit"),
                    h: 0
                };

                //find the longest column (t.h)
                for (var i = 0, tlength = t.cols.length; i < tlength; i++) {
                    if ($(t.cols[i]).height() > t.h) {
                        t.h = $(t.cols[i]).height();
                    }
                }

                if (t.cols.hasClass("lastUnit"))
                    t.cols.css("height", (t.h + 38));
                else
                    t.cols.css("height", (t.h + 20));
            });
        },
        outIE: function () {
            $(".calendar ul.line:has(.event.active)")
				.css({ zIndex: 0 })
				.find("li.unit:has(.event.active)")
				.css({ zIndex: 0 })
				.find(".event.active")
				.css({ zIndex: 0 });
            $(this)
				.removeClass("active")
				.find(".liLiner")
				.css({ backgroundColor: $(this).parent().parent().css('backgroundColor') })
				.end()
				.find("div.popup")
				.css({ display: "none" });
        },
        overIE: function () {
            $(".calendar .event.active")
			.triggerHandler("focusout");
            $(this).addClass("active")
				.find(".liLiner")
				.css({ backgroundColor: "#EDEDED" })
				.end()
				.find("div.popup")
				.css({ display: "block" });
            $(".calendar ul.line:has(.event.active)")
				.css({ zIndex: 9 })
				.find("li.unit:has(.event.active)")
				.css({ zIndex: 9 })
				.find(".event.active")
				.css({ zIndex: 9 });
        },
        out: function () {
            $(".calendar ul.line:has(.event.active)")
				.css({ zIndex: 5 })
				.find("li.unit:has(.event.active)")
				.css({ zIndex: 5 })
				.find(".event.active")
				.css({ zIndex: 5 });
            $(this)
				.removeClass("active")
				.find(".liLiner")
				.stop()
				.animate({ backgroundColor: $(this).find(".liLiner").parent().parent().css('backgroundColor') }, { duration: 200 })
				.end()
				.find("div.popup")
				.stop()
				.css({ opacity: 1 })
				.fadeOut(200, function () {
				    $(".calendar ul.line")
						.not("ul.line:has(.event.active)")
						.css({ zIndex: 0 })
						.end()
						.find("li.unit")
						.not("li.unit:has(.event.active)")
						.css({ zIndex: 0 })
						.end()
						.find(".event")
						.not(".event.active")
						.css({ zIndex: 0 });
				});
        },
        over: function () {
            $(".calendar .event.active")
				.triggerHandler("focusout");
            $(this)
				.addClass("active")
				.find(".liLiner")
				.stop()
				.animate({ backgroundColor: "#EDEDED" }, { duration: 200 })
				.end()
				.find("div.popup")
				.stop()
				.fadeIn(200);
            $(".calendar ul.line:has(.event.active)")
				.css({ zIndex: 9 })
				.find("li.unit:has(.event.active)")
				.css({ zIndex: 9 })
				.find(".event.active")
				.css({ zIndex: 9 });
        }
    },
    searchbox: {
        init: function (object) {
            var $searchForm = object || $('.search-wrapper-filtered');

            // cuny.searchbox.scope($searchForm);
            $("#search")
				.hover(function () {
				    cuny.mouseOverSearch = true;
				},
				function () {
				    cuny.mouseOverSearch = false;
				})
				.live("focusin.search", function () {
				    cuny.searchbox.over($searchForm);
				})
				.bind("focusout.search", function () {
				    cuny.searchbox.out($searchForm);
				})
				.find(".search-dropdown")
				.bind("click.search", function (e) {
				    $(".search-input-text", $("#search")).focus();
				})
				.end()
				.find("span")
				.bind("click", function (e) {
				    cuny.mouseOverSearch = false;
				    if ($("#search").hasClass("search-wrapper-active")) {
				        $("#search").trigger("focusout");
				    } else {
				        $("#search").trigger("focusin");
				    }
				});


            /**
            * selecting all checkboxes
            */
            $searchForm.find('.search-dropdown').find('label[for="all"]').prev().bind('click', function () {
                $(this)
					.parent()
					.siblings()
					.find(':checkbox')
					.attr('checked', this.checked)
					.attr('disabled', this.checked)
					.parent()
					.parent()
					.toggleClass('search-checkboxes-all');
                //cuny.searchbox.scope($searchForm);
            });
            $searchForm.find('.search-filter-item-individual').live('click', function () {
                if ($(this).parent().hasClass('search-checkboxes-all')) {
                    $(this)
						.siblings()
						.find(':checkbox')
						.attr('checked', "")
						.attr('disabled', "")
						.parent()
						.parent()
						.toggleClass('search-checkboxes-all');
                    $(this)
						.find(':checkbox')
						.attr('checked', "yes")
						.attr('disabled', "");
                    //cuny.searchbox.scope($searchForm);
                }
            });
        }, // end  searchbox	init
        scope: function ($searchForm) {
            var scope = $searchForm.find('.search-checkboxes-all').length !== 0 ? 'all' : "mixed";
            $searchForm.find('.search-scope').html(scope);
        },
        over: function ($searchForm, focused) {

            $searchForm
				.addClass('search-wrapper-active')
				.find('.ui-icon')
				.addClass('ui-icon-triangle-1-n')
				.removeClass('ui-icon-triangle-1-s')
				.end()
				.find('.search-dropdown')
				.stop()
				.css("height", "381px")
				.slideDown();
        },
        out: function ($searchForm) {
            if (cuny.mouseOverSearch) {
                return;
            }
            $searchForm
				.find('.ui-icon')
				.addClass('ui-icon-triangle-1-s')
				.removeClass('ui-icon-triangle-1-n')
				.andSelf()
				.find('.search-dropdown')
				.stop()
				.slideUp(500, function () {
				    $(this).hide();
				    $searchForm.removeClass('search-wrapper-active');
				});
        }
    },

    // controls  for the carousels go in here 
    // see plugins.js for the greenishSlide plugin itself
    // cuny.gScarousel.init() should be callable on ajax content.
    gScarousel: {
        init: function (context) {
            if ($(".gScarousel .carousel-cassette", context).greenishSlides) {
                $(".gScarousel .carousel-cassette", context).greenishSlides({
                    stayOpen: true,
                    handle: false,
                    easing: "easeInOutQuad",
                    active: 0,
                    circle: true,
                    resizable: false,
                    cache: true,
                    hooks: {
                        postActivate: cuny.gScarousel.postActivate,
                        preInit: cuny.gScarousel.preInit
                    }
                });
            }
        },
        postActivate: function () {
            var ai = $(this).index(),
				dots = $(this).closest(".gScarousel").find(".carousel-controls a"),
				dot;
            for (var i = 0, dl = dots.length; i < dl; i++) {
                dot = dots.eq(i);
                if (parseFloat(dot.html()) === ai) {
                    dot.addClass("active");
                } else {
                    dot.removeClass("active");
                }

            }
        },
        preInit: function () {
            //			Set Carousel Height.
            var slides = $(this).find(".liLiner").css("position", "absolute"),
				height = 0,
				slideHeight,
				controls;
            for (var i = (slides.length - 1); i >= 0; i--) {
                if ((slideHeight = slides.eq(i).height()) > height) {
                    height = slideHeight;
                }
            }

            $(this).css("height", height);
            //			Set Create controls.
            $(this).before(cuny.gScarousel.controls(slides));
        },
        controls: function (slides) {
            var controls,
				append;
            controls = $("<div/>").addClass("carousel-controls clearfix");
            append = $("<div/>").addClass("ui-icon carousel-control-item prev").bind("click", function () {
                var context = $(this).closest(".gScarousel").find(".carousel-cassette");
                $.gS.prev(context);

            });
            controls.append(append);
            for (var i = 0, sl = slides.length; i < sl; i++) {
                append = $("<a/>").addClass("ir ui-icon carousel-control-item slideDot").html(i).bind("click", function () {
                    var slide = $(this).closest(".gScarousel").find(".carousel-cassette").children().eq($(this).html());
                    $.gS.activate(slide);
                });
                controls.append(append);
            }
            append = $("<div/>").addClass("ui-icon carousel-control-item next").bind("click", function () {
                var context = $(this).closest(".gScarousel").find(".carousel-cassette");
                $.gS.next(context);

            });
            controls.append(append);
            return controls;
        }
    }, // end gScarousel
    multiCarousel: {
        init: function (context) {
            context.find(".multiCarousel").each(function () {
                var url = null;

                if ($.bbq.getState($(this).attr("id"))) {
                    url = $.bbq.getState($(this).attr("id"));
                } else {
                    url = $(this).find('.carouselTabs a').eq(0).attr("href");
                }
                $(this).find(".carouselWrapper").css({
                    "height": $(this).find('.carouselTabs').height()
                });
                cuny.multiCarousel.replaceCarousel($(this), url);
                $(this).find('.carouselTabs a[href="' + url + '"]').parent().addClass("active");
            });
            context.find('.multiCarousel .carouselTabs a').bind("click", function () {
                var multiCarouselObj = $(".multiCarousel").has($(this));
                multiCarouselObj.find('.carouselTabs .active').removeClass("active");
                $(this).parent().addClass("active").find(".ui-icon").removeClass("ui-icon-triangle-1-w").addClass('ui-icon-ajax-loading');
                cuny.multiCarousel.replaceCarousel(multiCarouselObj, $(this).attr("href"));
                return false;
            });
        },
        replaceCarousel: function (multiCarouselObj, requestUrl) {
            var state = {};
            state[$(multiCarouselObj).attr("id")] = requestUrl;
            $.bbq.pushState(state);

            $.ajax({
                url: requestUrl,
                context: $(multiCarouselObj),
                success: function (data) {
                    var toHeight = null;
                    $(this).find(".ui-icon-ajax-loading").removeClass("ui-icon-ajax-loading").addClass("ui-icon-triangle-1-w");

                    $(this)
						.find(".multiCarouselWrapper")
						.children()
						.css({
						    "display": "none",
						    "visibility": "hidden"
						})
						.filter(".multiCarouselContent")
						.css({
						    "display": "block",
						    "visibility": "hidden"
						})
						.html(data);

                    cuny.gScarousel.init($(this));

                    if ($(this).find(".multiCarouselWrapper").height() > $(this).find(".carouselTabs").height()) {
                        toHeight = $(this).find(".multiCarouselWrapper").innerHeight();
                    } else {
                        toHeight = $(this).find(".carouselTabs").innerHeight();
                    }

                    $(this).find(".contentWrapper").animate({ "height": toHeight }, { complete: function () {
                        $(this)
							.find(".multiCarouselContent")
							.css({
							    "visibility": "visible",
							    "display": "none"
							})
							.fadeIn();
                    }
                    });

                    return true;
                },
                error: function () {
                    var toHeight = null;
                    $(this)
						.find(".ui-icon-ajax-loading")
						.removeClass("ui-icon-ajax-loading")
						.addClass("ui-icon-triangle-1-w");
                    $(this)
						.find(".multiCarouselWrapper")
						.children()
						.css({
						    "display": "none",
						    "visibility": "hidden"
						})
						.filter(".multiCarouselSorry")
						.css({
						    "display": "block",
						    "visibility": "hidden"
						});

                    if ($(this).find(".multiCarouselWrapper").height() > $(this).find(".carouselTabs").height()) {
                        toHeight = $(this).find(".multiCarouselWrapper").height();
                    } else {
                        toHeight = $(this).find(".carouselTabs").height();
                    }

                    $(this).find(".contentWrapper").animate({ "height": toHeight }, { complete: function () {
                        $(this)
							.find(".multiCarouselSorry")
							.css({
							    "visibility": "visible",
							    "display": "none"
							})
							.fadeIn();
                    }
                    });
                    return false;
                }
            });
        }
    }, // end multiCarousel
    miniSites: {
        header: {
            init: function () {
                var $miniSiteHeader = $('.miniSite-header'),
					$mainSiteHeader = $('.miniSite-nav');
                $('.miniSite-header-toggle, .miniSite-header-inline-toggle').click(function () {
                    var $myText = $(this).find('.miniSite-header-toggle-text'),
					    toggleText = $myText.html().substr(0, 4) === "Show" ? "Hide " : "Show ";
                    $myText.html(toggleText + $myText.text().substr(4));
                    if (Modernizr.csstransitions) {
                        $miniSiteHeader.toggleClass('miniSite-header-open');
                        $mainSiteHeader.toggleClass('miniSite-header-open');
                    } else {
                        var myMargin = $miniSiteHeader.css('marginBottom'),
							myMainLinkHeight = $mainSiteHeader.find('.mainLink').eq(0).height(),
							myMainLinkPadding = null;

                        myMargin = (parseInt(myMargin, 10) < 0) ? "50px" : "-9px";
                        myMainLinkHeight = (parseInt(myMainLinkHeight, 10) === 0) ? "30px" : "0px";
                        myMainLinkPadding = (parseInt(myMainLinkHeight, 10) === 0) ? "0px" : "10px";
                        $miniSiteHeader.animate({ 'marginBottom': myMargin }, 400);
                        $mainSiteHeader.find('.mainLink').animate({ 'height': myMainLinkHeight, 'paddingTop': myMainLinkPadding, 'paddingBottom': myMainLinkPadding }, 400);
                    }
                    $(this).find('.ui-icon').toggleClass('ui-icon-triangle-1-n').toggleClass('ui-icon-triangle-1-s');
                });

            }
        }
    }
};

$(document).ready(function () {

    // initialize behaviours
    // this abstraction allows easy behaviour
    // initialization on ajax loaded content
    // inspired by:
    // http://drupal.org/node/205296 
    cuny.behaviors($('body'));

});





