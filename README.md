# WPEmerge [![Build Status](https://scrutinizer-ci.com/g/htmlburger/wpemerge/badges/build.png?b=master)](https://scrutinizer-ci.com/g/htmlburger/wpemerge/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/htmlburger/wpemerge/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/htmlburger/wpemerge/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/htmlburger/wpemerge/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/htmlburger/wpemerge/?branch=master)

A micro framework which modernizes WordPress as a CMS development by providing tools to implement MVC and more.

Integration is incremental - you can use it only on the pages you want or on all pages.

If you've used Laravel, Slim or Symfony and miss the control over the request&response flow in WordPress - WP Emerge is definitely for you.

## Documentation

https://htmlburger.gitbooks.io/wpemerge/content/

## Features

- Routes with optional rewrite rule integration
    ```php
    Router::get( '/', 'HomeController@index' );
    Router::get( '/custom', 'HomeController@custom' )
        ->rewrite( 'index.php?...' );
    ```
- __Real__ Controllers (not ViewModels)
    ```php
    class HomeController {
        public function index( $request ) {
            $name = $request->get( 'name' );
            return wpm_template( 'templates/home.php', [
                'welcome' => 'Welcome, ' . $name . '!',
            ] );
        }
    }
    ```
- [PSR-7](http://www.php-fig.org/psr/psr-7/) Responses (using [Guzzle/Psr7](https://github.com/guzzle/psr7))
    ```php
    class HomeController {
        public function index( $request ) {
            return wpm_response()
                ->withHeader( 'X-Custom-Header', 'foo' );
        }
    }
    ```
- Middleware
    ```php
    Router::get( '/', 'HomeController@index' )
        ->add( function( $request, $next ) {
            // perform action before
            $response = $next( $request );
            // perform action after
            return $response;
        } );
    ```
- Service container (using [Pimple](https://pimple.symfony.com/))
    ```php
    $container = WPEmerge::getContainer();
    $container['my_service'] = function() {
        return new MyService();
    };
    ```
- Service providers
    ```php
    class MyServiceProvider implements ServiceProviderInterface {
        public function register( $container ) {
            $container['my_service'] = function() {
                return new MyService();
            };
        }

        public function boot( $container ) {
            // bootstrap code
            // e.g. add hooks, actions etc.
        }
    }
    ```
- Custom template engine support (Twig and Blade available as add-on packages)
    ```php
    $container = WPEmerge::getContainer();
    $container[ WPEMERGE_TEMPLATING_ENGINE_KEY ] = function( $container ) {
        return new MyTemplateEngine();
    };
    ```

## Quickstart

1. Run `composer require htmlburger/wpemerge` in your theme directory
1. Make sure you've included the generated `autoload.php` file inside your `functions.php` file
    ```php
    require_once( 'vendor/autoload.php' );
    ```
1. Add the following to your `functions.php`:
    ```php
    add_action( 'init', function() {
        session_start(); // required for Flash and OldInput
    } );

    add_action( 'after_setup_theme', function() {
        WPEmerge::boot();

        Router::get( '/', function() {
            return wpm_output( 'Hello World!' );
        } );
    } );
    ```
