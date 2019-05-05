### 中国行政区划省市区（无数据表实现方案）

思路：通过身份证前6位来处理省市区的级联关系

[![Build Status](https://api.travis-ci.org/lvqingan/laravel-cn-region.svg?branch=master)](https://travis-ci.org/lvqingan/laravel-cn-region) [![Style Status](https://github.styleci.io/repos/183974432/shield?style=plastic)](https://github.styleci.io/repos/183974432)

#### 安装

1. 安装代码库

```bash
composer require lvqingan/laravel-cn-region:dev-master
```

2. 修改 `database/migration` 中的表结构，增加保存区域的字段（默认使用`region`）

```php
$table->char('region', 6);
$table->index(['region']);
```

3. 在 `Eloquent` 的模型中添加 `HasRegion` trait

> 默认使用 `region` 作为字段名，如果数据表使用的是其他字段来保存区域值，则需要定义属性 `$regionFieldName`

```php
   class User extends Model
   {
       use HasRegion;
   }
```

```php
   class User extends Model
   {
       use HasRegion;

       protected $regionFieldName = 'shengshiqu';
   }
```

#### 获取下拉列表数据

**省份**

```php
$provinces = (new \Lvqingan\Region\Loader('province'))->load();
```

```json
{
    "110000":"北京市",
    "820000":"澳门特别行政区"
}
```

**城市**

```php
$cities = (new \Lvqingan\Region\Loader('city', '340000'))->load();
```

```json
{
    "340100":"合肥市",
    "341800":"宣城市"
}
```

**区县**

```php
$districts = (new \Lvqingan\Region\Loader('340100', '340100'))->load();
```

```json
{
    "340103":"庐阳区",
    "340111":"包河区"
}
```

#### 获取名称

实例化的模型对象可以调用下面的属性（假设区域值为340104）

| 属性名称   |      说明      |  返回值 |
|----------|:-------------:|------:|
| province_code |  省份编码 | 340000 |
| city_code |    城市编码   |   340100 |
| district_code | 区县编码 |    340104 |
| province_name |  省份名称 | 安徽省 |
| city_name |    城市名称   |   合肥市 |
| district_name | 区县名称 |    蜀山区 |
| full_city_name |    完整城市名称   |   安徽省合肥市 |
| full_district_name | 完整区县名称 |    安徽省合肥市蜀山区 |

#### 数据查询

按区域编码直接查询区域等于该值的数据

```php
    User::whereRegion('340104')->get()
```

按区域编码直接查询区域包含在该范围内的数据

```php
    User::whereInRegion('340000')->get()
```
