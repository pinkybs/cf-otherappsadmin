<?php

/**
 * common logic's Operation
 *
 * @package    Bll
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create     2009-10-20    hwq
 */
class Admin_Bll_Common
{

    /**
     * get network list
     *
     * @return array
     */
    public static function getNetworkList()
    {
        $network = array(1 => 'すべての大学', 2 => '自分の大学のみ');

        return $network;
    }

    /**
     * get network list for mobile
     *
     * @return array
     */
    public static function getAgeRangeName()
    {
        $name = array('zero_range_count' => '1～10歳', 
                        'frist_range_count' => '11～20歳',
                        'second_range_count' => '21～30歳',
                        'third_range_count' => '31～40歳',
                        'fourth_range_count' => '41～50歳',
                        'fifth_range_count' => '51～60歳',
                        'sixth_range_count' => '61～70歳',
                        'seventh_range_count' => '71～80歳',
                        'eighth_range_count' => '81歳以上',
                        'unknown_range_count' => 'UNKNOWN');

        return $name;
    }
    
    /**
     * get gender name
     *
     * @return array
     */
    public static function getGenderName()
    {
        $name = array('female_count' => '女性', 
                        'male_count' => '男性',
                        'unknown_count' => 'UNKNOWN');

        return $name;
    }
    
    /**
     * get network list for mobile
     *
     * @return array
     */
    public static function getAreaName()
    {
        $name = array(
           'hokkayi_count' => '北海道', 
            'aomori_count' => '青森県', 
            'yiote_count' => '岩手県', 
            'miyagi_count' => '宮城県', 
            'akita_count' => '秋田県', 
            'yamagata_count' => '山形県', 
            'fukusima_count' => '福島県', 
            'yibaraki_count' => '茨城県', 
            'tochigi_count' => '栃木県', 
            'gunnma_count' => '群馬県', 
            'sayitama_count' => '埼玉県', 
            'chiba_count' => '千葉県', 
            'kanagawa_count' => '神奈川県', 
            'toukyou_count' => '東京都', 
            'niyigata_count' => '新潟県', 
            'toyama_count' => '富山県', 
            'yisikawa_count' => '石川県', 
            'fukuyi_count' => '福井県', 
            'yamanasi_count' => '山梨県', 
            'nagano_count' => '長野県', 
            'gifu_count' => '岐阜県',    
            'sizuoka_count' => '静岡県', 
            'ayichiken_count' => '愛知県', 
            'mie_count' => '三重県', 
            'oosakafu_count' => '大阪府', 
            'hyougo_count' => '兵庫県', 
            'nara_count' => '奈良県', 
            'wakayama_count' => '和歌山県', 
            'tottori_count' => '鳥取県',
            'simane_count' => '島根県',
            'okayama_count' => '和歌山県', 
            'hirosima_count' => '広島県', 
            'simane_count' => '島根県',
            'yamaguchi_count' => '山口県', 
            'tokusima_count' => '徳島県', 
            'kagawa_count' => '香川県', 
            'ehime_count' => '愛媛県', 
            'kouchi_count' => '高知県',     
            'hukuoka_count' => '福岡県', 
            'saga_count' => '佐賀県', 
            'nagasaki_count' => '長崎県', 
            'kumamoto_count' => '熊本県', 
            'ooyita_count' => '大分県', 
            'miyazaki_count' => '宮崎県', 
            'kagosima_count' => '鹿児島県', 
            'okinawa_count' => '沖縄県', 
            'abord_count' => '海外',   
            'unknown_count' => 'その他' );
        return $name;
    }
    
    /**
     * get network list for mobile
     *
     * @return array
     */
    public static function getPersonsRangeName()
    {
        $name = array('none_count' => '0人', 
                        'frist_range_count' => '1～10人', 
                        'second_range_count' => '11～20人',
                        'third_range_count' => '21～30人',
                        'fourth_range_count' => '31～40人',
                        'fifth_range_count' => '41～50人',
                        'sixth_range_count' => '51～60人',
                        'seventh_range_count' => '61～70人',
                        'eighth_range_count' => '71～80人',
                        'ninth_range_count' => '81～90人',
                        'tenth_range_count' => '90～100人',
                        'others_range_count' => '101人以上',
                        'unknown_range_count' => 'UNKNOWN');

        return $name;
    }
    
    /**
     * get time o'clock
     *
     * @return array
     */
    public static function getTimeName()
    {
        $name = array('zero_oclock_count' => '0', 
                      'one_oclock_count' => '1', 
                      'two_oclock_count' => '2', 
                      'three_oclock_count' => '3',
                      'four_oclock_count' => '4',
                      'five_oclock_count' => '5',
                      'six_oclock_count' => '6',
                      'seven_oclock_count' => '7',
                      'eight_oclock_count' => '8',
                      'nine_oclock_count' => '9',
                      'ten_oclock_count' => '10',
                      'eleven_oclock_count' => '11',
                      'twelve_oclock_count' => '12',
                      'thirteen_oclock_count' => '13',
                      'fourteen_oclock_count' => '14',
                      'fifteen_oclock_count' => '15',
                      'sixteen_oclock_count' => '16',
                      'seventeen_oclock_count' => '17',
                      'nighteen_oclock_count' => '18',
                      'nineteen_oclock_count' => '19',
                      'twenty_oclock_count' => '20',
                      'twenty_one_oclock_count' => '21',
                      'twenty_two_oclock_count' => '22',
                      'twenty_three_oclock_count' => '23');
        return $name;
    }
    
    /**
     * get earch day info
     *
     * @return array
     */
    public static function getEachDateInfo($arrInfo,$startDate,$type,$mixiId)
    {
        $dateInfo = array();
        $isSart = 1;
        //if null , no data
        if(empty($startDate)){
            $isSart = 0;
        } else {
            require_once 'Admin/Dal/Log.php';
            $dalLog = Admin_Dal_Log::getDefaultInstance();
            $aryInfo = $dalLog->getLoginUserByFilter($type,1,10,$mixiId,'',$startDate,'desc');
            //if date has no data , no data
            if(empty($aryInfo)){
                $isSart = 0;
            }
        }
        if($isSart == 1){
            foreach ((array)$aryInfo[0] as $key => $value){
                if($key == "mixi_app_id" || $key == "report_date"){
                    $dateInfo[0][$key] = $arrInfo[0][$key];
                } else {
                    $dateInfo[0][$key] = $arrInfo[0][$key] - $value;
                }
            }
        }
           
        $count = count($arrInfo);
        for($i = 0;$i < $count;$i++){
            if($i==0 && $isSart == 1){
                continue;
            }

            $k = $i;
            foreach ((array)$arrInfo[$i] as $key => $value){
                if($key == "mixi_app_id" || $key == "report_date"){
                    $dateInfo[$k][$key] = $value;
                } else {
                    if($isSart == 0 && $i==1){
                        $dateInfo[$k][$key] = $value - $arrInfo[0][$key];
                    } else {
                       $dateInfo[$k][$key] = $value - $arrInfo[$i-1][$key];
                    }
                }
    
            }
        }

        return $dateInfo;
    }

    /**
     * get previous date
     *
     * @return array
     */
    public static function dateDiff($nowDate)
    {
        if(!empty($nowDate)){
            //now date
            $date_elements  =  explode("-",$nowDate);  
            $mkDate = mktime(0,0,0,$date_elements[1],$date_elements[2],$date_elements[0]);
            //for pre date
            //$mkDate = $mkDate - 24*3600;
            $preDate = date( "Y-m-d", $mkDate - 24*3600);
            return $preDate;
        } else  {
            return ''; 
        }
    }

    /**
     * get range count
     *
     * @return array
     */
    public static function getRangeCount($aryParam)
    {
        require_once 'Admin/Dal/Log.php';
        $dalLog = Admin_Dal_Log::getDefaultInstance();
        $aryStartInfo = array();
        if(!empty($aryParam['startDate'])){
            //$aryStartInfo = $dalLog->getInfoByDate($searchType, $this->_appInfo['mixi_app_id'], $startDate);
            $aryTempInfo1 = $dalLog->getLoginUserByFilter($aryParam['searchType'],1,5,$aryParam['mixiAppId'],'',$aryParam['startDate'],'desc');
            $aryStartInfo = $aryTempInfo1[0];
        }
        //$aryEndInfo  = $dalLog->getInfoByDate($searchType, $this->_appInfo['mixi_app_id'], $endDate); 
        $aryTempInfo2 = $dalLog->getLoginUserByFilter($aryParam['searchType'],1,5,$aryParam['mixiAppId'],'',$aryParam['endDate'],'desc');
        $aryEndInfo = $aryTempInfo2[0];
        $count = $dalLog->getLoginCountByFilter($aryParam['mixiAppId'],$aryParam['startDate'],$aryParam['endDate']);
        $aryInfo = array();
        if ( $count==0 ||$aryParam['startDate'] > $aryEndInfo['report_date'] || empty($aryEndInfo) ) {
            $aryInfo = '';
        } else if(!empty($aryParam['startDate']) && !empty($aryStartInfo) && $aryParam['startDate']!=$aryParam['endDate'] && !empty($aryEndInfo)){
            foreach ($aryStartInfo as $key => $value){
                $aryInfo[$key] =  (int)$aryEndInfo[$key]- (int)$value;
            }
        }  else {
            $aryInfo = $aryEndInfo;
        }
        return $aryInfo;
    }
    
    /**
     * get five max data
     *
     */
    public static function getMaxCount($aryInfo)
    {
        $aryResult = array();
        $aryMaxInfo = array();
        $aryKeyTemp = array();
        $aryValueTemp = array();
        if(!empty($aryInfo)){
            foreach ($aryInfo as $key => $value){
                if($key != 'mixi_app_id' && $key != 'report_date'){
                    $aryKeyTemp[] = $key;
                    $aryValueTemp[] = $value;
                }
            }
        } else {
            $aryInfo = $aryInfo;
        }
        //
        $n = count($aryKeyTemp);   
        for ($i=0;$i<$n;$i++) 
        {     
            for ($j=$n-2;$j>=$i;$j--) 
            { 
                if($aryValueTemp[$j+1]>$aryValueTemp[$j]) 
                {
                    $tmp = $aryValueTemp[$j+1]; 
                    $aryValueTemp[$j+1]=$aryValueTemp[$j]; 
                    $aryValueTemp[$j]=$tmp; 
                    
                    $tmp = $aryKeyTemp[$j+1]; 
                    $aryKeyTemp[$j+1]=$aryKeyTemp[$j]; 
                    $aryKeyTemp[$j]=$tmp; 
                }
            }
        }
        $sum = $aryValueTemp[0];
        for ($k=1;$k<count($aryValueTemp);$k++) { 
            $sum = $sum + $aryValueTemp[$k];
        }
        $aryResult['sum'] = $sum;
        $max = count($aryKeyTemp) < 5 ? count($aryKeyTemp):5;
        
        for ($k=0;$k<$max;$k++) { 
            $aryMaxInfo[$k]['key'] = $aryKeyTemp[$k];
            $aryMaxInfo[$k]['count'] = $aryValueTemp[$k];
        }

        $aryResult['maxinfo'] = $aryMaxInfo;
        return $aryResult;
    }
    
}