# withPAPI
REST PHP API Consumer

###### Simple PHP Class for call an REST API endpoint and get JSON data with CURL


Example
```php
<?php
$endpoint = 'blog/posts'; // relative to what set in $host and $version in MyApi Class
// so result: http://api.test/api/v1/blog/posts

require_once 'api.php';
$my_api = new MyApi('testuser', 'testpassword');

$json_response = $my_api->http($endpoint, 'GET');

if ($response === false) {
    $response = [ 'status' => false, 'error' => 'CURL_ERR' ];
    exit(print_r($response)); // for debug reason, to remove in production
}else {
    $response = json_decode($json_response);
}

// Use $response ...
foreach ($response as $post){
    // do things with your resource
}

```

---

Author: Artur Mamedov

Inspired by: https://github.com/abraham/twitteroauth
