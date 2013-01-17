/*
----------------------------------------------
add app site
Created Date: 2009/09/11
Author: hwq

----------------------------------------------
*/
//define default page size
var CONST_DEFAULT_PAGE_SIZE = 2;

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
});
    
