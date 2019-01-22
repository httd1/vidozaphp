# Vidoza in PHP  
A very simple PHP [Vidoza API](https://vidoza.net/api).

## Example upload

```php
<?php

include 'Vidoza.php';

$vidoza=new Vidoza (TOKEN);

$upload=$vidoza->uploadFile ('video.mp4', ['file_title' => 'My Video']);

if ($upload ['status'] == 'ok'){

echo 'http://vidoza.net/'.$upload ['code'].'.html';

}else {

echo 'Error: '.$upload ['message'];

}
```
