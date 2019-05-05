<?php

namespace Lvqingan\Region\Tests;

use Lvqingan\Test\MockModels\Contact;
use Lvqingan\Test\MockModels\Migrator;
use Lvqingan\Test\MockModels\User;
use PHPUnit\Framework\TestCase;

class HasRegionTest extends TestCase
{
    /**
     * 测试默认区域字段名称.
     *
     * @test
     * @throws \ReflectionException
     */
    public function defaultFieldName()
    {
        $reflection = new \ReflectionClass(Contact::class);
        $method = $reflection->getMethod('getRegionFieldName');
        $method->setAccessible(true);

        $this->assertEquals('region', $method->invoke(new Contact()));
    }

    /**
     * 测试自定义区域字段名称.
     *
     * @test
     * @throws \ReflectionException
     */
    public function customFieldName()
    {
        $reflection = new \ReflectionClass(User::class);
        $method = $reflection->getMethod('getRegionFieldName');
        $method->setAccessible(true);

        $this->assertEquals('shengshiqu', $method->invoke(new User()));
    }

    /**
     * 测试错误的区域字段名.
     *
     * @test
     */
    public function wrongFieldName()
    {
        $object = new Contact();
        $object->setAttribute('wrong_field_name', '340104');

        $this->assertNull($object->province_name);
        $this->assertNull($object->city_name);
        $this->assertNull($object->district_name);
        $this->assertNull($object->full_city_name);
        $this->assertNull($object->full_district_name);
    }

    /**
     * 测试错误的区域字段值.
     *
     * @test
     */
    public function wrongRegionCode()
    {
        $object = new Contact();
        $object->setAttribute('region', '999999');

        $this->assertNull($object->province_name);
        $this->assertNull($object->city_name);
        $this->assertNull($object->district_name);
        $this->assertNull($object->full_city_name);
        $this->assertNull($object->full_district_name);
    }

    /**
     * 测试省市区名称获取.
     *
     * @test
     */
    public function regionName()
    {
        $object = new Contact();
        $object->setAttribute('region', '340104');

        $this->assertEquals('安徽省', $object->province_name);
        $this->assertEquals('合肥市', $object->city_name);
        $this->assertEquals('蜀山区', $object->district_name);
        $this->assertEquals('安徽省合肥市', $object->full_city_name);
        $this->assertEquals('安徽省合肥市蜀山区', $object->full_district_name);
    }

    /**
     * 测试区域等于查询.
     * 
     * @dataProvider provideContacts
     * @test
     *
     * @param array $contacts
     */
    public function whereRegion($contacts)
    {
        (new Migrator())->up();

        foreach ($contacts as $contact) {
            Contact::create([
                'name'   => $contact['name'],
                'region' => $contact['region'],
            ]);
        }

        foreach ($contacts as $contact) {
            $contacts = Contact::whereRegion($contact['region'])->get();

            $this->assertCount(1, $contacts);
            $this->assertEquals($contact['name'], $contacts[0]->name);
        }

        (new Migrator())->down();
    }

    /**
     * 测试区域包含查询.
     * 
     * @dataProvider provideContacts
     * @test
     *
     * @param array $contacts
     */
    public function whereInRegion($contacts)
    {
        (new Migrator())->up();

        foreach ($contacts as $contact) {
            Contact::create([
                'name'   => $contact['name'],
                'region' => $contact['region'],
            ]);
        }

        $contacts = Contact::whereInRegion('510100')->get();
        $this->assertCount(3, $contacts);

        $contacts = Contact::whereInRegion('510700')->get();
        $this->assertCount(1, $contacts);

        $contacts = Contact::whereInRegion('510000')->get();
        $this->assertCount(4, $contacts);

        (new Migrator())->down();
    }

    public function provideContacts()
    {
        return [
            [[
                ['name' => '张飞', 'region' => '510107'],
                ['name' => '刘备', 'region' => '510105'],
                ['name' => '关羽', 'region' => '510106'],
                ['name' => '诸葛亮', 'region' => '510703'],
            ]],
        ];
    }
}
