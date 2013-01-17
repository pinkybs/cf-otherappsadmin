
/**
 * common(/common.js)
 *
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create    2009/02/23    Liz
 */

/**
 * get browser
 *
 * @return integer
 */
function getOs()
{
    //IE,return 1
    if (navigator.userAgent.indexOf("MSIE")>0) {
       return 1;
    }
    //Firefox,return 2
    if (isFirefox=navigator.userAgent.indexOf("Firefox") > 0) {
       return 2;
    }
    //Safari,return 3
    if (isSafari=navigator.userAgent.indexOf("Safari") > 0) {
       return 3;
    }
    //Camino,return 4
    if (isCamino=navigator.userAgent.indexOf("Camino") > 0) {
       return 4;
    }
    //Gecko,return 5
    if (isMozilla=navigator.userAgent.indexOf("Gecko/") > 0) {
       return 5;
    }

    return 0;
}

/**
 * Get Absolute Location Ex
 *
 * @param object element
 * @return integer
 */
function GetAbsoluteLocationEx(element)
{
    if ( arguments.length != 1 || element == null ) {
        return null;
    }
    var elmt = element;
    var offsetTop = elmt.offsetTop;
    var offsetLeft = elmt.offsetLeft;
    var offsetWidth = elmt.offsetWidth;
    var offsetHeight = elmt.offsetHeight;
    while( elmt = elmt.offsetParent ) {
        //add this judge
        if ( elmt.style.position == 'absolute' || elmt.style.position == 'relative'
            || ( elmt.style.overflow != 'visible' && elmt.style.overflow != '' ) ) {
            break;
        }
        offsetTop += elmt.offsetTop;
        offsetLeft += elmt.offsetLeft;
    }
    return { absoluteTop: offsetTop, absoluteLeft: offsetLeft,
        offsetWidth: offsetWidth, offsetHeight: offsetHeight };
}

/**
 * get Absolute Left
 *
 * @param object  e
 * @return integer
 */
function   getAbsLeft(e)
{
    var l = e.offsetLeft;
    while (e=e.offsetParent) {
          l += e.offsetLeft;
    }
    return   l;
}

/**
 * get Absolute Left
 *
 * @param object  e
 * @return integer
 */
function   getAbsTop(e)
{
    var t=e.offsetTop;
    while (e=e.offsetParent) {
        t += e.offsetTop;
    }
    return   t;
}



/**
 * show page nav
 *
 * @param integer count
 * @param integer pageindex
 * @param integer pagesize
 * @param integer pagecount
 * @return string
 */
function showPagerNav(count,pageindex,pagesize,pagecount,action)
{
    if (!pagecount) {
        pagecount = 10;
    }

    if (!action) {
        action = 'changePageAction';
    }

    if (count <= pagesize) {
        return '';
    }

    var nav = '';

    var forward = '';
    var pagerleft = '';
    var pagercurrent = '';
    var pagerright = '';
    var next = '';
    var maxpage = Math.ceil(count/pagesize);
    var classA = 'border:1px solid #8AD84D;display:block;text-decoration:none;text-align:center;';
    var classAActive = classA + 'background-color:#8AD84D;color:#FFFFFF;';
    var classLi = 'float:left;margin-right:5px;display:inline;width:32px;';
    var classUl = 'clear:both;';

    if (pageindex > 1) {
        forward += '<li style="' + classLi + '"><a style="' + classA + '" href="javascript:' + action + '(' + (pageindex - 1) + ');">&lt;&lt;</a></li>';
    }

    if (maxpage > pageindex) {
        next = '<li style="' + classLi + '"><a style="' + classA + '" href="javascript:' + action + '(' + (pageindex + 1) + ');">&gt;&gt;</a></li>';
    }

    var page = Math.ceil(pagecount/2);

    //all page count
    var i = 1;

    //left nav
    var left = 0;
    for (left = pageindex - 1; left > 0 && left > pageindex - page; left--) {
        i++;
        pagerleft = '<li style="' + classLi + '"><a style="' + classA + '" href="javascript:' + action + '(' + left + ');">' + left + '</a></li>' + pagerleft;
    }

    //current nav number
    pagercurrent = '<li style="' + classLi + '"><a style="' + classAActive + '" href="javascript:' + action + '(' + pageindex + ');" class="active">' + pageindex + '</a></li>';

    //right nva
    var right = 0;
    for (right = pageindex + 1; right <= maxpage && right < pageindex + page ; right++) {
        i++;
        pagerright = pagerright + '<li style="' + classLi + '"><a style="' + classA + '" href="javascript:' + action + '(' + right + ');">' + right + '</a></li>';
    }

    //If right side is not enough, show the page number for left until the page number number is up to 1
    if (i < pagecount && left >= 1) {
        for (; left > 0 && i < pagecount; left--,i++) {
            pagerleft = '<li style="' + classLi + '"><a style="' + classA + '" href="javascript:' + action + '(' + left + ');">' + left + '</a></li>' + pagerleft;
        }
    }

    //If left side is not enough, showthe page number for right until the page number number is up to max
    if (i < pagecount && right <= maxpage) {
        for (; right <= maxpage && i < pagecount; right++,i++) {
            pagerright = pagerright + '<li style="' + classLi + '"><a style="' + classA + '" href="javascript:' + action + '(' + right + ');">' + right + '</a></li>';
        }
    }

    nav = '<ul class="clearfix" style="' + classUl + '">' + forward + pagerleft + pagercurrent + pagerright + next + '</ul>';

    return nav;
}

/**
 * quote String
 *
 * @param string str
 * @return string
 */
function quoteString(str)
{
    str = replaceAll(str,'\n','<br>');
    str = replaceAll(str,' ','&nbsp;');

    return str;
}

/**
 * replace the string
 *
 * @param string strOrg
 * @param string strFind
 * @param string strReplace
 * @return string
 */
function replaceAll(strOrg,strFind,strReplace)
{
     var index = 0;
     while (strOrg.indexOf(strFind,index) != -1) {
        strOrg = strOrg.replace(strFind,strReplace);
        index = strOrg.indexOf(strFind,index);
     }
     return strOrg
}





