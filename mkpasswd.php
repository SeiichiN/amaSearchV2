<?php
/**
 * http://espresso3389.hatenablog.com/entry/2014/04/15/024056
 * PHPで本気で安全なパスワードを生成する
 */

// パスワードに使っても良い文字集合
$password_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

// $sizeに指定された長さのパスワードを生成
function generate_password($size) {
    global $password_chars;
    $password_chars_count = strlen($password_chars);
    // mcrypt_create_iv は、php7.0以降廃止。
    //  $data = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
    // random_bytesは、mcrypt_create_ivの代わりに導入されたが、php7.0以降しか対応していない。
    //  $data = random_bytes($size);
    // openssl_random_pseudo_bytesは、ランダムなバイト文字列を生成
    // バイト文字列をそのまま使っていいいのかな？
    $data = openssl_random_pseudo_bytes($size);
    $pin = '';
    for ($n = 0; $n < $size; $n ++) {
        $pin .= substr($password_chars, ord(substr($data, $n, 1)) % $password_chars_count, 1);
    }
    return $pin;
}

print generate_password(8);
?>
