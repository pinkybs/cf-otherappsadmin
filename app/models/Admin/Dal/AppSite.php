<?php

require_once 'Admin/Dal/Abstract.php';

/**
 * Dal app site
 * AdminUser Data Access Layer
 *
 * @package    Admin/Dal
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create     2009/09/10    hwq
 */
class Admin_Dal_AppSite extends Admin_Dal_Abstract
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
     * list Site
     *
     * @param Integer $pageindex
     * @param Integer $pagesize
     * @return array
     */
    public function getAppSiteList($pageindex = 1, $pagesize = 10)
    {
        $start = ($pageindex - 1) * $pagesize;
        $sql = "SELECT a.*,c.*,o.*,u.login_id,u.name FROM admin_app a 
                    LEFT JOIN admin_app_category c ON c.cid = a.cid 
                    LEFT JOIN admin_owner_app o ON o.app_id = a.app_id  
                    LEFT JOIN admin_user u ON u.uid = o.uid ORDER BY a.create_time DESC LIMIT $start, $pagesize ";

        return $this->_rdb->fetchAll($sql);
    }

    /**
     * get list Site count
     *
     * @return integer
     */
    public function getSiteListCount()
    {
        $sql = 'SELECT COUNT(app_id) FROM admin_app';
        return $this->_rdb->fetchOne($sql);
    }

    /**
     * list Site by filter
     *
     * @param Integer $pageindex
     * @param Integer $pagesize
     * @return array
     */
    public function getSiteListByFilter($pageindex = 1, $pagesize = 10, $srhName = '', $srhOwner = '', $srhCate = '' ,$uid, $role_type,$log_tool=0)
    {
        $start = ($pageindex - 1) * $pagesize;
        $aryParams = array();

        $sql = "SELECT a.*,c.*,o.*,u.login_id,u.name,r.* FROM admin_app a 
                    LEFT JOIN admin_app_category c ON c.cid = a.cid 
                    LEFT JOIN admin_owner_app o ON o.app_id = a.app_id  
                    LEFT JOIN admin_user u ON u.uid = o.uid
                    LEFT JOIN admin_user_role r ON u.uid = r.uid where r.role_type = 3";
        if($role_type == 3 && !empty($uid)){
            $sql .= " AND o.uid=:uid ";
            $aryParams['uid'] = $uid;
        }
        
        if (!empty($srhName)) {
            $sql .= " AND a.app_name LIKE :app_name ";
            $aryParams['app_name'] = '%' .$srhName . '%';
        }
        if (!empty($srhOwner)) {
            $sql .= " AND u.login_id LIKE :login_id ";
            $aryParams['login_id'] = '%' .$srhOwner . '%';
        }
        if (!empty($srhCate)) {
            $sql .= " AND c.cid=:cid ";
            $aryParams['cid'] = $srhCate;
        }
        if(!empty($log_tool)){
            $sql .= " AND a.log_tool=:log_tool ";
            $aryParams['log_tool'] = 1;
        }
        $sql .= " ORDER BY a.app_id  LIMIT $start, $pagesize ";

        return $this->_rdb->fetchAll($sql, $aryParams);
    }

    /**
     * get list Site count by filter
     *
     * @return integer
     */
    public function getSiteListCountByFilter($srhName = '', $srhOwner = '',  $srhCate = '', $uid, $role_type,$log_tool='')
    {
        $aryParams = array();
        $sql = 'SELECT count(a.app_id) FROM admin_app a 
                    LEFT JOIN admin_app_category c ON c.cid = a.cid 
                    LEFT JOIN admin_owner_app o ON o.app_id = a.app_id  
                    LEFT JOIN admin_user u ON u.uid = o.uid
                    LEFT JOIN admin_user_role r ON u.uid = r.uid where r.role_type = 3';

        if($role_type == 3 && !empty($uid)){
            $sql .= " AND o.uid=:uid ";
            $aryParams['uid'] = $uid;
        }
        
        if (!empty($srhName)) {
                $sql .= " AND a.app_name LIKE :app_name ";
                $aryParams['app_name'] = '%' .$srhName . '%';
            }
            
       if (!empty($srhOwner)) {
            $sql .= " AND u.login_id  LIKE :login_id ";
            $aryParams['login_id'] = '%' .$srhOwner . '%';
        }
        if (!empty($srhCate)) {
            $sql .= " AND c.cid=:cid ";
            $aryParams['cid'] = $srhCate;
        }
        if(!empty($log_tool)){
            $sql .= " AND a.log_tool=:log_tool ";
            $aryParams['log_tool'] = 1;
        }
        
        return $this->_rdb->fetchOne($sql, $aryParams);
    }

    /**
     * get Site info by id
     *
     * @param Integer $id
     * @return array
     */
    public function getSiteById($id)
    {
        $sql = "SELECT a.*,c.*,o.*,u.login_id,u.name,r.* FROM admin_app a 
                    LEFT JOIN admin_app_category c ON c.cid = a.cid 
                    LEFT JOIN admin_owner_app o ON o.app_id = a.app_id  
                    LEFT JOIN admin_user u ON u.uid = o.uid
                    LEFT JOIN admin_user_role r ON u.uid = r.uid where r.role_type = 3 and a.app_id=:app_id";

        return $this->_rdb->fetchRow($sql, array('app_id' => $id));
    }
    
    /**
     * get Site info by id
     *
     * @param Integer $id
     * @return array
     */
    public function getSiteByOwner($uid)
    {
        
        $sql = "SELECT a.*,c.*,o.*,u.login_id,u.name,r.* FROM admin_app a 
                    LEFT JOIN admin_app_category c ON c.cid = a.cid 
                    LEFT JOIN admin_owner_app o ON o.app_id = a.app_id  
                    LEFT JOIN admin_user u ON u.uid = o.uid
                    LEFT JOIN admin_user_role r ON u.uid = r.uid where r.role_type = 3 and o.uid=:uid ";
                    

        return $this->_rdb->fetchAll($sql, array('uid' => $uid));
    }

    /**
     * get Site info by id
     *
     * @param Integer $id
     * @return array
     */
    public function getSiteListForOwner($pageindex = 1, $pagesize = 10, $uid)
    {
        $start = ($pageindex - 1) * $pagesize;
        $sql = "SELECT a.*,c.cid,c.category_name,u.login_id,u.name FROM admin_app a 
                    LEFT JOIN admin_app_category c ON c.cid = a.cid 
                    LEFT JOIN admin_owner_app o ON o.app_id = a.app_id  
                    LEFT JOIN admin_user u ON u.uid = o.uid
                    LEFT JOIN admin_user_role r ON u.uid = r.uid 
                    where r.role_type = 3 and o.uid=:uid ORDER BY a.app_id  LIMIT $start, $pagesize ";

        return $this->_rdb->fetchAll($sql, array('uid' => $uid));
    }
    
    /**
     * get Site info by id
     *
     * @param Integer $id
     * @return array
     */
    public function getSiteCountForOwner( $uid)
    {
        $sql = "SELECT count(*) FROM admin_app a 
                    LEFT JOIN admin_app_category c ON c.cid = a.cid 
                    LEFT JOIN admin_owner_app o ON o.app_id = a.app_id 
                    LEFT JOIN admin_user_role r ON o.uid = r.uid  
                    where r.role_type = 3 and o.uid=:uid ";

        return $this->_rdb->fetchOne($sql, array('uid' => $uid));
    }
    
    /**
     * insert Site
     *
     * @param array $info
     * @return integer
     */
    public function insertAppSite($info)
    {
        //$this->_wdb->insert('admin_app', $info);
        $this->_wdb->insert('admin_app', $info);
        return $this->_wdb->lastInsertId();
    }

    /**
     * insert Site
     *
     * @param array $info
     * @return integer
     */
    public function insertOwnerApp($info)
    {
        return $this->_wdb->insert('admin_owner_app', $info);
        //return $this->_wdb->insert('admin_owner_app', $info);
    }
    
    /**
     * update Site
     *
     * @param array $info
     * @param integer $id
     * @return integer
     */
    public function updateAppSite($info, $id)
    {
        $where = $this->_wdb->quoteInto('app_id = ?', $id);
        return $this->_wdb->update('admin_app', $info, $where);
    }
    
    /**
     * update Site
     *
     * @param array $info
     * @param integer $id
     * @return integer
     */
    public function updateOwnerApp($info, $aid)
    {
        $where = $this->_wdb->quoteInto('app_id = ?', $aid);
        return $this->_wdb->update('admin_owner_app', $info, $where);
    }
    
    /**
     * delete user
     *
     * @param integer $id
     * @return integer
     */
    public function deleteApp($aid)
    {
        $sql = "DELETE FROM admin_app WHERE app_id=:app_id ";
        return $this->_wdb->query($sql, array('app_id' => $aid));
    }
    
    /**
     * delete owner app relation 
     *
     * @param integer $aid
     * @return integer
     */
    public function deleteOwnerForApp($aid)
    {
        $sql = "DELETE FROM admin_owner_app WHERE app_id=:app_id ";
        return $this->_wdb->query($sql, array('app_id' => $aid));
    }
    
    public function deleteAppRelationForOwner($uid){
        $sql = "DELETE FROM admin_owner_app WHERE uid=:uid ";
        return $this->_wdb->query($sql, array('uid' => $uid));
    }
    
     /**
     * delete app  by oid 
     *
     * @param integer $uid
     * @return integer
     */
    public function deleteAppByOwner($uid){
        $sql = "DELETE FROM admin_app WHERE app_id in (SELECT app_id FROM admin_owner_app WHERE uid=:uid) ";
        return $this->_wdb->query($sql, array('uid' => $uid));
    }
    
     /**
     * create table for  app basic info
     *
     * @param integer $uid
     * @return integer
     */
    public function createAppBasicTable($mixiAppId)
    {
        $showSql = "show tables like '%app_basic_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_basic_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            login_count int(11) default 0, 
            daily_login_count int(11) default 0, 
            feed_count int(11) default 0, 
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
     /**
     * create table for app login user count for daily info
     *
     * @param integer $uid
     * @return integer
     */
    public function createDailyLoginCountTable($mixiAppId)
    {
        $showSql = "show tables like '%app_login_daily_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_login_daily_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            zero_oclock_count int(11) default 0, 
            one_oclock_count int(11) default 0, 
            two_oclock_count int(11) default 0, 
            three_oclock_count int(11) default 0, 
            four_oclock_count int(11) default 0,
            five_oclock_count int(11) default 0,
            six_oclock_count int(11) default 0,
            seven_oclock_count int(11) default 0,
            eight_oclock_count int(11) default 0,
            nine_oclock_count int(11) default 0,
            ten_oclock_count int(11) default 0,
            eleven_oclock_count int(11) default 0,
            twelve_oclock_count int(11) default 0,
            thirteen_oclock_count int(11) default 0,
            fourteen_oclock_count int(11) default 0,
            fifteen_oclock_count int(11) default 0,
            sixteen_oclock_count int(11) default 0,
            seventeen_oclock_count int(11) default 0,
            nighteen_oclock_count int(11) default 0,
            nineteen_oclock_count int(11) default 0,
            twenty_oclock_count int(11) default 0,
            twenty_one_oclock_count int(11) default 0,
            twenty_two_oclock_count int(11) default 0,
            twenty_three_oclock_count int(11) default 0,
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
     /**
     * create table for app login user count for daily info
     *
     * @param integer $uid
     * @return integer
     */
    public function createDailyUseCountTable($mixiAppId)
    {
        $showSql = "show tables like '%app_use_daily_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_use_daily_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            zero_oclock_count int(11) default 0, 
            one_oclock_count int(11) default 0, 
            two_oclock_count int(11) default 0, 
            three_oclock_count int(11) default 0, 
            four_oclock_count int(11) default 0,
            five_oclock_count int(11) default 0,
            six_oclock_count int(11) default 0,
            seven_oclock_count int(11) default 0,
            eight_oclock_count int(11) default 0,
            nine_oclock_count int(11) default 0,
            ten_oclock_count int(11) default 0,
            eleven_oclock_count int(11) default 0,
            twelve_oclock_count int(11) default 0,
            thirteen_oclock_count int(11) default 0,
            fourteen_oclock_count int(11) default 0,
            fifteen_oclock_count int(11) default 0,
            sixteen_oclock_count int(11) default 0,
            seventeen_oclock_count int(11) default 0,
            nighteen_oclock_count int(11) default 0,
            nineteen_oclock_count int(11) default 0,
            twenty_oclock_count int(11) default 0,
            twenty_one_oclock_count int(11) default 0,
            twenty_two_oclock_count int(11) default 0,
            twenty_three_oclock_count int(11) default 0,
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
     /**
     * create table for app login user count for daily info
     *
     * @param integer $uid
     * @return integer
     */
    public function createDailyFeedCountTable($mixiAppId)
    {
        $showSql = "show tables like '%app_feed_daily_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_feed_daily_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            zero_oclock_count int(11) default 0, 
            one_oclock_count int(11) default 0, 
            two_oclock_count int(11) default 0, 
            three_oclock_count int(11) default 0, 
            four_oclock_count int(11) default 0,
            five_oclock_count int(11) default 0,
            six_oclock_count int(11) default 0,
            seven_oclock_count int(11) default 0,
            eight_oclock_count int(11) default 0,
            nine_oclock_count int(11) default 0,
            ten_oclock_count int(11) default 0,
            eleven_oclock_count int(11) default 0,
            twelve_oclock_count int(11) default 0,
            thirteen_oclock_count int(11) default 0,
            fourteen_oclock_count int(11) default 0,
            fifteen_oclock_count int(11) default 0,
            sixteen_oclock_count int(11) default 0,
            seventeen_oclock_count int(11) default 0,
            nighteen_oclock_count int(11) default 0,
            nineteen_oclock_count int(11) default 0,
            twenty_oclock_count int(11) default 0,
            twenty_one_oclock_count int(11) default 0,
            twenty_two_oclock_count int(11) default 0,
            twenty_three_oclock_count int(11) default 0,
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
/**
     * create table for  app basic info
     *
     * @param integer $uid
     * @return integer
     */
    public function createAppGenderTable($mixiAppId)
    {
        $showSql = "show tables like '%app_gender_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_gender_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            female_count int(11) default 0,                                                                      
            male_count int(11) default 0,   
            unknown_count int(11) default 0,      
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
/**
     * create table for  app basic info
     *
     * @param integer $uid
     * @return integer
     */
    public function createAppAgeTable($mixiAppId)
    {
        $showSql = "show tables like '%app_age_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_age_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            zero_range_count int(11) default 0,       
            frist_range_count int(11) default 0,                                                                      
            second_range_count int(11) default 0,   
            third_range_count int(11) default 0,     
            fourth_range_count int(11) default 0,   
            fifth_range_count int(11) default 0,   
            sixth_range_count int(11) default 0,   
            seventh_range_count int(11) default 0,   
            eighth_range_count int(11) default 0,  
            unknown_range_count int(11) default 0,
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
    
/**
     * create table for  app basic info
     *
     * @param integer $uid
     * @return integer
     */
    public function createAppMymixiTable($mixiAppId)
    {
        $showSql = "show tables like '%app_mymixi_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_mymixi_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            none_count int(11) default 0,   
            frist_range_count int(11) default 0,                                                                      
            second_range_count int(11) default 0,   
            third_range_count int(11) default 0,     
            fourth_range_count int(11) default 0,   
            fifth_range_count int(11) default 0,   
            sixth_range_count int(11) default 0,   
            seventh_range_count int(11) default 0,   
            eighth_range_count int(11) default 0,  
            ninth_range_count int(11) default 0, 
            tenth_range_count int(11) default 0,  
            others_range_count int(11) default 0, 
            unknown_range_count int(11) default 0,
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
/**
     * create table for  app basic info
     *
     * @param integer $uid
     * @return integer
     */
    public function createAppInviteTable($mixiAppId)
    {
        $showSql = "show tables like '%app_invite_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_invite_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL, 
            none_count int(11) default 0,   
            frist_range_count int(11) default 0,                                                                      
            second_range_count int(11) default 0,   
            third_range_count int(11) default 0,     
            fourth_range_count int(11) default 0,   
            fifth_range_count int(11) default 0,   
            sixth_range_count int(11) default 0,   
            seventh_range_count int(11) default 0,   
            eighth_range_count int(11) default 0,  
            ninth_range_count int(11) default 0,  
            tenth_range_count int(11) default 0, 
            others_range_count int(11) default 0, 
            unknown_range_count int(11) default 0,
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
/**
     * create table for  app basic info
     *
     * @param integer $uid
     * @return integer
     */
    public function createAppAddressTable($mixiAppId)
    {
        $showSql = "show tables like '%app_address_info_$mixiAppId%'";
        $result =$this->_rdb->fetchAll($showSql);
        if(empty($result)) {
            $sql = "CREATE TABLE app_address_info_$mixiAppId (
            mixi_app_id int(11) NOT NULL,  
            hokkayi_count int(11) default 0, 
            aomori_count int(11) default 0, 
            yiote_count int(11) default 0, 
            miyagi_count int(11) default 0, 
            akita_count int(11) default 0, 
            yamagata_count int(11) default 0, 
            fukusima_count int(11) default 0, 
            yibaraki_count int(11) default 0, 
            tochigi_count int(11) default 0, 
            gunnma_count int(11) default 0, 
            sayitama_count int(11) default 0, 
            chiba_count int(11) default 0, 
            kanagawa_count int(11) default 0, 
            toukyou_count int(11) default 0, 
            niyigata_count int(11) default 0, 
            toyama_count int(11) default 0, 
            yisikawa_count int(11) default 0, 
            fukuyi_count int(11) default 0, 
            yamanasi_count int(11) default 0, 
            nagano_count int(11) default 0, 
            gifu_count int(11) default 0,    
            sizuoka_count int(11) default 0, 
            ayichiken_count int(11) default 0, 
            mie_count int(11) default 0, 
            oosakafu_count int(11) default 0, 
            hyougo_count int(11) default 0, 
            nara_count int(11) default 0, 
            wakayama_count int(11) default 0, 
            tottori_count int(11) default 0,
            okayama_count int(11) default 0,  
            simane_count int(11) default 0, 
            hirosima_count int(11) default 0, 
            yamaguchi_count int(11) default 0, 
            tokusima_count int(11) default 0, 
            kagawa_count int(11) default 0, 
            ehime_count int(11) default 0, 
            kouchi_count int(11) default 0,     
            hukuoka_count int(11) default 0, 
            saga_count int(11) default 0, 
            nagasaki_count int(11) default 0, 
            kumamoto_count int(11) default 0, 
            ooyita_count int(11) default 0, 
            miyazaki_count int(11) default 0, 
            kagosima_count int(11) default 0, 
            okinawa_count int(11) default 0, 
            abord_count int(11) default 0,   
            unknown_count int(11) default 0,  
            report_date date default NULL, PRIMARY KEY (mixi_app_id,report_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            return $this->_wdb->query($sql); 
        }
        
         return false;
    }
    
     /**
     * create table for  app basic info
     *
     * @param integer $uid
     * @return integer
     */
    public function CheckHasAppLogTable($mixiAppId,$type)
    {
        $showSql = "show tables like '%app_".$type."_info_$mixiAppId%'";
        $result = $this->_rdb->fetchAll($showSql);
         return count($result)>0;
    }
    
    /**
     * check is auth
     *
     * @param integer $uid
     * @param integer $fid
     * @return boolean
     */
    public function checkIsAuth($aid, $uid)
    {
        $sql = "SELECT COUNT('A') FROM admin_owner_app WHERE app_id=:app_id AND uid=:uid";

        $result = $this->_rdb->fetchOne($sql, array('app_id' => $aid, 'uid' => $uid));
        
        return $result == 1;
    }
    

}