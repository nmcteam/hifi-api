# HiFi CMS API component for PHP

This is the official [HiFi CMS](http://www.gethifi.com) API component for PHP. It lets you fetch, put, and delete HiFi CMS website content from a remote destination.

## Install

Install the HiFi CMS API component with Composer:

```console
composer require nmcteam/hifi-api
```

## Usage

```php
<?php
$hifi = new \Hifi\Api('www.example.com', 'username', 'password');

// Fetch content
$hits = $hifi->get([
    'type' => 'page'
]);
foreach ($hits as $hit) {
    echo $hit->title;
}

// Create or update content
$hifi->post([
    [
        'type' => 'page',
        'title' => 'New page',
        'content' => 'Content goes here',
        'parent' => 'a24c85d34ce9437bbfc9db696ccee814'
    ]
]);

// Delete content
$hifi->delete([
    [
        'id' => 'a24c85d34ce9437bbfc9db696ccee814'
    ]
]);
```

## Author

Josh Lockhart <josh@newmediacampaigns.com>

## License

MIT Public License

## Copyright

Copyright 2015, New Media Campaigns. All rights reserved.
