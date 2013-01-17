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
    $.ownertop.changePage($('#pageIndex').val());
    $("#btnSearch").click(function (){$.ownertop.search();})
    $("#btnClear").click(function (){$.ownertop.clear();})
    $("#btnNew").click(function (){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/user/addowner');
	    $('#frmBack').submit();
	    return false;
    })
});

$.ownertop = {
    
	/**
	 * search site by filter
	 * @param null
	 * @return void
	 */
	search : function()
	{
	    $('#hidSrhAppName').val($('#txtSrhAppName').val()) ;
	    $('#hidSrhOwner').val($('#txtSrhOwner').val()) ;
	    $.ownertop.changePage('1');
	},
	
	
	/**
	 * clear search filter
	 * @param null
	 * @return void
	 */
	clear:function()
	{
	    $('#txtSrhAppName').val('');
	    $('#txtSrhOwner').val('')
	    $.ownertop.search();
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
            url : UrlConfig.BaseUrl + '/ajax/user/ownertop',
            dataType: "json",
            data : {
                    pageIndex : pageIndex,
                    pageSize : CONST_DEFAULT_PAGE_SIZE,     
		            srhAppName : $('#hidSrhAppName').val(),
		            srhOwner : $('#hidSrhOwner').val()
                   },
            success : function(response) { $.ownertop.renderResults(response);}
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
                var html = $.ownertop.showInfo(responseObject.info);
                var nav = showPagerNav(responseObject.count, Number($('#pageIndex').val()), CONST_DEFAULT_PAGE_SIZE,10, '$.ownertop.changePage');
                cntTotal = responseObject.count;
                cntCurrent = CONST_DEFAULT_PAGE_SIZE > cntTotal ? cntTotal : CONST_DEFAULT_PAGE_SIZE
                $('div#divList').html(nav + html + nav);
            }
            else {
                //for after edit , can not find the page' record when the page has only one record 
                if (parseInt($('#pageIndex').val()) > 1) {
                    $.ownertop.changePage(parseInt($('#pageIndex').val()) - 1);
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
	            + '<th width="10%">オーナー名</th>'
	            + '<th width="12%">最終ログイン日時</th>'
	            + '<th width="15%">登録アプリケーション数</th>'
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
	        
	        var linkEdit = '<a href="javascript:void(0);" onclick="jQuery.ownertop.editOwner(' + array[i].uid + ');return false;" >編集</a>';
	        var linkDel = '<a href="javascript:void(0);" onclick="jQuery.ownertop.delOwner(' + array[i].uid + ');return false;">削除</a>';
	        
	        html += '<tr class="' + cssClass + '">'
	              + '    <td>' + array[i].uid + '</td>'
	              + '    <td><a href="javascript:void(0);" onclick="jQuery.ownertop.todetail(' + array[i].uid + ');return false;" >' + array[i].login_id + '</a></td>'
	              + '    <td>' ;
	              
	       if(array[i].lasted_login_time){
	           html += array[i].lasted_login_time.substring(0,10) + '</td>'
	       } else {
	           html += '</td>'
	       }
	       
	       html += '    <td >' + array[i].app_count + '</td>'
	              + '    <td>' + array[i].create_time.substring(0,10) + '</td>'
	              + '    <td>' + linkEdit + '　' + linkDel + '</td>'                       
	              + '</tr>';
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
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/user/ownerdetail/oid/' + id);
        $('#frmBack').submit();
        return false;
    },
    
    /**
     * to edit site 
     * @param  integer id
     * @return boolean
     */
    delOwner : function(id){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/user/delowner/oid/' + id);
        $('#frmBack').submit();
        return false;
    },
    
	/**
     * to edit site 
     * @param  integer id
     * @return boolean
     */
	editOwner : function(id){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/user/editowner/oid/' + id);
        $('#frmBack').submit();
        return false;
    }
    
    };
    
})(jQuery);
    


