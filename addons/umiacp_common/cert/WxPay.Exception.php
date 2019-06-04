<?php

/**
 *
 * 微信支付API异常类
 * @author widyhu
 *
 */
class WxPayExceptionCommon extends Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}

