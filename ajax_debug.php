<?php
header('Access-Control-Allow-Origin:*');
class interface_relay
{
    const CHARSET = 'UTF-8';
    private $target = '';
    private $msGets = '';
    private $maGetPostData = '';

    function __construct()
    {
        $this->getPOST();
        $this->getGET();
        if ($this->msGets != '' || count($this->maGetPostData) > 0) {
            if (strlen($this->msGets) > 0)
                $sUrl = $this->target . '?' . $this->msGets;
            else
                $sUrl = $this->target;
            header('Content-Type: text/html; charset=' . self::CHARSET);
            echo $this->getContent($sUrl);
        } else {
            header('Content-Type: text/html; charset=' . self::CHARSET);
            echo $this->getContent($this->target);
        }
    }

    function __destruct()
    {
        unset($target, $maGetPostData, $msGets);
    }

    private function getPOST()
    {
        $strTmp= file_get_contents("php://input");
        $arrTmp = explode('&',$strTmp);
        $arrRes = [];
        foreach ($arrTmp as $item) {
        	$arrItem = explode('=',$item);
        	if (isset($arrItem[1])) {
        		$arrRes[$arrItem[0]] = $arrItem[1];
        	} else {
        		$arrRes[$arrItem[0]] = '';
        	}
        }
        if (isset($arrRes['target_url'])) {
        	$this->target = urldecode($arrRes['target_url']);
        	unset($arrRes['target_url']);
        	foreach ($arrRes as $key => $value) {
        		$this->maGetPostData .= $key . '=' . $value . '&';
        	}
        } else {
        	return false;
        }
        unset($strTmp, $arrTmp);
        return strlen($this->maGetPostData) >= 1;
    }

    private function getGET()
    {
        if (count($_GET) > 0) {
            $aTmp = array();
            foreach ($_GET as $sKey => $sVal) {
                if ($sKey == 'target_url') {
                    $this->target = urldecode($sVal);
                } else {
                    $aTmp[] = $sKey . '=' . urlencode($sVal);
                }
            }
            $this->msGets = implode('&', $aTmp);
            return true;
        } else
            return false;
    }

    private function getContent($sGetUrl)
    {
        if (empty($sGetUrl)) {
            echo '!!!EMPTY TARGET_URL!!!';
            return NULL;
        }
        echo ' URL: ';
        var_dump($sGetUrl);
        echo ' get: ';
        var_dump($this->msGets);
        echo ' post: ';
        var_dump($this->maGetPostData);
        echo ' result: ';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sGetUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 1800);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (strlen($this->maGetPostData) > 0) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->maGetPostData);
        }
        $sData = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        return $sData;
    }
}

$o = new interface_relay();
unset($o);
?>