<?php

namespace AmeVirtuelle\Component\BankStatement\Parser;

use AmeVirtuelle\Component\BankStatement\Statement\Statement;

class CSVParser extends Parser
{
    const LINE_TYPE_STATEMENT = 'statement';
    const LINE_TYPE_TRANSACTION = 'transaction';
    const LINE_TYPE_HEADER = 'header';

    private $csvFields = array();
    private $firstDataLine = 0;

    /**
     * @param string $filePath
     *
     * @return Statement
     * @throws \RuntimeException
     */
    public function parseFile($filePath)
    {
        $fileObject = new \SplFileObject($filePath);

        return $this->parseFileObject($fileObject);
    }

    /**
     * @param array $fields
     *
     * @return void
     */
    public function setCSVFields( $fields )
    {
        $this->csvFields = $fields;
    }

    /**
     * @param int $lineNumber
     *
     * @return void
     */
    public function setFirstDataLine( $lineNumber )
    {
        $this->firstDataLine = $lineNumber;
    }

    /**
     * @param string $content
     *
     * @return Statement
     * @throws \InvalidArgumentException
     */
    public function parseContent($content)
    {
        if (is_string($content) === false) {
            throw new \InvalidArgumentException('Argument "$content" isn\'t a string type');
        }

        $fileObject = new \SplTempFileObject();
        $fileObject->fwrite($content);

        return $this->parseFileObject($fileObject);
    }

    /**
     * @param \SplFileObject $fileObject
     *
     * @return Statement
     */
    public function parseFileObject(\SplFileObject $fileObject)
    {
        $this->statement = $this->getStatementClass();

        $i = 0;
        foreach ($fileObject as $line) {
            if ($fileObject->valid()) {
                if ( $i >= $this->firstDataLine ) {
                    $transaction = $this->parseTransactionLine($line);
                    $this->statement->addTransaction($transaction);
                }
                /*
                switch ($this->getLineType($line)) {
                    case self::LINE_TYPE_STATEMENT:
                        $this->parseStatementLine($line);
                        break;
                    case self::LINE_TYPE_TRANSACTION:
                        $transaction = $this->parseTransactionLine($line);
                        $this->statement->addTransaction($transaction);
                        break;
                }
                */
            }

            $i++;
        }

        return $this->statement;
    }


    /*
     * Nakonec neni resene, asi by se melo pro kontrolu formatu souboru
     */
    protected function getLineType($line)
    {
        $csvLine = str_getcsv($line, ";");

        if ( !empty( $csvLine[0] ) ) {
            switch (substr($line, 0, 5)) {
                case 'MojeB':
                    return self::LINE_TYPE_STATEMENT;
                case '075':
                    return self::LINE_TYPE_TRANSACTION;
            }

        }

        return null;
    }

    protected function parseTransactionLine($line)
    {
        $transaction = $this->getTransactionClass();
        $csvLine = str_getcsv( $line, ";" );
        $transaction->setReceiptId( $csvLine[ $this->csvFields['receiptId'] ] );

        # Debit / Credit
        $amount = floatval( str_replace( ',', '.', $csvLine[ $this->csvFields['amount'] ] ) );

        if ( $amount > 0 ) {
            $transaction->setCredit( $amount );
        } else {
            $transaction->setDebit( $amount );
        }

        /*
        $postingCode = substr($line, 60, 1);
        switch ($postingCode) {
            case self::POSTING_CODE_DEBIT:
                $transaction->setDebit($amount);
                break;
            case self::POSTING_CODE_CREDIT:
                $transaction->setCredit($amount);
                break;
            case self::POSTING_CODE_DEBIT_REVERSAL:
                $transaction->setDebit($amount * (-1));
                break;
            case self::POSTING_CODE_CREDIT_REVERSAL:
                $transaction->setCredit($amount * (-1));
                break;
        }
        */

        # Variable symbol
        $transaction->setVariableSymbol( trim( $csvLine[ $this->csvFields['vs'] ] ) );

        # Constant symbol
        $transaction->setConstantSymbol( trim( $csvLine[ $this->csvFields['cs'] ] ) );

        # account number
#        $accountNumber = ltrim(substr($line, 3, 16), '0');
#        $transaction->setAccountNumber($accountNumber);

        # Counter account number
        $transaction->setCounterAccountNumber( trim( $csvLine[ $this->csvFields['accountNumber'] ] ) );

        # Specific symbol
        $transaction->setSpecificSymbol( trim( $csvLine[ $this->csvFields['ss'] ] ) );

        # Note
        $transaction->setNote( trim( $csvLine[ $this->csvFields['note'] ] ) );

        # Date created
        $date = $csvLine[ $this->csvFields['date'] ];

        $dateCreated = \DateTime::createFromFormat('d.m.Y', $date );
        $transaction->setDateCreated($dateCreated);

        return $transaction;
    }
}
