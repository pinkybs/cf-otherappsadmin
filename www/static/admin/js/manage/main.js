/*
----------------------------------------------
Script Editor main JavaScript

Created Date: 2009/05/13
Author: Yu Uno
Last Up Date : 2009/05/13
Author: Yu Uno
----------------------------------------------
*/


jQuery.extend(
{

      /**
       * @see  将json字符串转换为对象
       * @param   json字符串
       * @return 返回object,array,string等对象
       */
      evalJSON : function (strJson)
      {
            return eval( "(" + strJson + ")");
      },
      
      /**
       * @see  省略过长字符
       * @param string
       * @return string
       */
      truncateString : function (string, len, sep)
      {
            if(len==null) len=2;
            if(sep==null) sep='';
            
            var a=0;
            
            for(var i=0;i<string.length;i++){
                if (string.charCodeAt(i)>255)
                    a+=2;
                else
                    a++;
                
                if(a>=len)
                    return string.substr(0,i+1) + sep;
            }
            
            return string;
      },
      
      format : function(time, fmt) 
      { //author: meizz 
            var o = { 
                "M+" : time.getMonth()+1, //月份 
                "d+" : time.getDate(), //日 
                "h+" : time.getHours(), //小时 
                "m+" : time.getMinutes(), //分 
                "s+" : time.getSeconds(), //秒 
                "q+" : Math.floor((time.getMonth()+3)/3), //季度 
                "S" : time.getMilliseconds() //毫秒 
            }; 
            if(/(y+)/.test(fmt)) 
            fmt=fmt.replace(RegExp.$1, (time.getFullYear()+"").substr(4 - RegExp.$1.length)); 
            for(var k in o) 
            if(new RegExp("("+ k +")").test(fmt)) 
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length))); 
            return fmt; 
      },
      
      formatToDate : function(time, format)
      {
            if (!format) format = 'yyyy年MM月dd日 hh:mm';
            return $.format(new Date(time.replace(/-/g,"/")), format);
      },
      
      getCheckValue : function(id)
      {
            //alert(id);
            var rPort = document.getElementsByName(id);
            //alert(rPort.length;);
            for(m=0; m<rPort.length; m++)
            {
             　　if(rPort[m].checked) {
               　    return rPort[m].value;
                 }
            }
            
      },
      
      //clear 'space'
      cTrim : function(sInputString,iType)
      {
            var sTmpStr = ' ';
            var i = -1;
            
            if(iType == 0 || iType == 1) {
                while(sTmpStr == ' ') {
                    ++i;
                    sTmpStr = sInputString.substr(i,1);
                }
                
                sInputString = sInputString.substring(i);
            }
            
            if(iType == 0 || iType == 2) {
                sTmpStr = ' ';
                i = sInputString.length;
                
                while(sTmpStr == ' ') {
                    --i;
                    sTmpStr = sInputString.substr(i,1);
                }
                sInputString = sInputString.substring(0,i+1);
            }
            
            return sInputString;
      },
      
     //escape html tags
    escapeHtml : function(strContent)
    {
        return strContent.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;").replace(/\'/g,"&#039;");
    },
    
    nl2br : function(strContent)
    {
        return strContent.replace(/\r\n|\r|\n/g,'<br/>');
    },
    
    getCookie : function(name)
    {
        var result = null;
        var myCookie = document.cookie + ";";
        var searchName = name + "=";
        var startOfCookie = myCookie.indexOf(searchName);
        var endOfCookie;
        if (startOfCookie != -1) {
            startOfCookie += searchName.length;
            endOfCookie = myCookie.indexOf(";",startOfCookie);
            result = unescape(myCookie.substring(startOfCookie, endOfCookie));
        }
        return result;
    },
    
    ToCDB : function(str)
    {
        var tmp = "";
        for( var i=0; i<str.length; i++ ) {
            if( str.charCodeAt(i) > 65248 && str.charCodeAt(i) < 65375 ) {
                tmp += String.fromCharCode(str.charCodeAt(i) - 65248);
            }
            else {
                tmp += String.fromCharCode(str.charCodeAt(i));
            }
        }
        return tmp;
    }
});
