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

Copyright (c) 2013 Rolies106

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.