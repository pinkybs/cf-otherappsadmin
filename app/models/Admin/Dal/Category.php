<?php

require_once 'Admin/Dal/Abstract.php';

/**
 * Dal category
 * AdminUser Data Access Layer
 *
 * @package    Admin/Dal
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create     2009/09/10    hwq
 */
class Admin_Dal_Category extends Admin_Dal_Abstract
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
     * get all Category
     *
     * @param Integer $pageindex
     * @param Integer $pagesize
     * @return array
     */
    public function getCategoryList()
    {
        $sql = "SELECT * FROM admin_app_category ORDER BY `cid` ";

        return $this->_rdb->fetchAll($sql);
    }

}