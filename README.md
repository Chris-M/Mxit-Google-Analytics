Google Analytics
=============

Google Analytics as per the [Collection Protocol](https://developers.google.com/analytics/devguides/collection/protocol/v1/reference).

Specifically useful for MXit & mobile sites.

----

There are 2 different ways you can use this library.

- Non-blocking using `exec` & `cURL`. ([src](https://segment.io/blog/how-to-make-async-requests-in-php/))
- Blocking using `file_get_contents`, but `cURL`-less.

----

## Usage

```php
require_once("googleanalytics.php");
Ga::hit("UA-0XXXX043-1");
#and with switch
Ga::hit("UA-0XXXX043-1", true);
```

### Notes

_Non-blocking_ (Default)

Make sure your server has `php_curl` installed.

_Blocking_

Make sure your server has the `allow_url_fopen` flag set to `true`.

## Credit

Based on WillemLabu/[ga-collection](https://github.com/WillemLabu/ga-collection)