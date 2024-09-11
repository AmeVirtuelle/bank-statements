<?php

namespace JakubZapletal\Component\BankStatement\Statement;

use JakubZapletal\Component\BankStatement\Statement\Transaction\TransactionInterface;

class Statement implements StatementInterface, \Countable, \Iterator
{
    /**
     * @var string
     */
    protected $accountNumber;

    /**
     * @var float
     */
    protected $balance;

    /**
     * @var float
     */
    protected $debitTurnover;

    /**
     * @var float
     */
    protected $creditTurnover;

    /**
     * @var string
     */
    protected $serialNumber;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var \DateTime
     */
    protected $dateLastBalance;

    /**
     * @var float
     */
    protected $lastBalance;

    /**
     * @var TransactionInterface[]
     */
    protected $transactions = array();

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param $balance
     *
     * @return $this
     */
    public function setBalance($balance): self
    {
        $this->balance = (float) $balance;

        return $this;
    }

    /**
     * @return float
     */
    public function getCreditTurnover(): float
    {
        return $this->creditTurnover;
    }

    /**
     * @param $creditTurnover
     *
     * @return $this
     */
    public function setCreditTurnover($creditTurnover): self
    {
        $this->creditTurnover = (float) $creditTurnover;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateCreated(): ?\DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     *
     * @return $this
     */
    public function setDateCreated(\DateTime $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return string
     */
    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    /**
     * @param $serialNumber
     *
     * @return $this
     */
    public function setSerialNumber($serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * @return float
     */
    public function getDebitTurnover(): float
    {
        return $this->debitTurnover;
    }

    /**
     * @param $debitTurnover
     *
     * @return $this
     */
    public function setDebitTurnover($debitTurnover): self
    {
        $this->debitTurnover = (float) $debitTurnover;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    /**
     * @param $accountNumber
     *
     * @return $this
     */
    public function setAccountNumber($accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Split account number to parts
     *
     * @return array
     */
    public function getParsedAccountNumber(): array
    {
        $parsedAccountNumber = array(
            'prefix'   => null,
            'number'   => null,
            'bankCode' => null
        );

        $accountNumber = $this->getAccountNumber();

        $splitBankCode = explode('/', $accountNumber);
        if (count($splitBankCode) === 2) {
            $parsedAccountNumber['bankCode'] = $splitBankCode[1];
        }

        $splitNumber = explode('-', $splitBankCode[0]);
        if (count($splitNumber) === 2) {
            $parsedAccountNumber['prefix'] = $splitNumber[0];
            $parsedAccountNumber['number'] = $splitNumber[1];
        } else {
            if (strlen($splitNumber[0]) <= 10) {
                $parsedAccountNumber['number'] = $splitNumber[0];
            } else {
                $parsedAccountNumber['prefix'] = substr($splitNumber[0], 0, strlen($splitNumber[0]) - 10);
                $parsedAccountNumber['number'] = substr($splitNumber[0], -10, 10);
            }
        }

        return $parsedAccountNumber;
    }

    /**
     * @return \DateTime
     */
    public function getDateLastBalance(): \DateTime
    {
        return $this->dateLastBalance;
    }

    /**
     * @param \DateTime $dateLastBalance
     *
     * @return $this
     */
    public function setDateLastBalance(\DateTime $dateLastBalance): self
    {
        $this->dateLastBalance = $dateLastBalance;

        return $this;
    }

    /**
     * @return float
     */
    public function getLastBalance(): float
    {
        return $this->lastBalance;
    }

    /**
     * @param float $lastBalance
     *
     * @return $this
     */
    public function setLastBalance($lastBalance): self
    {
        $this->lastBalance = (float) $lastBalance;

        return $this;
    }

    /**
     * @return TransactionInterface[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param TransactionInterface $transaction
     *
     * @return $this
     */
    public function addTransaction(TransactionInterface $transaction): self
    {
        $added = false;

        foreach ($this->transactions as $addedTransaction) {
            if ($transaction === $addedTransaction) {
                $added = true;
                break;
            }
        }

        if ($added !== true) {
            $this->transactions[] = $transaction;
        }

        return $this;
    }

    /**
     * @param TransactionInterface $transaction
     *
     * @return $this
     */
    public function removeTransaction(TransactionInterface $transaction): self
    {
        foreach ($this->transactions as $key => $addedTransaction) {
            if ($transaction === $addedTransaction) {
                unset($this->transactions[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * @see \Countable::count()
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->transactions);
    }

    /**
     * @see \Iterator::current()
     *
     * @return TransactionInterface
     */
    public function current(): TransactionInterface
    {
        return current($this->transactions);
    }

    /**
     * @see \Iterator::key()
     *
     * @return mixed
     */
    public function key(): mixed
    {
        return key($this->transactions);
    }

    /**
     * @see \Iterator::next()
     *
     * @return void
     */
    public function next(): void
    {
        next($this->transactions);
    }

    /**
     * @see \Iterator::rewind()
     *
     * @return void
     */
    public function rewind(): void
    {
        reset($this->transactions);
    }

    /**
     * @see \Iterator::valid()
     *
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->transactions) !== null;
    }
}
