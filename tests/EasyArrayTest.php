<?php

use EasyArray\EasyArray;
use PHPUnit\Framework\TestCase;

class EasyArrayTest extends TestCase
{
    public function testGet()
    {
        $array = new EasyArray([5,6,7,8]);

        $first = $array->get(1);

        $this->assertEquals(6, $first);
    }

    public function testFindIndex()
    {
        $array = new EasyArray([5,6,7,8]);

        $found = $array->findIndex(
            function(int $item) {
                return $item == 7;
            }
        );

        $this->assertEquals($found, 2);
    }

    public function testMerge()
    {
        $array_1 = new EasyArray([1,2]);
        $array_2 = new EasyArray([3,4]);

        $merged = $array_1->merge($array_2);

        $this->assertCount(4, $merged->items());
        $this->assertEquals($merged->items()[0], 1);
        $this->assertEquals($merged->items()[3], 4);
    }

    public function testFirst()
    {
        $array = new EasyArray([5,6,7,8]);

        $first = $array->first();

        $this->assertEquals(5, $first);
    }

    public function testFirstEmpty()
    {
        $array = new EasyArray([]);

        $first = $array->first();

        $this->assertNull($first);
    }

    public function testLast()
    {
        $array = new EasyArray([5,6,7,8]);

        $last = $array->last();

        $this->assertEquals(8, $last);
    }

    public function testLastEmpty()
    {
        $array = new EasyArray([]);

        $last = $array->last();

        $this->assertNull($last);
    }

    public function testFind()
    {
        $array = new EasyArray([5,6,7,8]);

        $found = $array->find(
            function(int $item) {
                return ($item % 2) == 0;
            }
        );

          $this->assertEquals($found, 6);
    }

    public function testWalk()
    {
        $array = new EasyArray([1,2,3]);

        $walked = $array->walk(
            function(int $item) {
                return $item * 2;
            }
        );

        $walkedArray = $array->items();
        $this->assertInstanceOf(EasyArray::class, $walked);
        $this->assertEquals($walkedArray[0], 2);
        $this->assertEquals($walkedArray[1], 4);
        $this->assertEquals($walkedArray[2], 6);
    }

    public function testCount()
    {
        $array = new EasyArray([1,2,3,4]);

        $this->assertEquals($array->count(), 4);
    }

    public function testPop()
    {
        $array = new EasyArray([1,2,3,4]);

        $poppedValue = $array->pop();

        $this->assertEquals(4, $poppedValue);
    }

    public function testArrayPopEmpty()
    {
        $array = new EasyArray([]);

        $poppedValue = $array->pop();

        $this->assertNull($poppedValue);
    }

    public function testMap()
    {
        $array = new EasyArray(['a', 'b']);

        $mapped = $array->map(
            function(string $item) {
                return $item . $item;
            });
        
        $mappedArr = $mapped->items(); 
        $lastVal = array_pop($mappedArr);

        $this->assertInstanceOf(EasyArray::class, $mapped);
        $this->assertEquals($lastVal, 'bb');
    }

    public function testFilter()
    {
        $array = new EasyArray([1,2,3,4]);

        $filteredArray = $array->filter(
            function(int $item) {
                return ($item % 2) == 0;
            });

        $this->assertCount(2, $filteredArray->items());
        $this->assertContains(2, $filteredArray->items());
        $this->assertContains(4, $filteredArray->items());
    }

    public function testIterable()
    {
        $items = [1,2,3,4];
        $array = new EasyArray($items);

        foreach($array as $key => $value) {
            $this->assertEquals($value, $items[$key]);
        }
    }
}
