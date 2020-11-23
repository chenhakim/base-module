<?php
namespace Module\GoogleAdmobSsv;


use Module\GoogleAdmobSsv\GoogleAdmobSsv\Signature;

class GoogleAdmobSsv {

    /**
     * AdMob 密钥服务器地址
     * @var string
     */
    private $keys_url = '';

	public function __construct($config)
	{
        $this->keys_url = $config['keys_url'];
	}

    /**
     * verify google string
     *
     * @param null $strData
     * @return null
     */
    public function verifyString($strData="")
    {
        $Signature = new Signature($this->keys_url, $strData);

        $arrData = $Signature->verify();
        Logger('===', [$arrData]);
        return $arrData;
    }
}
