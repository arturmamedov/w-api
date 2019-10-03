# wApi
REST PHP API Consumer

###### Simple PHP Class for call an REST API endpoint and get JSON data with CURL


Example
```php
<?php
$endpoint = 'blog/posts'; // relative to what set in $host and $version in MyApi Class
// so result: http://api.test/api/v1/blog/posts

# 1 - Include and init
require_once 'src'.DIRECTORY_SEPARATOR.'api.php';
$w_api = new wApi('testuser', 'testpassword');

# 2 - Make call
$json_response = $w_api->http($endpoint, 'GET');

# 3 - Read Response
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
