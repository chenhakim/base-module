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


if (! function_exists('resolve_client_ip_from_server')) {
    function resolve_client_ip_from_server()
    {
        $trustedHeaders = [
            'HTTP_ALI_REAL_CLIENT_IP',
            'HTTP_TRUE_CLIENT_IP',
            'HTTP_REMOTEIP',
        ];

        foreach ($trustedHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = trim((string) $_SERVER[$header]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                if ($ip && strcasecmp($ip, 'unknown') !== 0 && filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        foreach (['REMOTE_ADDR', 'SERVER_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim((string) $_SERVER[$key]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '';
    }
}

if ( ! function_exists( 'get_client_ip' ) ) {
    /**
     * 获取ip地址
     */
    function get_client_ip(){
        return resolve_client_ip_from_server();
    }
}

if ( ! function_exists( 'getClientIp' ) ) {
    /**
     * 获取ip地址
     */
    function getClientIp(){
        return resolve_client_ip_from_server();
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

/**
 * URL base64编码
 * '+' -> '-'
 * '/' -> '_'
 * '=' -> ''
 * @param $string
 */
if (! function_exists('url_safe_base64_encode')) {

    function url_safe_base64_encode($data) {
        $data = base64_encode($data);
        $data = str_replace(['+','/','='], ['-','_',''], $data);
        return $data;
    }
}
/**
 * URL base64解码
 * '-' -> '+'
 * '_' -> '/'
 * 字符串长度%4的余数，补'='
 * @param  $string
 */
if (! function_exists('url_safe_base64_decode')) {
    function url_safe_base64_decode($data) {
        $data = str_replace(['-','_'], ['+','/'], $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}

if (! function_exists('uniqueid')) {
    /**
     * 创建一个分布式唯一ID
     *
     * @return string
     */
    function uniqueid()
    {
        $uniqid = uniqid(gethostname(), true);
        $md5 = substr(md5($uniqid), 12, 8); // 8位md5
        $uint = hexdec($md5);
        return sprintf('%s%010u', date('YmdHis'), $uint);
    }
}
