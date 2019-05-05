<?php

namespace Lvqingan\Region;

use Illuminate\Support\Str;
use Lvqingan\Region\Data\Data;
use Lvqingan\Region\Data\Province;

class Loader
{
    /**
     * @var Data
     */
    private $data;

    /**
     * @var string
     */
    private $filterCode = '';

    /**
     * Loader constructor.
     *
     * @param string $dataType   区域类型
     * @param string $filterCode 过滤编码
     */
    public function __construct($dataType, $filterCode = '')
    {
        $dataClass = 'Lvqingan\\Region\\Data\\'.Str::studly($dataType);

        if (class_exists($dataClass)) {
            $this->data = new $dataClass();
        } else {
            throw new \InvalidArgumentException(
                sprintf('无效的区域类型 `%s`, 只允许使用 `province`, `city`和`district`', $dataType)
            );
        }

        if (!empty($filterCode)) {
            if (preg_match('/^\d{6}$/', $filterCode)) {
                $this->filterCode = $filterCode;
            } else {
                throw new \InvalidArgumentException(
                    sprintf('无效的过滤编码 `%s`, 只允许使用6位数字', $filterCode)
                );
            }
        }
    }

    /**
     * @return array
     */
    public function load()
    {
        if ($this->data instanceof Province) {
            if (!empty($this->filterCode)) {
                throw new \InvalidArgumentException(
                    sprintf('省份不需要传递过滤编码 `%s`', $this->filterCode)
                );
            }

            return $this->data->all();
        } else {
            if (empty($this->filterCode)) {
                throw new \InvalidArgumentException(
                    '城市、区县必须传递过滤编码'
                );
            }
            $filtered = $this->data->find($this->filterCode);

            if (is_array($filtered)) {
                return $filtered;
            } else {
                return [];
            }
        }
    }
}
