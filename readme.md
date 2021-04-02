# BackpackImageTraits

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

**Backpack for Laravel helper traits for processing image upload fields (including in repeatable fields)**

This package provides helper traits for processing Backpack's built-in image fields. With these traits, 
you'll only have to add a function with one line of code to process image fields.



## Installation


### Dependencies

This package requires
* PHP 7.2+
* [intervention-image:^2.5][link-intervention-image]
* [backpack/crud:4.1.*][link-backpack]


### Installation

Via Composer
``` bash
composer require eleven59/backpack-image-traits
```



## Usage

The two traits included in this package allow you to process Backpack for Laravel fields of the 'image' 
type with only a single line of code. More lines of code available if you need to tweak things (see 
options below).


### Image fields
For single top-level image fields:
1) Add HasImageFields Trait to Model
2) Add setAttribute function for the image field

```php
class Rogue extends Model
{
    use HasImageFields;
    
    /**
     * i.e. $this->avatar is a CRUD image field 
     */
    public function setAvatarAttribute($value)
    {
        $this->attributes['avatar'] = $this->uploadImageData($value);
    }
}
```


### Images within repeatable fields
If you have a repeatable field in your model that has one or more child image fields:
1) Add HasImageFields and HasImagesInRepeatableFields Traits to Model
2) Add setAttribute function for the repeatable field

```php
class Mage extends Model
{
    use HasImageFields, HasImagesInRepeatableFields;
    
    protected $casts = ['spells' => 'array']; // You should already have this
    
    /** 
     * i.e. $this->spells is a CRUD field of the 'repeatable' type 
     * each child entity has one or more fields of the 'image' type
     */
    public function setSpellsAttribute($value)
    {
        $this->attributes['spells'] = $this->uploadRepeatableImageData($value);
    }
}
```


### Options

Both traits support the same array of options to customize how the image upload is processed. For
repeatable fields, the same options will be used for every image field in the repeatable field's
child array. 

All available options are displayed below with their default values. 

```php
public function setAvatarAttribute($value)
{
    $this->attributes['avatar'] = $this->uploadImageData($value, [
        'disk' => 'public', // Storage disk as defined in config/filesystems.php
        'delete_path' => null, // Path of old value; file will be deleted if specified (don't use for repeatable)
        'directory' => $this->table, // Directory in storage disk to use; defaults to model's table name
        'quality' => 65, // Intervention Image quality setting, default is 65
        'format' => 'jpg', // Format to use for the generated image, default is jpg
        'transformation' => null, // Accepts a callable to make additional transformations (see advanced examples)
        'callback' => null, // Accepts a callable to override the return function (see advanced examples)
    ]);
}
```
**Supported formats**: all [intervention image formats][link-encode] except data-url, so as of Intervention
Image 2.5.1:
* jpg
* png
* gif
* tif
* bmp
* ico
* psd
* webp


### Advanced examples using callables

The **transformations** callable can be used to perform additional transformations using the Intervention 
Image object (see [Intervention Image docs][link-intervention-docs]).

```php
public function setLogoAttribute($value)
{
    $this->attributes['logo'] = $this->uploadImageData($value, [
        'format' => 'png',
        'transformations' => function(Intervention\Image\Image $image) {
            // Remove all red and blue from the image
            $image->colorize(-100, 0, -100);
        },
    ]);
}
```

The **callback** callable allows you to override the return function using the generated filename as an input
variable.

```php
public function setSecretPhotoAttribute($value)
{
    $this->attributes['secret_photo'] = $this->uploadImageData($value, [
        'disk' => 'local',
        'directory' => 'secret_photos',
        'callback' => function($filename) {
            // Return storage path instead of public url
            return Storage::disk('local')->path('secret_photos/'.$filename);
        },
    ]);
}
```

## Change log

Breaking changes will be listed here. For other changes see commit log.



## Credits

- [Nik Linders @ eleven59.nl][link-author]
- Built with [Laravel Backpack addon skeleton][link-skeleton]



## License

This project was released under the MIT license, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information. 

However, please note that you do need Backpack installed, so you need to also abide by its [YUMMY License](https://github.com/Laravel-Backpack/CRUD/blob/master/LICENSE.md). That means in production you'll need a Backpack license code. You can get a free one for non-commercial use (or a paid one for commercial use) on [backpackforlaravel.com](https://backpackforlaravel.com).

[ico-version]: https://img.shields.io/packagist/v/eleven59/backpack-image-traits.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/eleven59/backpack-image-traits.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/eleven59/backpack-image-traits
[link-downloads]: https://packagist.org/packages/eleven59/backpack-image-traits
[link-author]: https://github.com/eleven59
[link-skeleton]: https://github.com/Laravel-Backpack/addon-skeleton
[link-encode]: http://image.intervention.io/api/encode
[link-backpack]: https://github.com/Laravel-Backpack/CRUD
[link-intervention-image]: https://github.com/Intervention/image
[link-intervention-docs]: http://image.intervention.io/
