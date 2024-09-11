<?php

namespace amevirtuelle\Component\BankStatement\Tests\Parser;

use amevirtuelle\Component\BankStatement\Parser\Parser;
use amevirtuelle\Component\BankStatement\Statement\Statement;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    protected $parser;

    protected function setUp()
    {
        $this->parser = $this->getMockForAbstractClass('\amevirtuelle\Component\BankStatement\Parser\Parser');
    }

    public function testGetStatement()
    {
        $reflectionParser = new \ReflectionClass($this->parser);
        $property = $reflectionParser->getProperty('statement');
        $property->setAccessible(true);
        $property->setValue($this->parser, new Statement());

        $this->assertInstanceOf(
            '\amevirtuelle\Component\BankStatement\Statement\Statement',
            $this->parser->getStatement()
        );
    }

    public function testGetStatementClass()
    {
        $reflectionParser = new \ReflectionClass($this->parser);
        $method = $reflectionParser->getMethod('getStatementClass');
        $method->setAccessible(true);

        $this->assertInstanceOf(
            '\amevirtuelle\Component\BankStatement\Statement\Statement',
            $method->invoke($this->parser)
        );
    }

    public function testGetTransactionClass()
    {
        $reflectionParser = new \ReflectionClass($this->parser);
        $method = $reflectionParser->getMethod('getTransactionClass');
        $method->setAccessible(true);

        $this->assertInstanceOf(
            '\amevirtuelle\Component\BankStatement\Statement\Transaction\Transaction',
            $method->invoke($this->parser)
        );
    }
}
