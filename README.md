# Bank Statements

[![Build Status](https://travis-ci.org/AmeVirtuelle/bank-statements.svg?branch=master)](https://travis-ci.org/AmeVirtuelle/bank-statements)
[![Coverage Status](https://coveralls.io/repos/AmeVirtuelle/bank-statements/badge.png?branch=master)](https://coveralls.io/r/AmeVirtuelle/bank-statements?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7feae3d9-f29f-41c2-9843-218f2eac5db9/mini.png)](https://insight.sensiolabs.com/projects/7feae3d9-f29f-41c2-9843-218f2eac5db9)

[![Latest Stable Version](https://poser.pugx.org/AmeVirtuelle/bank-statements/v/stable.png)](https://packagist.org/packages/AmeVirtuelle/bank-statements)
[![Total Downloads](https://poser.pugx.org/AmeVirtuelle/bank-statements/downloads.png)](https://packagist.org/packages/AmeVirtuelle/bank-statements)
[![Latest Unstable Version](https://poser.pugx.org/AmeVirtuelle/bank-statements/v/unstable.png)](https://packagist.org/packages/AmeVirtuelle/bank-statements)
[![License](https://poser.pugx.org/AmeVirtuelle/bank-statements/license.png)](https://packagist.org/packages/AmeVirtuelle/bank-statements)

This is a PHP library to parse bank account statements. The purpose of this library is to simplify bank statements processing
and usage in your application in more standardized way. The parser result is an instance of:
`AmeVirtuelle\Component\BankStatement\Statement\StatementInterface` containing detail information
about a statement and an array of `AmeVirtuelle\Component\BankStatement\Statement\Transaction\TransactionInterface` with further
information about transactions.


### Supported formats/bank list

* ABO (`*.gpc`) [[doc](doc/abo.md)]
 * Česká spořitelna (CZ): `AmeVirtuelle\Component\BankStatement\Parser\ABO\CeskaSporitelnaCZParser`
 * ČSOB (CZ): `AmeVirtuelle\Component\BankStatement\Parser\ABOParser`
 * Fio banka (CZ): `AmeVirtuelle\Component\BankStatement\Parser\ABOParser`
 * GE Money Bank (CZ): `AmeVirtuelle\Component\BankStatement\Parser\ABOParser`
 * Komerční banka (CZ), *alias KM format*: `AmeVirtuelle\Component\BankStatement\Parser\ABOParser`
 * Raiffeisenbank (CZ): `AmeVirtuelle\Component\BankStatement\Parser\ABOParser`
* XML
 * ČSOB (CZ) [[doc](doc/xml/csob_cz.md)]: `AmeVirtuelle\Component\BankStatement\Parser\XML\CSOBCZParser`
* CSV


## Installation

Note that Bank Statements is [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) compliant:

### Composer

If you don't have Composer [install](http://getcomposer.org/doc/00-intro.md#installation) it:

```bash
$ curl -s https://getcomposer.org/installer | php
```

Add `AmeVirtuelle/bank-statements` to `composer.json`:

```bash
$ composer require "AmeVirtuelle/bank-statements:1.0.*@dev"
```


## Usage

Parsing of each format is provided by a class implementing:

```php
AmeVirtuelle\Component\BankStatement\Parser\ParserInterface
```

Thanks to the interface we can rely on two main public methods: `parseFile` and `parseContent`.

* `parseFile` expects as an argument a **path to file** and then processes the parsing
* `parseContent` expects as an argument a **string of content** and then processes the parsing

Both methods return a class implementing:

```php
AmeVirtuelle\Component\BankStatement\Statement\StatementInterface
```

The statement class includes transaction items, which are classes implementing:

```php
AmeVirtuelle\Component\BankStatement\Statement\Transaction\TransactionInterface
```

This behaviour ensures the **same approach to the parsing and results for all parsers**.

All abstract classes and standard classes are **easily extendable**, allowing implement parsing process of any data.

The basic statement class:

```php
AmeVirtuelle\Component\BankStatement\Statement\Statement
```

implements `Countable` and `Iterator`, so we can call function `count()` on it's instances or traverse them using `foreach()`.
Keep in mind that transactions of the statements are used. If you need more functionality in the statement class,
I recommend extend this class.

### Examples

The parsing:

```php
use AmeVirtuelle\Component\BankStatement\Parser\ABOParser;

$parser = new ABOParser();

// by path to file
$path = '/path/to/file';
$statement = $parser->parseFile($path);

// by content
$content = 'string of data';
$statement = $parser->parseContent($content);
```

Manipulation with the statement:

```php
echo count($statement); // echo count of transaction items

foreach ($statement as $transaction) {
    // do something with each transaction
}

echo $statement->getAccountNumber(); // echo an account number of the statement
```


## Contributing

Contributions are welcome! Please see the [Contribution Guidelines](contributing.md).
