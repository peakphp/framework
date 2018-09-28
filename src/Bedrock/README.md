# Peak\Bedrock

Create HTTP Request/Response application compatible PSR-7, PSR-11 and PSR-15.

Example with Zend\Diactoros: 
```php
use Peak\Bedrock\Application\Application;
use Peak\Bedrock\Kernel;
use Peak\Bedrock\Http\Request\HandlerResolver;
use Peak\Collection\PropertiesBag;
use Peak\Di\Container; // PSR-11
use Peak\Bedrock\Http\Response\Emitter;
use Zend\Diactoros\ServerRequestFactory; // PSR-7

$container = new Container();
$app = new Application(
    new Kernel('prod', $container),
    new HandlerResolver($container),
    new PropertiesBag([
        'version' => '1.0',
        'name' => 'app'
    ])
);

$app->stack(BootstrapMiddleware::class);

$app->all('/', [
    CookieMiddleware::class,
    HomePageHandler::class,
    function() {
        return new Response('Hello');
    }
]);

$app->get('/user/([a-zA-Z0-9]+)', [
    UserProfileHandler::class
]);

$app->post('/userForm/([a-zA-Z0-9]+)', [
    AuthenticationMiddleware::class,
    UserFormHandler::class
]);

$app->stack([
    LogNotFoundMiddleware::class,
    PageNotFoundHandler::class
]);

// Execute the app stack
try {
    // create request from globals
    $request = ServerRequestFactory::fromGlobals();
    
    // handle request and emit app stack response
    $app->run($request, new Emitter());
} catch(Exception $e) {
    // overwrite app stack with error middleware
    $app->set(new DevExceptionHandler($e))
        ->run($request, new Emitter());
}

```