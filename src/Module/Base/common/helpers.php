<?php
if (! function_exists('utf8_urldecode')) {

    /**
     * 支持解码 %uxxxx 格式的url编码。
     *
     * @param $str
     * @return string
     */
	function utf8_urldecode($str) {
		$ret = urldecode($str);
		if (mb_check_encoding($ret, 'UTF-8')) {
			$str = $ret;
		}

		$ret = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", $str);
		$ret = html_entity_decode($ret, null, 'UTF-8');
		if (mb_check_encoding($ret, 'UTF-8')) {
			$str = $ret;
		}

		return $str;
	}
}


if ( ! function_exists( 'get_client_ip' ) ) {
    /**
     * 获取ip地址
     */
    function get_client_ip(){
        if(isset($_SERVER['HTTP_REMOTEIP']) && !empty($_SERVER['HTTP_REMOTEIP'])){
            return $_SERVER['HTTP_REMOTEIP'];
        }else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if(count($ips) > 1){
                return $ips[1];
            }
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif ( isset($_SERVER['SERVER_ADDR']) ) {

            return $_SERVER['SERVER_ADDR'];
        } else {

            return $_SERVER['REMOTE_ADDR'];
        }
    }
}

if ( ! function_exists( 'getClientIp' ) ) {
    /**
     * 获取ip地址
     */
    function getClientIp(){
        if(isset($_SERVER['HTTP_REMOTEIP']) && !empty($_SERVER['HTTP_REMOTEIP'])){
            return $_SERVER['HTTP_REMOTEIP'];
        }else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if(count($ips) > 1){
                return $ips[1];
            }
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif ( isset($_SERVER['REMOTE_ADDR']) ) {

            return $_SERVER['REMOTE_ADDR'];
        } else {

            return $_SERVER['SERVER_ADDR'];
        }
    }
}

// php 判断某个值在二维数组里
if ( ! function_exists( 'deep_in_array' ) ) {
    function deep_in_array($value, $array) {
        foreach($array as $item) {
            if(!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }

            if(in_array($value, $item)) {
                return true;
            } else if(deep_in_array($value, $item)) {
                return true;
            }
        }
        return false;
    }
}



if (! function_exists('create_unique_id')) {

    /**
     * 创建一个拥有自校验能力的分布式唯一ID
     *
     * 开头4位，用于区分不同服务产生的ID。
     * 最后2位为校验码，用于校验订ID是否正确。
     *
     * @param string 前缀，必须是4个英文字母。
     * @return string 总长度为30位
     */
    function create_unique_id($prefix)
    {
        // 生成分布式唯一ID。
        $uniqid = uniqid(gethostname(), true);
        $md5 = substr(md5($uniqid), 12, 8); // 8位md5
        $uint = hexdec($md5);
        $uniqid = $prefix . sprintf('%s%010u', date('YmdHis'), $uint);

        // 校验码为前28位ID的平均值。
        $ckc = 0;
        for ($i = 0, $len = strlen($uniqid); $i < $len; $i ++) {
            $ckc += base_convert(substr($uniqid, $i, 1), 36, 10);
        }
        // 取余得到固定两位的校验码。
        $ckc %= base_convert('zz', 36, 10);
        $ckc = sprintf('%02s', base_convert($ckc, 10, 36));

        // 最终ID。
        $uniqid = strtoupper($uniqid . $ckc);
        return $uniqid;
    }
}

if (! function_exists('check_unique_id')) {

    /**
     * 校验分布式唯一ID的正确性
     *
     * 校验该ID的格式是否正确。
     *
     * @return boolean
     */
    function check_unique_id($id)
    {
        $ckc_1 = substr((string) $id, - 2);

        // 取得ID前28位的平均值。
        $uniqid = substr($id, 0, 28);
        $ckc_2 = 0;
        for ($i = 0, $len = strlen($uniqid); $i < $len; $i ++) {
            $ckc_2 += base_convert(substr($uniqid, $i, 1), 36, 10);
        }
        // 取余得到固定两位的校验码。
        $ckc_2 %= base_convert('zz', 36, 10);
        $ckc_2 = sprintf('%02s', base_convert($ckc_2, 10, 36));

        return strtoupper($ckc_1) == strtoupper($ckc_2);
    }
}

if (! function_exists('isJson')) {
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}



