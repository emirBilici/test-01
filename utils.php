<?php

/**
 * @param $name
 * @return string|void
 */
function post($name)
{
    if (isset($_POST[$name])) {
        return htmlspecialchars(trim($_POST[$name]));
    }
}

/**
 * @param $name
 * @return string|void
 */
function get($name)
{
    if (isset($_GET[$name])) {
        return htmlspecialchars(trim($_GET[$name]));
    }
}

/**
 * @param $name
 * @return mixed|void
 */
function session($name)
{
    if (isset($_SESSION[$name])) {
        return $_SESSION[$name];
    }
}

/**
 * @param $page
 * @param string $project_name
 * @return string
 */
function Title($page = null, string $project_name = PROJECT_NAME): string
{
    if (!$page) {
        return $project_name;
    } else {
        return "$page - $project_name";
    }
}

/**
 * @param string $url
 * @return void
 */
function site_url(string $url = REDIRECT_HOMEPAGE) {
    header('Location:' . $url);
    exit();
}

/**
 * @return mixed
 */
function getData(): mixed
{
    return json_decode(file_get_contents('php://input')); // Post data
}

/**
 * @param $value
 * @return string
 */
function input($value): string
{
    return htmlspecialchars(trim($value));
}

/**
 * @param string $message
 * @param string $targetId
 * @param int $status
 * @return false|string
 */
function message(string $message, string $targetId, int $status = 1): bool|string
{
    return json_encode([
        'message' => $message,
        'target' => $targetId,
        'status' => $status
    ]);
}

/**
 * @return void
 */
function LoginSession(): void
{
    if (!session('login')) {
        site_url();
    }
}

/**
 * @return string
 */
function generateRandomString(): string
{
    $length = 8;
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNIOQRSTUVWXYZ";
    $string = "";

    for ($p = 0; $p < $length; $p++) {
        @$string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

/**
 * @param $data
 * @return void
 */
function jsonOutput($data): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit();
}

/**
 * @param $str
 * @param $options
 * @return string
 */
function Slug($str, $options = array())
{
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => true
    );
    $options = array_merge($defaults, $options);
    $char_map = array(
        // Latin
        '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'AE', '??' => 'C',
        '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I',
        '??' => 'D', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O',
        '??' => 'O', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Y', '??' => 'TH',
        '??' => 'ss',
        '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'ae', '??' => 'c',
        '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i',
        '??' => 'd', '??' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o',
        '??' => 'o', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'y', '??' => 'th',
        '??' => 'y',
        // Latin symbols
        '??' => '(c)',
        // Greek
        '??' => 'A', '??' => 'B', '??' => 'G', '??' => 'D', '??' => 'E', '??' => 'Z', '??' => 'H', '??' => '8',
        '??' => 'I', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => '3', '??' => 'O', '??' => 'P',
        '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'Y', '??' => 'F', '??' => 'X', '??' => 'PS', '??' => 'W',
        '??' => 'A', '??' => 'E', '??' => 'I', '??' => 'O', '??' => 'Y', '??' => 'H', '??' => 'W', '??' => 'I',
        '??' => 'Y',
        '??' => 'a', '??' => 'b', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'z', '??' => 'h', '??' => '8',
        '??' => 'i', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => '3', '??' => 'o', '??' => 'p',
        '??' => 'r', '??' => 's', '??' => 't', '??' => 'y', '??' => 'f', '??' => 'x', '??' => 'ps', '??' => 'w',
        '??' => 'a', '??' => 'e', '??' => 'i', '??' => 'o', '??' => 'y', '??' => 'h', '??' => 'w', '??' => 's',
        '??' => 'i', '??' => 'y', '??' => 'y', '??' => 'i',
        // Turkish
        '??' => 'S', '??' => 'I', '??' => 'C', '??' => 'U', '??' => 'O', '??' => 'G',
        '??' => 's', '??' => 'i', '??' => 'c', '??' => 'u', '??' => 'o', '??' => 'g',
        // Russian
        '??' => 'A', '??' => 'B', '??' => 'V', '??' => 'G', '??' => 'D', '??' => 'E', '??' => 'Yo', '??' => 'Zh',
        '??' => 'Z', '??' => 'I', '??' => 'J', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => 'O',
        '??' => 'P', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', '??' => 'F', '??' => 'H', '??' => 'C',
        '??' => 'Ch', '??' => 'Sh', '??' => 'Sh', '??' => '', '??' => 'Y', '??' => '', '??' => 'E', '??' => 'Yu',
        '??' => 'Ya',
        '??' => 'a', '??' => 'b', '??' => 'v', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'yo', '??' => 'zh',
        '??' => 'z', '??' => 'i', '??' => 'j', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => 'o',
        '??' => 'p', '??' => 'r', '??' => 's', '??' => 't', '??' => 'u', '??' => 'f', '??' => 'h', '??' => 'c',
        '??' => 'ch', '??' => 'sh', '??' => 'sh', '??' => '', '??' => 'y', '??' => '', '??' => 'e', '??' => 'yu',
        '??' => 'ya',
        // Ukrainian
        '??' => 'Ye', '??' => 'I', '??' => 'Yi', '??' => 'G',
        '??' => 'ye', '??' => 'i', '??' => 'yi', '??' => 'g',
        // Czech
        '??' => 'C', '??' => 'D', '??' => 'E', '??' => 'N', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U',
        '??' => 'Z',
        '??' => 'c', '??' => 'd', '??' => 'e', '??' => 'n', '??' => 'r', '??' => 's', '??' => 't', '??' => 'u',
        '??' => 'z',
        // Polish
        '??' => 'A', '??' => 'C', '??' => 'e', '??' => 'L', '??' => 'N', '??' => 'o', '??' => 'S', '??' => 'Z',
        '??' => 'Z',
        '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'l', '??' => 'n', '??' => 'o', '??' => 's', '??' => 'z',
        '??' => 'z',
        // Latvian
        '??' => 'A', '??' => 'C', '??' => 'E', '??' => 'G', '??' => 'i', '??' => 'k', '??' => 'L', '??' => 'N',
        '??' => 'S', '??' => 'u', '??' => 'Z',
        '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'g', '??' => 'i', '??' => 'k', '??' => 'l', '??' => 'n',
        '??' => 's', '??' => 'u', '??' => 'z'
    );
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}