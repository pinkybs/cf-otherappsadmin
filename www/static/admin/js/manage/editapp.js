/*
----------------------------------------------
edit app site
Created Date: 2009/10/09
Author: hwq

----------------------------------------------
*/

(function($) {

$().ready(function() {
    var appErr = "0";
    $("#btnSubmit").click(function (){$.editapp.doSubmit();})
    $("#btnBack").click(function (){
        $('#frmBack').submit(); 
        return false;
    })
});

$.editapp = {

	/**
	 * check app name 
	 * @param null
	 * @return void
	 */
	checkAppValue : function()
	{
	    var txtAppName = $('#txtAppName').val();
	    //clear 'space'
	    txtAppName = jQuery.cTrim(txtAppName, 0);
	
	    if ( !txtAppName ) {
	        $("div#divAppErr").html('アプリケーション名を設定して下さい。');
	        $( 'div#divAppErr' ).css({ display: "" });
	        appErr = '1';
	    } else {
	       $("div#divAppErr").html('');
	       $( 'div#divAppErr' ).css({ display: "none" });
	       appErr = '0';
	    }
	 },
	 
    /**
     * check mixi id 
     * @param null
     * @return void
     */
    checkIDValue: function()
    {
        var txtMixiId = $('#txtMixiID').val();
        //clear 'space'
        txtMixiId = jQuery.cTrim(txtMixiId, 0);
    
        if ( !txtMixiId ) {
            $("div#divIdErr").html('ミクシィIDを入力して下さい。');
            $("div#divIdErr").css({ display: "" });
            appErr = '1';
        } else if(!(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(txtMixiId))){
           $("div#divIdErr").html('有効な数字を入力してください。');
           $("div#divIdErr").css({ display: "" });
           appErr = '1';
        }else {
           $("div#divIdErr").html('');
           $("div#divIdErr").css({ display: "none" });
           appErr = '0';
        }
     },
	 
    /**
     * check url
     * @param null
     * @return void
     */
    checkUrlValue : function()
    {
        var txtUrl = $('#txtUrl').val();
        //clear 'space'
        txtUrl = jQuery.cTrim(txtUrl, 0);
    
        if (!txtUrl) {
            appErr = '1';
            $("div#divUrlErr").html('OpenSocialサイトURLを設定して下さい。');
            $("div#divUrlErr").css({ display: "" });
        } else {
            result = /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(txtUrl)
            if (result) {
                appErr = '0';
                $("div#divUrlErr").html('');
                $("div#divUrlErr").css({ display: "none" });
            }else {
                appErr = '1';
                $("div#divUrlErr").html('不正なURLです');
                $("div#divUrlErr").css({ display: "" });
            }
        }
     },
     
    /**
     * check owner 
     * @param null
     * @return void
     */
    checkOwnerValue : function()
    {
        var selOwner = $('#selOwner').val();
        var alertInfo = '';
        //clear 'space'
        selOwner = jQuery.cTrim(selOwner, 0);
    
        if ( !selOwner ) {
            appErr = '1';
            $("div#divOwnerErr").html('オーナー名を設定して下さい。');
            $( 'div#divOwnerErr' ).css({ display: "" });
        } else {
            appErr = '0';
           $("div#divOwnerErr").html('');
           $( 'div#divOwnerErr' ).css({ display: "none" });
        }
     },
     
    /**
     * confirm Value
     *
     */
    confirmValue : function()
    {
        $.editapp.checkAppValue();
        $.editapp.checkIDValue();
        $.editapp.checkUrlValue();
        $.editapp.checkOwnerValue();
        
        return true;
    },
    
    /**
     * do new appsite
     *
     */
    doSubmit : function()
    {
        $.editapp.confirmValue();
        if( appErr == '1') {
            return;
        }
        var frmEdit = $('#frmEdit');
        frmEdit.attr("action", UrlConfig.BaseUrl + '/ajax/manage/editsite');
        
        $("form#frmEdit").ajaxSubmit( function(data) { $.editapp.renderResultsSubmit(data); } );
        
    },
    
/**
     * renderResultsSubmit
     *
     */
    renderResultsSubmit : function(response)
    { 
        if (response == 'true') {  
			$('div#completeMsg').html('サイトの変更が完了しました。');
			$( 'div#completeMsg' ).css({ display: "" });
        }
        else {
            $('div#completeMsg').html('変更失敗しました。しばらくたってからもう一度お試し下さい。');
            $( 'div#completeMsg' ).css({ display: "" });
        }
    }
};
    
})(jQuery);