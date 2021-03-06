<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$app = new Application();
$app['debug'] = true; //debug mode

$app->get('/hello/{name}', function ($name) use ($app) {
    return sprintf('Hello %s!', $app->escape($name));
});

$blogPosts = array(
    1 => array(
        'date'      => '2011-03-29',
        'author'    => 'igorw',
        'title'     => 'Using Silex',
        'body'      => 'blablabla',
    ),
);

$app->get('/blog', function () use ($blogPosts) {
    $output = '';
    foreach ($blogPosts as $post) {
        $output .= $post['title'];
        $output .= '<br />';
    }

    return $output;
});

$app->get('/blog/{id}', function (Application $app, Request $request, $id) use ($blogPosts) {
    if (!isset($blogPosts[$id])) {
        $app->abort(404, "Post $id does not exist.");
    }

    $post = $blogPosts[$id];

    return  "<h1>{$post['title']}</h1>".
    "<p>{$post['body']}</p>";
})
    ->assert('id', '\d+');

$app->run();