<?php

/**
 * Site logic's Operation
 *
 * @package    Admin/Bll
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create     2009/10/09    hwq
 */
final class Admin_Bll_Site
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
     * delete site by id
     *
     * @param integer $id
     * @return boolean
     */
    public function delSite($aid,$chkPw,$uid)
    {
        if (empty($aid) || empty($chkPw) || empty($uid)) {
            return false;
        }
        try {
            
            require_once 'Admin/Dal/User.php';
            $dalUser = Admin_Dal_User::getDefaultInstance();
            $userInfo = $dalUser-> getUserByUid($uid);
            if($userInfo['password'] != sha1($chkPw)){
                return false;
            }
            
            require_once 'Admin/Dal/AppSite.php';
            $dalApp = Admin_Dal_AppSite::getDefaultInstance();
            $rowCurSite = $dalApp->getSiteById($aid);
            if (empty($rowCurSite)) {
                return false;
            }

            $this->_wdb->beginTransaction();
            //delete site
            $dalApp->deleteOwnerForApp($aid);
            //delete site pages
            $dalApp->deleteApp($aid);
            $this->_wdb->commit();
            return true;
        }
        catch (Exception $e) {
            $this->_wdb->rollBack();
            return false;
        }
    }

    /**
     * add site
     *
     * @param array $info
     * @return boolean
     */
    public function addSite($info,$ownerId)
    {
        try {
            require_once 'Admin/Dal/AppSite.php';
            $dalApp = Admin_Dal_AppSite::getDefaultInstance();
            $aryInfo = array();
            
            $info['create_time'] = date('Y-m-d H:i:s');
            $this->_wdb->beginTransaction();
            $aryInfo['app_id'] = (int)$dalApp->insertAppSite($info);
            $aryInfo['uid'] = (int)$ownerId;
            $dalApp->insertOwnerApp($aryInfo);
            $dalApp->createAppBasicTable($info['mixi_app_id']);
            $dalApp->createAppGenderTable($info['mixi_app_id']);
            $dalApp->createAppAgeTable($info['mixi_app_id']);
            $dalApp->createAppMymixiTable($info['mixi_app_id']);
            $dalApp->createAppAddressTable($info['mixi_app_id']);
            $dalApp->createDailyLoginCountTable($info['mixi_app_id']);
            $dalApp->createDailyUseCountTable($info['mixi_app_id']);
            $dalApp->createDailyFeedCountTable($info['mixi_app_id']);

            $this->_wdb->commit();
            return true;
        }
        catch (Exception $e) {
            $this->_wdb->rollBack();
            return false;
        }
    }

    /**
     * edit site
     *
     * @param array $info
     * @param integer $id
     * @return boolean
     */
    public function editSite($info, $aid, $owerId)
    {
        try {
            require_once 'Admin/Dal/AppSite.php';
            $dalApp = Admin_Dal_AppSite::getDefaultInstance();
            $rowSite = $dalApp->getSiteById($aid);
            if (empty($rowSite)) {
                return false;
            }

            $this->_wdb->beginTransaction();
            $id = $dalApp->updateAppSite($info, $aid);
            $dalApp->updateOwnerApp(array('uid' => $owerId),$aid);
            $this->_wdb->commit();
            return true;
        }
        catch (Exception $e) {
            $this->_wdb->rollBack();
            return false;
        }
    }

}