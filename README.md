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
          "url":  "git@ngitlab.unipay.com:unipay/custom-crud.git"
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

# Usage

## CRUD

To use custom crud package you should extend you controller:

```javascript
class TestCrudController extends  CustomCrudController{
    public function setup(){

    }
}
```

## Export

```javascript
class TestCrudController extends \Unipay\CustomCrud\Controllers\CustomCrudController{
    public function setup(){        
        $this->crud->enableServerSideExport('testExport');
    }
}
```

Add this route in web.php 

```javascript

Route::group(['prefix' => 'console', 'middleware' => 'admin'], function(){
    Route::get('testExport', 'Admin\TestCrudController@export');
}


```

## Sum and count

Enable count of rows under the filters

```javascript
public function setup(){        
    $this->crud->addCount();
}
```

Enable sum of some fields under the filters

```javascript
public function setup(){        
    $this->crud->addSum('field_name');
}
```

Enable sum of amount format fields like amount and commission

```javascript
public function setup(){        
    $this->crud->addSumMoney('field_name');
}
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
    true // true if you want to add comment text area
);

```

 

