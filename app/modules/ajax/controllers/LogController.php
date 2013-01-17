<?php

/** @see Zend_Json */
require_once 'Zend/Json.php';

/**
 * Admin Manage Ajax Controller
 * Manage ajax operation
 *
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create    2009/03/25    zhangxin
 */
class Ajax_LogController extends MyLib_Zend_Controller_Action_Ajax
{
    /**
     * post-Initialize
     * called after parent::init method execution.
     * it can override
     * @return void
     */
    public function postInit()
    {
    }
    
	/**
     * get basic info
     *
     */
    public function getbasicinfoAction()
    {
        $pageIndex = (int)$this->_request->getPost('pageIndex', 1);
        $pageSize = (int)$this->_request->getPost('pageSize', 10);
        $startDate = $this->_request->getPost('startDate');
        $endDate = $this->_request->getPost('endDate');
        $mixiAppId = $this->_request->getPost('mixiAppId');

        if(empty($endDate)){
            $endDate = date("Y-m-d");
        }

        if(!empty($startDate)&&($endDate < $startDate) && $startDate > date("Y-m-d")){
            echo false;
            return;
        }

        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($startDate);
        
        require_once 'Admin/Dal/Log.php';
        $dalLog = Admin_Dal_Log::getDefaultInstance();
        $aryBasicInfo = $dalLog->getLoginUserByFilter('basic',$pageIndex,$pageSize,$mixiAppId,$startDate,$endDate,'asc');
        $count = $dalLog->getLoginCountByFilter($mixiAppId,$startDate,$endDate);
        if(count($aryBasicInfo)>0) {
            $aryDateBasicInfo = Admin_Bll_Common::getEachDateInfo($aryBasicInfo,$preDate,'basic',$mixiAppId);
        } else {
            $aryDateBasicInfo = '';
        }
     
        $response = array('info' => $aryDateBasicInfo, 'count' => $count);
        $response = Zend_Json::encode($response);
        
        echo $response;
    }
    
    
    /**
     * check is validate admin user before action
     *
     */
    function preDispatch()
    {

    }
}