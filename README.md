# GET request tracker

Do PHP cURL GET requests with connection and response details saved to database with the ability to save the request response data into file.

### Requires

* PHP version >= 8.0
* PHP ext-curl
* PHP ext-pdo

## Usage

Run `get_requester.sql` into your MySQL server.

Edit DB connection details line 5-8 `src/getTracker.php`.

Edit cURL call settings line 17-21 `src/getTracker.php`.

Edit cURL GET request user agents line 12 `src/getTracker.php`.

To use:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

$get = new getTracker();
```

#### Examples

```php
$get->url = 'https://cdn.nba.com/static/json/liveData/scoreboard/todaysScoreboard_00.json';

echo json_encode($get->doGET());
```

Will return similar to:

```json
{
  "http_code": 200,
  "size": 126,
  "connect_time": 1.008247,
  "total_time": 1782657,
  "saved_as": null
}
```

Save the returned data into `xyz.json`

```php
$url = "https://website.com/api/user/?id=xyz";
echo json_encode(callCurl($url, 'xyz.json'));
```

Will return similar to:

```json
{
  "http_code": 200,
  "size": 126,
  "connect_time": 1.008247,
  "total_time": 1782657,
  "saved_as": "xyz.json"
}
```

**If connection times out**

```json
{
  "http_code": 408,
  "message": "Response timed out"
}
```