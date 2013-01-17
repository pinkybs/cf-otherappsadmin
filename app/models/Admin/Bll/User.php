<?php

/**
 * User logic's Operation
 *
 * @package    Admin/Bll
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create     2009/03/20    zhangxin
 */
final class Admin_Bll_User
{
    /**
     * db config
     * @var array
     */
    protected $_config;

    /**
     * db read adapter
     * @var Zend_Db_Abstract
     */
    protected $_rdb;

    /**
     * db write adapter
     * @var Zend_Db_Abstract
     */
    protected $_wdb;
    
    protected static $_instance;

    /**
     * init the user's variables
     *
     * @param array $config ( config info )
     */
    public function __construct($config = null)
    {
        if (is_null($config)) {
            $config = Zend_Registry::get('dbConfig');
        }

        $this->_config = $config;
        $this->_rdb = $config['readDB'];
        $this->_wdb = $config['writeDB'];
    }

    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     * get user info
     *
     * @param Integer $uid
     * @return array
     */
    public static function getUserInfo($uid)
    {
        require_once 'Admin/Dal/User.php';
        $dalUser = Admin_Dal_User::getDefaultInstance();
        return $dalUser->getUserByUid($uid);
    }

	/**
     * change user login id
     *
     * @param Integer $uid
     * @param string $loginId
     * @return boolean
     */
    public function changeUserLoginId($uid, $loginId)
    {
        if (empty($uid) || empty($loginId)) {
            return false;
        }

        try {
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $rowUser = $dalUser->getUser($uid);
            //user exist
            if (empty($rowUser)) {
                return false;
            }

            //update login id
            $dalUser->updateUser(array('login_id' => $loginId), $uid);

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

 	/**
     * change user password
     *
     * @param Integer $uid
     * @param string $newPass
     * @return boolean
     */
    public function changeUserPassword($uid, $newPass)
    {
        if (empty($uid) || empty($newPass)) {
            return false;
        }

        try {
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $rowUser = $dalUser->getUser($uid);
            //old password correct
            if ($rowUser['password'] != sha1($oldPass)) {
                return false;
            }

            //update password
            $dalUser->updateUser(array('password' => sha1($newPass)), $uid);

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    
    /**
     * update user last login time
     *
     * @param Integer $uid
     * @param string $loginId
     * @return boolean
     */
    public function updateUserLoginTime($loginId)
    {
        if (empty($loginId)) {
            return false;
        }

        try {
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $rowUser = $dalUser->getUserByLoginId($loginId);
            //user exist
            if (empty($rowUser)) {
                return false;
            }
            $login_time = date('Y-m-d H:i:s');
            //update login id
            $dalUser->updateUserLoginTime($rowUser['uid'], $loginId, $login_time);

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * add user
     *
     * @param array $info, integer $type
     * @return boolean
     */
    public function addUser($info,$userType)
    {
        try {
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $aryInfo = array();
           
            $info['create_time'] = date('Y-m-d H:i:s');
            $info['password'] = sha1($info['password']);
            $this->_wdb->beginTransaction();
            $aryInfo['uid'] = (int)$dalUser->insertUser($info);
            $aryInfo['role_type'] = (int)$userType;
            $dalUser->insertUserType($aryInfo);
            $this->_wdb->commit();
            return true;
        }
        catch (Exception $e) {
            $this->_wdb->rollBack();
            return false;
        }
    }
    
   /**
     * edit owner
     *
     * @param array $info, integer $type
     * @return boolean
     */
    public function editUser($info,$uid)
    {
        try {
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $rowUser = $dalUser->getUserByUid($uid);
            //user exist
            if (empty($rowUser)) {
                return false;
            }
            $info['create_time'] = date('Y-m-d H:i:s');
            $info['password'] = sha1($info['password']);
            $this->_wdb->beginTransaction();
            $dalUser->updateUser($info,$uid);
            $this->_wdb->commit();
            return true;
        }
        catch (Exception $e) {
            $this->_wdb->rollBack();
            return false;
        }
    }
    
   /**
     * edit owner
     *
     * @param integer $uid
     * @return boolean
     */
    public function deleteUser($uid,$chkPw,$type,$nowuid)
    {
        if (empty($uid) || empty($chkPw)) {
            return false;
        }
        try {
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $rowUser = $dalUser->getUserByUid($uid);
            //user exist
            if (empty($rowUser)) {
                return false;
            }
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $userInfo = $dalUser-> getUserByUid($nowuid);
            if($userInfo['password'] != sha1($chkPw)){
                return false;
            }
            
            $this->_wdb->beginTransaction();
            $dalUser->deleteUserRoleByUid($uid);
            if($type == 3){
                require_once 'Admin/Dal/AppSite.php';
                $dalApp = Admin_Dal_AppSite::getDefaultInstance();
                $dalApp->deleteAppByOwner($uid);
                $dalApp->deleteAppRelationForOwner($uid);
            }
            $dalUser->deleteUser($uid);
            $this->_wdb->commit();
            return true;
        }
        catch (Exception $e) {
            
            $this->_wdb->rollBack();
            return false;
        }
    }
    
}