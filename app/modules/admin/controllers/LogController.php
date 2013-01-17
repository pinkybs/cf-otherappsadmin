<?php

/**
 * Admin System Setting Controller(modules/admin/controllers/Admin_LogController.php)
 * Linno Admin System Setting Controller
 *
 * @copyright  Copyright (c) 2008 Community Factory Inc. (http://communityfactory.com)
 * @create    2009/11/23    hwq
 */
class Admin_LogController extends MyLib_Zend_Controller_Action_Admin
{
    /**
     * app id
     * @var Integer
     */
    protected $_id;
    
    /**
     * app info
     * @var array
     */
    protected $_appInfo;
   
    /**
     * start date
     * @var String
     */
    protected $_startDate;
    
    /**
     * end date
     * @var String
     */
    protected $_endDate;
    
    /**
     * check circle id
     */
    private function checkId()
    {
        $appId = $this->_request->getParam('appId');
        $pageIndex = (int)$this->_request->getParam('pageIndex');
        $hidSrhName = $this->_request->getParam('hidSrhName');
        $hidSrhOwner = $this->_request->getParam('hidSrhOwner');
        $hidSrhCate = (int)$this->_request->getParam('hidSrhCate');
        require_once 'Admin/Dal/AppSite.php';
        $dalApp = Admin_Dal_AppSite::getDefaultInstance();
        //app id is null
        if(empty($appId)){
            /*if ($this->_user->role_type == 3) {
                $aryApp = $dalApp->getSiteByOwner($this->_user->uid);
            } else {
                $aryApp = $dalApp->getSiteListByFilter(1,100,'','','',$this->_user->uid,$this->_user->role_type);
            }
    
            $showKey = '';
            //get app count for each owner
            foreach ($aryApp as $key => $value) {
                if($value['log_tool']) {
                    $showKey = $key;
                    break;
                }
            }
            $this->_appInfo = $aryApp[$showKey];*/
            if (!($this->_request->getActionName() == 'listsite')){
                $this->_forward('notfound', 'error', 'admin');
                return false;
            }
        } else {
            //if app id is not number or <0
            if (!(is_numeric($appId) && $appId > 0)) {
                $this->_forward('notfound', 'error', 'admin');
                return false;
            }
            
            $aryApp = $dalApp->getSiteById($appId);
            $hasTable = $dalApp->CheckHasAppLogTable($aryApp['mixi_app_id'],'basic');
            if (!$hasTable) {
                $this->_forward('nodata', 'error', 'admin');
                return false;
            }
            $this->_appInfo = $aryApp;
            $this->view->appInfo = $this->_appInfo;
            $this->_id = $this->_appInfo['app_id'];
        }
        $this->view->pageIndex = empty($pageIndex) ? 1 : $pageIndex;
        $this->view->hidSrhName = empty($hidSrhName) ? '' : $hidSrhName;
        $this->view->hidSrhOwner = empty($hidSrhOwner) ? '' : $hidSrhOwner;
        $this->view->hidSrhCate = empty($hidSrhCate) ? '' : $hidSrhCate;
        return true;
    }    

    /**
     * check uid
     *
     */
    private function getRelatedInfo()
    {
        $result = $this->checkId();
        if (!$result) {
            return  $result;
        }
        
        $startDate = $this->_request->getParam('datepickerStart');
        $endDate = $this->_request->getParam('datepickerEnd');
        $type = (int)$this->_request->getParam('showType' ,1 );
        if(empty($endDate)){
            $endDate = date("Y-m-d");
         }

        if(!empty($startDate)&&($startDate > $endDate)){
             $this->_redirect($this->_baseUrl . '/log/'.$this->_request->getActionName().'?appId='.$this->_id);
             return false;
        }
        $this->_startDate = $startDate;
        $this->_endDate = $endDate;
        $this->view->startDate = $this->_startDate;
        $this->view->endDate = $this->_endDate;

        return $type;
    }
    
    /**
     * get basic info
     *
     */
    private function getBasicInfo($searchType,$showType)
    {
        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($this->_startDate);
        require_once 'Admin/Dal/Log.php';
        $dalLog = Admin_Dal_Log::getDefaultInstance();
        //get basic info
        $aryBasicInfo = $dalLog->getLoginUserByFilter('basic',1,100,$this->_appInfo['mixi_app_id'],$startDate,$endDate,'asc');
        if($this->_startDate!=$this->_endDate){
            if(count($aryBasicInfo)>0) {
                $aryDateBasicInfo = Admin_Bll_Common::getEachDateInfo($aryBasicInfo,$preDate,'basic',$this->_appInfo['mixi_app_id']);
            } else {
                $aryDateBasicInfo = '';
            }
        }
        if($this->_startDate==$this->_endDate){
            $aryDateBasicInfo = $dalLog->getDailyCount($searchType,$this->_appInfo['mixi_app_id'],$this->_startDate);
        }

        require_once  "MyLib/libchart/classes/libchart.php";
        if(count($aryBasicInfo)>0) {
            //if line gragh
            if($showType == 2 ){
                $chart = new LineChart(900, 350);
            } else {//if hisgarmh
                $chart = new VerticalBarChart(900, 350);
            }
            
            $dataSet = new XYDataSet();
            if($showType == 2 ){
                if(count($aryDateBasicInfo)==1){
                    $dataSet->addPoint(new Point(0, 0));
                }
            }
            $aryTimeName = Admin_Bll_Common::getTimeName();
            foreach ($aryDateBasicInfo as $key=>$value){
                if($this->_startDate == $this->_endDate){
                    if($key != 'mixi_app_id' && $key != 'report_date'){
                        $dataSet->addPoint(new Point($aryTimeName[$key].'時', $value));
                    }
                } else{
                    if($searchType == 'login'){
                        $dataSet->addPoint(new Point($value['report_date'], $value['login_count']));
                    }else if($searchType == 'use'){
                        $dataSet->addPoint(new Point($value['report_date'], $value['daily_login_count']));
                    }else if($searchType == 'feed'){
                        $dataSet->addPoint(new Point($value['report_date'], $value['feed_count']));
                    }
                }
            }

            $chart->setDataSet($dataSet);
            $aryParam = array('searchType' => 'basic',
                'mixiAppId' => $this->_appInfo['mixi_app_id'],
                'startDate' => $preDate,
                'endDate' => $this->_endDate);
            $aryBasic = Admin_Bll_Common::getRangeCount($aryParam);
            if($searchType == 'login'){
                $average = floor($aryBasic['login_count']/count($aryBasicInfo));
                $sum = $aryBasic['login_count'];
            }else if($searchType == 'use'){
                $average = floor($aryBasic['daily_login_count']/count($aryBasicInfo));
                $sum = $aryBasic['daily_login_count'];
            }else if($searchType == 'feed'){
                $average = floor($aryBasic['feed_count']/count($aryBasicInfo));
                $sum = $aryBasic['feed_count'];
            }

            $chart->setTitle("Sum:".$sum."      Average:".$average);
            $chart->render("generated/".$searchType."/".$searchType.$this->_user->uid."_".$showType.".png");
            $this->view->sum = $sum;
        }

        return $aryBasicInfo;
    }
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
        $this->view->uid = $this->_user->uid;
    }

    /**
     * sys user controller index action
     *
     */
    public function indexAction()
    {
        $this->_forward('list', 'log', 'admin');
        return;
    }
    
    /**
     * manager controller list site action
     *
     */
    public function listAction()
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
     * app top action
     *
     */
    public function topAction()
    {
        $result = $this->checkId();
        if (!$result) {
            return;
        }
        $startDate = $this->_request->getParam('datepickerStart');
        $endDate = $this->_request->getParam('datepickerEnd');

        if(empty($endDate)){
            $endDate = date("Y-m-d");
        }

        if(!empty($startDate)&&($endDate < $startDate)){
             return;
        }
        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($startDate);
        
        require_once 'Admin/Dal/Log.php';
        $dalLog = Admin_Dal_Log::getDefaultInstance();
        $aryBasicInfo = $dalLog->getLoginUserByFilter('basic',1,100,$this->_appInfo['mixi_app_id'],$startDate,$endDate,'asc');

        if(count($aryBasicInfo)>0) {
            $aryDateBasicInfo = Admin_Bll_Common::getEachDateInfo($aryBasicInfo,$preDate,'basic',$this->_appInfo['mixi_app_id']);
        } else {
            $aryDateBasicInfo = '';
        }
        
        require_once 'Admin/Bll/Common.php';
        //get gender info
        $aryParam = array('searchType' => 'gender',
            'mixiAppId' => $this->_appInfo['mixi_app_id'],
            'startDate' => $preDate,
            'endDate' => $endDate);
        $aryGenderInfo = Admin_Bll_Common::getRangeCount($aryParam);
        if(!empty($aryGenderInfo)) {
            $sum = $aryGenderInfo['male_count'] + $aryGenderInfo['female_count'] + $aryGenderInfo['unknown_count'];
            $aryGenderInfo['male_rate'] = floor($aryGenderInfo['male_count'] / $sum * 100);
            $aryGenderInfo['female_rate'] = floor($aryGenderInfo['female_count'] / $sum * 100);
            $aryGenderInfo['unknown_rate'] = floor($aryGenderInfo['unknown_count'] / $sum * 100);
        }

        //get age info
        $aryParam['searchType'] = 'age';
        $aryAgeInfo = Admin_Bll_Common::getRangeCount($aryParam); 
        if(!empty($aryAgeInfo)) {
            $result = Admin_Bll_Common::getMaxCount($aryAgeInfo);
        }
        if(!empty($result['maxinfo'])){
            $aryMaxAgeInfo = $result['maxinfo'];
            foreach ($aryMaxAgeInfo as $key => $value){
                $aryName = Admin_Bll_Common::getAgeRangeName();
                $aryMaxAgeInfo[$key]['name'] = $aryName[$aryMaxAgeInfo[$key]['key']];
                $aryMaxAgeInfo[$key]['rate'] = floor($aryMaxAgeInfo[$key]['count']/$result['sum']*100);
            }
        }

        //get address info
        $aryParam['searchType'] = 'address';
        $aryAddressInfo = Admin_Bll_Common::getRangeCount($aryParam);
        if(!empty($aryAddressInfo)) {
            $result = Admin_Bll_Common::getMaxCount($aryAddressInfo);
        }
        if(!empty($result['maxinfo'])){
            $aryMaxAddressInfo = $result['maxinfo'];
            $aryName = Admin_Bll_Common::getAreaName();
            foreach ($aryMaxAddressInfo as $key => $value){
                $aryMaxAddressInfo[$key]['name'] = $aryName[$value['key']];
                $aryMaxAddressInfo[$key]['rate'] = floor($value['count']/$result['sum']*100);
            }
        }

        //get mymixi info
        $aryParam['searchType'] = 'mymixi';
        $aryMyMixiInfo = Admin_Bll_Common::getRangeCount($aryParam);
        if(!empty($aryMyMixiInfo)) {
            $result = Admin_Bll_Common::getMaxCount($aryMyMixiInfo);
        }
        if(!empty($result['maxinfo'])){
            $aryMaxMyMixiInfo = $result['maxinfo'];
            $aryName = Admin_Bll_Common::getPersonsRangeName();
            foreach ($aryMaxMyMixiInfo as $key => $value){
                $aryMaxMyMixiInfo[$key]['name'] = $aryName[$value['key']];
                $aryMaxMyMixiInfo[$key]['rate'] = floor($value['count'] / $result['sum'] * 100);
            }
        }
        //get invite info
        $aryParam['searchType'] = 'invite';
        $aryInviteInfo = Admin_Bll_Common::getRangeCount($aryParam);
		info_log(count(aryInviteInfo),log);
		info_log('ccc',log);
        if(!empty($aryInviteInfo)) {
            $result = Admin_Bll_Common::getMaxCount($aryInviteInfo);
        }
        if(!empty($result['maxinfo'])){
            $aryMaxInviteInfo = $result['maxinfo'];
            $aryName = Admin_Bll_Common::getPersonsRangeName();
            foreach ($aryMaxInviteInfo as $key => $value){
                $aryMaxInviteInfo[$key]['name'] = $aryName[$value['key']];
                $aryMaxInviteInfo[$key]['rate'] = floor($value['count']/$result['sum']*100);
            }
        }
        $this->view->startDate = $startDate;
        $this->view->endDate = $endDate;
        $this->view->inviteInfo = $aryMaxInviteInfo;
        $this->view->myMixiInfo = $aryMaxMyMixiInfo;
        $this->view->addressInfo = $aryMaxAddressInfo;
        $this->view->ageInfo = $aryMaxAgeInfo;
        $this->view->genderInfo = $aryGenderInfo;
        $this->view->basicInfo = $aryDateBasicInfo;
        $this->view->action = 'log/top';
        $this->view->isTop = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
       
    /**
     * login user action
     *
     */
    public function loginuserAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }

        $aryBasicInfo = $this->getBasicInfo('login',$type);
        $this->view->type = $type;
        $this->view->aryBasicInfo = $aryBasicInfo;
        $this->view->beginDate = $aryBasicInfo[0]['report_date'];
        $this->view->action = 'log/loginuser';
        $this->view->isLogin = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * feed action
     *
     */
    public function feedAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }
        $aryBasicInfo = $this->getBasicInfo('feed',$type);
        $this->view->type = $type;
        $this->view->aryBasicInfo = $aryBasicInfo;
        $this->view->beginDate = $aryBasicInfo[0]['report_date'];
        $this->view->action = 'log/feed';
        $this->view->isFeed = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * gender action
     *
     */
    public function genderAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }
        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($this->_startDate);

        $aryParam = array('searchType' => 'gender',
            'mixiAppId' => $this->_appInfo['mixi_app_id'],
            'startDate' => $preDate,
            'endDate' => $this->_endDate);
        $aryGender = Admin_Bll_Common::getRangeCount($aryParam);
        require_once  "MyLib/libchart/classes/libchart.php";
        if(count($aryGender)>0) {
            //if line gragh
            if($type == 2 ){
                $chart = new HorizontalBarChart(900, 300);
                
            } else {//if hisgarmh
                $chart = new PieChart(900, 300);
            }
            //$chart = new PieChart(900, 300);
            $dataSet = new XYDataSet();
            $aryName = Admin_Bll_Common::getGenderName();
            foreach ($aryGender as $key=>$value){
                if($key != "mixi_app_id" && $key != "report_date"){
                    $dataSet->addPoint(new Point($aryName[$key], $value));
                }
            }
            $chart->setDataSet($dataSet);
            $chart->setTitle("男女比率");
            $chart->render("generated/gender/gender".$this->_user->uid."_".$type.".png");
        }
        if(!empty($aryGender)) {
            $sum = $aryGender['male_count'] + $aryGender['female_count'] + $aryGender['unknown_count'];
            $aryGender['male_rate'] = floor($aryGender['male_count'] / $sum * 100);
            $aryGender['female_rate'] = floor($aryGender['female_count'] / $sum * 100);
            $aryGender['unknown_rate'] = floor($aryGender['unknown_count'] / $sum * 100);
        }
        $this->view->type = $type;
        $this->view->aryInfo = $aryGender;
        //$this->view->beginDate = $aryGender['report_date'];
        $this->view->action = 'log/gender';
        $this->view->isGender = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }

    /**
     * age action
     *
     */
    public function ageAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }
        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($this->_startDate);

        $aryParam = array('searchType' => 'age',
            'mixiAppId' => $this->_appInfo['mixi_app_id'],
            'startDate' => $preDate,
            'endDate' => $this->_endDate);
        $aryAge = Admin_Bll_Common::getRangeCount($aryParam);
        require_once  "MyLib/libchart/classes/libchart.php";
        if(count($aryAge)>0) {
            //if line gragh
            if($type == 2 ){
                $chart = new HorizontalBarChart(900, 300);
                
            } else {//if hisgarmh
                $chart = new PieChart(900, 300);
            }
            //$chart = new PieChart(900, 300);
            $dataSet = new XYDataSet();
            $aryName = Admin_Bll_Common::getAgeRangeName();
            
            if(!empty($aryAge)) {
                $sum = 0;
                foreach ($aryAge as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $sum = $sum + $value;
                    }
                }
                $this->view->sum = $sum;

                foreach ($aryAge as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $dataSet->addPoint(new Point($aryName[$key], $value));
                    }
                }
                $chart->setDataSet($dataSet);
                $chart->setTitle("年齢比率");
                $chart->render("generated/age/age".$this->_user->uid."_".$type.".png");
            }
        }

        $this->view->type = $type;
        $this->view->aryInfo = $aryAge;
        $this->view->aryAgeName = $aryName;
        //$this->view->beginDate = $aryGender['report_date'];
        $this->view->action = 'log/age';
        $this->view->isAge = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * address action
     *
     */
    public function addressAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }
        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($this->_startDate);

        $aryParam = array('searchType' => 'address',
            'mixiAppId' => $this->_appInfo['mixi_app_id'],
            'startDate' => $preDate,
            'endDate' => $this->_endDate);
        $aryAddress = Admin_Bll_Common::getRangeCount($aryParam);
        require_once  "MyLib/libchart/classes/libchart.php";
        if(count($aryAddress)>0) {
            //if line gragh
            if($type == 2 ){
                $chart = new HorizontalBarChart(900, 800);
                
            } else {//if hisgarmh
                $chart = new PieChart(900, 400);
            }
            //$chart = new PieChart(900, 300);
            $dataSet = new XYDataSet();
            $aryName = Admin_Bll_Common::getAreaName();
            
            if(!empty($aryAddress)) {
                $sum = 0;
                foreach ($aryAddress as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $sum = $sum + $value;
                    }
                }
                $this->view->sum = $sum;

                foreach ($aryAddress as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $dataSet->addPoint(new Point($aryName[$key], $value));
                    }
                }
                $chart->setDataSet($dataSet);
                $chart->setTitle("現在地比率");
                $chart->render("generated/address/address".$this->_user->uid."_".$type.".png");
            }
        }

        $this->view->type = $type;
        $this->view->aryInfo = $aryAddress;
        $this->view->aryAddressName = $aryName;
        //$this->view->beginDate = $aryGender['report_date'];
        $this->view->action = 'log/address';
		$this->view->isAddress = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
   /**
     * mymixi action
     *
     */
    public function mymixiAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }
        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($this->_startDate);

        $aryParam = array('searchType' => 'mymixi',
            'mixiAppId' => $this->_appInfo['mixi_app_id'],
            'startDate' => $preDate,
            'endDate' => $this->_endDate);
        $aryMymixi = Admin_Bll_Common::getRangeCount($aryParam);
        require_once  "MyLib/libchart/classes/libchart.php";
        if(count($aryMymixi)>0) {
            //if line gragh
            if($type == 2 ){
                $chart = new HorizontalBarChart(900, 350);
                
            } else {//if hisgarmh
                $chart = new PieChart(900, 350);
            }
            //$chart = new PieChart(900, 300);
            $dataSet = new XYDataSet();
            $aryName = Admin_Bll_Common::getPersonsRangeName();
            
            if(!empty($aryMymixi)) {
                $sum = 0;
                foreach ($aryMymixi as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $sum = $sum + $value;
                    }
                }
                $this->view->sum = $sum;
                //$arrAgeInfo = array();
                foreach ($aryMymixi as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $dataSet->addPoint(new Point($aryName[$key], $value));
                    }
                }
                $chart->setDataSet($dataSet);
                $chart->setTitle("マイミク分布数");
                $chart->render("generated/mymixi/mymixi".$this->_user->uid."_".$type.".png");
            }
        }

        $this->view->type = $type;
        $this->view->aryInfo = $aryMymixi;
        $this->view->aryMixiName = $aryName;
        //$this->view->beginDate = $aryGender['report_date'];
        $this->view->action = 'log/mymixi';
		$this->view->isMyMixi = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
   /**
     * invite action
     *
     */
    public function inviteAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }
        require_once 'Admin/Bll/Common.php';
        $preDate = Admin_Bll_Common::dateDiff($this->_startDate);

        $aryParam = array('searchType' => 'invite',
            'mixiAppId' => $this->_appInfo['mixi_app_id'],
            'startDate' => $preDate,
            'endDate' => $this->_endDate);
        $aryInvite = Admin_Bll_Common::getRangeCount($aryParam);
        require_once  "MyLib/libchart/classes/libchart.php";
        if(count($aryInvite)>0) {
            //if line gragh
            if($type == 2 ){
                $chart = new HorizontalBarChart(900, 350);
                
            } else {//if hisgarmh
                $chart = new PieChart(900, 350);
            }
            //$chart = new PieChart(900, 300);
            $dataSet = new XYDataSet();
            $aryName = Admin_Bll_Common::getPersonsRangeName();
            
            if(!empty($aryInvite)) {
                $sum = 0;
                foreach ($aryInvite as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $sum = $sum + $value;
                    }
                }
                $this->view->sum = $sum;
                //$arrAgeInfo = array();
                foreach ($aryInvite as $key=>$value){
                    if($key != "mixi_app_id" && $key != "report_date"){
                        $dataSet->addPoint(new Point($aryName[$key], $value));
                    }
                }
                $chart->setDataSet($dataSet);
                $chart->setTitle("マイミク招待者数");
                $chart->render("generated/invite/invite".$this->_user->uid."_".$type.".png");
            }
        }
        
        $this->view->type = $type;
        $this->view->aryInfo = $aryInvite;
        $this->view->aryInviteName = $aryName;
        //$this->view->beginDate = $aryGender['report_date'];
        $this->view->action = 'log/invite';
		$this->view->isInvite = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * daily use action
     *
     */
    public function dailyuserAction()
    {
        $type = $this->getRelatedInfo();
        if ($type == false) {
            return;
        }
        $aryBasicInfo = $this->getBasicInfo('use',$type);
        $this->view->type = $type;
        $this->view->aryBasicInfo = $aryBasicInfo;
        $this->view->beginDate = $aryBasicInfo[0]['report_date'];
        $this->view->action = 'log/dailyuser';
		$this->view->isUse = 1;
        $this->view->title = 'OpenSocial APP Control Panel｜Admin';
        $this->render();
    }
    
    /**
     * preDispatch
     *
     */
    function preDispatch()
    {

    }
    

    


    
}