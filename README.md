# GET request tracker

Do cURL GET requests with connection/response details saved to database with the ability to save response data.

### Requires

* PHP version >= 8.0
* PHP ext-curl

## Usage

Run `get_requester.sql` into MySQL server.

Edit connection details line 5 `callCurl.php`.

To use the function:

```php
require_once('callCurl.php');
```

#### Examples

```php
$url = "https://website.com/api/user/?id=xyz";
echo json_encode(callCurl($url));
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
  "saved_as": 'xyz.json'
}
```

**If connection times out**

```json
{
  "http_code": 408,
  "message": "Response timed out"
}
```