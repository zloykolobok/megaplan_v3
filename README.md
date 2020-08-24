# Пакет для работы с APIv3 Мегаплан

Клиент для работы с APIv3 [Мегаплан](https://megaplan.ru/)

Документация по [APIv3 Мегаплан](https://dev.megaplan.ru/apiv3/index.html)

## Требования
PHP 7.0.0 (и выше) с расширением libcurl

## Установка
### В консоли с помощью Composer

1. Установите менеджер пакетов Composer.
2. В консоли выполните команду
```bash
composer require zloykolobok/megaplan_v3
```

### В файле composer.json своего проекта
1. Добавьте строку `"zloykolobok/megaplan_v3": "*"` в список зависимостей вашего проекта в файле composer.json
```
...
    "require": {
        ...
        "zloykolobok/megaplan_v3": "*"
        ...
...
```
2. Обновите зависимости проекта. В консоли перейдите в каталог, где лежит composer.json, и выполните команду:
```bash
composer update
```

## Начало работы
- Создайте приложение в Вашем мегаплан.
- Импортируйте класс в ваш код.
```php
use Zloykolobok\Megaplan_v3\Megaplan;
```
- Создайте экземпляр объекта и укажите ключ(который можно взять из приложения, которое Вы создали в п.1) и домен
```php
$key = 'NmE2MGZkOWRmMjE3OThiZTY';
$domain = 'https://test.megaplan.ru';
$mega = new Megaplan($key, $domain);
```
- Вызовите метод `$test->send($action, $method, $data, $header)`, в который передайте
    - $action - url необходимого действия
    - $method - метод POST или GET
    - $data - массив данных
    - $header - массив дополнительных заголовков
    
## Примеры
### Получение сотрудников с указанием пагинации
```php
$pagination = json_encode(['limit' => 1, 'pageAfter' => ["contentType" => "Employee", "id" => '1000003']]);
$res = $mega->send('api/v3/employee?' . $pagination);
```
### Добавление комментария к сделке
```php
$data = ["contentType" => "Comment", "content" => 'Текст комментария'];
$res = $mega->send('api/v3/deal/12/comments', 'POST', $data);
```
