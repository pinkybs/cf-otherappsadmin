/*
----------------------------------------------
add app site
Created Date: 2009/09/11
Author: hwq

----------------------------------------------
*/
//define default page size
var CONST_DEFAULT_PAGE_SIZE = 10;

$(document).ready(function() {
    changePage($('#index').val());
});

function changePage(pageIndex)
{
        //ajax show list request
        $('#index').val(pageIndex);
        
        jQuery.ajax({
            type : "POST",
            url : UrlConfig.BaseUrl + '/ajax/log/getbasicinfo',
            dataType: "json",
            data : {
                    pageIndex : pageIndex,
                    pageSize : CONST_DEFAULT_PAGE_SIZE,
                    startDate : $("#datepickerStart").val(),
                    endDate : $("#datepickerEnd").val(),
                    mixiAppId : $("#mixiAppId").val()
                   },
            success : function(response) { renderResults(response);}
        });
}


/**
 * response from site view ajax request
 * @param  object response
 * @return void
 */
function renderResults(response)
{    
        var responseObject = response;
        var cntTotal = 0;
        var cntCurrent = 0;
        //show response array data to list table
        if (responseObject && responseObject.info && responseObject.info.length > 0) {            
            var html = showInfo($('#type').val(),responseObject.info);
            var nav = showPagerNav(responseObject.count, Number($('#index').val()), CONST_DEFAULT_PAGE_SIZE,10, 'changePage');
            cntTotal = responseObject.count;
            cntCurrent = CONST_DEFAULT_PAGE_SIZE > cntTotal ? cntTotal : CONST_DEFAULT_PAGE_SIZE
            $('div#divList').html(html + nav);
        }
        else {
            //for after edit , can not find the page' record when the page has only one record 
            if (parseInt($('#index').val()) > 1) {
                changePageAction(parseInt($('#index').val()) - 1);
            }
            else {
               $('div#divList').html('まだ何もありません。');
            }
        }

        $('#lblTotalCount').html(cntTotal);
        $('#lblPageCount').html(cntCurrent>cntTotal ? cntTotal : cntCurrent);
}

/**
 * show site table
 * @param  object array
 * @return string
 */
function showInfo(type,array)
{
    //concat html tags to array data
    var html = '';                
                    
    html += '<table id="login" class="mytable" cellspacing="0" summary="The technical specifications of the Apple '
            + 'PowerMac G5 series">'
            + '<tr>'
            + '<th scope="col">ランク</th>'
            + '<th scope="col">年月日</th>'
            + '<th scope="col">';
	if(type == 'login') {
	    html += '登録者数</th>';
	} else if(type == 'daily'){
        html += '利用者数</th>';
	} else if(type == 'feed'){
	    html += '配信フィード数</th>';
    } else {
        html += '</th>';
    }
    html += '</tr>';

    //for each row data
    for (var i = 0 ; i < array.length ; i++) {   
        var cssClass = '';
            if (1 == i % 2) {
                cssClass = 'overlight';
            }     
        html += '<tr class="' + cssClass + '">'
              + '    <td class="row">' + (i + 1) + '</td>'
              + '    <td class="row">' + array[i].report_date + '</td>';   
              
	    if(type == 'login') {
	        html += '<td class="row a">' + array[i].login_count + '</td>';
	    } else if(type == 'daily'){
	        html += '<td class="row a">' + array[i].daily_login_count + '</td>';
	    } else if(type == 'feed'){
	        html += '<td class="row b">' + array[i].feed_count + '</td>';
	    } else {
	        html += '<td class="row b"></th>';
	    }           
	    html += '</tr>';
    }
    
    html += '</table>';
    
    return html;
}