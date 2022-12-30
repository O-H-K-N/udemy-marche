<?php

namespace App\Constants;

class Common
{
  const PRODUCT_ADD = '1';
  const PRODUCT_REDUCE = '2';

  // 連組配列としてまとめる
  const PRODUCT_LIST = [
    'add' => self::PRODUCT_ADD,
    'reduce' => self::PRODUCT_REDUCE
  ];
}