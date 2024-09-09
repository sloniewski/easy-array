<?php
declare(strict_types=1);

use EasyArray\EasyArray;
use PHPUnit\Framework\TestCase;

class TestClass {
    public $value;
    public function __construct($value) {
        $this->value = $value;
    }
}

class EasyArrayTest extends TestCase
{
    public function offsetSet()
    {
        $array = new EasyArray([5,6,7,8]);
        $array[0] = 10;
        $array[] = 9;
        $this->assertEquals(9, $array->items()[4]);
        $this->assertEquals(10, $array->items()[0]);
    }

    public function testOffsetExists()
    {
        $array = new EasyArray([5,6,7,8]);

        $this->assertTrue(isset($array[3]));
        $this->assertTrue(isset($array[0]));
        $this->assertFalse(isset($array[4]));
    }

    public function testOffsetGet()
    {
        $array = new EasyArray([5,6,7,8]);

        $this->assertEquals(5, $array[0]);
    }

    public function testShift()
    {
        $array = new EasyArray([5,6,7,8]);

        $item = $array->shift();

        $this->assertCount(3, $array->items());
        $this->assertEquals(5, $item);
    }

    public function testUnshift()
    {
        $array = new EasyArray([5,6,7,8]);

        $returnValue = $array->unshift(4);

        $this->assertCount(5, $array->items());
        $this->assertEquals(4, $array->items()[0]);
        $this->assertInstanceOf(EasyArray::class, $returnValue);
    }

    public function testFlatten()
    {
        $array = new EasyArray([[5, 6], [7, [8, 9]]]);

        $flat = $array->flatten();

        $this->assertCount(5, $flat->items());
        $this->assertInstanceOf(EasyArray::class, $flat);
    }

    public function testGet()
    {
        $array = new EasyArray([5,6,7,8]);

        $first = $array->get(1);
        $this->assertEquals(6, $first);

        $nullVal = $array->get(99);
        $this->assertNull($nullVal);
    }

    public function testGetIndex() 
    {
        $array = new EasyArray([5,6,7,8]);

        $index = $array->getIndex(6);

        $this->assertEquals(1, $index);
    }

    public function testFailGetIndex()
    {
        $array = new EasyArray([5,6,7,8]);

        $index = $array->getIndex(99);

        $this->assertNull($index);
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

        $notFound  =  $array->findIndex(
            function(int $item) {
                return $item == 88;
            }
        );
        $this->assertNull($notFound);
    }

    public function testMerge()
    {
        $array_1 = new EasyArray([1,2]);
        $array_2 = new EasyArray([3,4]);

        $merged = $array_1->merge($array_2);

        $this->assertCount(2, $array_1->items());
        $this->assertCount(2, $array_2->items());

        $this->assertCount(4, $merged->items());
        $this->assertEquals($merged->items()[0], 1);
        $this->assertEquals($merged->items()[3], 4);
    }

    public function testFirst()
    {
        $array = new EasyArray([5,6,7,8]);
        $first = $array->first();
        $this->assertEquals(5, $first);
        $this->assertCount(4, $array->items());

        $emptyArray = new EasyArray([]);
        $notFound = $emptyArray->first();
        $this->assertNull($notFound);
    }

    public function testLast()
    {
        $array = new EasyArray([0 => 5, 11 => 6, 14 => 7, 5 => 8]);
        $last = $array->last();
        $this->assertEquals(8, $last);
        $this->assertCount(4, $array->items());

        $array_2 =  new EasyArray([5,6,7,8]);
        $last_2 = $array_2->last();
        $this->assertEquals(8, $last_2);
        $this->assertCount(4, $array_2->items());

        $emptyArray = new EasyArray([]);
        $emptyVal = $emptyArray->last();
        $this->assertNull($emptyVal);
        $this->assertCount(0, $emptyArray->items());
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
        $this->assertSame($array, $walked);
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
        $array = new EasyArray([3 => 'a', 4 => 'b']);

        $mapped = $array->map(
            function(string $item) {
                return $item . $item;
            });
        
        $mappedArr = $mapped->items(); 
        $lastVal = array_pop($mappedArr);

        $this->assertInstanceOf(EasyArray::class, $mapped);
        $this->assertEquals($lastVal, 'bb');
        $this->assertEquals($mapped->get(3), 'aa');
        $this->assertNotSame($array, $mapped);
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
        $this->assertInstanceOf(EasyArray::class, $filteredArray);
        $this->assertSame($array, $filteredArray);
    }

    public function testFiltered()
    {
        $array = new EasyArray([1,2,3,4]);

        $filteredArray = $array->filtered(
            function(int $item) {
                return ($item % 2) == 0;
            });

        $this->assertCount(2, $filteredArray->items());
        $this->assertContains(2, $filteredArray->items());
        $this->assertContains(4, $filteredArray->items());
        $this->assertInstanceOf(EasyArray::class, $filteredArray);
        $this->assertNotSame($array, $filteredArray);
    }

    public function testIterable()
    {
        $items = [1,2,3,4];
        $array = new EasyArray($items);

        foreach($array as $key => $value) {
            $this->assertEquals($value, $items[$key]);
        }

        foreach($array as $key => $value) {
            $array[$key] += 2;
        }

        $this->assertEquals(5, $array[2]);
    }

    public function testIterableWithKeys()
    {
        $items = [
            "a" => 1,
            "b" => 2,
            "c" => 3,
            "d" => 4
        ];

        $array = new EasyArray($items);

        foreach($array as $key => $value) {
            $this->assertEquals($value, $items[$key]);
        }

        foreach($array as $key => $value) {
            $array[$key] += 2;
        }

        $this->assertEquals(5, $array["c"]);
    }

    public function testReduce()
    {
        $array = new EasyArray([
            4 => 1,
            5 => 2,
            6 => 3,
            7 => 4,
        ]);

        $sum = $array->reduce(
            function(?int $carry, int $item) {return $carry + $item;}
        );

        $this->assertEquals(10, $sum);
    }

    public function testSumValues()
    {
        $array = new EasyArray([
            4 => 1, 
            5 => 2,
            6 => 3,
            7 => 4,
        ]);

        $this->assertEquals(10, $array->sumValues());
    }

    public function testSumAttribute()
    {
       $array = new EasyArray([
            new TestClass(1), 
            new TestClass(2), 
            new TestClass(3), 
            new TestClass(4), 
        ]);

        $this->assertEquals(10, $array->sumAttribute('value'));
    }

    public function testValues()
    {
        $array = new EasyArray([
            4 => 1, 
            5 => 2,
            6 => 3,
            7 => 4,
        ]);

        $this->assertEquals(
            $array->values(), 
            [ 1, 2, 3, 4 ]
        );
    }

    public function testDiff()
    {
        $first = new EasyArray([1,2,3,4]);
        $second = new EasyArray([3,4,5,6]);

        $this->assertInstanceOf(EasyArray::class, $first->diff($second));
        $this->assertEquals($first->diff($second)->values(), [1,2]);
        $this->assertEquals($second->diff($first)->values(), [5,6]);
        $this->assertEquals($first->diff($second)->items(), [0 => 1, 1 => 2]);
        $this->assertEquals($second->diff($first)->items(), [2 => 5, 3 => 6]);
    }

    public function testIntersect()
    {
        $first = new EasyArray([1,2,3,4]);
        $second = new EasyArray([3,4,5,6]);

        $this->assertInstanceOf(EasyArray::class, $first->intersect($second));
        $this->assertEquals($first->intersect($second)->values(), [3,4]);
        $this->assertEquals($second->intersect($first)->values(), [3,4]);
        $this->assertEquals($first->intersect($second)->items(), [2 => 3, 3 => 4]);
        $this->assertEquals($second->intersect($first)->items(), [0 => 3, 1 => 4]);
    }

    public function testClone()
    {
        $array = new EasyArray([]);
        $firstClone = clone($array);
        $secondClone =  $array->clone();

        $this->assertInstanceOf(EasyArray::class, $firstClone);
        $this->assertInstanceOf(EasyArray::class, $secondClone);

        $array2 = new EasyArray([ [1,2], [3,4] ]);
        $array2Clone = $array2->clone();
        $this->assertEquals($array2->items(), $array2Clone->items());
    }

    public function testIsEmpty()
    {
        $array = new EasyArray([]);

        $this->assertTrue($array->isEmpty());
        $this->assertFalse($array->isNotEmpty());
    }

    public function testIsNotEmpty()
    {
        $array = new EasyArray([1]);

        $this->assertFalse($array->isEmpty());
        $this->assertTrue($array->isNotEmpty());
    }

    public function testSortNoSortFuncProvided()
    {
        $array = new EasyArray([4,5,3,2,1,0]);

        $sorted = $array->sort();

        $this->assertEquals(0,$sorted->items()[0]);
        $this->assertEquals(5,$sorted->items()[5]);
        $this->assertInstanceOf(EasyArray::class, $sorted);
        $this->assertSame($array, $sorted);
        $this->assertTrue($array === $sorted);
    }

    public function testSortedNoSortFuncProvided()
    {
        $array = new EasyArray([4,5,3,2,1,0]);

        $sorted = $array->sorted();

        $this->assertEquals(0,$sorted->items()[0]);
        $this->assertEquals(4,$array->items()[0]);
        $this->assertEquals(5,$sorted->items()[5]);
        $this->assertEquals(0,$array->items()[5]);
        $this->assertInstanceOf(EasyArray::class, $sorted);
        $this->assertNotSame($array, $sorted);
        $this->assertFalse($array === $sorted);
    }

    public function testSortIntegersByFunction()
    {
        $array = new EasyArray([4,5,3,3,1,0]);

        $sorter = function(int $first, int $second): int {
            if($first == $second) {
                return EasyArray::EQUALS;
            }

            return $first < $second ? EasyArray::GREATER : EasyArray::LESS;
        };

        $sorted = $array->sort($sorter);

        $this->assertEquals(0,$array->items()[5]);
        $this->assertEquals(5, $array->items()[0]);
        $this->assertInstanceOf(EasyArray::class, $sorted);
    }

    public function testSortStringsByFunction()
    {
        $array = new EasyArray(['x','y','c','b','b','z','a']);

        $sorter = function(string $first, string $second): int {
            return -1 * strcmp($first, $second);
        };

        $sorted = $array->sort($sorter);

        $this->assertEquals('a',$array->items()[6]);
        $this->assertEquals('z', $array->items()[0]);
        $this->assertInstanceOf(EasyArray::class, $sorted);
        $this->assertTrue($array === $sorted);
    }

    public function testSlice()
    {
        $array = new EasyArray(["a", "b", "c", "d", "e"]);

        $slice = $array->slice(2);
        $this->assertEquals('c',$slice->items()[0]);
        $this->assertEquals('e', $slice->items()[2]);
        $this->assertInstanceOf(EasyArray::class, $slice);
    }

    public function testKeyBy()
    {
        $array = new EasyArray([
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
            'e' => 5
        ]);

        $keyFunc = function(int $item): int {
            return $item**2;
        };


        $array->keyBy($keyFunc);

        $this->assertEquals(2,$array->items()[4]);
        $this->assertEquals(5, $array->items()[25]);
        $this->assertCount(5, $array->items());
    }

    public function testIncludes()
    {
        $first = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $second = new EasyArray(['e','b']);

        $this->assertTrue($first->includes($second));
        $this->assertFalse($second->includes($first));
    }

    public function testIncludesInOrder()
    {
        $first_1 = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $second_1 = new EasyArray(['b','c']);

        $this->assertTrue($first_1->includesInOrder($second_1));
        $this->assertFalse($second_1->includesInOrder($first_1));

        $first_2 = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $second_2 = new EasyArray(['b']);

        $this->assertTrue($first_2->includesInOrder($second_2));
        $this->assertFalse($second_2->includesInOrder($first_2));

        $first_3 = new EasyArray(['a', 'b', 'c', 'd', 'e', 'f']);
        $second_3 = new EasyArray(['c', 'd', 'e']);

        $this->assertTrue($first_3->includesInOrder($second_3));
        $this->assertFalse($second_3->includesInOrder($first_3));

    }

    public function testIncludesInOrderFail()
    {
        $first = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $second = new EasyArray(['a','e']);

        $this->assertFalse($first->includesInOrder($second));
        $this->assertFalse($second->includesInOrder($first));
    }

    public function testRemoveFirst()
    {
        $array = new EasyArray(['a', 'b', 'c', 'd', 'e']);

        $array->removeFirst();
        $this->assertCount(4, $array);
        $this->assertEquals($array->first(), "b");
        $this->assertEquals($array->items(), ['b', 'c', 'd', 'e']);

    }

    public function testRemoveLast()
    {
        $array = new EasyArray(['a', 'b', 'c', 'd', 'e']);

        $array->removeLast();
        $this->assertCount(4, $array);
        $this->assertEquals($array->last(), "d");
        $this->assertEquals($array->items(), ['a', 'b', 'c', 'd']);
    }

    public function testIsSameAs()
    {
        $first = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $second = new EasyArray(['a', 'b', 'c', 'd', 'e']);

        $this->assertTrue($first->isSameAs($second));
    }

    public function testIsSameAsFail()
    {
        $first = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $second = new EasyArray(['a', 'b', 'c', 'd', 'x']);

        $this->assertFalse($first->isSameAs($second));
    }

    public function testRotatePostive()
    {
        $first = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $first->rotate(4);

        $this->assertEquals($first->first(), 'e');
    }

    public function testRotateNegative()
    {
        $first = new EasyArray(['a', 'b', 'c', 'd', 'e']);
        $first->rotate(-2);

        $this->assertEquals($first->last(), 'c');
    }

    public function testPurge()
    {
        $array = new EasyArray([1,2,3,4,5]);
        $array->purge();
        $this->assertCount(0, $array->items());
    }

    public function testClonePreservesKeys()
    {
        $first = new EasyArray(["a" => 1, "b" => 2, 3 => "d"]);

        $second = clone($first);

        $this->assertEquals($first->get("a"), $second->get("a"));
        $this->assertEquals($first->get("b"), $second->get("b"));
        $this->assertEquals($first->get(3), $second->get(3));

    }

    public function testKeySort()
    {
        $first = new EasyArray([2 => "b", 3 => "c", 1 => "a"]);
        $second = $first->keySort();

        $this->assertEquals("a", $second->first());
        $this->assertSame($first, $second);

    }

    public function testKeySorted()
    {
        $first = new EasyArray([2 => "b", 3 => "c", 1 => "a"]);
        $second = $first->keySorted();

        $this->assertEquals("a", $second->first());
        $this->assertNotSame($first, $second);
    }

    public function testKeySortWithSortFunc()
    {
        $first = new EasyArray([2 => "b", 3 => "c", 1 => "a"]);
        $second = $first->keySort();

        $sorter = function(int $first, int $second): int {
            if($first == $second) {
                return EasyArray::EQUALS;
            }

            return $first > $second ? EasyArray::GREATER : EasyArray::LESS;
        };

        $second = $first->keySort($sorter);

        $this->assertEquals("a", $second->first());
        $this->assertSame($first, $second);
    }

    public function testKeySortedWithSortFunc()
    {
        $first = new EasyArray([2 => "b", 3 => "c", 1 => "a"]);
        $second = $first->keySort();

        $sorter = function(int $first, int $second): int {
            if($first == $second) {
                return EasyArray::EQUALS;
            }

            return $first > $second ? EasyArray::GREATER : EasyArray::LESS;
        };

        $second = $first->keySorted($sorter);

        $this->assertEquals("a", $second->first());
        $this->assertNotSame($first, $second);
    }

    public function testCloneOnReplicate()
    {
        $now = new \DateTime();

        $array = new EasyArray([$now]);
        $array->cloneItemsOnReplicate();
        $flat = $array->flattened();

        $this->assertNotSame($array->first(), $flat->first());
        $this->assertNotSame($array, $flat);
    }

    public function testNotCloneItemsOnReplicate()
    {
        $now = new \DateTime();

        $array = new EasyArray([$now]);

        $flat = $array->flattened();

        $this->assertSame($array->first(), $flat->first());
        $this->assertNotSame($array, $flat);
    }
}
