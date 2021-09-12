# Workflow

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require pitangent/workflow
```

``` bash
Pitangent\Workflow\WorkflowServiceProvider::class
```

php artisan jwt:secret

## Usage
``` bash
$ php artisan pitangent:controller --model={{MODEL_NAME}}
```
``` bash
Route::group(['prefix' => 'auth'], function () {
    Route::post('signup',                   'API\AuthController@register');
    Route::post('signin',                   'API\AuthController@authenticate');
    Route::get('refresh',                   'API\AuthController@refresh');
    Route::post('request-password',         'API\AuthController@requestPassword');
    Route::post('set-password',             'API\AuthController@resetPassword');

    //AUTH PROTECTED
    Route::group(['middleware' => 'jwt:auth'], function () {
        Route::get('me',                    'API\AuthController@me');
        Route::get('logout',                'API\AuthController@logout');
        Route::post('change-password',      'API\AuthController@changePassword');
    });
});

```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email khadimul@pitangent.com instead of using the issue tracker.

## Credits

- [Khadimul Miah][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ows/workflow.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ows/workflow.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/ows/workflow/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/ows/workflow
[link-downloads]: https://packagist.org/packages/ows/workflow
[link-travis]: https://travis-ci.org/ows/workflow
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/ows
[link-contributors]: ../../contributors
