# Peak\Bedrock

Create HTTP Request/Response application compatible PSR-7, PSR-11 and PSR-15.

Example with Zend\Diactoros: 
```php
use Peak\Di\Container; // PSR-11
use Peak\Bedrock\Aplication\ApplicationFactory; // PSR-15
use Peak\Bedrock\Http\StackFactory; // PSR-15
use Peak\Bedrock\Http\Response\Emitter;
use Zend\Diactoros\ServerRequestFactory; // PSR-7

$app = new Application(
    new Kernel('prod', new Container()),
    new HandlerResolver(),
    new PropertiesBag([
        'version' => '1.0', 
        'name' => 'app'
    ]) 
)

// Adding multiple middlewares and route middleware to application stack
$app->add([
    BootstrapMiddleware::class,
    $app->all('/', [
        CookieMiddleware::class,
        HomePageHandler::class
    ]),
    $app->get('/user/([a-zA-Z0-9]+)', [
        UserProfileHandler::class
    ]),
    $app->post('/userForm/([a-zA-Z0-9]+)', [
        AuthenticationMiddleware::class,
        UserFormHandler::class
    ]),
    LogNotFoundMiddleware::class
    PageNotFoundHandler::class
]);

// Execute the app stack
try {
    // create response emitter
    $emitter = new Emitter();
    
    // create request from globals
    $request = ServerRequestFactory::fromGlobals();
    
    // handle request and emit app stack response
    $app->run($request, $emitter);
} catch(Exception $e) {
    // overwrite app stack with error middleware
    $app->set(new DevExceptionHandler($e))
        ->run($request, $emitter);
}

```