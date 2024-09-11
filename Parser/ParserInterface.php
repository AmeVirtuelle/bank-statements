<?php

namespace amevirtuelle\Component\BankStatement\Parser;

use amevirtuelle\Component\BankStatement\Statement\StatementInterface;

interface ParserInterface
{
    /**
     * @param string $filePath
     *
     * @return StatementInterface
     * @throw \Exception
     */
    public function parseFile($filePath);

    /**
     * @param string $content
     *
     * @return StatementInterface
     * @throw \Exception
     */
    public function parseContent($content);

    /**
     * @return StatementInterface
     */
    public function getStatement();
}
