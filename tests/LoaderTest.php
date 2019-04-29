<?php

namespace Lvqingan\Test;

use Lvqingan\Region\Loader;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    /**
     * 可以正确加载全部省份数据
     * @test
     */
    public function loadProvinces()
    {
        $provinces = (new Loader('province'))->load();

        $this->assertCount(34, $provinces);
        $this->assertEquals('安徽省', $provinces['340000']);

        $this->expectExceptionMessage('省份不需要传递过滤编码 `340000`');
        (new Loader('province', '340000'))->load();
    }

    /**
     * 可以正确加载城市数据
     * @test
     */
    public function loadCities()
    {
        $cities = (new Loader('city', '111111'))->load();
        $this->assertCount(0, $cities);

        $cities = (new Loader('city', '340000'))->load();

        $this->assertCount(16, $cities);
        $this->assertEquals('合肥市', $cities['340100']);

        $this->expectExceptionMessage('城市、区县必须传递过滤编码');
        (new Loader('city'))->load();
    }

    /**
     * 可以正确加载区县数据
     * @test
     */
    public function loadDistricts()
    {
        $districts = (new Loader('district', '111111'))->load();
        $this->assertCount(0, $districts);

        $districts = (new Loader('district', '340100'))->load();

        $this->assertCount(10, $districts);
        $this->assertEquals('蜀山区', $districts['340104']);

        $this->expectExceptionMessage('城市、区县必须传递过滤编码');
        (new Loader('district'))->load();
    }

    /**
     * 只允许三种类型作为区域类型参数（province, city, district）
     * @test
     */
    public function restrictDataType()
    {
        $this->expectExceptionMessage('无效的区域类型 `foo`, 只允许使用 `province`, `city`和`district`');

        new Loader('foo');
    }

    /**
     * 只允许6位数字作为过滤编码参数
     * @test
     */
    public function restrictFilterCode()
    {
        $this->expectExceptionMessage('无效的过滤编码 `123`, 只允许使用6位数字');

        new Loader('district', '123');
    }
}
