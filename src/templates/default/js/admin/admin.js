//jquery fx begin
$(document).ready(function()
{

	var activemenu = $('#page-header').attr('rel');
	var activemenuselector = $('#' + activemenu);
	if (activemenuselector.length) {
		activemenuselector.addClass('active');
	}


	////////////////////////////////
	// LISTING FORM MANIPULATION
	if ($('.formfilter-enable').length) {
		formfilterRefresh();

		// Bind to StateChange Event
	    History.Adapter.bind(window,'statechange',function(event){ // Note: We are using statechange instead of popstate
	        var state = History.getState(); // Note: We are using History.getState() instead of event.state

	        //If this is state from form filter
	        if (state.data.statetype == 'formfilter') {

	        	$('#page').val(state.data.page);
	        	$('#sortby').val(state.data.sortby);
	        	$('#sorttype').val(state.data.sorttype);
	        	$('#fkeywordfilter').val(state.data.keyword);

	        	//Clear current filter tag
	        	$('.filter-tags ul').html('');
	        	if (state.data.filtertaglist.length > 0) {
	        		var html = '';
	        		for (var i = 0; i < state.data.filtertaglist.length; i++) {

	        			var html = '<li class="tag closable filtertag" data-filtername="'+state.data.filtertaglist[i].name
							+ '" data-filtervalue="' + encodeURIComponent(state.data.filtertaglist[i].value)+'" id="filtertag_'+state.data.filtertaglist[i].name+'"><span><em>'
							+ $('#ffilter option[value="'+state.data.filtertaglist[i].name+'"]').text() +' is equal to <b>'
							+ state.data.filtertaglist[i].text +'</b></em><span class="close" onclick="$(\'#filtertag_'
							+ state.data.filtertaglist[i].name + '\').remove();$(\'#page\').val(\'1\');formfilterRefresh();"><i class="fa fa-times"></i></span></span></li>';

						$('.filter-tags ul').append(html);
	        		}
	        	}

	        	formfilterRefreshTrigger();
	        }
	    });
	}

	$('.dropdown-filter-box input, .dropdown-filter-box select, .dropdown-filter-box label').click(function(e) {
        e.stopPropagation();
    });


	// Check all checkboxes when the one in a table head is checked:
	$('.check-all').click(
		function(){
			$(".checkbox-item").attr('checked', $(this).is(':checked'));
			tablerowCheckClick();
		}
	);


	if ($('.checkbox-item').length) {
		tablerowCheckClick();
	}

	var timer;
    $("#fkeywordfilter").on('keyup',function() {
        timer && clearTimeout(timer);
        timer = setTimeout(formfilterRefresh, 300);
    });

    $('.sidebar-nav h2').bind('click', function(){
    	$('.h2active').removeClass('h2active');
    	$(this).addClass('h2active');
    	$('.nav.nav-list').removeAttr('style');
    	$.cookie("navActive", $(this).next().attr('id') , { expires: 60 });
    	$(this).next().slideToggle('fast');
    })

	// END FORM MANIPULATION
	////////////////////////////



});

function scrollTo(selector)
{
	var target = $('' + selector);
	if (target.length) {
		var top = target.offset().top;
		$('html,body').animate({scrollTop: top}, 1000);
		return false;
	}
}


//use this function to keep connection (prevent login session expired for contents manipulation) alive
function ping()
{
	var nulltimestamp = new Date().getTime();
	var t = setTimeout("ping()", 1000*60*5); //5 minute
    $.ajax({
		 type: "GET",
		 url: rooturl_admin + 'null/index/timestamp/' + nulltimestamp,
		 dataType: "xml",
		 success: function(xml) {}
	 }); //close $.ajax
}


// JavaScript Document
function delm(theURL)
{
	bootbox.confirm(delConfirm, function(confirmed){
		if(confirmed)
			window.location.href=theURL;
	})
}


// JavaScript Document
function delajax(theURL, element, btnelement)
{
	//Show indicator
	$(element).css('background-color', '#eeeeee');
	$(element + ' ' + btnelement).after('<img src="'+imageDir+'admin/spinner.gif" class="load-indicator" />').hide();
	$(element + ' ' + btnelement).popover('hide');

	//Start Ajax request
	$.ajax({
	   	type: "POST",
	   	dataType: 'xml',
	   	url: theURL,
	   	error: function(){
	   		$(element).find('.load-indicator').remove();
	   		$(element + ' ' + btnelement).show();
	   		$(element).css('background-color', 'none');
	   	},
	   	success: function(xml){
	   		var success = $(xml).find('success').text();

	   		if (success == '1') {
	   			$(element + ' td').css('background-color', '#ffff99').fadeOut();

	   			//decrease count
   				if ($('.delete-decrease-count').length) {
	   				$('.delete-decrease-count').text(parseInt($('.delete-decrease-count').text().replace(',', '')) - 1);
	   			}

	   		} else {
	   			var message = $(xml).find('message').text();
	   			alert(message);

	   			$(element).find('.load-indicator').remove();
	   			$(element + ' ' + btnelement).show();
	   			$(element).css('background-color', 'none');
	   		}
	   	}
	});
}


function formbulksubmit(theURL)
{
	var bulkaction = $('#fbulkaction').val();
	var numberCheckedItem = $('.checkbox-item:checked:enabled').length;

	if (numberCheckedItem == 0) {
		bootbox.alert($('#bulkItemNoSelected').val());
	} else if (bulkaction == '') {
		bootbox.alert($('#bulkActionInvalidWarn').val());
	} else {
		bootbox.confirm(delConfirm, function(confirmed){
			if (confirmed) {

				var selecteditems = [];
				$('.checkbox-item:checked:enabled').each(function(){
					selecteditems.push($(this).val());
				});

				//Show indicator
				$('.bulk-actions').after('<img src="'+imageDir+'admin/spinner.gif" class="load-indicator" />').hide();

				//Start Ajax request
				$.ajax({
				   	type: "POST",
				   	dataType: 'xml',
				   	data: 'ids=' + selecteditems.join(',') + '&bulkaction=' + bulkaction,
				   	url: theURL + '?token=' + $('#token').val(),
				   	error: function(){
				   		$('.bulk-actions').parent().find('.load-indicator').remove();
						$('.bulk-actions').show();
				   	},
				   	success: function(xml){
				   		var success = $(xml).find('success').text();

				   		if (success == '1') {

				   			$(xml).find('successitem').each(function(){
				   				$('#rowid-' + $(this).text() + ' td').css('background-color', '#ffff99').fadeOut();
				   			});

				   			//decrease count
			   				if ($('.delete-decrease-count').length) {
				   				$('.delete-decrease-count').text(parseInt($('.delete-decrease-count').text().replace(',', '')) - $(xml).find('successitem').length);
				   			}

				   		} else {
				   			var message = $(xml).find('message').text();
				   			alert(message);
				   		}

				   		$('.bulk-actions').parent().find('.load-indicator').remove();
						$('.bulk-actions').show();
				   	}
				});
			}
		})
	}
}


function tablerowCheckClick()
{
	var numberCheckedItem = $('.checkbox-item:checked:enabled').length;
	if (numberCheckedItem > 0) {
		$('.bulk-actions').fadeIn();

	} else {
		$('.bulk-actions').fadeOut();

	}


	$('.checkbox-item:checked:enabled').each(function(){
		if (!$(this).parent().parent().hasClass('is-prepareremove')) {
			$(this).parent().parent().addClass('is-prepareremove');
		}
	});

	$('.checkbox-item:not(:checked)').each(function(){
		if ($(this).parent().parent().hasClass('is-prepareremove')) {
			$(this).parent().parent().removeClass('is-prepareremove');
		}
	});
}


function formfilterToggle()
{
	//hide all other filter_more
	$('.filter_more').hide();

	var selectedfilter = $('#ffilter').val();
	if (selectedfilter != "") {
		$('#filter_' + selectedfilter + '_more').show().focus();
	}
}


function formfilterAdd()
{
	var selectedfilter = $('#ffilter').val();
	var selectedfilterMore = $('#filter_' + selectedfilter + '_more').val();
	var selectedfilterMoreTag = $('#filter_' + selectedfilter + '_more').prop('tagName').toLowerCase();

	//Require more data to filter
	if (selectedfilterMore == '') {
		$('#filter_' + selectedfilter + '_more').focus();
	} else {

		var filterData = '';
		var filterText = '';

		if (selectedfilterMoreTag == 'select') {
			filterData = selectedfilterMore;
			filterText = $('#filter_' + selectedfilter + '_more option:selected').text();
		} else {
			filterData = filterText = selectedfilterMore;
		}

		//Check xem da co filter tag chua
		if ($('#filtertag_' + selectedfilter).length) {
			//Update current Filter tag
			$('#filtertag_' + selectedfilter).attr('data-filtervalue', encodeURIComponent(filterData));
			$('#filtertag_' + selectedfilter + ' span em b').text(filterText);
		} else {
			//Add new Filter tag
			var html = '<li class="tag closable filtertag" data-filtername="'+selectedfilter
				+ '" data-filtervalue="' + encodeURIComponent(filterData)+'" id="filtertag_'+selectedfilter+'"><span><em>'
				+ $('#ffilter option:selected').text() +' is equal to <b>'
				+ filterText +'</b></em><span class="close" onclick="$(\'#filtertag_'
				+ selectedfilter + '\').remove();$(\'#page\').val(\'1\');formfilterRefresh();"><i class="fa fa-times"></i></span></span></li>';

			$('.filter-tags ul').append(html);
		}

		$('.dropdown-filter-box').dropdown('toggle');
		$('#page').val('1');
		formfilterRefresh();
	}
}

function formfilterPaging(page)
{
	$('#page').val(page);
	formfilterRefresh();
}

function formfilterRefresh()
{
	//Store Data for click Back Button (using History.pushState)
	var stateData = {
		'statetype' : 'formfilter',
		'sortby' : $('#sortby').val(),
		'sorttype' : $('#sorttype').val(),
		'page' : $('#page').val(),
		'keyword' : $('#fkeywordfilter').val(),
		'filtertaglist' : []
	};

	//request ajax
	var querystring = '';

	if ($('#sortby').val() != '') {
		if (querystring != '') {
			querystring += '&';
		}
		querystring += 'sortby=' + $('#sortby').val();
	}

	if ($('#sorttype').val() != '') {
		if (querystring != '') {
			querystring += '&';
		}
		querystring += 'sorttype=' + $('#sorttype').val();
	}

	if (parseInt($('#page').val()) > 1) {
		if (querystring != '') {
			querystring += '&';
		}
		querystring += 'page=' + $('#page').val();
	}

	$('.filtertag').each(function(){
		querystring += '&' + $(this).attr('data-filtername') + '=' + $(this).attr('data-filtervalue');
		stateData.filtertaglist.push({'name': $(this).attr('data-filtername'), 'value' : $(this).attr('data-filtervalue'), 'text' : $(this).find('em b').text()});
	});

	if ($('#fkeywordfilter').val() != "") {
		querystring += '&keyword=' + encodeURIComponent($('#fkeywordfilter').val());
	}


	//////////////////////////
	//Update URL (Using history.pushState)
	if (querystring == '') {
		History.pushState(stateData, null, $('#pageurl').val());
	} else {
		History.pushState(stateData, null, '?' + querystring);
	}

	if (formfilterRefreshTriggerCount == 0) {
		formfilterRefreshTrigger();
		formfilterRefreshTriggerCount++;
	}
}

var formfilterRefreshTriggerCount = 0;
function formfilterRefreshTrigger()
{
	//request ajax
	var querystring = '';

	if ($('#sortby').val() != '') {
		if (querystring != '') {
			querystring += '&';
		}
		querystring += 'sortby=' + $('#sortby').val();
	}

	if ($('#sorttype').val() != '') {
		if (querystring != '') {
			querystring += '&';
		}
		querystring += 'sorttype=' + $('#sorttype').val();
	}

	if (parseInt($('#page').val()) > 1) {
		if (querystring != '') {
			querystring += '&';
		}
		querystring += 'page=' + $('#page').val();
	}

	$('.filtertag').each(function(){
		querystring += '&' + $(this).attr('data-filtername') + '=' + $(this).attr('data-filtervalue');
	});

	if ($('#fkeywordfilter').val() != "") {
		querystring += '&keyword=' + encodeURIComponent($('#fkeywordfilter').val());
	}

	//Show ajax indicator
	$('#fkeywordfilter').addClass('filter-indicator-loading');

	//Start Ajax request
	$.ajax({
	   	type: "POST",
	   	dataType: 'json',
	   	data: querystring,
	   	url: $('#filterurl').val(),
	   	error: function(){
	   		$('#fkeywordfilter').removeClass('filter-indicator-loading');
	   	},
	   	success: function(json){

	   		$('.delete-decrease-count').text(json.total);

	   		if (json.total > 0) {

	   			//update token
	   			$('#token').val(json.token);
	   			$('#sortby').val(json.sortby);
	   			$('#sorttype').val(json.sorttype);


	   			var html = '';

	   			for (var i = 0; i < json.items.length; i++) {
	   				var item = json.items[i];
	   				html += '<tr id="rowid-'+item[json.primaryproperty]+'">';
	   				html += '  <td><input type="checkbox" class="checkbox-item" value="'+item[json.primaryproperty]+'" onchange="tablerowCheckClick()"/></td>';

	   				//loop through table header to get display column
	   				$('.formfilter-enable .formfilterth').each(function(){
	   					html += '<td>' + item[$(this).attr('id')] + '</td>';
	   				});

	   				html += '  <td><a href="'+json['editurlprefix']+item[json.primaryproperty]+'" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> Edit</a> &nbsp; <a title="Delete ?" data-removeurl="'+json['deleteurlprefix']+item[json.primaryproperty] + '?token=' + json.token + '" data-rowid="rowid-'+item[json.primaryproperty]+'" href="javascript:void(0);" class="btn btn-xs btn-default remove-record-button"><i class="fa fa-trash-o"></i></a></td>';
	   				html += '</tr>';
	   			}

	   			$('.formfilter-empty').hide();
	   			$('.formfilter-enable table tbody').html(html);
	   			$('.formfilter-enable table').show();

	   			//Remove via ajax for generated controller index page
	   			$('.remove-record-button').popover({
	   				html : true,
	   				placement : 'left',
	   				content : function(){
	   					return '<div class="remove-record-button-popoverwrapper"><a href="javascript:delajax(\'' + $(this).attr('data-removeurl') + '\', \'#' + $(this).attr('data-rowid') + '\', \'.remove-record-button\')" class="btn btn-xs btn-danger">YES</a> <a class="btn btn-xs btn-default" href="javascript:$(\'#' + $(this).attr('data-rowid') + ' .remove-record-button\').popover(\'toggle\');" >NO</a></div>';
	   				}
	   			});

	   			///////////////////
	   			// SORTABLE BINDING
	   			$('.formfilterth.is-sortable').each(function(){

	   				$(this).unbind('click');

	   				var plaintext = $(this).text();

	   				//remove previous link
	   				$(this).find('a').remove();


	   				if ($(this).attr('id') == json.sortby) {
	   					var html = '<span>' + $(this).text();

	   					if (json.sorttype == 'ASC') {
	   						html += '<i class="fa fa-caret-up"></i>';
	   					} else {
	   						html += '<i class="fa fa-caret-down"></i>';
	   					}

	   					html += '</span>';


	   					$(this).html(html);
	   					$(this).bind('click', function(){
	   						$('#sortby').val($(this).attr('id'));
	   						$('#sorttype').val(json.sorttype == 'ASC' ? 'DESC' : 'ASC');
	   						$('#page').val('1');
	   						formfilterRefresh();
	   					});

	   				} else {
	   					$(this).html($(this).text());
	   					$(this).bind('click', function(){
	   						$('#sortby').val($(this).attr('id'));
	   						$('#sorttype').val('DESC');
	   						$('#page').val('1');
	   						formfilterRefresh();
	   					});
	   				}
	   			});


	   			///////////////////
	   			// PAGINATION
	   			var options = {
	   				size: 'normal',
		            currentPage: json.page,
		            totalPages: json.totalpage,
		            shouldShowPage:function(type, page, current){
		                switch(type)
		                {
		                    case "first":
		                    case "last":
		                        return false;
		                    default:
		                        return true;
		                }
		            },
		            itemTexts: function (type, page, current) {
	                    switch (type) {
		                    case "first":
		                        return "First";
		                    case "prev":
		                        return "<i class=\"fa fa-chevron-left\"></i>";
		                    case "next":
		                        return "<i class=\"fa fa-chevron-right\"></i>";
		                    case "last":
		                        return "Last";
		                    case "page":
		                        return page;
	                    }
	                },
	                tooltipTitles: function (type, page, current) {
                        switch (type) {
                            case "first":
                                return "First page";
                            case "prev":
                                return "Previous page";
                            case "next":
                                return "Next page";
                            case "last":
                                return "Last page";
                            case "page":
                                return "go to page " + page;
                        }
                    },
		            onPageChanged: function(event, oldPage, newPage) {
		            	$('#page').val(newPage);
		            	formfilterRefresh();
		            }
		        }
		        //reset paginatorbox
		        $('.formfilterpaginatorwrapper').html('<div></div>');
	   			$('.formfilterpaginatorwrapper div').bootstrapPaginator(options);

	   		} else {
	   			//No record found
	   			$('.formfilter-enable table').hide();
	   			$('.formfilter-empty').show();
	   		}

			$('#fkeywordfilter').removeClass('filter-indicator-loading');
	   	}
	});
}


function resetpasswordHandler(url)
{
	bootbox.confirm(delConfirm, function(confirmed){
		if (confirmed) {

			//show indicator
			var currentbuttonLabel = $('#resetpasswordbutton').text();
			$('#resetpasswordbutton').html('<i class="fa fa-spinner"></i> Processing...');

			//ajax request
			$.ajax({
		   	type: "POST",
		   	dataType: 'xml',
		   	url: url,
		   	error: function(){
		   		alert('request error');
		   		$('#resetpasswordbutton').text(currentbuttonLabel);
		   	},
		   	success: function(xml){
		   		var success = $(xml).find('success').text();
		   		var message = $(xml).find('message').text();

		   		if (success == '1') {
		   			bootbox.alert(message);
		   		} else {
		   			bootbox.alert(message);
		   		}

		   		$('#resetpasswordbutton').text(currentbuttonLabel);
		   	}
		    });

		}
	});
}

