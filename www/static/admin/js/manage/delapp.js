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
    $("#btnSubmit").click(function (){$.delapp.doDelete();})
    $("#btnBack").click(function (){
        $('#frmBack').submit(); 
        return false;
    })
});

$.delapp = {
	/**
     * check owner 
     * @param null
     * @return void
     */
    checkPWValue : function()
    {
        var txtPW = $('#txtPW').val();
        //clear 'space'
        txtPW = jQuery.cTrim(txtPW, 0);
    
        if (!txtPW) {
            $("div#divPWErr").html('管理パスワードを設定して下さい。');
            $("div#divPWErr").css({ display: "" });
            appErr = '1';
        } else if(txtPW.length <6 || txtPW.length >12 || /\W/.test(txtPW)){
            $("div#divPWErr").html('管理パスワードを半角英数字6文字以上12文字以内で入力して下さい。');
            $("div#divPWErr").css({ display: "" });
        } else {
           $("div#divPWErr").html('');
           $("div#divPWErr").css({ display: "none" });
           appErr = '0';
        }
     },
     
    /**
     * confirm Value
     *
     */
    confirmValue : function()
    {
        $.delapp.checkPWValue();
        
        return true;
    },
    
    /**
     * do new appsite
     *
     */
    doDelete : function()
    {
        $.delapp.confirmValue();
        if( appErr == '1') {
            return;
        }
        var frmEdit = $('#frmDel');
        frmEdit.attr("action", UrlConfig.BaseUrl + '/ajax/manage/delsite');
        
        $("form#frmDel").ajaxSubmit( function(data) { $.delapp.renderResultsSubmit(data); } );
        
    },
    
/**
     * renderResultsSubmit
     *
     */
    renderResultsSubmit : function(response)
    { 
        if (response == '2') {  
			$('div#completeMsg').html('サイトの削除が完了しました。');
			$('div#completeMsg').css({ display: "" });
			setTimeout(function(){$.delapp.doBack();},3000);
        }
        else if (response == '1') { 
            $('div#completeMsg').html('パスワードは違いです。');
            $( 'div#completeMsg' ).css({ display: "" }); 
        }
        else {
            $('div#completeMsg').html('削除失敗しました。しばらくたってからもう一度お試し下さい。');
            $( 'div#completeMsg' ).css({ display: "" });
            location.href = UrlConfig.BaseUrl + '/manage/listsite';      
            //window.location.href=UrlConfig.BaseUrl + '/ajax/manage/delsite';
            //setTimeout(window.location.href= '/manage/listsite', 1000);
        }
    },
    
    doBack : function(){
        $('#frmBack').attr("action", UrlConfig.BaseUrl + '/manage/listsite');
        $('#frmBack').submit();
        return false;
    }
    
};
    
})(jQuery);