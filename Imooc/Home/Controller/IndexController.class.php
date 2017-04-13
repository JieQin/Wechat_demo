<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{
	public function index(){
		$timestamp = $_GET['timestamp'];
		$signature = $_GET['signature'];
		$echostr   = $_GET['echostr'];
		$nonce     = $_GET['nonce'];
		$token     = 'weixin';
		$array     = array();
		$array     = array($nonce, $timestamp, $token);
		sort($array);
		$str = sha1( implode( $array ) );
		if( $str == $signature && $echostr  ){
			echo $echostr;
			exit;
		}else{
			$this->reponseMsg();
		}
	}
	//订阅事件,文本回复
	public function reponseMsg(){
		//1.获取到微信推送过来的post数据(xml格式)
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		//2.处理消息类型.并设置回复类型和内容
		$postObj = simplexml_load_string( $postArr );
		//3.判断该数据包是否是订阅的时间推送
		if( strtolower( $postObj->MsgType ) == 'event' ){
			//4.如果是关注事件
			if( strtolower( $postObj->Event )  == 'subscribe' ){
				//5.回复用户消息
				$content  = '欢迎关注我的微信公众号!';
				$indexModel = D('Index');
				$indexModel->responseText($postObj, $content);
			}
			if( strtolower( $postObj->Event ) == 'click' ){
				if( strtolower( $postObj->EventKey ) == 'item1' ){
					$content = "这是item1的菜单事件推送";
				}
				if( strtolower( $postObj->EventKey ) == 'songs' ){
					$content = "这是歌曲菜单事件推送";
				}
				$indexModel = D('Index');
				$indexModel->responseText($postObj, $content);
			}
			if( strtolower( $postObj->Event ) == 'view' ){
				$content = "跳转的连接:".$postObj->EventKey;
				$indexModel = D('Index');
				$indexModel->responseText($postObj, $content);
			}
		}
	}
	public function responseMsg(){
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		$postObj = simplexml_load_string($postArr);
		if( strtolower( $postObj->MsgType ) == 'text' && trim($postObj->Content) == 'tuwen1' ){
			$indexModel = D('Index');
                        $arr      = array(
                                        array(
                                            'title'=>'imooc',
                                            'description'=>'imooc is very cool',
                                            'picUrl'=>'http:bizhi.zhuoku.com/wall/jie/20070518/avril/011.jpg',
                                            'url'=>'http:www.imooc.com',
                                        ),
                                        array(
                                            'title'=>'baidu',
                                            'description'=>'baidu is very laji',
                                            'picUrl'=>'http:img.mukewang.com/573347590001c52b07410741.jpg',
                                            'url'=>'http:www.baidu.com',
                                        ),
                                    );
			$indexModel->responseNews($postObj, $arr);
		}else{
				switch( trim( $postObj->Content ) ){
					case 1:
						$content =  "您输入的是数字1";
					break;
					case 2:
						$content = "您输入的是数字2";
					break;
					case '你好':
						$content = "您好.我叫魏杰!很高心认识您!";
					break;
					case '搜索':
						$content = "<a href='http:www.biying.com'>必应</a>";
					break;
					case '电话号码':
						$content = "15600045558";
					break;
					default:		
						$ch = curl_init();
 					        $url = 'http://apis.baidu.com/heweather/pro/weather?city='.$postObj->Content;
    						$header = array(
        					'apikey: 25cc47466a5204ac406381ce6d6a4348',
    						);
    						// 添加apikey到header
    						curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    						// 执行HTTP请求
    						curl_setopt($ch , CURLOPT_URL , $url);
    						$res = curl_exec($ch);
    						$arr = json_decode($res, true);
						$content = "预警:".$arr['HeWeather data service 3.0']['0']['alarms']['0']['level']."\n"."污染程度:".$arr['HeWeather data service 3.0']['0']['aqi']['city']['qlty']."\n"."城市:".$arr['HeWeather data service 3.0']['0']['basic']['city']."\n"."气温:".$arr['HeWeather data service 3.0']['0']['daily_forecast']['0']['tmp']['min']."~".$arr['HeWeather data service 3.0']['0']['daily_forecast']['0']['tmp']['max'];
					break;
				}
			$indexModel = D('Index');
			$indexModel->responseText($postObj, $content);
		}
	}
	public function getWxServerIp(){
		$access_token = 'Ah7zireRoS4GLI-1-pC9oSe-trMM-7BqUo04CVhPrMYq7qYsOBon4Leg_m0o5JEzycQtd5oCqsypB6y-5aZ3cMqBvcRFWFNnm4WU3qNUJsrcICeq90785NtDY0HTiHngNZSgAIAVHA';
		$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$access_token;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$res = curl_exec($ch);
		curl_close($ch);
		if( curl_errno($ch) ){
			var_dump( curl_errno($ch) );
		}
		$arr = json_decode($res, true);
		echo "<pre>";
		var_dump($arr);
		echo "</pre>";
	}
	public function demo(){
		$indexModel = D('Index');
		var_dump($indexModel);
	}
	public function tianqi(){
		
						$ch = curl_init();
 					        $url = 'http://apis.baidu.com/heweather/pro/weather?city=beijing';
    						$header = array(
        					'apikey: 25cc47466a5204ac406381ce6d6a4348',
    						);
    						// 添加apikey到header
    						curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    						// 执行HTTP请求
    						curl_setopt($ch , CURLOPT_URL , $url);
    						$res = curl_exec($ch);
    						$arr = json_decode($res, true);
						$content = "预警:".$arr['HeWeather data service 3.0']['0']['alarms']['0']['level']."\n"."污染程度:".$arr['HeWeather data service 3.0']['0']['aqi']['city']['qlty']."\n"."城市:".$arr['HeWeather data service 3.0']['0']['basic']['city']."\n"."气温:".$arr['HeWeather data service 3.0']['0']['daily_forecast']['0']['tmp']['min']."~".$arr['HeWeather data service 3.0']['0']['daily_forecast']['0']['tmp']['max'];
						dump($content);
	}
	public function http_url($url, $type="get", $res="json", $arr=''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if( $type == 'post' ){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
		}
		$output = curl_exec($ch);
		curl_close($ch);
		if( $res == 'json' ){
			if( curl_errno($ch) ){
				return $curl_errno($ch);
			}else{
				return json_decode($output, true);
			}
		}
	}
	public function getWxAccessToken(){
		if( $_SESSION['access_token'] && $_SESSION['expire_time']>time() ){
			return $_SESSION['access_token'];
		}else{
			$appid = 'wx79b230cc76e620e4';
			$appsecret = '1ebf923be2aa9fc59c56ffd24f3e7180';
			$url   = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
			$res = $this->http_url( $url, 'get', 'json' );
			$access_token = $res['access_token'];
			$_SESSION['access_token'] = $access_token;
			$_SESSION['expire_time']  = time()+7000;
			return $access_token;
		}
	}
	public function definedItem(){
		$access_token = $this->getWxAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		$postArr = array(
			'button'=>array(
				array(
					'name'=>urlencode('菜单一'),
					'type'=>'click',
					'key'=>'item1',
				),
				array(
					'name'=>urlencode('菜单二'),
					'sub_button'=>array(
						array(
							'name'=>urlencode('歌曲'),
							'type'=>'click',
							'key'=>'songs',
						),
						array(
							'name'=>urlencode('电影'),
							'type'=>'view',
							'url'=>'http://www.biying.com',
						),
					),
				),
				array(
					'name'=>urlencode('菜单三'),
					'type'=>'view',
					'url'=>'http://www.imooc.com',
				),
			),
		);
		$postJson = urldecode( json_encode( $postArr ) );
		$res = $this->http_url($url, 'post', 'json', $postJson);
		dump($res);
	}
	public function sendMsgAll(){
		$access_token = $this->getWxAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=".$access_token;
		$array = array(
			'touser'=>'opiO1szrOLkuKgvmzmcpTvCUPef8',
			'text'=>array( 'content'=>'imooc is very good.' ),
			'msgtype'=>'text',
		);
		$postJson = json_encode( $array );
		$res = $this->http_url( $url, 'post', 'json', $postJson );
		dump($res);
	}
	public function sendTemplateMsg(){
		$access_token = $this->getWxAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$array = array(
			'touser'=>'opiO1szrOLkuKgvmzmcpTvCUPef8',
			'template_id'=>'71JkL_fhaZywVT3MB8iQhpIElS_7Jn1DDur81GRypxs',
			'url'=>'http://www.imooc.com',
			'data'=>array(
				'name'=>array('value'=>'hello','color'=>'#173177'),
				'money'=>array('value'=>100,'color'=>'#173177'),
				'date'=>array('value'=>date('Y-m-d H:i:s'),'color'=>'#173177'),
			),
		);
		$postJson = json_encode($array);
		$res = $this->http_url($url, 'post', 'json', $postJson);
		dump($res);
	}
	public function getBaseInfo(){
		$appid = 'wx79b230cc76e620e4';
		$redirect_url = urlencode('http://150.95.152.182/imooc.php/Home/Index/getUserOpenId');
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_url.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
		header('location:'.$url);
	}
	public function getUserOpenId(){
		$appid = 'wx79b230cc76e620e4';
		$appsecret = '1ebf923be2aa9fc59c56ffd24f3e7180';
		$code = $_GET['code'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
		$res = $this->http_url($url, 'get');
		dump($res);
	}
	public function getUserDetail(){
		$appid = 'wx79b230cc76e620e4';
		$redirect_url = urlencode('http://150.95.152.182/imooc.php/Home/Index/getUserInfo');
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_url.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
		header('location:'.$url);
	}
	public function getUserInfo(){
		$appid = 'wx79b230cc76e620e4';
		$appsecret = '1ebf923be2aa9fc59c56ffd24f3e7180';
		$code = $_GET['code'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
		$res = $this->http_url($url, 'get');
		dump($res);die;
		$access_token = $res['access_token'];
		$openid = $res['openid'];
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$msg = $this->http_url($url);
		dump($msg);
	}
}
