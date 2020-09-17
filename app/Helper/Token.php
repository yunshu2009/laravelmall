<?php

namespace App\Helper;

class Token
{

    /**
     * When checking nbf, iat or expiration times,
     * we want to provide some extra leeway time to
     * account for clock skew.
     */
    public static $leeway = 0;

    public static $supported_algs = array(
        'HS256' => array('hash_hmac', 'SHA256'),
        'HS512' => array('hash_hmac', 'SHA512'),
        'HS384' => array('hash_hmac', 'SHA384'),
        'RS256' => array('openssl', 'SHA256'),
    );

    /**
     * @param $jwt string
     * @return bool|int|object|null
     */
    public static function decode($jwt)
    {
        $key = config('token.secret');
        $allowed_algs = [config('token.alg')];

        if (empty($key)) {
            return false;
        }
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            return false;
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === ($header = self::jsonDecode(self::urlsafeB64Decode($headb64)))) {
            return false;
        }
        if (null === $payload = self::jsonDecode(self::urlsafeB64Decode($bodyb64))) {
            return false;
        }
        $sig = self::urlsafeB64Decode($cryptob64);

        if (empty($header->alg)) {
            return false;
        }
        if (empty(self::$supported_algs[$header->alg])) {
            return false;
        }
        if (!is_array($allowed_algs) || !in_array($header->alg, $allowed_algs)) {
            return false;
        }
        if (is_array($key) || $key instanceof \ArrayAccess) {
            if (isset($header->kid)) {
                $key = $key[$header->kid];
            } else {
                return false;
            }
        }

        // Check the signature
        if (!self::verify("$headb64.$bodyb64", $sig, $key, $header->alg)) {
            return false;
        }

        // Check if the nbf if it is defined. This is the time that the
        // token can actually be used. If it's not yet that time, abort.
        if (isset($payload->nbf) && $payload->nbf > (time() + self::$leeway)) {
            return false;
        }

        // Check that this token has been created before 'now'. This prevents
        // using tokens that have been created for later use (and haven't
        // correctly used the nbf claim).
        if (isset($payload->iat) && $payload->iat > (time() + self::$leeway)) {
            return false;
        }

        // Check if this token has expired.
        if (isset($payload->exp) && (time() - self::$leeway) >= $payload->exp) {
            return 10002;
        }

        if (isset($payload->uid)) {
            if (! self::verifyPlatform($payload->uid)) {
                return false;
            }
        }

        return $payload;
    }

    public static function authorization()
    {
        $token = app('request')->header('X-'.config('app.name').'-Token');

        if (empty($token)) {
            $token = app('request')->get('X-'.config('app.name').'-Token');
        }

        if (empty($token)) {
            return false;
        }

        // Log::debug('Authorization', ['token' => $token]);
        if ($payload = self::decode($token)) {
            if (is_object($payload) && property_exists($payload, 'uid')) {
                return $payload->uid;
            }
        }

        if ($payload == 10002) {
            return 'token-expired';
        }

        return false;
    }

    public static function refresh()
    {
        $token = app('request')->header('X-'.config('app.name').'-Authorization');

        if ($token) {
            if ($payload = self::decode($token)) {
                if (is_object($payload)) {

                    // 超过1天
                    if (property_exists($payload, 'exp')) {
                        if ((time()+config('token.ttl')*60-$payload->exp) > config('token.refresh_ttl')*60) {
                            return self::new_token($payload);
                        }
                    }

                    // 版本号不匹配
                    if (property_exists($payload, 'ver')) {
                        if (version_compare(config('token.ver'), $payload->ver) != 0) {
                            return self::new_token($payload);
                        }
                    }

                    // 没有版本号
                    if (!property_exists($payload, 'ver')) {
                        return self::new_token($payload);
                    }
                }
            }
        }

        return false;
    }

    private static function new_token($payload)
    {
        return self::encode([
            'uid' => $payload->uid,
            'ver' => config('token.ver')
        ]);
    }

    private static function str_mix($domain, $uuid)
    {
        $uuid = explode('-', $uuid);
        $domain = explode('.', $domain);
        $mixed = array_merge($uuid, $domain);
        arsort($mixed);
        return implode('-', $mixed);
    }

    private static function parse_domain($url)
    {
        $data = parse_url($url);
        $host = $data['host'];

        if (preg_match('/^www.*$/', $host)) {
            return str_replace('www.', '', $host);
        }

        return $host;
    }

    /**
     * Converts and signs a PHP object or array into a JWT string.
     *
     * @param object|array  $payload    PHP object or array
     * @param string        $key        The secret key.
     *                                  If the algorithm used is asymmetric, this is the private key
     * @param string        $alg        The signing algorithm.
     *                                  Supported algorithms are 'HS256', 'HS384', 'HS512' and 'RS256'
     * @param array         $head       An array with header elements to attach
     *
     * @return string A signed JWT
     *
     * @uses jsonEncode
     * @uses urlsafeB64Encode
     */
    public static function encode(array $payload, $keyId = null, $head = null)
    {
        $key = config('token.secret');
        $alg = config('token.alg');

        if (!isset($payload['exp'])) {
            $payload['exp'] = time() + config('token.ttl') * 60;
        }

        if (isset($payload['uid'])) {
            $payload['platform'] = self::setPlatform($payload['uid']);
        }

        $header = array('typ' => 'JWT', 'alg' => $alg);
        if ($keyId !== null) {
            $header['kid'] = $keyId;
        }
        if (isset($head) && is_array($head)) {
            $header = array_merge($head, $header);
        }
        $segments = array();
        $segments[] = self::urlsafeB64Encode(self::jsonEncode($header));
        $segments[] = self::urlsafeB64Encode(self::jsonEncode($payload));
        $signing_input = implode('.', $segments);

        $signature = self::sign($signing_input, $key, $alg);
        $segments[] = self::urlsafeB64Encode($signature);

        return implode('.', $segments);
    }

    /**
     * @param $msg
     * @param $key
     * @param string $alg
     * @return bool|string
     */
    public static function sign($msg, $key, $alg = 'HS256')
    {
        if (empty(self::$supported_algs[$alg])) {
            return false;
        }
        list($function, $algorithm) = self::$supported_algs[$alg];
        switch ($function) {
            case 'hash_hmac':
                return hash_hmac($algorithm, $msg, $key, true);
            case 'openssl':
                $signature = '';
                $success = openssl_sign($msg, $signature, $key, $algorithm);
                if (!$success) {
                    return false;
                } else {
                    return $signature;
                }
        }
    }

    /**
     * @param $msg
     * @param $signature
     * @param $key
     * @param $alg
     * @return bool
     */
    private static function verify($msg, $signature, $key, $alg)
    {
        if (empty(self::$supported_algs[$alg])) {
            return false;
        }

        list($function, $algorithm) = self::$supported_algs[$alg];
        switch ($function) {
            case 'openssl':
                $success = openssl_verify($msg, $signature, $key, $algorithm);
                if (!$success) {
                    return false;
                } else {
                    return $signature;
                }
            case 'hash_hmac':
            default:
                $hash = hash_hmac($algorithm, $msg, $key, true);
                if (function_exists('hash_equals')) {
                    return hash_equals($signature, $hash);
                }
                $len = min(self::safeStrlen($signature), self::safeStrlen($hash));

                $status = 0;
                for ($i = 0; $i < $len; $i++) {
                    $status |= (ord($signature[$i]) ^ ord($hash[$i]));
                }
                $status |= (self::safeStrlen($signature) ^ self::safeStrlen($hash));

                return ($status === 0);
        }
    }

    /**
     * @param $input
     * @return bool|mixed|null
     */
    public static function jsonDecode($input)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            /** In PHP >=5.4.0, json_decode() accepts an options parameter, that allows you
             * to specify that large ints (like Steam Transaction IDs) should be treated as
             * strings, rather than the PHP default behaviour of converting them to floats.
             */
            $obj = json_decode($input, false, 512, JSON_BIGINT_AS_STRING);
        } else {
            /** Not all servers will support that, however, so for older versions we must
             * manually detect large ints in the JSON string and quote them (thus converting
             *them to strings) before decoding, hence the preg_replace() call.
             */
            $max_int_length = strlen((string) PHP_INT_MAX) - 1;
            $json_without_bigints = preg_replace('/:\s*(-?\d{'.$max_int_length.',})/', ': "$1"', $input);
            $obj = json_decode($json_without_bigints);
        }

        if (function_exists('json_last_error') && $errno = json_last_error()) {
            self::handleJsonError($errno);
        } elseif ($obj === null && $input !== 'null') {
            return false;
        }
        return $obj;
    }

    /**
     * @param $input
     * @return bool|false|string
     */
    public static function jsonEncode($input)
    {
        $json = json_encode($input);
        if (function_exists('json_last_error') && $errno = json_last_error()) {
            self::handleJsonError($errno);
        } elseif ($json === 'null' && $input !== null) {
            return false;
        }
        return $json;
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    public static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
    public static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * @param $errno
     * @return bool
     */
    private static function handleJsonError($errno)
    {
        $messages = array(
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON'
        );

        return false;
    }

    /**
     * Get the number of bytes in cryptographic strings.
     *
     * @param string
     *
     * @return int
     */
    private static function safeStrlen($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }

    private static function setPlatform($uid)
    {
//        $platform = Header::getUserAgent('Platform');
//        $key = "platform:{$uid}";
//        // cache
//        \Cache::put($key, $platform, 0);
//        return $platform;
    }

    private static function verifyPlatform($uid)
    {
        return true;

//        // 测试时注释下面这句
//        $platform = Header::getUserAgent('Platform');
//
//        $key = "platform:{$uid}";
//
//        if ($platform == Cache::get($key)) {
//            return true;
//        }
//
//        return false;
    }
}
