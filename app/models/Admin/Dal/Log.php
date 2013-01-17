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
class Admin_Dal_Log extends Admin_Dal_Abstract
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
     * get login user for last five days
     *
     * @param Integer $role_type
     * @return array
     */
    public function getLoginUserByFilter($tableType,$pageIndex, $pageSize= 10,$mixiAppId, $startDay , $endDay , $orderType = 'desc')
    {
        $start = ($pageIndex - 1) * $pageSize;
        $searchTable = "app_".$tableType."_info_".$mixiAppId;
        
        $sql = "SELECT * FROM $searchTable WHERE 1 ";
        
        if(!empty($startDay)) {
            $sql .= " AND report_date >='".$startDay."'";
        }
        
        if(!empty($endDay)) {
            $sql .= "AND report_date <='".$endDay."'";
        }
        
        $sql .= "ORDER BY report_date $orderType LIMIT $start, $pageSize ";

        return $this->_rdb->fetchAll($sql);
    }
    
    /**
     * get login count user count by
     *
     * @param Integer $role_type
     * @return array
     */
    public function getLoginCountByFilter($mixiAppId, $startDay, $endDay)
    {
        $searchTable = "app_basic_info_".$mixiAppId;
        $sql = "SELECT COUNT(*) FROM $searchTable WHERE 1";

        if(!empty($startDay)) {
            $sql .= " AND report_date >='".$startDay."'";
        }
        
        if(!empty($endDay)) {
            $sql .= " AND report_date <='".$endDay."'";
        }

        return $this->_rdb->fetchOne($sql);
    }
    
/**
     * get login user for last five days
     *
     * @param Integer $role_type
     * @return array
     */
    public function getInfoByDate($tableType,$mixiAppId,$date)
    {
        $searchTable = "app_".$tableType."_info_".$mixiAppId;
        
        $sql = "SELECT * FROM $searchTable WHERE mixi_app_id=:mixi_app_id";
        
        if(!empty($date)) {
            $sql .= " AND report_date=:report_date";
        }
        
        return $this->_rdb->fetchRow($sql,array('mixi_app_id'=>$mixiAppId,'report_date'=>$date));
    }
    
/**
     * get login user for last five days
     *
     * @param Integer $role_type
     * @return array
     */
    public function getMaxDateByFilter($tableType,$pageIndex,$pageSize= 10,$mixiAppId, $startDay , $endDay)
    {
        $start = ($pageIndex - 1) * $pageSize;
        $searchTable = "app_".$tableType."_info_".$mixiAppId;
        
        $sql = "SELECT * FROM $searchTable WHERE 1 ";
        
        if(!empty($startDay)) {
            $sql .= " AND report_date >='".$startDay."'";
        }
        
        if(!empty($endDay)) {
            $sql .= "AND report_date <='".$endDay."'";
        }
        
        $sql .= "ORDER BY report_date DESC LIMIT $start, $pageSize ";

        return $this->_rdb->fetchAll($sql);
    }

    
    public function getDailyCount($tableType,$mixiAppId,$date)
    {
        $searchTable = "app_".$tableType."_daily_info_".$mixiAppId;
        
        $sql = "SELECT * FROM $searchTable WHERE mixi_app_id=:mixi_app_id";
        
        if(!empty($date)) {
            $sql .= " AND report_date=:report_date";
        }
        
        return $this->_rdb->fetchRow($sql,array('mixi_app_id'=>$mixiAppId,'report_date'=>$date));
    }
}