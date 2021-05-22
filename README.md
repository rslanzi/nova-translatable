<p align="center"><img src="https://riccardoslanzi.com/img/nova-translatable.png"></p>
<h1>Laravel Nova Translatable</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rslanzi/nova-translatable.svg?style=flat-square)](https://packagist.org/packages/rslanzi/nova-translatable)
[![Packagist](https://img.shields.io/packagist/l/rslanzi/nova-translatable.svg)]()


This [Laravel Nova](https://nova.laravel.com/) field allows you to manage translated fields with [astrotomic/laravel-translatable](https://github.com/Astrotomic/laravel-translatable).

## Requirements

```json
laravel/nova: ^2.9 || ^3.0
astrotomic/laravel-translatable: ^11.0
waynestate/nova-ckeditor4-field: ^0.6.0
```

## Features

* Supports almost all Nova fields
* Supports default validation automatically
* Simple to implement with minimal code changes (after astrotomic/laravel-translatable support)
* Locale tabs to switch between different locale values of the same field

## Supported fields
* Code
* Counted text (with max char and warning treshold)
* CKEditor
* Json
* Sluggable
* Text (also single line)
* Textarea
* Trix

## Installation

Firstly, set up [astrotomic/laravel-translatable](https://github.com/astrotomic/laravel-translatable).

Then, install this package in a Laravel Nova project via Composer:

```bash
# Install nova-translatable
composer require rslanzi/nova-translatable
# Publish configuration
php artisan vendor:publish --tag="nova-translatable-config"
```

And finally, set the locales in it as an array with string keys, like this:

```php
'locales' => [
    'en' => 'English',
    'it' => 'Italiano',
],
```
## Usage

### Text Field 
Single line text field
```php
NovaTranslatable::make('Title')
    ->singleLine()
```

### Textarea Field: 
Multi line text field
```php
NovaTranslatable::make('Text')
    ->hideFromIndex()
```

### Counted Text Field: 
Text field with chars counter
```php
NovaTranslatable::make('Title')
    ->singleLine()
    ->counted()
```
#### Counted with max chars threshold. 
Exceeded the threshold, the counter turns red.
```php
NovaTranslatable::make('Title')
    ->singleLine()
    ->counted()
    ->maxChars(60)
    ->warningAt(50),
```
#### Counted with max chars and warning thresholds.
Exceeded the warning threshold, the counter turns orange, exceeded the max chars threshold, the counter turns red.
```php
NovaTranslatable::make('Title')
    ->singleLine()
    ->counted()
    ->maxChars(60)
    ->warningAt(50),
```

### CKEditor Field 
CKEditor WYSIWYG editor. Usefull to manage HTML fields.
```php
NovaTranslatable::make('Text')
    ->ckeditor()
```

### Trix Field 
Trix field
```php
NovaTranslatable::make('Text')
    ->trix()
```

### Code Field 
Code field. Use a syntax highlighted text area.
```php
NovaTranslatable::make('Text')
    ->code()
```
#### Code Field with custom language
Code field. Use a syntax highlighted text area.
```php
NovaTranslatable::make('Text')
    ->code()
    ->language('php')
```
**The Code field's currently supported languages are:**
dockerfile, javascript, markdown, nginx, php, ruby, sass, shell, vue, xml, yaml

### Json Field 
```php
NovaTranslatable::make('Text')
    ->json()
```

### Sluggable Field
Automatically populate a slug field based on another field. Title in this case.
```php
NovaTranslatable::make('Title')
    ->sluggable('Slug'),
NovaTranslatableSlug::make('Slug')
    ->hideFromIndex(),
```

#### Sluggable options
##### Maximum length
Limit the maximum length of the generated slug:
```php
NovaTranslatableSlug::make('Slug')
    ->maxLength(150),
```
##### Separator
Sets the string with which to replace all whitespace in the field.
```php
NovaTranslatableSlug::make('Slug')
    ->separator('-'),
```
##### Unique
The generated slugs won't be unique unless you call the unique() method:
```php
NovaTranslatableSlug::make('Slug')
    ->unique(),
```
##### Unique on Model
When these two options are set the generated slug will be unique on the set model regarding the attribute value of the NovaTranslatableSlug field.
```php
NovaTranslatableSlug::make('Slug')
    ->unique()
    ->model(static::$model),
```
##### Usage with [Spatie\Sluggable](https://github.com/spatie/laravel-sluggable)
If in your Eloquent model use Spatie\Sluggable's HasSlug trait and implements its getSlugOptions() method, you don't have to set the separator, maximum length or language for the field. The values you already set on your model will be used.


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Support the development
**Do you like this project? Support it by donating**

- PayPal: [Donate](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=rslanzi%40gmail%2ecom&lc=CY&item_name=NovaTranslatable&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest)

## Security Vulnerabilities

If you discover a security vulnerability within Nova Translatable, please send an e-mail to Riccardo Slanzi at rslanzi@gmail.com. All security vulnerabilities will be promptly addressed.

## License

Nova Translatable is free software distributed under the terme of the [MIT license](LICENSE.md).
