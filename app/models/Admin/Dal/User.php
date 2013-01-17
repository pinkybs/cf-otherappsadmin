<?php

require_once 'Admin/Dal/Abstract.php';

/**
 * Dal User
 * AdminUser Data Access Layer
 *
 * @package    Admin/Dal
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create     2009/03/20    zhangxin
 */
class Admin_Dal_User extends Admin_Dal_Abstract
{

    protected static $_instance;

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
    public function getUserByUid($uid)
    {
        $sql = "SELECT u.*,r.* FROM admin_user u , admin_user_role r WHERE r.uid = u.uid AND u.uid=:uid";

        return $this->_rdb->fetchRow($sql, array('uid' => $uid));
    }

    /**
     * get user info by loginid
     *
     * @param string $loginId
     * @return array
     */
    public function getUserByLoginId($loginId)
    {
        $sql = "SELECT * FROM admin_user u , admin_user_role r  WHERE r.uid = u.uid AND u.login_id=:login_id";

        return $this->_rdb->fetchRow($sql, array('login_id' => $loginId));
    }
    
    /**
     * get user info by loginid
     *
     * @param string $loginId
     * @return array
     */
    public function checkUserByLoginId($loginId)
    {
        $sql = "SELECT count('A') FROM admin_user u , admin_user_role r  
        WHERE r.uid = u.uid AND u.login_id=:login_id";
        
        return $this->_rdb->fetchOne($sql, array('login_id' => $loginId));
    }

    /**
     * get user list
     *
     * @param Integer $role_type
     * @return array
     */
    public function getUserList($role_type)
    {
        $sql = "SELECT u.* FROM admin_user u , admin_user_role r 
        WHERE r.uid = u.uid AND r.role_type =:role_type";

        return $this->_rdb->fetchAll($sql, array('role_type' => $role_type));
    }
    
    /**
     * get user list
     *
     * @param Integer $role_type
     * @return array
     */
    public function getUserListByType($pageIndex, $pageSize,$role_type)
    {
        $start = ($pageIndex - 1) * $pageSize;
        $sql = "SELECT u.* FROM admin_user u , admin_user_role r 
        WHERE r.uid = u.uid AND r.role_type =:role_type LIMIT $start, $pageSize ";

        return $this->_rdb->fetchAll($sql, array('role_type' => $role_type));
    }
    
    /**
     * get user count by type
     *
     * @param Integer $role_type
     * @return array
     */
    public function getUserCountByType($role_type)
    {
        $sql = "SELECT count(*) FROM admin_user u , admin_user_role r 
        WHERE r.uid = u.uid AND r.role_type =:role_type";

        return $this->_rdb->fetchOne($sql, array('role_type' => $role_type));
    }
    
    /**
     * get owner user list
     *
     * @param Integer $role_type
     * @return array
     */
    public function getOwnerListByFilter($pageIndex, $pageSize, $srhAppName, $srhOwner)
    {
        //$sql = "SELECT u.* FROM admin_user u , admin_user_role r WHERE r.uid = u.uid AND r.role_type =:role_type";
        
        
        $start = ($pageIndex - 1) * $pageSize;
        $aryParams = array();

        $sql = "select u.* FROM admin_user u 
            LEFT JOIN admin_user_role r ON u.uid = r.uid WHERE r.role_type = 3";
        
        if (!empty($srhAppName)) {
            
            $sql .= " AND u.uid in (SELECT uid FROM admin_owner_app o 
                LEFT JOIN admin_app a on  o.app_id = a.app_id WHERE  a.app_name LIKE :app_name ) ";
            $aryParams['app_name'] = '%' .$srhAppName . '%';
        }
        if (!empty($srhOwner)) {
            $sql .= " AND u.login_id LIKE :login_id ";
            $aryParams['login_id'] = '%' .$srhOwner . '%';
        }

        $sql .= " ORDER BY u.uid  LIMIT $start, $pageSize ";

        return $this->_rdb->fetchAll($sql, $aryParams);

    }
    
    /**
     * get list Site count by filter
     *
     * @return integer
     */
    public function getOwnerListCountByFilter($srhAppName = '', $srhOwner = '')
    {
        $aryParams = array();
        $sql = 'SELECT count(*) FROM admin_user u 
            LEFT JOIN admin_user_role r ON u.uid = r.uid WHERE r.role_type = 3';

        if (!empty($srhAppName)) {
            
            $sql .= " AND u.uid in (SELECT uid FROM admin_owner_app o 
                LEFT JOIN admin_app a on  o.app_id = a.app_id WHERE  a.app_name LIKE :app_name ) ";
            $aryParams['app_name'] = '%' .$srhAppName . '%';
        }
        if (!empty($srhOwner)) {
            $sql .= " AND u.login_id LIKE :login_id ";
            $aryParams['login_id'] = '%' .$srhOwner . '%';
        }
         
        return $this->_rdb->fetchOne($sql, $aryParams);
    }
    
    /**
     * get application count for each Owner
     *
     * @param integer $uid
     * @return integer
     */
    public function getAppCountEachOwner($uid)
    {
        $sql = 'SELECT count(*) FROM admin_owner_app WHERE uid =:uid';
         return $this->_rdb->fetchOne($sql, array('uid' => $uid));
    }
    
    /**
     * insert user
     *
     * @param array $info
     * @return integer
     */
    public function insertUser($info)
    {
        $this->_wdb->insert('admin_user', $info);
        return $this->_wdb->lastInsertId();
    }

    /**
     * insert user
     *
     * @param array $info
     * @return integer
     */
    public function insertUserType($info)
    {
        return $this->_wdb->insert('admin_user_role', $info);
    }
    
    /**
     * update user
     *
     * @param array $info
     * @param integer $id
     * @return integer
     */
    public function updateUser($info, $id)
    {
        $where = $this->_wdb->quoteInto('uid = ?', $id);
        return $this->_wdb->update('admin_user', $info, $where);
    }

    /**
     * delete user
     *
     * @param integer $id
     * @return integer
     */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM admin_user WHERE uid=:uid ";
        return $this->_wdb->query($sql, array('uid' => $id));
    }
    
    /**
     * delete User Role
     *
     * @param integer $uid
     * @return integer
     */
    public function deleteUserRoleByUid($uid)
    {
        $sql = "DELETE FROM admin_user_role WHERE uid=:uid ";
        return $this->_wdb->query($sql, array('uid' => $uid));
    }
    
    /**
     * update user last login time
     *
     * @param Integer $uid
     * @param string $loginId
     * @return boolean
     */
    public function updateUserLoginTime($uid, $loginId,$loginTime)
    {
        $sql = "UPDATE admin_user SET lasted_login_time=:login_time 
        WHERE uid=:uid AND login_id=:login_id ";
        return $this->_wdb->query($sql, array('login_time' => $loginTime , 'uid' => $uid , 'login_id' => $loginId ,));
    }
    

    

}