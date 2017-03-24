# PHP_AJAX

#### 介绍

用 PHP 中转 Get/Post 请求，实现 AJAX 跨域访问资源。

由于文件中设置了：
```
header('Access-Control-Allow-Origin:*');
```
所以自身可以响应跨域请求。

所以将此文件放在一个固定位置，可做任意请求的中转站。

// *ajax_old.php* 中使用了 *$_POST*，需设置 *php.ini*。

// *ajax_debug.php* 将打印所有变量信息。


#### 用法

将 *ajax.php* 放到合适位置，通过 Get/Post 访问，附加 target_url 参数。

```
<script type="text/javascript">
    $.get("ajax.php",
        {
            target_url: 'http://www.convert-unix-time.com/api',
            timestamp: 'now'
        }, function (data) {
            document.write(data);
        });
</script>
```

#### 参考
[http://blog.csdn.net/hzbigdog/article/details/8207433](http://blog.csdn.net/hzbigdog/article/details/8207433)