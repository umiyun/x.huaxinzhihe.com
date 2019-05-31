<?php


class WeixinPay
{


    protected $appid;
    protected $mch_id;
    protected $key;
    protected $openid;
    protected $out_trade_no;
    protected $body;
    protected $total_fee;

    function __construct($appid, $openid, $mch_id, $key, $out_trade_no, $body, $total_fee)
    {
        $this->appid = trim($appid);
        $this->openid = trim($openid);
        $this->mch_id = trim($mch_id);
        $this->key = trim($key);
        $this->out_trade_no = trim($out_trade_no);
        $this->body = trim($body);
        $this->total_fee = floatval($total_fee) * 100;
    }


    public function pay()
    {
        //统一下单接口
        $return = $this->weixinapp();
        return $return;
    }

    public function finance($pars) {

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

        $pars['nonce_str'] = $this->createNoncestr();
        $pars['sign'] = $this->getSign($pars);
        $xml = $this->arrayToXml($pars);
        $resp = $this->wxHttpsRequestPem($xml, $url);
        $rearr = $this->xmlToArray($resp);
        return $rearr;
    }

    //统一下单接口

    private function weixinapp()
    {
        //统一下单接口
        $unifiedorder = $this->unifiedorder();
        if ($unifiedorder['result_code'] != 'SUCCESS') {
            return $unifiedorder;
        }
        $parameters = array(
            'appId' => $this->appid,                                    //小程序ID
            'timeStamp' => '' . time() . '',                            //时间戳
            'nonceStr' => $this->createNoncestr(),                      //随机串
            'package' => 'prepay_id=' . $unifiedorder['prepay_id'],     //数据包
            'signType' => 'MD5'//签名方式
        );
        //签名
        $parameters['paySign'] = $this->getSign($parameters);
        return $parameters;
    }

    private function unifiedorder()
    {
        global $_W;
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $parameters = array(
            'appid' => $this->appid,                                                //小程序ID
            'mch_id' => $this->mch_id,                                              //商户号
            'nonce_str' => $this->createNoncestr(),                                 //随机字符串
            'body' => $this->body,                                                  //商品描述
            'out_trade_no' => $this->out_trade_no,                                  //商户订单号
            'total_fee' => $this->total_fee,                                        //总金额 单位 分
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],                          //终端IP
            'notify_url' => $_W['siteroot'] .'addons/' . YOUMI_NAME. '/payresult.php',          //通知地址  确保外网能正常访问
            'openid' => $this->openid,                                              //用户id
            'trade_type' => 'JSAPI'                                                 //交易类型
        );

        youmi_internal_log('parameters', $parameters);

        //统一下单签名
        $parameters['sign'] = $this->getSign($parameters);
        $xmlData = $this->arrayToXml($parameters);
        $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url, 60));

        youmi_internal_log('unifiedorder_return', $return);

        return $return;
    }

    private function createNoncestr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


    //数组转换成xml

    private function getSign($Obj)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }

    private function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }


    //xml转换成数组

    private function arrayToXml($arr)
    {
        $xml = "<root>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</root>";
        return $xml;
    }


    //微信小程序接口

    private function xmlToArray($xml)
    {


        //禁止引用外部xml实体


        libxml_disable_entity_loader(true);


        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);


        $val = json_decode(json_encode($xmlstring), true);


        return $val;
    }


    //作用：产生随机字符串，不长于32位

    private static function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);


        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);


        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }


    //作用：生成签名

    public function orderquery()
    {
        global $_W;
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $parameters = array(
            'appid' => $this->appid,                                                //小程序ID
            'mch_id' => $this->mch_id,                                              //商户号
            'nonce_str' => $this->createNoncestr(),                                 //随机字符串
            'out_trade_no' => $this->out_trade_no,                                  //商户订单号
        );

        //统一下单签名
        $parameters['sign'] = $this->getSign($parameters);
        $xmlData = $this->arrayToXml($parameters);
        $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url, 60));

        return $return;
    }


    ///作用：格式化参数，签名过程需要使用

    public function wxHttpsRequestPem($vars, $url, $second = 30, $aHeader = array())
    {
        global $_W;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, YOUMI_CERT . $_W['uniacid'] . '/apiclient_cert.pem');
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, YOUMI_CERT . $_W['uniacid'] . '/apiclient_key.pem');
        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }


}		
			
		
