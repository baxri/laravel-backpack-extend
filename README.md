# Unipay Custom Crud 

## Install

This Package Requires Laravel Backpack
```note
"backpack/base": "0.8.*",
"backpack/crud": "3.*"
```

- add repository into main composer.json file.

```json
"repositories" : [
      {
          "type": "vcs",
          "url":  "http://ngitlab.unipay.com/unipay/custom-crud.git"
      }
]
```
- install via composer 

```bash
composer require unipay/custom-crud
```


- add the ServiceProvider to the providers array in config/app.php

```php
Unipay\I18ncontent\CustomCrudServiceProvider::class
```

## RouteButton

Add button to action bar, with route and confirmation modal

in CustomCrudController:

```javascript
$this->crud->addRouteButton(
    'Cancel', // Button title 
    route('your_route'), // Your route
    'danger', // Button type (success, danger) 
    'Modal Title', // Modal title
    'Are you sure?', // Confirmation text
    true // tru if you want to add comment
);

```

 

