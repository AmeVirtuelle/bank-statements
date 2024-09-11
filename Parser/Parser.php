<?php

namespace amevirtuelle\Component\BankStatement\Parser;

use amevirtuelle\Component\BankStatement\Statement\Statement;
use amevirtuelle\Component\BankStatement\Statement\Transaction\Transaction;

abstract class Parser implements ParserInterface
{
    /**
     * @var Statement
     */
    protected $statement;

    /**
     * @param string $filePath
     *
     * @return Statement
     * @throw \Exception
     */
    abstract public function parseFile($filePath);

    /**
     * @param string $content
     *
     * @return Statement
     * @throw \Exception
     */
    abstract public function parseContent($content);

    /**
     * @return Statement
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * Get a new instance of statement class
     *
     * @return Statement
     */
    protected function getStatementClass()
    {
        return new Statement();
    }

    /**
     * Get a new instance of transaction class
     *
     * @return Transaction
     */
    protected function getTransactionClass()
    {
        return new Transaction();
    }
}
