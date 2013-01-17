<?php

/**
 * Admin Manage Controller(modules/admin/controllers/Admin_ManageController.php)
 * Linno Admin Manage Controller
 *
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create    2009/03/20    zhangxin
 */
class Admin_ManageController extends MyLib_Zend_Controller_Action_Admin
{ 
    /**
     * app id
     * @var Integer
     */
    protected $_aid;
    
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
     * preRender
     * @return void
     */
    public function preRender()
    {
        $this->view->loginId = $this->_user->login_id;
    }

    /**
     * manage controller index action
     *
     */
    public function indexAction()
    {
        $this->_forward('topmenu', 'manage', 'admin');
        return;
    }

    /**
     * manager controller top action
     *
     */
    public function topmenuAction()
    {
        if($this->_user->role_type == 3){
            $this->view->isAdminAuth = 0;
        } else {
            $this->view->isAdminAuth = 1;
        }
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }

    /**
     * manager controller list site action
     *
     */
    public function listsiteAction()
    {
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhName = $this->_request->getParam('hidSrhName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        $hidSrhCate = (int)$this->_request->getParam('hidSrhCate');

        require_once 'Admin/Dal/Category.php';
        $dalCategory = Admin_Dal_Category::getDefaultInstance();
        $this->view->lstCate = $dalCategory->getCategoryList();
        
        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->hidSrhName = empty($hidSrhName) ? '' : $hidSrhName;
        $this->view->hidSrhOwner = empty($hidSrhOwner) ? '' : $hidSrhOwner;
        $this->view->hidSrhCate = empty($hidSrhCate) ? '' : $hidSrhCate;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }

	/**
     * manager controller add site action
     *
     */
    public function addsiteAction()
    {
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhName = $this->_request->getParam('hidSrhName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        $hidSrhCate = (int)$this->_request->getParam('hidSrhCate');

        require_once 'Admin/Dal/Category.php';
        $dalCategory = Admin_Dal_Category::getDefaultInstance();
        $this->view->lstCate = $dalCategory->getCategoryList();
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $this->view->lstOwner = $dalUser->getUserList(3);

        $this->view->pageIndex = $pageIndex;
        $this->view->hidSrhName = $hidSrhName;
        $this->view->hidSrhOwner = $hidSrhOwner;
        $this->view->hidSrhCate = $hidSrhCate;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }

    /**
     * manager controller edit site action
     *
     */
    public function editsiteAction()
    {
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhName = $this->_request->getParam('hidSrhName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        $hidSrhCate = (int)$this->_request->getParam('hidSrhCate');

        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();
        $rowSite = $dalApp->getSiteById($this->_aid);
        if (empty($rowSite)) {
            $this->_redirect($this->_baseUrl . '/manage/addsite');
            return;
        }

        require_once 'Admin/Dal/Category.php';
        $dalCategory = Admin_Dal_Category::getDefaultInstance();
        $this->view->lstCate = $dalCategory->getCategoryList();
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $this->view->lstOwner = $dalUser->getUserList(3);

        $this->view->siteInfo = $rowSite;
        $this->view->pageIndex = $pageIndex;
        $this->view->hidSrhName = $hidSrhName;
        $this->view->hidSrhOwner = $hidSrhOwner;
        $this->view->hidSrhCate = $hidSrhCate;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
   /** manager controller app site detail
     *
     */
    public function appdetailAction()
    {
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhName = $this->_request->getParam('hidSrhName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        $hidSrhCate = (int)$this->_request->getParam('hidSrhCate');

        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();
        $rowSite = $dalApp->getSiteById($this->_aid);
        if (empty($rowSite)) {
            $this->_redirect($this->_baseUrl . '/manage/addsite');
            return;
        }

        $this->view->siteInfo = $rowSite;
        $this->view->pageIndex = $pageIndex;
        $this->view->hidSrhName = $hidSrhName;
        $this->view->hidSrhOwner = $hidSrhOwner;
        $this->view->hidSrhCate = $hidSrhCate;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }

    /**
     * manager controller delete site action
     *
     */
    public function delappAction()
    {
        $result = $this->checkId();
        if ($result == false) {
            return;
        }
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhName = $this->_request->getParam('hidSrhName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        $hidSrhCate = (int)$this->_request->getParam('hidSrhCate');

        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();
        $rowSite = $dalApp->getSiteById($this->_aid);
        if (empty($rowSite)) {
            $this->_redirect($this->_baseUrl . '/manage/addsite');
            return;
        }

        require_once 'Admin/Dal/Category.php';
        $dalCategory = Admin_Dal_Category::getDefaultInstance();
        $this->view->lstCate = $dalCategory->getCategoryList();
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        $this->view->lstOwner = $dalUser->getUserList(3);

        $this->view->siteInfo = $rowSite;
        $this->view->pageIndex = $pageIndex;
        $this->view->hidSrhName = $hidSrhName;
        $this->view->hidSrhOwner = $hidSrhOwner;
        $this->view->hidSrhCate = $hidSrhCate;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * check uid
     *
     */
    public function checkId()
    {
        $this->_aid  = (int)$this->_request->getParam('aid');
        $uid = $this->_user->uid;
        $type = $this->_user->role_type;
        if (!(is_numeric($this->_aid) && $this->_aid > 0)) {
            $this->_forward('notfound', 'error', 'admin');
            return false;
        }
        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();
        $rowApp = $dalApp->getSiteById($this->_aid);
        if (empty($rowApp)) {
            $this->_forward('notfound', 'error', 'admin');
            return;
        }
         //need the manager's confirmation
        if ($type == 3 && $rowApp['uid'] != $uid) {
            $this->_redirect($this->_baseUrl . '/error/noauthority');
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