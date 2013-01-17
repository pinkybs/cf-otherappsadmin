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
    $.managerlist.changePage($('#pageIndex').val());
    $("#btnNew").click(function (){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/user/addmanager');
	    $('#frmBack').submit();
	    return false;
    })
});

$.managerlist = {
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
            url : UrlConfig.BaseUrl + '/ajax/user/managerlist',
            dataType: "json",
            data : {
                    pageIndex : pageIndex,
                    pageSize : CONST_DEFAULT_PAGE_SIZE
                   },
            success : function(response) { $.managerlist.renderResults(response);}
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
                var html = $.managerlist.showInfo(responseObject.info);
                var nav = showPagerNav(responseObject.count, Number($('#pageIndex').val()), CONST_DEFAULT_PAGE_SIZE,10, '$.managerlist.changePage');
                cntTotal = responseObject.count;
                cntCurrent = CONST_DEFAULT_PAGE_SIZE > cntTotal ? cntTotal : CONST_DEFAULT_PAGE_SIZE
                $('div#divList').html(nav + html + nav);
            }
            else {
                //for after edit , can not find the page' record when the page has only one record 
                if (parseInt($('#pageIndex').val()) > 1) {
                    $.managerlist.changePage(parseInt($('#pageIndex').val()) - 1);
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
	            + '<th width="10%"> ユーザー名</th>'
	            + '<th width="10%">最終ログイン日時</th>'
	            + '<th width="10%">登録日</th>'
	            + '<th>変更　削除</th>'
	            + '</tr>'
	            + '</thead>'
	            + '<tbody>';
	
	    //for each row data
	    for (var i = 0 ; i < array.length ; i++) {        
	        var cssClass = 'a';
	        if (1 == i % 2) {
	            cssClass = 'b';
	        }
	        
	        var linkEdit = '<a href="javascript:void(0);" onclick="jQuery.managerlist.editManager(' + array[i].uid + ');return false;" >編集</a>';
	        var linkDel = '<a href="javascript:void(0);" onclick="jQuery.managerlist.delManager(' + array[i].uid + ');return false;">削除</a>';
	        
	        html += '<tr class="' + cssClass + '">'
	              + '    <td>' + array[i].uid + '</td>'
	              + '    <td>' + array[i].login_id + '</td>'
	              + '    <td>' ;
	              
	       if(array[i].lasted_login_time){
	           html += array[i].lasted_login_time.substring(0,10) + '</td>'
	       } else {
	           html += '</td>'
	       }
	       
	       html += '    <td>' + array[i].create_time.substring(0,10) + '</td>'
	              + '    <td>' + linkEdit + '　' + linkDel + '</td>'                       
	              + '</tr>';
	    }
	    
	    html += '</tbody>'
	            + '</table>';
	    
	    return html;
	},
    
    /**
     * to del manager 
     * @param  integer id
     * @return boolean
     */
    delManager : function(id){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/user/delmanager/uid/' + id);
        $('#frmBack').submit();
        return false;
    },
    
	/**
     * to edit site 
     * @param  integer id
     * @return boolean
     */
	editManager : function(id){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/user/editmanager/uid/' + id);
        $('#frmBack').submit();
        return false;
    }
    
    };
    
})(jQuery);
    


