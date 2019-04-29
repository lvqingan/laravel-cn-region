<?php

namespace Lvqingan\Region;

use Illuminate\Support\Str;
use Lvqingan\Region\Data\City;
use Lvqingan\Region\Data\District;
use Lvqingan\Region\Data\Province;

/**
 * @property string $province_code 省编码
 * @property string $city_code 市编码
 * @property string $district_code 区县编码
 * @property string $province_name 省名称
 * @property string $city_name 市名称
 * @property string $district_name 区县名称
 * @property string $full_city_name 完整城市名称（包含省）
 * @property string $full_district_name 完整区县名称（包含省市）
 * @method static \Illuminate\Database\Eloquent\Builder|static whereRegion($regionCode) 按区域等于查询
 * @method static \Illuminate\Database\Eloquent\Builder|static whereInRegion($regionCode) 按区域包含查询
 * @mixin \Illuminate\Database\Eloquent\Model
 * @package Lvqingan\Region
 */
trait HasRegion
{
    /**
     * 获取区域字段名称（默认为region），如果需要修改需要在模型中定义属性 $regionFieldName
     */
    protected function getRegionFieldName()
    {
        if (isset($this->regionFieldName)) {
            return $this->regionFieldName;
        } else {
            return 'region';
        }
    }

    /**
     * 获取省份编码
     * @return null|string
     */
    public function getProvinceCodeAttribute()
    {
        $region = $this->getAttribute($this->getRegionFieldName());

        if (! is_null($region)) {
            return substr($region, 0, 2) . '0000';
        }

        return null;
    }

    /**
     * 获取省份名称
     * @return null|string
     */
    public function getProvinceNameAttribute()
    {
        $provinceCode = $this->province_code;

        if (! is_null($provinceCode)) {
            $provinceName = (new Province())->find($provinceCode);

            return $provinceName ?: null;
        }

        return null;
    }

    /**
     * 获取城市编码
     * @return null|string
     */
    public function getCityCodeAttribute()
    {
        $region = $this->getAttribute($this->getRegionFieldName());

        if (! is_null($region) && ! Str::endsWith($region, '0000')) {
            return substr($region, 0, 4) . '00';
        }

        return null;
    }

    /**
     * 获取城市名称
     * @return null|string
     */
    public function getCityNameAttribute()
    {
        $cityCode = $this->city_code;

        if (! is_null($cityCode)) {
            $cityName = (new City())->find($cityCode);

            return $cityName ?: null;
        }

        return null;
    }

    /**
     * 获取区县编码
     * @return null|string
     */
    public function getDistrictCodeAttribute()
    {
        $region = $this->getAttribute($this->getRegionFieldName());

        if (! is_null($region) && ! Str::endsWith($region, '0000') && ! Str::endsWith($region, '00')) {
            return $region;
        }

        return null;
    }

    /**
     * 获取区县编名称
     * @return null|string
     */
    public function getDistrictNameAttribute()
    {
        $districtCode = $this->district_code;

        if (! is_null($districtCode)) {
            $districtName = (new District())->find($districtCode);

            return $districtName ?: null;
        }

        return null;
    }

    /**
     * 获取完整区县名称（包含省市）
     * @return null|string
     */
    public function getFullDistrictNameAttribute()
    {
        return $this->district_name ? ($this->province_name . $this->city_name . $this->district_name) : null;
    }

    /**
     * 获取完整城市名称（包含省）
     * @return null|string
     */
    public function getFullCityNameAttribute()
    {
        return $this->city_name ? ($this->province_name . $this->city_name) : null;
    }

    /**
     * 按区域等于查询
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  string                               $regionCode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRegion($query, $regionCode)
    {
        return $query->where($this->getRegionFieldName(), '=', $regionCode);
    }

    /**
     * 按区域包含查询
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  string                               $regionCode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereInRegion($query, $regionCode)
    {
        if (Str::endsWith($regionCode, '0000')) {
            $regionCode = substr($regionCode, 0, 2);
        } elseif (Str::endsWith($regionCode, '00')) {
            $regionCode = substr($regionCode, 0, 4);
        }

        return $query->where($this->getRegionFieldName(), 'like', $regionCode . '%');
    }
}
