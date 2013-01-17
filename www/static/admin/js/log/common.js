/*
----------------------------------------------
common
Created Date: 2009/11/11
Author: hwq

----------------------------------------------
*/

$(document).ready(function() {
    $("#datepickerStart").datepicker({
            dateFormat: 'yy-mm-dd'
    });
    $("#datepickerEnd").datepicker({
            dateFormat: 'yy-mm-dd'
    });
    $("#btnSearch").click(function (){
       $("#frmSearch").submit();
    })
    $("#btnBack").click(function (){
        $('#frmSearch').attr("action", UrlConfig.BaseUrl + '/log/list');
        $('#frmSearch').submit(); 
        return false;
    })
});

function ckShowGragh(type) 
{
    $("#showType").val(type);
    //alert($("input#showType").val());
    $("#frmSearch").submit();
    return false;
}
