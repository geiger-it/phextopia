# Phextopia

A [Nextopia](https://www.nextopia.com/) PHP Client Library 
to consume [their json api](https://merchant.nextopiasoftware.com/api_docs/?f=search&o=json).

##### Install

`composer require geiger-it/phextopia`

##### Simple Mode:

```php
$client = new \Phextopia\Client( $clientId = '74PuWjnHUbN7pfh9XtPzVVknfmFxPcYk' );
$search = new \Phextopia\Search($client);

$result = $search->find(); // automatically retrieves $_GET['keywords'] = 'shirt'

```

##### Hard Mode:
```php
$client = new \Phextopia\Client( $clientId = '74PuWjnHUbN7pfh9XtPzVVknfmFxPcYk', $useGET = false);
$search = new \Phextopia\Search($client);

$result = $search->find('shirt');

// or with all the options:

$result = $search->find('shirt', [
    'xml' => 1,
    'page' => 3,
    // (optional) overrides $_SERVER['REMOTE_ADDR']
    'ip' => $request->getClientIp(), 
    // (optional) user_agent overrides $_SERVER['HTTP_USER_AGENT']
    'user_agent' => $request->getUserAgent(), 
    'res_per_page' => '12',
    'force_or_search' => '1:10',
    'requested_fields' => 'Name,Price,Sku,Image',
    'trim_length' => '80',
    'abstracted_fields' => 'Name,Description',
    'initial_sort' => 'Platform:ASC',
    'initial_sort_order' => 'Linux,Windows,Mac',
    'no_metaphones' => '4:10',
    //...
]);

```

##### Page Builder Usage:

```php
    $client = new \Phextopia\Client( $clientId = '74PuWjnHUbN7pfh9XtPzVVknfmFxPcYk' );
    $page = new Phextopia\PageBuilder($client);
    
    $result = $page->load('My Test Page');
```