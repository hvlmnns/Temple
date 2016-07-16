<?php


class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Underware\Exception\Exception
     */
    public function testException()
    {
        throw new \Underware\Engine\Exception\Exception("test", "myfile", "myline");
    }


    public function testExceptionMessage()
    {
        $expected = "test";
        $e        = new \Underware\Engine\Exception\Exception($expected, "myfile", "myline");
        $result   = $e->getMessage();
        $this->assertEquals($expected, $result);
    }


    public function testExceptionFile()
    {
        $expected = "test";
        $e        = new \Underware\Engine\Exception\Exception("test", $expected, "myline");
        $result   = $e->getUnderwareFile();
        $this->assertEquals($expected, $result);
    }


    public function testExceptionLine()
    {
        $expected = "myline";
        $e        = new \Underware\Engine\Exception\Exception("test", "myfile", $expected);
        $result   = $e->getUnderwareLine();
        $this->assertEquals($expected, $result);
    }

}