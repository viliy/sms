<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/4/2
 */

//$curl = "curl 'http://git.linghit.com:666/algorithm/qin-mi-guan-xi/settings/integrations' -H 'If-None-Match: W/\"492eada09af4aac3ed3a61e7a006fbc7\"' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8' -H 'User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Mobile Safari/537.36' -H 'Accept: text/html, application/xhtml+xml, application/xml' -H 'Referer: http://git.linghit.com:666/algorithm/qin-mi-guan-xi/settings/integrations' -H 'Cookie: UM_distinctid=15fa4d1dab9795-0023a280e779f8-31657c00-1aeaa0-15fa4d1daba52; _gitlab_session=ad6a10c99fc119dee9d5d0ddea12bb68' -H 'Connection: keep-alive' -H 'X-XHR-Referer: http://git.linghit.com:666/algorithm/qin-mi-guan-xi/settings/integrations' --compressed";

date_default_timezone_set('Asia/Shanghai');
echo PHP_EOL;

echo date_default_timezone_get();
echo PHP_EOL;

$date = '1991-04-14 00:00:00';

echo $date;
echo PHP_EOL;

echo strtotime('1991-04-14 00:00:00');
echo PHP_EOL;
echo date('Y-m-d H:i:s', strtotime('1991-04-14 00:00:00'));
echo PHP_EOL;
echo date('Y-m-d H:i:s', '671558400');
echo PHP_EOL;
echo date('I', 671558400);
echo PHP_EOL;

echo mktime(0, 0, 0, 4, 14, 1991);

echo PHP_EOL;
