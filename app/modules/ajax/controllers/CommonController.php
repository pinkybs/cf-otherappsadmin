<?php
/** @see Zend_Json */
require_once 'Zend/Json.php';
/** @see MyLib_Zend_Controller_Action_Ajax */
require_once 'MyLib/Zend/Controller/Action/Ajax.php';

/**
 * Common Ajax Controllers
 *
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create      2009/10/09    hwq
 */
class Ajax_CommonController extends MyLib_Zend_Controller_Action_Ajax
{
    /**
     * check url 
     *
     */
    public function validateurlAction()
    {
        $url = $this->_request->getParam('url', '');
        
        $valid = false;
        
        if ($url != '' && $url != 'http://') {
            require_once 'MyLib/Network.php';
            $valid = MyLib_Network::validateUrl($url);
        }
        
        echo $valid ? 'true' : 'false';
    }
}
