# LaraVice - Laravel WebSerVice

A Laravel Web Service App with various services for documents (PDF, Excel etc.), images, data and more.

> Under development

Postman Collection: [postman_collection.json](postman_collection.json)

## Set Up

* Install Composer dependencies: `composer install`
* Copy [.env.example](.env.example) to `.env` and setup file.
* Generate app key `php artisan key:generate`
* Optional: Run check script `php artisan app:check`
* Optional: Run check script `php artisan app:config` if the `laravice.config.yaml` file is missing
* Packages: Setup following packages or disable in the `laravice.config.yaml` file
    * Install Node Modules in the `packages/imgly-background-removal-node`

## Development

### Add Service

To quickly generate a new Service, Controller and add API route to [routes/api.php](routes/api.php),
you may run the `app:make:service` Artisan command.  
Desired namespace: Case \ Action \ Name

```shell
php artisan app:make:service Images\RemoveBackground\VendorPackage
```

This Command would create following files:

* Service: `Services/Images/RemoveBackground/VendorPackageService.php`
* Single Action Controller: `Http/Controllers/Images/RemoveBackground/VendorPackageController.php`
* Route: `images/remove-background/vendor-package`:

```php
<?php
// routes/api.php
use App\Services\Images\RemoveBackground\VendorPacakgeService;
//...
if (VendorPackageService::active()) {
    Route::post('images/remove-background/vendor-package', VendorPackageController::class);
}
```

### Todos / Roadmap

- [ ] PDF Generator
    - [ ] [DomPDF](https://github.com/barryvdh/laravel-dompdf)
    - [ ] [Laravel-pdf](https://spatie.be/docs/laravel-pdf)
- [ ] Image Tools
    - [X] [Resizing images](https://spatie.be/docs/image/image-manipulations/resizing-images)
    - [ ] [Optimizing images](https://spatie.be/docs/image/image-manipulations/optimizing-images) / [image-optimizer](https://github.com/spatie/image-optimizer)
    - [X] [Adjustments](https://spatie.be/docs/image/image-manipulations/adjustments)
    - [X] [Image canvas](https://spatie.be/docs/image/image-manipulations/image-canvas)
    - [X] [Effects](https://spatie.be/docs/image/image-manipulations/effects)
    - [ ] [Watermarks](https://spatie.be/docs/image/image-manipulations/watermarks)
    - [ ] [Text on images](https://github.com/Muetze42/gd-text)
    - [ ] Create Square Images
    - [X] WebP Converter
    - [ ] Remove Background
        - [ ] [Python Tool 1](https://github.com/danielgatis/rembg)
        - [ ] [Python Tool 2](https://github.com/nadermx/backgroundremover)
        - [X] [Node.js Tool](https://github.com/imgly/background-removal-js)
        - find alternatives
- [ ] [Convert HTML to an image, PDF or string](https://spatie.be/docs/browsershot/v4/introduction)
- [ ] [Psalm](https://psalm.dev/) Return type Generator
- [ ] XML Converter
- [X] Inspire :)

## Not sure / Effort? / Possible? / Useful as service?

- [ ] PDFs with [PDFreactor](https://www.pdfreactor.com/)
- [ ] PDF or snapshot from a URL or an HTML page with [PhpWeasyPrint](https://github.com/pontedilana/php-weasyprint)
- [ ] [Easily convert images with Glide](https://github.com/spatie/laravel-glide)
- [ ] [Encrypting and signing data using private/public keys](https://github.com/spatie/crypto)
- [ ] [Crawler](https://github.com/spatie/crawler)
- [ ] [Pixelmatch](https://github.com/spatie/pixelmatch-php)
- [ ] HTML / Markdown / Blade / Twig to valid E-Mail HTML Converter
- [ ] [pdf-to-text](https://github.com/spatie/pdf-to-text)
- ~~SCSS to CSS Converter~~
- ~~Less to CSS Converter~~
- ~~HTML Minify~~
- ~~CSS Minify~~
- ~~JS Minify~~
- ~~Validator~~
- [ ] Excel & CSV Converter
- ~~Password Generator~~
- [ ] Markdown Converter
- [ ] [icon-scraper](https://github.com/barryvdh/icon-scraper)
- [ ] [Sitemap Generator](https://github.com/spatie/laravel-sitemap)
- [ ] [Fake data generator](https://fakerphp.github.io/)
- ~~MIME Detector [thephpleague/mime-type-detection](https://github.com/thephpleague/mime-type-detection)~~

## Hints

- [ ] Google Fonts Download and Integration. (For text on image etc.)
