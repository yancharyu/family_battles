<?php

interface HistoryInterface
{
    public function set($str);
    public function clear();
}

class History
{
    public static function set($str)
    {
        $_SESSION['history'][] = $str;
        debug($str);
        // if (is_countable($_SESSION['history'])) {
        //   // $_SESSION['history']の数が以上になったら古い順番に消去していく
        //   if (count($_SESSION['history']) >= 5) {
        //     array_shift($_SESSION['history']);
        //   }
        // }
    }

    public static function clear()
    {
        unset($_SESSION['history']);
    }
}
