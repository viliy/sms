#!/bin/zsh

array=(xian-tian-ming-pan cheng-gu)

for var in ${array[@]};
do
#echo $var
echo "curl 'http://git.linghit.com:666/algorithm/$var/settings/integrations' -i -H 'If-None-Match: W/"492eada09af4aac3ed3a61e7a006fbc7"' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8' -H 'User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Mobile Safari/537.36' -H 'Accept: text/html, application/xhtml+xml, application/xml' -H 'Referer: http://git.linghit.com:666/algorithm/qin-mi-guan-xi/settings/integrations' -H 'Cookie: UM_distinctid=15fa4d1dab9795-0023a280e779f8-31657c00-1aeaa0-15fa4d1daba52; _gitlab_session=ad6a10c99fc119dee9d5d0ddea12bb68' -H 'Connection: keep-alive' -H 'X-XHR-Referer: http://git.linghit.com:666/algorithm/qin-mi-guan-xi/settings/integrations' --compressed"

done;



