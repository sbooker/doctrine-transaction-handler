[Read in English](README.EN.md)

# Doctrine Transaction Handler (`sbooker/doctrine-transaction-handler`)

[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
[![Total Downloads][badge-downloads]][downloads]

Готовая реализация `TransactionHandler` для интеграции [sbooker/transaction-manager](https://github.com/sbooker/transaction-manager) с Doctrine ORM.

## Назначение библиотеки

Эта библиотека — "мост" между абстрактным менеджером транзакций и Doctrine ORM. Она избавляет вас от необходимости писать собственный адаптер.

Так же библиотека решает фундаментальную проблему **управления памятью и состоянием в долгоживущих процессах** (воркерах, консьюмерах), которые являются одним из сценариев использования `transaction-manager`.

## Ключевые особенности

*   **Автоматическое управление памятью и состоянием:** После каждого успешного коммита обработчик вызывает `EntityManager::clear()`. Это **критически важно** для долгоживущих процессов и обеспечивает:
    *   **Предотвращение утечек памяти:** Unit of Work не разрастается бесконечно.
    *   **Гарантию свежести данных:** На каждой новой итерации сущности будут загружены из БД заново.
    *   **Изоляцию операций:** Состояние предыдущей транзакции не влияет на следующую.
*   **Готовое решение:** Просто установите и используйте.
*   **Пессимистичная блокировка:** Гарантирует целостность данных при одновременном доступе "из коробки" (`LockMode::PESSIMISTIC_WRITE`).
*   **Правильная обработка исключений:** Корректно преобразует `UniqueConstraintViolationException` в `UniqueConstraintViolation` из `transaction-manager`.

## Установка

```bash
composer require sbooker/doctrine-transaction-handler
```
**Зависимости:**
*   `sbooker/transaction-manager`
*   `doctrine/orm`

## Использование

Использование библиотеки сводится к одному шагу — созданию экземпляра `DoctrineTransactionHandler` и передаче его в `TransactionManager`.

```php
// bootstrap.php или ваш DI-контейнер

use Doctrine\ORM\EntityManagerInterface;
use Sbooker\TransactionManager\TransactionManager;
use Sbooker\TransactionManager\DoctrineTransactionHandler;

/** @var EntityManagerInterface $entityManager */

// 1. Создаем обработчик, передав в него EntityManager
$transactionHandler = new DoctrineTransactionHandler($entityManager);

// 2. Создаем менеджер транзакций
$transactionManager = new TransactionManager($transactionHandler);

// 3. Готово! Теперь используйте $transactionManager в вашем прикладном коде
$transactionManager->transactional(function () {
    // ... ваш код ...
});
// После успешного выполнения этого блока, $entityManager->clear() будет вызван автоматически.
```

После этой простой настройки вы можете полноценно использовать `TransactionManager` во всем приложении, как описано в его [документации](https://github.com/sbooker/transaction-manager).

## License
See [LICENSE][license] file.

[badge-release]: https://img.shields.io/packagist/v/sbooker/doctrine-transaction-handler.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/sbooker/doctrine-transaction-handler.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/sbooker/doctrine-transaction-handler.svg?style=flat-square

[release]: https://img.shields.io/packagist/v/sbooker/doctrine-transaction-handler
[license]: https://github.com/sbooker/doctrine-transaction-handler/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/sbooker/doctrine-transaction-handler