<?php
function getSalt()
{
    $str = "abcdefghijklmnopqrstuvwxyz0123456789";
    $number = 5;
    $salt = "";

    for ($i = 0; $i < $number; $i++) {
        if ($i) {
            $salt .= "-";
        }
        for ($j = 0; $j < $number; $j++) {
            $salt .= substr($str, rand(0, strlen($str)), 1);
        }
    }
    return $salt;
}
