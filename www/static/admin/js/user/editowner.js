/*
----------------------------------------------
add app site
Created Date: 2009/09/11
Author: hwq

----------------------------------------------
*/

//define default page size
var CONST_DEFAULT_PAGE_SIZE = 30;
(function($) {

$().ready(function() {
    var appErr = "0";
    $("#btnSubmit").click(function (){$.editowner.doSubmit();})
    $("#btnBack").click(function (){
        $('#frmBack').submit(); 
        return false;
    })
    
});

$.editowner = {

    /**
     * check app name 
     * @param null
     * @return void
     */
    checkNameValue : function()
    {
        var txtOwnerName = $('#txtOwnerName').val();
        //clear 'space'
        txtOwnerName = jQuery.cTrim(txtOwnerName, 0);
    
        if ( !txtOwnerName ) {
            $("div#divNameErr").html('オーナー名を1文字以上50文字以内で入力して下さい。');
            $("div#divNameErr" ).css({ display: "" });
            appErr = '1';
        } else {
           $("div#divNameErr").html('');
           $("div#divNameErr").css({ display: "none" });
           appErr = '0';
        }
     },
     
    /**
     * check app name 
     * @param null
     * @return void
     */
    checkIdValue : function()
    {
        var txtLoginId = $('#txtLoginId').val();
        //clear 'space'
        txtLoginId = jQuery.cTrim(txtLoginId, 0);
    
        if (!txtLoginId) {
            $("div#divIdErr").html('管理サイトログインIDを設定して下さい。');
            $("div#divIdErr").css({ display: "" });
            appErr = '1';
        } else if(txtLoginId.length <6 || txtLoginId.length >12 || /\W/.test(txtLoginId)){
            $("div#divIdErr").html('管理サイトログインIDを半角英数字6文字以上12文字以内で入力して下さい。');
            $("div#divIdErr").css({ display: "" });
            appErr = '1';
        }else {
           $("div#divIdErr").html('');
           $("div#divIdErr").css({ display: "none" });
           appErr = '0';
        }
     },
     
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
            $("div#divPWErr").html('管理サイトパスワードを設定して下さい。');
            $("div#divPWErr").css({ display: "" });
            appErr = '1';
        } else if(txtPW.length <6 || txtPW.length >12 || /\W/.test(txtPW)){
            $("div#divPWErr").html('管理サイトパスワードを半角英数字6文字以上12文字以内で入力して下さい。');
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
        $.editowner.checkNameValue();
        $.editowner.checkIdValue();
        $.editowner.checkPWValue();
    },
    
    /**
     * do new appsite
     *
     */
    doSubmit : function()
    {
        $.editowner.confirmValue();
        if( appErr == '1') {
            return;
        }
        var frmAdd = $('#frmAdd');
        frmAdd.attr("action", UrlConfig.BaseUrl + '/ajax/user/editowner');
        //frmAdd[0].submit();
        
        $("form#frmAdd").ajaxSubmit( function(data) { $.editowner.renderResultsSubmit(data); } );
        
    },
    
/**
     * renderResultsSubmit
     *
     */
    renderResultsSubmit : function(response)
    { 
        if (response == '2') {  
            $('div#completeMsg').html('情報変更が完了しました。');
            $('div#completeMsg').css({ display: "" });
        } else if (response == '1'){
            $('div#completeMsg').html('同じ管理サイトログインIDがすでに登録されています。');
            $('div#completeMsg').css({ display: "" });
        } else {
            $('div#completeMsg').html('入力内容に誤りがあります。');
            $('div#completeMsg').css({ display: "" });
        }
    }
};
    
})(jQuery);