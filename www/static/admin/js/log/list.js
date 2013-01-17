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
    $.listsite.changePage($('#pageIndex').val());
    $("#btnSearch").click(function (){$.listsite.search();})
    $("#btnClear").click(function (){$.listsite.clear();})
});

$.listsite = {
    
	/**
	 * search site by filter
	 * @param null
	 * @return void
	 */
	search : function()
	{
	    $('#hidSrhName').val($('#txtSrhName').val()) ;
	    $('#hidSrhCate').val($('#selSrhCate').val()) ;
	    $('#hidSrhOwner').val($('#txtSrhOwner').val()) ;
	    $.listsite.changePage('1');
	},
	
	
	/**
	 * clear search filter
	 * @param null
	 * @return void
	 */
	clear:function()
	{
	    $('#txtSrhName').val('');
	    $('#selSrhCate').val('');
	    $('#txtSrhOwner').val('')
	    $.listsite.search();
	},
    /**
       * submit to login action
       * @param  null
       * @return void
       */
    changePage : function(pageIndex)
    {
        //ajax show list request
        $('#pageIndex').val(pageIndex);
        
        jQuery.ajax({
            type : "POST",
            url : UrlConfig.BaseUrl + '/ajax/manage/getlogapplist',
            dataType: "json",
            data : {
                    pageIndex : pageIndex,
                    pageSize : CONST_DEFAULT_PAGE_SIZE,     
                    srhCate : $('#hidSrhCate').val(),
		            srhName : $('#hidSrhName').val(),
		            srhOwner : $('#hidSrhOwner').val()
                   },
            success : function(response) { $.listsite.renderResults(response);}
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
                var html = $.listsite.showInfo(responseObject.info);
                var nav = showPagerNav(responseObject.count, Number($('#pageIndex').val()), CONST_DEFAULT_PAGE_SIZE,10, '$.listsite.changePage');
                cntTotal = responseObject.count;
                cntCurrent = CONST_DEFAULT_PAGE_SIZE > cntTotal ? cntTotal : CONST_DEFAULT_PAGE_SIZE
                $('div#divList').html(nav + html + nav);
            }
            else {
                //for after edit , can not find the page' record when the page has only one record 
                if (parseInt($('#pageIndex').val()) > 1) {
                    $.listsite.changePage(parseInt($('#pageIndex').val()) - 1);
                }
                else {
                   $('div#divList').html('まだ何もありません。');
                }
            }

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
	            + '<th width="15%">サイト名</th>'
	            + '<th width="20%">owner</th>'
	            + '<th width="20%">カテゴリー</th>'
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
	              + '    <td ><a href="'+UrlConfig.BaseUrl +'/log/top?appId=' + array[i].app_id + '" >' + array[i].app_name + '</a></td>'
	              + '    <td>' + array[i].login_id + '</td>';
	        if (array[i].category_name == 'null' || array[i].category_name == null) {
	            html += '    <td>-</td>';
	        } else {
	            html += '    <td>' + array[i].category_name + '</td>';
	        }
	        html += '</tr>';
	    }
	    
	    html += '</tbody>'
	            + '</table>';
	    
	    return html;
	},
	
    /**
     * to app detail 
     * @param  integer id
     * @return boolean
     */
	todetail : function(id){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/log/appdetail/aid/' + id);
        $('#frmBack').submit();
        return false;
    },
    
    /**
     * to edit site 
     * @param  integer id
     * @return boolean
     */
    delapp : function(id){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/manage/delapp/aid/' + id);
        $('#frmBack').submit();
        return false;
    }
    
    };
    
})(jQuery);
    


