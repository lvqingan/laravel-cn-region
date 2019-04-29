<?php

namespace Lvqingan\Region\Data;

interface Data
{
    /**
     * 获取全部数据
     * @return array
     */
    public function all();

    /**
     * 根据编码返回数据
     * @param string $code
     * @return array|string|bool
     */
    public function find($code);
}
