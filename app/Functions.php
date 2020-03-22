<?php

if (! function_exists('get_milli_second')) {
    function get_milli_second()
    {
        list($s1, $s2) = explode(' ', microtime());

        return intval((float)$s1 + (float)$s2) * 1000;
    }
}

if (! function_exists('curl_request')) {
    /**
     * CURL Request
     */
    function curl_request($api, $method = 'GET', $params = array(), $headers = [], $json_decode = true)
    {
        $curl = curl_init();

        switch (strtoupper($method)) {
            case 'GET':
                if (!empty($params)) {
                    $api .= (strpos($api, '?') ? '&' : '?') . http_build_query($params);
                }
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
//            \Log::debug('curl请求失败' . json_encode($error));
            curl_close($curl);
            return false;
        } else {
            // 解决windows 服务器 BOM 问题
            $response = trim($response, chr(239).chr(187).chr(191));

            if ($json_decode) {
                $response = json_decode($response, true);
            }
        }

        curl_close($curl);

        return $response;
    }
}

if (! function_exists('format_array')) {
    function format_array($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($value === null) {
                    $array[$key] = '';
                } elseif (is_array($value)) {
                    $value = format_array($value);
                    if ($value === null) {
                        $array[$key] = '';
                    } else {
                        $array[$key] = $value;
                    }
                }
            }
        }

        return $array;
    }
}

if (! function_exists('object_to_array')) {
    /**
     * 对象 转 数组
     * @param object $obj 对象
     * @return array
     */
    if (!function_exists('object_to_array')) {
        function object_to_array($obj)
        {
            $obj = (array)$obj;
            foreach ($obj as $k => $v) {
                if (gettype($v) == 'resource') {
                    return false;
                }
                if (gettype($v) == 'object' || gettype($v) == 'array') {
                    $obj[$k] = (array)object_to_array($v);
                }
            }
            return $obj;
        }
    }
}

if (! function_exists('ip')) {
    function ip($ip2long=true)
    {
        $ip = request()->ip();

        if (is_null($ip)) {
            $ip = '127.0.0.1';
        }

        return $ip2long ? ip2long($ip) : $ip;
    }

}
