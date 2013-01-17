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
class Ajax_UserController extends MyLib_Zend_Controller_Action_Ajax
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
    public function ownertopAction()
    {
        $pageIndex = (int)$this->_request->getPost('pageIndex', 1);
        $pageSize = (int)$this->_request->getPost('pageSize', 10);
        $srhAppName = $this->_request->getPost('srhAppName','');
        $srhOwner = $this->_request->getPost('srhOwner','');

        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();

        $result = $dalUser->getOwnerListByFilter($pageIndex, $pageSize, $srhAppName, $srhOwner);
        $count = (int)$dalUser->getOwnerListCountByFilter($srhAppName, $srhOwner);
        
        //get app count for each owner
        foreach ($result as $key => $item) {
              $result[$key]['app_count'] = $dalUser->getAppCountEachOwner($item['uid']);
        }
                
        $response = array('info' => $result, 'count' => $count);
        $response = Zend_Json::encode($response);
        
        echo $response;
    }
    
    /**
     * site list view
     *
     */
    public function managerlistAction()
    {
        $pageIndex = (int)$this->_request->getPost('pageIndex', 1);
        $pageSize = (int)$this->_request->getPost('pageSize', 10);

        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();

        $result = $dalUser->getUserListByType($pageIndex, $pageSize, 2);
        $count = (int)$dalUser->getUserCountByType(2);
        
        $response = array('info' => $result, 'count' => $count);
        $response = Zend_Json::encode($response);
        
        echo $response;
    }

	/**
     * delete site
     *
     */
    public function delownerAction()
    {
        if ($this->_request->isPost()) {
            $uid = (int)$this->_request->getPost('uid');
            $txtPassword = $this->_request->getPost('inputPw');
            $roleType = $this->_request->getPost('type');
            if (empty($uid) || empty($txtPassword)) {
                echo '0';
                return;
            }

            if($this->_user->role_type == 3){
                echo '0'; 
                return;
             }
            
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $userInfo = $dalUser-> getUserByUid($this->_user->uid);
            if($userInfo['password'] != sha1($txtPassword)){
                echo '1';
                return;
            }

            require_once 'Admin/Bll/User.php';
            $bllUser = new Admin_Bll_User();
            $result = $bllUser->deleteUser($uid,$txtPassword,$roleType,$this->_user->uid);

            echo $result ? '2' : '0';
        }
    }

	/**
     * add owner
     *
     */
    public function addownerAction()
    {
        if ($this->_request->isPost()) {
            $txtOwnerName = $this->_request->getPost('txtOwnerName');
            $txtLoginId = $this->_request->getPost('txtLoginId');
            $txtPW = $this->_request->getPost('txtPW');

            if (empty($txtOwnerName) || empty($txtLoginId) || empty($txtPW) 
                || mb_strlen($txtOwnerName, 'UTF-8') > 50 || mb_strlen($txtLoginId, 'UTF-8') > 50 
                || mb_strlen($txtPW, 'UTF-8') > 12 || mb_strlen($txtPW, 'UTF-8') < 6) {
                echo '0';
                return;
            }
            
            require_once 'Admin/Dal/User.php';
            $dalUser = new Admin_Dal_User();
            $rowUser = $dalUser->getUserByLoginId($txtLoginId);
            if(!empty($rowUser)) {
                echo '1';
                return;
            }

            require_once 'Admin/Bll/User.php';
            $bllUser = new Admin_Bll_User();
            $aryInfo = array();
            $aryInfo['name'] = $txtOwnerName;

            $aryInfo['login_id'] = $txtLoginId;
            $aryInfo['password'] = $txtPW;
            $result = $bllUser->addUser($aryInfo,3);

            echo $result ? '2' : '0';
        }
    }

	/**
     * edit site
     *
     */
    public function editownerAction()
    {
        if ($this->_request->isPost()) {
            $uid = (int)$this->_request->getPost('hidUid');
            $txtOwnerName = $this->_request->getPost('txtOwnerName');
            $txtLoginId = $this->_request->getPost('txtLoginId');
            $txtPW = $this->_request->getPost('txtPW');
            
            //check input data
            if (empty($txtOwnerName) || empty($txtLoginId) || empty($txtPW) 
                || mb_strlen($txtOwnerName, 'UTF-8') > 50 || mb_strlen($txtLoginId, 'UTF-8') > 50 
                || mb_strlen($txtPW, 'UTF-8') > 12 || mb_strlen($txtPW, 'UTF-8') < 6) {
                echo '0';
                return;
            }
            
            require_once 'Admin/Dal/User.php';
            $dalUser = new Admin_Dal_User();
            //check login id is mul
            $cntMultitude = $dalUser->checkUserByLoginId($txtLoginId);
            if($cntMultitude > 1 ) {
                echo '1';
                return;
            }

            require_once 'Admin/Bll/User.php';
            $bllUser = new Admin_Bll_User();
            $aryInfo = array();
            $aryInfo['name'] = $txtOwnerName;

            $aryInfo['login_id'] = $txtLoginId;
            $aryInfo['password'] = $txtPW;
            $result = $bllUser->editUser($aryInfo,$uid,3);

            echo $result ? '2' : '0';
        }
    }
    
    /**
     * delete manager
     *
     */
    public function delmanagerAction()
    {
        if ($this->_request->isPost()) {
            $uid = (int)$this->_request->getPost('uid');
            $txtPassword = $this->_request->getPost('inputPw');
            $roleType = $this->_request->getPost('type');

            if (empty($uid) || empty($txtPassword)) {
                echo '0';
                return;
            }
            
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $userInfo = $dalUser-> getUserByUid($this->_user->uid);
            if($userInfo['role_type']==3){
                echo '0';
                return;
            }
            
            if($userInfo['password'] != sha1($txtPassword)){
                echo '1';
                return;
            }

            require_once 'Admin/Bll/User.php';
            $bllUser = new Admin_Bll_User();
            $result = $bllUser->deleteUser($uid,$txtPassword,$roleType,$this->_user->uid);

            echo $result ? '2' : '0';
        }
    }
    
    
    /**
     * change password
     *
     */
    public function changepassAction()
    {
        if ($this->_request->isPost()) {
            $oldPass = $this->_request->getPost('txtOldPw');
            $newPass = $this->_request->getPost('txtNewPw');
            $confirmPass = $this->_request->getPost('txtConfirmPw');

            if (empty($oldPass) || empty($newPass) || empty($confirmPass)) {
                echo 'false';
                return;
            }
            if ($newPass !== $confirmPass) {
                echo 'false';
                return;
            }

            //change user pass
            require_once 'Admin/Bll/User.php';
            $bllUser = Admin_Bll_User::getDefaultInstance();
            $result = $bllUser->changeUserPassword($this->_user->uid, $oldPass, $newPass);

            echo $result ? 'true' : 'false';
        }
    }
    
    /**
     * add sys manager
     *
     */
    public function addmanagerAction()
    {
        if ($this->_request->isPost()) {
            $txtLoginId = $this->_request->getPost('txtLoginId');
            $txtPW = $this->_request->getPost('txtPW');

            if ( empty($txtLoginId) || empty($txtPW) 
                || mb_strlen($txtLoginId, 'UTF-8') > 50 
                || mb_strlen($txtPW, 'UTF-8') > 12 || mb_strlen($txtPW, 'UTF-8') < 6) {
                echo '0';
                return;
            }
            
            require_once 'Admin/Dal/User.php';
            $dalUser = new Admin_Dal_User();
            $cntMultitude = $dalUser->checkUserByLoginId($txtLoginId);
            if($cntMultitude >= 1 ) {
                echo '1';
                return;
            }

            require_once 'Admin/Bll/User.php';
            $bllUser = new Admin_Bll_User();
            $aryInfo = array();
            $aryInfo['login_id'] = $txtLoginId;
            $aryInfo['password'] = $txtPW;
            $result = $bllUser->addUser($aryInfo,2);

            echo $result ? '2' : '0';
        }
    }
    
    /**
     * edit site
     *
     */
    public function editmanagerAction()
    {
        if ($this->_request->isPost()) {
            $uid = (int)$this->_request->getPost('hidUid');
            $txtLoginId = $this->_request->getPost('txtLoginId');
            $txtPW = $this->_request->getPost('txtPW');

            if (empty($txtLoginId) || empty($txtPW) 
                || mb_strlen($txtLoginId, 'UTF-8') > 50 
                || mb_strlen($txtPW, 'UTF-8') > 12 || mb_strlen($txtPW, 'UTF-8') < 6) {
                echo '0';
                return;
            }

            require_once 'Admin/Dal/User.php';
            $dalUser = new Admin_Dal_User();
            $cntMultitude = $dalUser->checkUserByLoginId($txtLoginId);
            if($cntMultitude > 1 ) {
                echo '1';
                return;
            }
            
            require_once 'Admin/Bll/User.php';
            $bllUser = new Admin_Bll_User();
            $aryInfo = array();

            $aryInfo['login_id'] = $txtLoginId;
            $aryInfo['password'] = $txtPW;
            $result = $bllUser->editUser($aryInfo,$uid);

            echo $result ? '2' : '0';
        }
    }
    
    /**
     * check is validate admin user before action
     *
     */
    function preDispatch()
    {

    }
}