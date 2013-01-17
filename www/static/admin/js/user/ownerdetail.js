/*
----------------------------------------------
app site list 
Created Date: 2009/09/11
Author: hwq

----------------------------------------------
*/

//define default page size
var CONST_DEFAULT_PAGE_SIZE = 10;
(function($) {

$().ready(function() {
    $.ownerdetail.changePage('1');
    $("#btnBack").click(function (){
        $('#frmBack').submit(); 
        return false;
    })
});

$.ownerdetail = {
    /**
       * submit to login action
       * @param  null
       * @return void
       */
    changePage : function(pageIndex)
    {
        //ajax show list request
        $('#appPageIndex').val(pageIndex);
        
        jQuery.ajax({
            type : "POST",
            url : UrlConfig.BaseUrl + '/ajax/manage/applistbyowner',
            dataType: "json",
            data : {
                    pageIndex : pageIndex,
                    pageSize : CONST_DEFAULT_PAGE_SIZE,
                    oid : $('#hidUid').val()
                   },
            success : function(response) { $.ownerdetail.renderResults(response);}
        });
    },
    
	/**
	 * response from site view ajax request
	 * @param  object response
	 * @return void
	 */
	renderResults : function(response)
	{    
            var responseObject = response;
            var cntTotal = 0;
            var cntCurrent = 0;
            //show response array data to list table
            if (responseObject && responseObject.info && responseObject.info.length > 0) {            
                var html = $.ownerdetail.showInfo(responseObject.info);
                var nav = showPagerNav(responseObject.count, Number($('#pageIndex').val()), CONST_DEFAULT_PAGE_SIZE,10, '$.ownerdetail.changePage');
                cntTotal = responseObject.count;
                cntCurrent = CONST_DEFAULT_PAGE_SIZE > cntTotal ? cntTotal : CONST_DEFAULT_PAGE_SIZE
                $('div#divList').html(nav + html + nav);
            }
            else {
                //for after edit , can not find the page' record when the page has only one record 
                if (parseInt($('#appPageIndex').val()) > 1) {
                    $.ownerdetail.changePage(parseInt($('#appPageIndex').val()) - 1);
                }
                else {
                   $('div#divList').html('まだ何もありません。');
                }
            }
                //$j('input#pageIndex').val(page);
            $('#lblTotalCount').html(cntTotal);
            $('#lblPageCount').html(cntCurrent>cntTotal ? cntTotal : cntCurrent);
	},
	
	/**
	 * show site table
	 * @param  object array
	 * @return string
	 */
	showInfo : function(array)
	{
	    //concat html tags to array data
	    var html = '';
	    
	    html += '<table width="100%" cellpadding="0" cellspacing="0" border="0" id="dataGrid" style="clear: both;">'
	            + '<thead>'
	            + '<tr class="head">'
	            + '<th width="5%">ID</th>'
	            + '<th width="10%">アプリケーション名</th>'
	            + '<th width="10%">カテゴリー</th>'
	            + '<th width="12%">登録日</th>'
	            + '</tr>'
	            + '</thead>'
	            + '<tbody>';
	
	    //for each row data
	    for (var i = 0 ; i < array.length ; i++) {        
	        var cssClass = 'a';
	        if (1 == i % 2) {
	            cssClass = 'b';
	        }
	        
	        html += '<tr class="' + cssClass + '">'
	              + '    <td>' + array[i].app_id + '</td>'
	              + '    <td>' + array[i].app_name + '</td>';
	        if (array[i].category_name == 'null' || array[i].category_name == null) {
                html += '    <td>-</td>';
            } else {
	            html +=  '    <td>' + array[i].category_name + '</td>';
	        }
            html +=  '    <td>' + array[i].create_time.substring(0,10) + '</td>'                  
	              + '</tr>';
	    }
	    
	    html += '</tbody>'
	            + '</table>';
	    
	    return html;
	}

    };
    
})(jQuery);