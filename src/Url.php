<?php

namespace Es3\Utility;

class Url
{
    public function ToUrlParams(array $params): string
    {
        $buff = "";
        foreach ($params as $k => $v) {
            $buff .= $k . "=" . $v . "&";
        }

        $buff = trim($buff, "&");
        return $buff;
    }
}