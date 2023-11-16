<?php
declare(strict_types=1);

use EasyArray\EasyArray;
use PHPUnit\Framework\TestCase;

class EasyArrayTypedTest extends TestCase
{
    public function testHasTypeSet()
    {
        $array = new EasyArray([], true);
        $this->assertFalse($array->hasTypeSet());

        $array->push(1);
        $this->assertTrue($array->hasTypeSet());
    }

    public function testGetType()
    {
        $array = new EasyArray([], true);
        $array->push((new \DateTime()));

        $this->assertEquals("DateTime", $array->getType()->getTypeName());

        $array_1 = new EasyArray(["a", "b"], true);

        $this->assertEquals("string", $array_1->getType()->getTypeName());

        $array_1 = new EasyArray([NULL], true);

        $this->assertEquals("NULL", $array_1->getType()->getTypeName());
    }

    public function testHasSameType()
    {

    }

    public function testHasSameTypeAsValue()
    {

    }

    public function testSetTypeFromValueFailOnNotTyped()
    {
        
    }

    public function testSetTypeFromValueFailOnTypeAlreadySet()
    {

    }


    public function testConstructorFail() 
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([1,'a', (new \DateTime())], true);
    }

    public function testSetSuccess()
    {
        $array = new EasyArray([1,2,3], true);
        $array->set(2, 8);

        $this->assertEquals($array->count(), 3);
        $this->assertEquals($array->get(2), 8);
        $this->assertEquals("integer", $array->getType()->getTypeName());
    }

    public function testSetFail()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([new \DateTime()], true);
        $array->set(0,new \DateTimeImmutable());
    }

    public function testSetFailOnNull()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([0], true);
        $array->set(1,NULL);
    }

    public function testSetFailOnBool()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([false], true);
        $array->set(1,0);
    }

    public function testOffsetSetSuccess()
    {
        $array = new EasyArray([1,2,3], true);
        $array[2] = 8;

        $this->assertEquals($array->count(), 3);
        $this->assertEquals($array[2], 8);
    }

    public function testOffsetSetFail()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([new \DateTime()], true);
        $array[2] = new \DateTimeImmutable();
    }

    public function testOffsetSetOnEmptySuccess()
    {
        $array = new EasyArray([], true);
        $array[0] = 1;
        $array[1] = 2;
    }
    
    public function testOffsetSetOnEmptyFail()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([], true);
        $array[0] = "a";
        $array[1] = 1;
    }

    public function testPushFail()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([1,2,3], true);
        $array->push("a");
    }

    public function testPushSuccess()
    {
        $array = new EasyArray([1,2,3], true);
        $array->push(8);

        $this->assertEquals($array->count(), 4);
        $this->assertEquals($array->last(), 8);
    }

    public function testPushOnEmpty()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([], true);
        $array->push("a");
        $array->push(1);
    }

    public function testPushObjects()
    {
        $this->expectException(\TypeError::class);
        $array = new EasyArray([], true);
        $array->push("a");
        $array->push(1);
    }

    public function testMergeFail()
    {
        $first = new EasyArray([1,2], true);
        $second = new EasyArray(["a", "b"], true);

        $this->expectException(\TypeError::class);
        $first->merge($second);
    }

    public function testMergeEmpty()
    {
        $first = new EasyArray([], true);
        $second = new EasyArray(["a", "b"], true);

        $merged = $first->merge($second);
        $this->assertCount(2, $merged->items());

        $third = new EasyArray([1,2]);
        $this->expectException(\TypeError::class);
        $secondMerge = $merged->merge($third);
    }


    public function testDiffThrowTypeError()
    {

    }

    public function testIntersectThrowTypeError()
    {

    }

    public function testMapClosureReturningWrongType()
    {

    }
    public function testWalkClosureReturningWrongType()
    {

    }

    public function testIncludesOtherType()
    {

    }

    public function testIncludesInOrderOtherType()
    {
        
    }
}