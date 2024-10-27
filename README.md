# Mist

Mist is a *lightning-fast*, *lightweight* **API Mocking tool** that helps you to focus on what you do best: **coding**. 

The time of waiting for an endpoint, hardcoding responses, using complex tools or paying a service to mock your API
is over!

With Mist, you can spin up realistic API responses in seconds, creating a seamless development and testing environment.

It can also help you with testing, helping you create Mock API endpoints for your scenarios!

Wether you're a Frontend Developer or you need a way to Mock your APIs for testing or your pipelines...
**Install Mist and start coding!**

By leveraging a ReactPHP server, Mist can help you reduce your pipelines execution time, handle a decent amount of 
requests asynchronously with little processor and memory usage... and everything using the PHP you already know and love!

## Start using Mist

Using Mist is easy and involves using tools you already know and use in your everyday

### Installing with Composer

### Configuring the Mist "project"

If you're using Symfony or Laravel go and check the specific sections on how to use Mist with both frameworks in a more
seamlessly integrated way.

If you're using another framework, you're using Mist in a vanilla PHP project, or even if you're not using PHP at all and
just want to confortably mock some APIs here's how you can configure it.

You just need to create a `mist.config.php` file in the root of your project, and Mist will automatically use it as
it configuration source.

And what do you have to put in that config file? It's just plain old PHP that returns a Mist Config intance. Here you
can see a simple example:

```php
<?php
return new Config('127.0.0.1', 8080, false, null, null);
```

For a more advanced usage go and check the [*Mist Config* entry in the docs]()

### Start creating your Mock endpoints!

With Mist making Mocks is as easy as it can get as it provides a fluent API and static methods to register your mocks.

Here are a few simple examples of how you can use Mist.

```php
<?php
Mist::post('/api/message/{id}')
    ->headers(['Content-Type' => 'application/json'])
    ->body(json_encode(['message' => 'Hello, World!']));

Mist::get('/api/user/{id}')
    ->headers(['Content-Type' => 'application/json'])
    ->dynamicResponse(function ($params, $request) {
        $id = $params['id'];
        return ['id' => $id, 'name' => 'User ' . $id];
    })
    ->delay(10000);
```

As you can see the code is simple and clear. You can play with Mist to build more complex Mocks, but remember this is not
a Framework for developing APIs but a simple tool to mock them!

Mist comes with handy methods that will help you adding delay, or passing simple functions so you can add little logic
to customize your responses.

If you want to do a more advance usage (organize your Mist endpoints in different files and/or folders) you can go and check the full Response definition reference or the [*Mist Config* entry in the docs]() 

### Using Mist with Symfony

### Using Mist with Laravel

### Using Mist as a Phar executable