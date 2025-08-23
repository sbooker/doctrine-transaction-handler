[Read in Russian](README.md)

# Doctrine Transaction Handler (`sbooker/doctrine-transaction-handler`)

[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
[![Total Downloads][badge-downloads]][downloads]

A ready-to-use `TransactionHandler` implementation for integrating [sbooker/transaction-manager](https://github.com/sbooker/transaction-manager) with Doctrine ORM.

## Library's Purpose

This library is a "bridge" between the abstract transaction manager and the Doctrine ORM. It saves you from having to write your own adapter.

The library also solves the fundamental problem of **memory and state management in long-running processes** (workers, consumers), which is one of the use cases for `transaction-manager`.

## Key Features

*   **Automatic Memory and State Management:** After each successful commit, the handler calls `EntityManager::clear()`. This is **critically important** for long-running processes and provides:
    *   **Memory Leak Prevention:** The Unit of Work does not grow indefinitely.
    *   **Data Freshness Guarantee:** On each new iteration, entities will be re-loaded from the DB.
    *   **Operation Isolation:** The state of the previous transaction does not affect the next one.
*   **Ready-to-use Solution:** Just install and use.
*   **Pessimistic Locking:** Guarantees data integrity during concurrent access out of the box (`LockMode::PESSIMISTIC_WRITE`).
*   **Correct Exception Handling:** Properly converts `UniqueConstraintViolationException` into `UniqueConstraintViolation` from `transaction-manager`.

## Installation

```bash
composer require sbooker/doctrine-transaction-handler
```
**Dependencies:**
*   `sbooker/transaction-manager`
*   `doctrine/orm`

## Usage

Using the library comes down to a single step: creating an instance of `DoctrineTransactionHandler` and passing it to the `TransactionManager`.

```php
// bootstrap.php or your DI container

use Doctrine\ORM\EntityManagerInterface;
use Sbooker\TransactionManager\TransactionManager;
use Sbooker\TransactionManager\DoctrineTransactionHandler;

/** @var EntityManagerInterface $entityManager */

// 1. Create the handler, passing the EntityManager to it
$transactionHandler = new DoctrineTransactionHandler($entityManager);

// 2. Create the transaction manager
$transactionManager = new TransactionManager($transactionHandler);

// 3. Done! Now use $transactionManager in your application code
$transactionManager->transactional(function () {
    // ... your code ...
});
// After this block executes successfully, $entityManager->clear() will be called automatically.
```

After this simple setup, you can fully use the `TransactionManager` throughout your application as described in its [documentation](https://github.com/sbooker/transaction-manager).

## License
See [LICENSE][license] file.

[badge-release]: https://img.shields.io/packagist/v/sbooker/doctrine-transaction-handler.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/sbooker/doctrine-transaction-handler.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/sbooker/doctrine-transaction-handler.svg?style=flat-square

[release]: https://packagist.org/packages/sbooker/doctrine-transaction-handler
[license]: https://github.com/sbooker/doctrine-transaction-handler/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/sbooker/doctrine-transaction-handler

[badge-release]: https://img.shields.io/packagist/v/sbooker/doctrine-transaction-handler.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/sbooker/doctrine-transaction-handler.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/sbooker/doctrine-transaction-handler.svg?style=flat-square

[release]: https://img.shields.io/packagist/v/sbooker/doctrine-transaction-handler
[license]: https://github.com/sbooker/doctrine-transaction-handler/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/sbooker/doctrine-transaction-handler