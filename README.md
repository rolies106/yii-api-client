# Yii API v0.1.0 Beta

API engine based on Yii, it will handle authorize, access_token, and token validation from third apps request. This is still beta so it'll need enhancement and testing all over places. Please feel free to report an issue or send your pull request.

## Requirement

- Webserver (Apache >2.1)
- PHP Version >5.3 (Needed for anonymous function)
- Yii Framework >1.1.x

## Starting your first clone

### After cloning please rename this following file :

- ```index.default.php``` to ```index.php```
- ```protected/yiic.default.php``` to ```protected/yiic.php```
- ```protected/config/console.default.php``` to ```protected/config/console.php```
- ```protected/config/main.default.php``` to ```protected/config/main.php```

### Change configuration for these files as you need (path for framework and another configuration) :

- ```console.php```
- ```main.php```
- ```index.php```

### Migrations

Run this migration command from your console for each module (`api` and `user`) :

```
./protected/yiic migrate --migrationPath=application.modules.[MODULE].migrations
```

### Make sure you already download Yii framework and make sure path to framework is correct on index.php

*Note: You must clone this project to VHOST instead of in sub-folder to avoid broken url in redirection.*

## Documentations

**[In Plan]***

## Copyrights

rolies106. 2013