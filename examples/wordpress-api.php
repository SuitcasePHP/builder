<?php

require __DIR__ . '/../vendor/autoload.php';

use Suitcase\Builder\SDK;

// Build your SDK
$sdk = SDK::make('https://www.your-wordpress-blog.com/wp-json/wp/v2');

// Add your posts as a resource into your SDK
$sdk->add('posts');

// Get fetch all of your posts
$posts = $sdk->use('posts')->get()->getBody()->getContents();

foreach (json_decode($posts) as $post) {
    // do something with your posts
}
