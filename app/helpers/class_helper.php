<?php
function generateClassCode($length = 6) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // avoid ambiguous chars
    $max = strlen($chars) - 1;
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, $max)];
    }
    return $code;
}
