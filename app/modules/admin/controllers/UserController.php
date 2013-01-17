<?php

/**
 * Admin System Setting Controller(modules/admin/controllers/Admin_SettingController.php)
 * Linno Admin System Setting Controller
 *
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create    2009/03/23    zhangxin
 */
class Admin_UserController extends MyLib_Zend_Controller_Action_Admin
{
    /**
     * app id
     * @var Integer
     */
    protected $_id;

    /**
     * post-Initialize
     * called after parent::init method execution.
     * it can override
     * @return void
     */
    public function postInit()
    {   
        if ($this->_user->role_type == 3 ) {
            $this->_redirect($this->_baseUrl . '/error/noauthority');
            return;
        }
    }
    
    /**
     * preRender
     * @return void
     */
    public function preRender()
    {
        $this->view->loginId = $this->_user->login_id;
    }

    /**
     * sys user controller index action
     *
     */
    public function indexAction()
    {
        return $this->_forward('topmenu', 'manage', 'admin');
    }

    /**
     * sys owner list action
     *
     */
    public function ownertopAction()
    {
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhAppName = $this->_request->getParam('hidSrhAppName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');

        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->hidSrhAppName = empty($hidSrhAppName) ? '' : $hidSrhAppName;
        $this->view->hidSrhOwner = empty($hidSrhOwner) ? '' : $hidSrhOwner;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }

    /**
     * sys add owner action
     *
     */
    public function addownerAction()
    {
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhAppName = $this->_request->getParam('hidSrhAppName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');

        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->hidSrhAppName = empty($hidSrhAppName) ? '' : $hidSrhAppName;
        $this->view->hidSrhOwner = empty($hidSrhOwner) ? '' : $hidSrhOwner;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * sys edit owner action
     *
     */
    public function editownerAction()
    {
        $this->_id = (int)$this->_request->getParam('oid');
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhAppName = $this->_request->getParam('hidSrhAppName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $rowOwner = $dalUser->getUserByUid($this->_id);
        if (empty($rowOwner)) {
            $this->_redirect($this->_baseUrl . '/user/addowner');
            return;
        }
        
        $this->view->ownerInfo = $rowOwner;
        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->hidSrhAppName = empty($hidSrhAppName) ? '' : $hidSrhAppName;
        $this->view->hidSrhOwner = empty($hidSrhOwner) ? '' : $hidSrhOwner;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * sys owner list action
     *
     */
    public function managertopAction()
    {
        $pageIndex = (int)$this->_request->getParam('pageIndex');

        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }

    /**
     * sys add owner action
     *
     */
    public function addmanagerAction()
    {
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * sys edit manager action
     *
     */
    public function editmanagerAction()
    {
        $this->_id = (int)$this->_request->getParam('uid');
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $rowOwner = $dalUser->getUserByUid($this->_id);
        if (empty($rowOwner)) {
            $this->_redirect($this->_baseUrl . '/user/addowner');
            return;
        }
        
        $this->view->ownerInfo = $rowOwner;
        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
   /** manager controller app site detail
     *
     */
    public function ownerdetailAction()
    {
        $this->_id = (int)$this->_request->getParam('oid');
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhName = $this->_request->getParam('hidSrhName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');

        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $rowOwner = $dalUser->getUserByUid($this->_id);
        if (empty($rowOwner)) {
            $this->_redirect($this->_baseUrl . '/user/addowner');
            return;
        }
        
        $rowOwner['app_count'] = $dalUser->getAppCountEachOwner($rowOwner['uid']);
        $rowOwner['apps']  = '';
        require_once 'Admin/Dal/AppSite.php';
        $dalSite = Admin_Dal_AppSite::getDefaultInstance();
        $array = $dalSite->getSiteByOwner($rowOwner['uid']);
        if(!empty($array)){
            //get app count for each owner
            foreach ($array as  $item) {
                  $rowOwner['apps'] .= '[ID:'.$item['app_id'].']' .$item['app_name'].'アプリ ' ;
            }
        }
        
        $this->view->ownerInfo = $rowOwner;
        $this->view->pageIndex = $pageIndex;
        $this->view->hidSrhName = $hidSrhName;
        $this->view->hidSrhOwner = $hidSrhOwner;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * sys delete owner action
     *
     */
    public function delownerAction()
    {
        $this->_id = (int)$this->_request->getParam('oid');
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhAppName = $this->_request->getParam('hidSrhAppName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $rowOwner = $dalUser->getUserByUid($this->_id);
        if (empty($rowOwner)) {
            $this->_redirect($this->_baseUrl . '/user/addowner');
            return;
        }
        
        $rowOwner['app_count'] = $dalUser->getAppCountEachOwner($rowOwner['uid']);
        $rowOwner['apps']  = '';
        require_once 'Admin/Dal/AppSite.php';
        $dalSite = Admin_Dal_AppSite::getDefaultInstance();
        $array = $dalSite->getSiteByOwner($rowOwner['uid']);
        if(!empty($array)){
            //get app count for each owner
            foreach ($array as  $item) {
                  $rowOwner['apps'] .= '[ID:'.$item['app_id'].']' .$item['app_name'].'アプリ ' ;
            }
        } else {
            $rowOwner['apps'] .= '-';
        }
        $this->view->ownerInfo = $rowOwner;
        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->hidSrhAppName = empty($hidSrhAppName) ? '' : $hidSrhAppName;
        $this->view->hidSrhOwner = empty($hidSrhOwner) ? '' : $hidSrhOwner;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * sys delete owner action
     *
     */
    public function delmanagerAction()
    {
        $this->_id = (int)$this->_request->getParam('uid');
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $rowManager = $dalUser->getUserByUid($this->_id);
        if (empty($rowManager)) {
            $this->_redirect($this->_baseUrl . '/user/addmanager');
            return;
        }
        
        $this->view->managerInfo = $rowManager;
        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * check uid
     *
     */
    public function checkId()
    {
        if (!(is_numeric($this->_id) && $this->_id > 0)) {
            $this->_forward('notfound', 'error', 'admin');
            return false;
        }
        
        return true;
    }
    
    /**
     * preDispatch
     *
     */
    function preDispatch()
    {
    }
}