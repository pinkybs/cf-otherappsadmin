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
class Ajax_ManageController extends MyLib_Zend_Controller_Action_Ajax
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
     * site list view
     *
     */
    public function listsiteAction()
    {
        $pageIndex = (int)$this->_request->getPost('pageIndex', 1);
        $pageSize = (int)$this->_request->getPost('pageSize', 10);
        $srhCate = $this->_request->getPost('srhCate');
        $srhName = $this->_request->getPost('srhName','');
        $srhOwner = $this->_request->getPost('srhOwner','');

        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();

        $result = $dalApp->getSiteListByFilter($pageIndex, $pageSize, $srhName, $srhOwner, 
                $srhCate ,$this->_user->uid,$this->_user->role_type);
        $count = (int)$dalApp->getSiteListCountByFilter($srhName, $srhOwner, $srhCate ,
                $this->_user->uid,$this->_user->role_type);

        $response = array('info' => $result, 'count' => $count);
        $response = Zend_Json::encode($response);
        
        echo $response;
    }

	/**
     * delete site
     *
     */
    public function delsiteAction()
    {
        if ($this->_request->isPost()) {
            $aid = (int)$this->_request->getPost('hidAid');
            $txtPassword = $this->_request->getPost('txtPW');
            if (empty($aid) || empty($txtPassword)) {
                echo '0';
                return;
            }

            if($this->_user->role_type == 3){
                require_once 'Admin/Dal/AppSite.php';
                $dalApp = new Admin_Dal_AppSite();
                $isAuth = $dalApp->checkIsAuth($aid, $this->_user->uid);
                if(!$isAuth){
                    echo '0';
                    return;
                }
             }

            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $userInfo = $dalUser-> getUserByUid($this->_user->uid);
            if($userInfo['password'] != sha1($txtPassword)){
                echo '1';
                return;
            }

            require_once 'Admin/Bll/Site.php';
            $bllSite = new Admin_Bll_Site();
            $result = $bllSite->delSite($aid,$txtPassword,$this->_user->uid);

            echo $result ? '2' : '0';
        }
    }
    
	/**
     * add site
     *
     */
    public function addsiteAction()
    {
        if ($this->_request->isPost()) {
            $txtAppName = $this->_request->getPost('txtAppName');
            $txtMixiId = $this->_request->getPost('txtMixiID');
            $selOwner = (int)$this->_request->getPost('selOwner');
            $selCate = (int)$this->_request->getPost('selCate');
            $txtUrl = $this->_request->getPost('txtUrl');
            $haslog = (int)$this->_request->getPost('chxlog');
            $hasCgm = (int)$this->_request->getPost('chxCgm');
            $hasPoint = (int)$this->_request->getPost('chxPoint');
            $hasAffiliate = (int)$this->_request->getPost('chxAffiliate');
            $hasAdvise = (int)$this->_request->getPost('chxAdvise');

            if (empty($txtAppName) || empty($txtMixiId)|| empty($txtUrl) || empty($selOwner )
                || mb_strlen($txtAppName, 'UTF-8') > 50 || mb_strlen($txtUrl, 'UTF-8') > 255) {
                echo 'false';
                return;
            }

            require_once 'Admin/Bll/Site.php';
            $bllSite = new Admin_Bll_Site();
            $aryInfo = array();
            $aryInfo['app_name'] = $txtAppName;

            //valide url check
            require_once 'Zend/Uri.php';
            $uri = Zend_Uri::factory('http://' . $txtUrl );
            if (!$uri->valid()) {
                echo 'invalid_url';
                return;
            }
            $aryInfo['mixi_app_id'] = $txtMixiId;
            $aryInfo['site_url'] = $txtUrl;
            $aryInfo['cid'] = $selCate;
            $aryInfo['log_tool'] = $haslog;
            $aryInfo['CGM_tool'] = $hasCgm;
            $aryInfo['point_tool'] = $hasPoint;
            $aryInfo['affiliate_tool'] = $hasAffiliate;
            $aryInfo['adviser_tool'] = $hasAdvise;
            $result = $bllSite->addSite($aryInfo,$selOwner);

            echo $result ? 'true' : 'false';
        }
    }
    
	/**
     * edit site
     *
     */
    public function editsiteAction()
    {
        if ($this->_request->isPost()) {
            $txtAppName = $this->_request->getPost('txtAppName');
            $txtMixiId = $this->_request->getPost('txtMixiID');
            $selOwner = (int)$this->_request->getPost('selOwner');
            $selCate = (int)$this->_request->getPost('selCate');
            $txtUrl = $this->_request->getPost('txtUrl');
            $haslog = (int)$this->_request->getPost('chxlog');
            $hasCgm = (int)$this->_request->getPost('chxCgm');
            $hasPoint = (int)$this->_request->getPost('chxPoint');
            $hasAffiliate = (int)$this->_request->getPost('chxAffiliate');
            $hasAdvise = (int)$this->_request->getPost('chxAdvise');
            $aid = $this->_request->getPost('id');

            if (empty($txtAppName) || empty($txtMixiId)|| empty($txtUrl) || empty($selOwner)
                || mb_strlen($txtAppName, 'UTF-8') > 50 || mb_strlen($txtUrl, 'UTF-8') > 255) {
                echo 'false';
                return;
            }

            require_once 'Admin/Bll/Site.php';
            $bllSite = new Admin_Bll_Site();
            $aryInfo = array();
            $aryInfo['app_name'] = $txtAppName;

            //valide url check
            require_once 'Zend/Uri.php';
            $uri = Zend_Uri::factory('http://' . $txtUrl );
            if (!$uri->valid()) {
                echo 'invalid_url';
                return;
            }
            $aryInfo['mixi_app_id'] = $txtMixiId;
            $aryInfo['site_url'] = $txtUrl;
            $aryInfo['cid'] = $selCate;
            $aryInfo['log_tool'] = $haslog;
            $aryInfo['CGM_tool'] = $hasCgm;
            $aryInfo['point_tool'] = $hasPoint;
            $aryInfo['affiliate_tool'] = $hasAffiliate;
            $aryInfo['adviser_tool'] = $hasAdvise;
            $result = $bllSite->editSite($aryInfo, $aid, $selOwner);

            echo $result ? 'true' : 'false';
        }
    }
    
    /**
     * appli list for owner
     *
     */
    public function applistbyownerAction()
    {
        $oid = (int)$this->_request->getPost('oid');
        $pageIndex = (int)$this->_request->getPost('pageIndex', 1);
        $pageSize = (int)$this->_request->getPost('pageSize', 10);

        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();

        $result = $dalApp->getSiteListForOwner($pageIndex, $pageSize, $oid);
        $count = (int)$dalApp->getSiteCountForOwner($oid);
        
        $response = array('info' => $result, 'count' => $count);
        $response = Zend_Json::encode($response);
        
        echo $response;
    }
    
    /**
     * get app list which has log tool
     *
     */
    public function getlogapplistAction()
    {
        $pageIndex = (int)$this->_request->getPost('pageIndex', 1);
        $pageSize = (int)$this->_request->getPost('pageSize', 10);
        $srhCate = $this->_request->getPost('srhCate');
        $srhName = $this->_request->getPost('srhName','');
        $srhOwner = $this->_request->getPost('srhOwner','');

        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();

        $result = $dalApp->getSiteListByFilter($pageIndex, $pageSize, $srhName, $srhOwner, 
                $srhCate ,$this->_user->uid,$this->_user->role_type,1);
        $count = (int)$dalApp->getSiteListCountByFilter($srhName, $srhOwner, $srhCate ,
                $this->_user->uid,$this->_user->role_type,1);

        $response = array('info' => $result, 'count' => $count);
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