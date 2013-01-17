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
    $("#btnDelete").click(function (){$.deluser.doDelete();})
    $("#btnBack").click(function (){
        var action = '';
        if(Number($('#hidType').val()) == 2){
           action= UrlConfig.BaseUrl + "/user/managertop"
        } else {
           action= UrlConfig.BaseUrl + "/user/ownertop"
        }
        $('#frmBack').attr("action", action);
        $('#frmBack').submit(); 
        return false;
    })
});

$.deluser = {
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
            $("div#divPWErr").css({display: "" });
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
        $.deluser.checkPWValue();
        
        return true;
    },
    
    /**
     * do delete manager
     *
     */
    doDelete : function()
    {
        $.deluser.confirmValue();
        if( appErr == '1') {
            return;
        }
        if(Number($('#hidType').val()) == 2){
            var url = UrlConfig.BaseUrl + '/ajax/user/delmanager';
        } else {
            var url = UrlConfig.BaseUrl + '/ajax/user/delowner';
        }

        $.ajax({
             type: "POST",
             url: url,
             data: {uid : $('#hidUid').val(),
                    inputPw : $('#txtPW').val(),
                    type: $('#hidType').val()},
             error : function () {
             },
             success: function(response){$.deluser.renderResultsSubmit(response);}
             })
    },
    
/**
     * renderResultsSubmit
     *
     */
    renderResultsSubmit : function(response)
    { 
        if (response == '2') {  
            var message = '';
            if(Number($('#hidType').val()) == 2){
                message = '管理システムユーザー';
            } else {
                message = 'オーナー';
            }
			$('div#completeMsg').html(message + 'の削除が完了しました。');
			$('div#completeMsg').css({ display: "" });
			setTimeout(function(){$.deluser.doBack();},3000);
        }　else if (response == '1') { 
            $('div#completeMsg').html('パスワードは違いです。');
            $( 'div#completeMsg' ).css({ display: "" }); 
        }
        else {
            $('div#completeMsg').html('削除失敗しました。しばらくたってからもう一度お試し下さい。');
            $( 'div#completeMsg' ).css({ display: "" });
        }
    },
    
    doBack : function(){
        var action = '';
        if(Number($('#hidType').val()) == 2){
           action= UrlConfig.BaseUrl + "/user/managertop"
        } else {
           action= UrlConfig.BaseUrl + "/user/ownertop"
        }
        $('#frmBack').attr("action", action);
        $('#frmBack').submit(); 
        return false;
    }
};
    
})(jQuery);