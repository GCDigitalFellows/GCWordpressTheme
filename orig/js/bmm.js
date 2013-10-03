var loaderImage = '<center>loading, please wait...<br /><img src="/shared/images/shared/ajax-loader-grey.gif" alt="loading, please wait..." /></center>';
var siteUrl = "http://dev.gc.cuny.edu";

$(function () {
    if (bodyClass != "")
        $("body").addClass(bodyClass);

    if (mainContentClass != "")
        $("article#main-content").addClass(mainContentClass);

    if (miniSiteTitle != "")
        $("h1#MicroSiteLabel").html(miniSiteTitle);

    if ($("h1.miniSite-header").length > 0) {
        if (!$("div.body article.main").has("h1").length) {
            $("div.body article.main").prepend("<div id='microprinthead'>" + $("h1.miniSite-header").text() + "</div>");
        }
    }

    if (currentSection != "") {
        $("#" + currentSection).addClass("active");
        $("aside.mod-external-REPLACE").removeClass("mod-external-REPLACE").addClass("mod-external-" + currentSection.toLowerCase());
        $("a.external-link-REPLACE").removeClass("external-link-REPLACE").addClass("external-link-" + currentSection.toLowerCase());
        $("div.REPLACE").removeClass("REPLACE").addClass(currentSection.toLowerCase());

        switch (currentSection) {
            case "DropdownOne":
                $(".REPLACE").removeClass("REPLACE").addClass("dropdownone");
                break;
            case "DropdownTwo":
                $(".REPLACE").removeClass("REPLACE").addClass("dropdowntwo");
                break;
            case "DropdownThree":
                $(".REPLACE").removeClass("REPLACE").addClass("dropdownthree");
                break;
            case "DropdownFour":
                $(".REPLACE").removeClass("REPLACE").addClass("dropdownfour");
                break;
            case "DropdownFive":
                $(".REPLACE").removeClass("REPLACE").addClass("dropdownfive");
                break;
            case "DropdownSix":
                $(".REPLACE").removeClass("REPLACE").addClass("dropdownsix");
                break;
            case "DropdownSeven":
                $(".REPLACE").removeClass("REPLACE").addClass("dropdownseven");
                break;
        }
    }

    if (showHumantiesMenu == "false")
        $("div.humanitiesLink").hide();
    else
        $("div.humanitiesLink").show();

    if (showARCMenu == "false")
        $("div.arcLink").hide();
    else
        $("div.arcLink").show();

    if (showLeftColumn == "false")
        $("#LeftColumn").hide();
    else
        $("#LeftColumn").show();

    if (showMiddleColumn == "false")
        $("#MiddleColumn").hide();
    else
        $("#MiddleColumn").show();

    if (showMainColumn == "false")
        $("#main-content").hide();
    else
        $("#main-content").show();

 	if (showMainAlt == "false")
        $("#MainAlt").hide();
    else
		$("#MainAlt").show();
		
 	if (showFullTop == "false")
        $("#FullTop").hide();
    else
		$("#FullTop").show();
		
 	if (showFullBottom == "false")
        $("#FullBottom").hide();
    else
        $("#FullBottom").show();
 
	if (showHomeAlt == "false")
        $("#HomeAlt").hide();
    else
		$("#HomeAlt").show();

    if (showRightColumn == "false")
        $("#RightColumn").hide();
    else
        $("#RightColumn").show();

    if (showTwoColumnDiv == "false")
        $("div.twoColumnHeader").hide();
    else
        $("div.twoColumnHeader").show();

    if (showTwoColumnAside == "false")
        $("aside.twoColumnHeader").hide();
    else {
        if ($("multiCarousel" == "true"))
            $("aside.twoColumnHeader").show().addClass("multiCarousel").attr("id", "multiCarouselOne");
        else
            $("aside.twoColumnHeader").show();
    }
   
    if (setupHomePage == "true") {
        $("header").addClass("frontpage");
        $("#main-content").removeClass("main").addClass("clearfix");
        $("#LeftColumn").hide();
        $("#RightColumn").hide();
		$("#FullBottom").hide();
		$("#FullTop").hide();
		$("#MainAlt").hide();
		$("#HomeAlt").hide();
    }
   
    if (setupNoMenu == "true") {
        $("#main-content").removeClass("main").addClass("main2");
        $("#LeftColumn").hide();
		$("#FullBottom").hide();
		$("#FullTop").hide();
		$("#MainAlt").hide();
		$("#HomeAlt").hide();
    }
	

    if ($.query.get("CategoryFilter").length > 0)
        $("select#calendar-type").selectmenu("value", $.query.get("CategoryFilter"));

    if (runCalendarSetup == "true") {
        var setWeekViewClass = false;
        var skipScope = false;
        var formatExists = false;
        var scopeExists = false;

        $("#LeftColumn").removeClass("leftcolum-calendar-fix");

        //        if ($.query.get("day").length > 0) {
        //            $('#calendar-format-picker input[rel="list"]').attr("checked", "checked").button("refresh");
        //            $('#calendar-scope-picker input[rel="day"]').attr("checked", "checked").button("refresh");
        //            $("article#main-content").removeClass("threeColumnContent").removeClass("calendar").removeClass("viewWeek");
        //            $("#LeftColumn").css("min-height", "350px");
        //        }
        //        else 
        //        {
        if ($.query.get("format") != '') {
            formatExists = false;

            if ($.query.get("format") == "grid") {
                setWeekViewClass = true;
                $("#RightColumn").hide();
                $("article#main-content").addClass("threeColumnContent calendar");
                $('#calendar-format-picker input[rel="grid"]').attr("checked", "checked").button("refresh");
            }
            else {
                //$("#LeftColumn").css("min-height", "350px");
                //$("#LeftColumn").addClass("leftcolum-calendar-fix");

                if ($.query.get("scope") != '') {
                    scopeExists = true;
                    if ($.query.get("scope") == "month") {
                        $("#RightColumn").hide();
                        $("article#main-content").addClass("threeColumnContent calendar");
                        $('#calendar-format-picker input[rel="grid"]').attr("checked", "checked").button("refresh");
                        $('#calendar-scope-picker input[rel="month"]').attr("checked", "checked").button("refresh");
                    }
                    else {
                        $('#calendar-scope-picker input[rel="' + $.query.get("scope") + '"]').attr("checked", "checked").button("refresh");
                        $("#RightColumn").show();

                        if ($.query.get("scope") == "day") {
                            $("article#main-content").removeClass("threeColumnContent").removeClass("calendar").removeClass("viewWeek");
                            $('#calendar-format-picker input[rel="list"]').attr("checked", "checked").button("refresh");
                            $('#calendar-format-picker input[rel="grid"]').removeAttr("checked").button("refresh");
                        }
                        else if ($.query.get("scope") == "week") {
                            $("article#main-content").addClass("viewWeek");
                            $('#calendar-format-picker input[rel="' + $.query.get("format") + '"]').attr("checked", "checked").button("refresh");
                            //$("#LeftColumn").css("min-height", "350px");
                            $("#LeftColumn").addClass("leftcolum-calendar-fix");
                        }
                    }
                }
                else {
                    setWeekViewClass = true;
                    $("#RightColumn").hide();
                    $("article#main-content").addClass("threeColumnContent calendar");
                    $('#calendar-format-picker input[rel="grid"]').attr("checked", "checked").button("refresh");
                }
            }
        }
        else {
            setWeekViewClass = true;
            $("#RightColumn").hide();
            $("article#main-content").addClass("threeColumnContent calendar");
            $('#calendar-format-picker input[rel="grid"]').attr("checked", "checked").button("refresh");
        }

        if (!skipScope) {
            if ($.query.get("scope") != '') {
                scopeExists = ($.query.get("scope") != "day");
                $('#calendar-scope-picker input[rel="' + $.query.get("scope") + '"]').attr("checked", "checked").button("refresh");

                if ($.query.get("scope") == "day") {
                    $("article#main-content").removeClass("threeColumnContent").removeClass("calendar").removeClass("viewWeek");
                    $('#calendar-format-picker input[rel="list"]').attr("checked", "checked").button("refresh");
                    $('#calendar-format-picker input[rel="grid"]').removeAttr("checked").button("refresh");
                    //$("#LeftColumn").css("min-height", "350px");
                    $("#LeftColumn").addClass("leftcolum-calendar-fix");
                    $("#RightColumn").show();
                }
                else if (($.query.get("scope") == "week") && (setWeekViewClass)) {
                    $("article#main-content").addClass("threeColumnContent").addClass("calendar").addClass("viewWeek");
                    $("#RightColumn").hide();
                }

            }
            else
                $('#calendar-scope-picker input[rel="month"]').attr("checked", "checked").button("refresh");
        }

        if (!formatExists && !scopeExists) {
            if ($.query.get("day") != '') {
                $('#calendar-format-picker input[rel="list"]').attr("checked", "checked").button("refresh");
                $('#calendar-scope-picker input[rel="day"]').attr("checked", "checked").button("refresh");
                $('#calendar-format-picker input[rel="grid"]').removeAttr("checked").button("refresh");
                $('#calendar-scope-picker input[rel="month"]').removeAttr("checked").button("refresh");
                $('#calendar-scope-picker input[rel="week"]').removeAttr("checked").button("refresh");
                $("article#main-content").removeClass("threeColumnContent").removeClass("calendar").removeClass("viewWeek");
                //$("#LeftColumn").css("min-height", "350px");
                $("#LeftColumn").addClass("leftcolum-calendar-fix");
                $("#RightColumn").show();
            }
        }

        if ($.query.get("type") != '')
            $("select#calendar-type").selectmenu("value", $.query.get("type"));
        //}

        // Initialize Datepicker and Bind It's onSelect Method
        if ($.query.get("day") != '') {
            $("#datepicker").datepicker({ onSelect: function (date) { calendarFilterRedirect(null, null, null, date, null, null, null, null); },
                defaultDate: $.query.get("day"),
                prevText: "",
                nextText: ""
            });
        }
        else {
            $("#datepicker").datepicker({ onSelect: function (date) { calendarFilterRedirect(null, null, null, date, null, null, null, null); },
                prevText: "",
                nextText: ""
            });
        }

        // Initialize Datepicker and Bind It's onSelect Method
        $("#datepicker").datepicker({ onSelect: function (date) { calendarFilterRedirect(null, null, "day", date, null, null, null, null); } });

        // Bind Calendar Type's onChange Method
        $("select.caltype").change(function () { calendarFilterRedirect($(this).val(), null, null, null, null, null, null, null); });

        // Bind Centers and Institutes onChange Method
        $("select.filtercenters").change(function () { if ($(this).val() != "0") calendarFilterRedirect(null, null, null, null, $(this).val(), null, null, null); });

        // Bind Academic Programs Parent onChange Method
        $("select.filterprogramsparent").change(function () { if ($(this).val() != "0") calendarFilterRedirect(null, null, null, null, null, $(this).val(), null, null); });

        // Bind Academic Programs Child onChange Method
        $("select.filterprogramschild").change(function () { if ($(this).val() != "0") calendarFilterRedirect(null, null, null, null, null, null, $(this).val(), null); });

        //    // Bind Event Series onChange Method
        $("select.filterseries").change(function () { if ($(this).val() != "0") calendarFilterRedirect(null, null, null, null, null, null, null, $(this).val()); });

        // Initialize Format Picker Buttonset and Bind onClick Method
        $("#calendar-format-picker").buttonset();
        $("#calendar-format-picker input").click(function () {
            $("#calendar-format-picker input").each(function () { $(this).removeAttr("checked").button("refresh"); });
            $(this).attr("checked", "checked").button("refresh");
            calendarFilterRedirect(null, $(this).attr("rel"), null, null, null, null, null, null);
        });

        // Initialize Scope Picker Buttonset and Bind onClick Method
        $("#calendar-scope-picker").buttonset();
        $("#calendar-scope-picker input").click(function () {
            $("#calendar-scope-picker input").each(function () { $(this).removeAttr("checked").button("refresh"); });
            $(this).attr("checked", "checked").button("refresh");
            calendarFilterRedirect(null, null, $(this).attr("rel"), null, null, null, null, null);
        });

        // Moved from script.js b/c calendar class isn't applied to article#main-content until just above
        // initialize calendar popups, but onlfy if tryCalendarSetup=true (set in script.js)
        if (tryCalendarSetup) {
            if ($('article#main-content').hasClass("calendar")) { // ... but only if there is one
                cuny.calendar.init();
            }
        }
    }

    // Bind Policy and Procedures Category Filter onChange Method
    //$("select#calendar-type").change(function () { if ($(this).val() != "0") PolicyAndProcedureRedirect($(this).val()); });

    // Bind onchange event for left nav drop down
    $("div.navddl").find("select").selectmenu({ change: function (e, object) {
        if ($(this).hasClass("filtercenters"))
            calendarFilterRedirect(null, null, null, null, $(this).val(), null, null, null);
        else if ($(this).hasClass("filterprogramsparent"))
            calendarFilterRedirect(null, null, null, null, null, $(this).val(), null, null);
        else if ($(this).hasClass("filterprogramschild"))
            calendarFilterRedirect(null, null, null, null, null, null, $(this).val(), null);
        else if ($(this).hasClass("filterseries"))
            calendarFilterRedirect(null, null, null, null, null, null, null, $(this).val());
        else {
            if (object.value != null && object.value != undefined && object.value != "")
                location.href = object.value;
        }
    }
    });

    if (ajaxConfig.length > 0)
        loadAjax(ajaxConfig[0], ajaxConfig[1], ajaxConfig[2]);

    if (runDirectorySearchSetup == "true") {

        LoadFullDirectory();

        $("#RightColumn").css("width", "0 !important").removeClass("cunyCols").removeClass("rightCol");
        $("div.content-filter").attr("style", "margin:10px !important");
        $("article#main-content").attr("style", "width: 720px; float: right; !important");
        $("div.bottom div#directory_length label select").attr("style", "width:50px !important");

        $("#directory").delegate("tr.odd", "click", function () {
            var rowId = $(this).attr("id");
            location.href = '/Directory-Search/Detail/?id=' + rowId;
        });

        $("#directory").delegate("tr.even", "click", function () {
            var rowId = $(this).attr("id");
            location.href = '/Directory-Search/Detail/?id=' + rowId;
        });

        $("#btnSearch").click(function () {
            LoadFilteredDirectory();
        });

        $("#btnReset").click(function () {
            location.href = '/directory-search';
        });

        $("input#txtSearch").keypress(function (e) {
            if (e.which == 13)
                LoadFilteredDirectory();
        });
    }

    $(".carousel-wrapper").each(function (e) {
        var carouselRel = $(this).attr("rel");
        var carouselId = $(this).attr("id");

        var currentInnerStyle = $(this).find(".slidesjs-container").attr("style");
        $(this).find(".slidesjs-container").attr("style", "height:" + $(this).height() + " !important; " + currentInnerStyle);

        if (carouselRel) {
            $('#' + carouselId).slidesjs({
                width: $(this).width(),
                height: $(this).height(),
                play: {
                    auto: true,
                    interval: carouselRel
                }
            });
        }
        else {
            $('#' + carouselId).slidesjs({
                width: $(this).width(),
                height: $(this).height()
            });

        }
    });

    $(".carousel-wrapper").each(function () {
        $(this).addClass("normalize-height");
        $(this).find(".slidesjs-controlwrapper").attr("style", "top: " + ($(this).find(".slidesjs-container").height() + 2) + "px !important;");
    });
});


function loadAjax(loadType, htmlWrapper, inputParameter) {
    $("#" + htmlWrapper).html(loaderImage);
    
    switch(loadType) {
        case "program_requirement":
            $("#" + htmlWrapper).load(siteUrl + "PageMethods.ashx?type=" + loadType + "&docid=" + inputParameter, 
                null, 
                function(responseText){ responseText });
            break;
    }
}

function calendarFilterRedirect(typeSelection, formatSelection, scopeSelection, daySelection, filter1Selection, filter2Selection, filter3Selection, filter4Selection) 
{
    var queryString = "";

    if (typeSelection != null)
        queryString += "type=" + typeSelection;
    else if ($.query.get("type") != '')
        queryString += "type=" + $.query.get("type");

    if (formatSelection != null) {
        if (queryString.length > 0) queryString += "&";
        queryString += "format=" + formatSelection;
    }
    else if ($.query.get("format") != '') {
        if (queryString.length > 0) queryString += "&";
        queryString += "format=" + $.query.get("format");
    }

    if (scopeSelection != null) {
        if (queryString.length > 0) queryString += "&";
        queryString += "scope=" + scopeSelection;
    }
    else if ($.query.get("scope") != '') {
        if (queryString.length > 0) queryString += "&";
        queryString += "scope=" + $.query.get("scope");
    }

    if (daySelection != null) {
        if (queryString.length > 0) queryString += "&";
        queryString += "day=" + daySelection;
    }
    else if ($.query.get("day") != '') {
        if (queryString.length > 0) queryString += "&";
        queryString += "day=" + $.query.get("day");
    }
    
    if (filter1Selection != null) {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter1=" + filter1Selection;
    }
    else if ($.query.get("filter1") != '') {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter1=" + $.query.get("filter1");
    }

    if (filter2Selection != null) {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter2=" + filter2Selection;
    }
    else if ($.query.get("filter2") != '') {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter2=" + $.query.get("filter2");
    }

    if (filter3Selection != null) {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter3=" + filter3Selection;
    }
    else if ($.query.get("filter3") != '') {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter3=" + $.query.get("filter3");
    }

    if (filter4Selection != null) {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter4=" + filter4Selection;
    }
    else if ($.query.get("filter4") != '') {
        if (queryString.length > 0) queryString += "&";
        queryString += "filter4=" + $.query.get("filter4");
    }

    if (queryString.length > 0) queryString = ("?" + queryString);

    var pagePath = window.location.href.split("?");

    if (pagePath[0] == "/")
        location.href = '/default.aspx' + queryString;
    else
        location.href = pagePath[0] + queryString;
}

function PolicyAndProcedureRedirect(policyFilter) {

    var pagePath = window.location.href.split("?");

    if (pagePath[0] == "/")
        location.href = "/default.aspx?CategoryFilter=" + policyFilter;
    else
        location.href = pagePath[0] + "?CategoryFilter=" + policyFilter;
}

function clearNextPrevButtons() {
    $(".ui-icon-circle-triangle-w").html("");
    $(".ui-icon-circle-triangle-e").html("");
}

$("#main-search-button").click(function (event) {
    search();
});

$("#main-search-box").keyup(function (event) {
    document.forms[0].action = "/search?searchtext=" + $("#main-search-box").val();
    if (event.keyCode == 13)
        search();
});

// see: http://devnet.kentico.com/Forums.aspx?forumid=13&threadid=1427
function search() {
    document.forms[0].action = "/search?searchtext=" + $("#main-search-box").val();
    location.href = "/search?searchtext=" + $("#main-search-box").val();
}

function textonly() {
    window.open('/shared/templates/textonly.aspx?url=' + window.location);
}

//alert(window.location.href);


function LoadFullDirectory() {
    $.ajax({
        type: "POST",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        url: "/shared/webmethods/GetPeopleDirectory.asmx/GetAll",
        data: "{ }",
        success: function (result) {
            var people = (typeof result.d) == 'string' ? eval('(' + result.d + ')') : result.d;

            //$("#directory").dataTable().fnDestroy();
            BuildTableBody(people);

            $('#directory').dataTable({
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "bAutoWidth": true,
                "bJQueryUI": false,
                "bLengthChange": true,
                "bInfo": true,
                "bLoading": true,
                "bProcessing": true,
                "sDom": 'f<"top"i<"clear">>rt<"bottom"lp><"clear">',
                "aaSorting": [[0, "asc"]],
                "iDisplayStart": 0,
                "oLanguage": {
                    "sEmptyTable": "No results found"
                }
            });
        },
        error: function (xhr, ajaxOptions) {
            alert(xhr.status);
        }
    });
}

function LoadFilteredDirectory() {
    var employeeSelection = $("#ddlEmployeeTypeFilter").val();
    var departmentSelection = $("#ddlDepartment").val();

    // Get Current Page Size
    $("#hidPageSize").text($("#directory_length select").val());
    
    // Get Current Sort Column and Type
    $("#directory th").each(function () {
        if ($(this).hasClass("sorting_desc") || $(this).hasClass("sorting_asc")) {
            var sortType = '';

            if ($(this).hasClass("sorting_asc"))
                sortType = "asc";
            else if ($(this).hasClass("sorting_desc"))
                sortType = "desc";

            $("#hidSortIndex").text($(this).index());
            $("#hidSortType").text(sortType);
        }
    });

    // Get Current Sort Page
    $("#directory_paginate span a").each(function () {
        if ($(this).hasClass("paginate_active")) {
            $("#hidCurrentPage").text($(this).index());
        }
    });

    // Get Current Search Term
    $("#hidSearchString").text($("input#txtSearch").val());

    // Make the Call
    $.ajax({
        type: "POST",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        url: "/shared/webmethods/GetPeopleDirectory.asmx/GetByType",
        data: GetSelectionJSON(employeeSelection, departmentSelection),
        success: function (result) {
            var people = (typeof result.d) == 'string' ? eval('(' + result.d + ')') : result.d;
            var sortIndex = $("#hidSortIndex").text();
            var sortType = $("#hidSortType").text();
            var startPage = $("#hidCurrentPage").text();
            var searchString = $("#hidSearchString").text();

            if (sortIndex == undefined || sortIndex == null || sortIndex == "")
                sortIndex = 0;

            if (sortType == undefined || sortType == null || sortType == '')
                sortType = "asc";

            if (searchString == undefined || searchString == null)
                searchString = "";

            $("#directory").dataTable().fnDestroy();
            BuildTableBody(people);

            $('#directory').dataTable({
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "bAutoWidth": true,
                "bJQueryUI": false,
                "bLengthChange": true,
                "bInfo": true,
                "bLoading": true,
                "bProcessing": true,
                //"iDisplayLength": $("#hidPageSize").text(),
                "sDom": 'f<"top"i<"clear">>rt<"bottom"lp><"clear">',
                "aaSorting": [[sortIndex, sortType]],
                "iDisplayStart": startPage,
                "oSearch": { "sSearch": searchString },
                "oLanguage": {
                    "sEmptyTable": "No results found"
                }
            });
        },
        error: function (xhr, ajaxOptions) {
            alert(xhr.status);
        }
    });
}

function BuildTableBody(people) {

    var tableRows = '';

    for (var i = 0; i < people.length; i++) {
        tableRows += ('<tr id="' + people[i].ItemGuid + '"><td>' + people[i].DisplayName + '</td>');
        tableRows += ('<td><a href="mailto:' + people[i].Email + '">' + people[i].Email + '</a></td>');
        tableRows += ('<td>' + people[i].PublishedOfficePhone + '</td>');
        tableRows += ('<td>' + people[i].Organization + '</td></tr>');
    }

    if (tableRows != '')
        tableRows = ('<thead><tr><th width="25%" style="border-left:none;">Name</th><th width="25%">Email</th><th width="15%">Phone</th><th width="15%">Organization</th></tr></thead><tbody>' + tableRows + '</tbody>');
    else
        tableRows = ('<thead><tr><th width="25%" style="border-left:none;">Name</th><th width="25%">Email</th><th width="15%">Phone</th><th width="15%">Organization</th></tr></thead><tbody></tbody>');

    $("#directory").html(tableRows);
}

function GetSelectionJSON(EmployeeType, Department) {
    var returnFilter = '{ "EmployeeTypeSelection" : "' + EmployeeType + '", "Department" : "' + Department + '" }';
    //alert(returnFilter);
    return returnFilter;
}