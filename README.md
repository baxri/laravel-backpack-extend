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
 

## Credits

- [George Ramazashvili](http://ramaza.info) - Author
