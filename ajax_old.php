<?php
header('Access-Control-Allow-Origin:*');
class interface_relay
{
    private $TARGET = '';
    const CHARSET = 'UTF-8';
    private $msGets = '';
    private $maGetPostData = array();

    function __construct()
    {
        $this->getPOST();
        $this->getGET();
        if ($this->msGets != '' || count($this->maGetPostData) > 0) {
            if (strlen($this->msGets) > 0)
                $sUrl = $this->TARGET . '?' . $this->msGets;
            else
                $sUrl = $this->TARGET;
            header('Content-Type: text/html; charset=' . self::CHARSET);
            echo $this->getContent($sUrl);
        } else {
            header('Content-Type: text/html; charset=' . self::CHARSET);
            echo $this->getContent($this->TARGET);
        }
    }

    function __destruct()
    {
        unset($maGetPostData, $msGets);
    }

    private function getPOST()
    {
        if (count($_POST) > 0) {
            $aTmp = array();
            foreach ($_POST as $sKey => $sVal) {
                if ($sKey == 'target_url') {
                    $this->TARGET = urldecode($sVal);
                } else {
                    $aTmp[$sKey] = urlencode($sVal);
                }
            }
            $res = '';
            foreach ($aTmp as $aKey => $aVal) {
                $res .= ($aKey . '=' . $aVal . '&');
            }
            $this->maGetPostData[0] = $res;
            return true;
        } else
            return false;
    }

    private function getGET()
    {
        if (count($_GET) > 0) {
            $aTmp = array();
            foreach ($_GET as $sKey => $sVal) {
                if ($sKey == 'target_url') {
                    $this->TARGET = urldecode($sVal);
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sGetUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 1800);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (count($this->maGetPostData) > 0) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, implode('', $this->maGetPostData));
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