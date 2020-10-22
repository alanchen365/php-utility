<?php

/**
 * 判断一个变量是否为空，但不包括 0 和 '0'.
 *
 * @param $value
 *
 * @return bool 返回true 说明为空 返回false 说明不为空
 */
function superEmpty($value): bool
{
    // 如果是一个数组
    if (is_array($value)) {
        if (count($value) == 1 && isset($value[0]) && $value[0] !== 0 && $value[0] !== '0' && empty($value[0])) {
            unset($value[0]);
        }
        return empty($value) ? true : false;
    }

    // 如果是一个对象
    if (is_object($value)) {
        return empty($value->id) ? true : false;
    }

    // 如果是其它
    if (empty($value)) {
        if (is_int($value) && 0 === $value) {
            return false;
        }

        if (is_string($value) && '0' === $value) {
            return false;
        }

        return true;
    }

    return false;
}

function nowdate(string $format = 'Y-m-d H:i:s'): string
{
    return date($format, time());
}