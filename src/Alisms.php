<?php
/**
 * @Author: LBF
 * @Date:   2017-03-12 00:05:36
 * @Last Modified by:   LBF
 * @Last Modified time: 2017-03-12 19:19:52
 *  define('ACCESS_ID','XXXXXXXXXX');
    define('ACCESS_SECRET','XXXXXXXXXX');
    $sms=new Alisms(ACCESS_ID,ACCESS_SECRET);
    $sms->setSignName('XXXXX')
    ->setTemplateCode('SMS_52355065')
    ->setRecMobile('18629152395')
    ->setParamString('{"code":"156987"}')
    ->send();
 */
namespace Alisms;

use Alisms\Core\Regions\ProductDomain;
use Alisms\Core\Regions\EndpointProvider;
use Alisms\Core\Regions\Endpoint;

use Alisms\Sms\Request\V20160927 as Sms;
use Alisms\Core\Profile\DefaultProfile;
use Alisms\Core\DefaultAcsClient;

class Alisms{

    protected static $APP_ID;
    protected static $APP_SECRET;
    protected $client;
    protected $request;
    protected $signName;
    protected $templateCode;
    protected $recMobile;
    protected $paramString;

    public function __construct($app_id='',$app_secrect=''){

        self::$APP_ID=$app_id || config('alisms.app_id')?:die('APP_ID Not Empty');
        self::$APP_SECRET=$app_secrect || config('alisms.app_secret')?:dei('APP_SECRECT Not empty');

        $regionIds = [
         "cn-hangzhou",
         "cn-beijing",
         "cn-qingdao",
         "cn-hongkong",
         "cn-shanghai",
         "us-west-1",
         "cn-shenzhen",
         "ap-southeast-1"
        ];
        $productDomains =array(
         new ProductDomain("Mts", "mts.cn-hangzhou.aliyuncs.com"),
         new ProductDomain("ROS", "ros.aliyuncs.com"),
         new ProductDomain("Dm", "dm.aliyuncs.com"),
         new ProductDomain("Sms", "sms.aliyuncs.com"),
         new ProductDomain("Bss", "bss.aliyuncs.com"),
         new ProductDomain("Ecs", "ecs.aliyuncs.com"),
         new ProductDomain("Oms", "oms.aliyuncs.com"),
         new ProductDomain("Rds", "rds.aliyuncs.com"),
         new ProductDomain("BatchCompute", "batchCompute.aliyuncs.com"),
         new ProductDomain("Slb", "slb.aliyuncs.com"),
         new ProductDomain("Oss", "oss-cn-hangzhou.aliyuncs.com"),
         new ProductDomain("OssAdmin", "oss-admin.aliyuncs.com"),
         new ProductDomain("Sts", "sts.aliyuncs.com"),
         new ProductDomain("Push", "cloudpush.aliyuncs.com"),
         new ProductDomain("Yundun", "yundun-cn-hangzhou.aliyuncs.com"),
         new ProductDomain("Risk", "risk-cn-hangzhou.aliyuncs.com"),
         new ProductDomain("Drds", "drds.aliyuncs.com"),
         new ProductDomain("M-kvstore", "m-kvstore.aliyuncs.com"),
         new ProductDomain("Ram", "ram.aliyuncs.com"),
         new ProductDomain("Cms", "metrics.aliyuncs.com"),
         new ProductDomain("Crm", "crm-cn-hangzhou.aliyuncs.com"),
         new ProductDomain("Ocs", "pop-ocs.aliyuncs.com"),
         new ProductDomain("Ots", "ots-pop.aliyuncs.com"),
         new ProductDomain("Dqs", "dqs.aliyuncs.com"),
         new ProductDomain("Location", "location.aliyuncs.com"),
         new ProductDomain("Ubsms", "ubsms.aliyuncs.com"),
         new ProductDomain("Drc", "drc.aliyuncs.com"),
         new ProductDomain("Ons", "ons.aliyuncs.com"),
         new ProductDomain("Aas", "aas.aliyuncs.com"),
         new ProductDomain("Ace", "ace.cn-hangzhou.aliyuncs.com"),
         new ProductDomain("Dts", "dts.aliyuncs.com"),
         new ProductDomain("R-kvstore", "r-kvstore-cn-hangzhou.aliyuncs.com"),
         new ProductDomain("PTS", "pts.aliyuncs.com"),
         new ProductDomain("Alert", "alert.aliyuncs.com"),
         new ProductDomain("Push", "cloudpush.aliyuncs.com"),
         new ProductDomain("Emr", "emr.aliyuncs.com"),
         new ProductDomain("Cdn", "cdn.aliyuncs.com"),
         new ProductDomain("COS", "cos.aliyuncs.com"),
         new ProductDomain("CF", "cf.aliyuncs.com"),
         new ProductDomain("Ess", "ess.aliyuncs.com"),
         new ProductDomain("Ubsms-inner", "ubsms-inner.aliyuncs.com"),
         new ProductDomain("Green", "green.aliyuncs.com")

        );
        $endpoint = new Endpoint("cn-hangzhou", $regionIds, $productDomains);
        $endpoints = array($endpoint);
        EndpointProvider::setEndpoints($endpoints);
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou",self::$APP_ID, self::$APP_SECRET);
        $this->client = new DefaultAcsClient($iClientProfile);
        $this->request = new Sms\SingleSendSmsRequest();
    }

    public function setSignName($signName){
        $this->signName=$signName;
        return $this;
    }

    public function setTemplateCode($templateCode){
        $this->templateCode=$templateCode;
        return $this;
    }

    public function setRecMobile($mobile){
        $this->recMobile=$mobile;
        return $this;
    }
    public function setParamString($json){
        $this->paramString=$json;
        return $this;
    }
    public function send(){
        $request=$this->request;
        $signName=$this->signName || config('alisms.sign_ame');
        $templateCode=$this->templateCode || config('alisms.template_code');
        $request->setSignName($signName);/*签名名称*/
        $request->setTemplateCode($templateCode);/*模板code*/
        $request->setRecNum($this->recMobile);/*目标手机号*/
        $request->setParamString($this->paramString);/*模板变量，数字一定要转换为字符串*/
        try{
            $reponse=$this->client->getAcsResponse($request);
            return $reponse;

        }catch(ClientException $e){
            return $e;

        }catch (ServerException $e){
            return $e;
        }

    }
}
