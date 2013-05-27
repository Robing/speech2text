<?php

//先用ffmpeg  将mp3格式转换成flac格式
//ffmpeg -i 11.mp3 -acodec flac 11.flac
//ffmpeg -i 11.flac -ar 16000 -y 22.flac 

$lang = 'zh-CN'; //en-US 为英文 

$page =  simpleRequest("https://www.google.com/speech-api/v1/recognize?xjerr=1&client=chromium&lang=zh-CN", array());

echo $page;

function simpleRequest( $url , $post_data = array() ,$option=array())
    {/*{{{*/

        if ( '' == $url )
        {
            return false;
        }
        $url_ary = parse_url( $url );
        if ( !isset( $url_ary['host'] ) )
        {
            return false;
        }
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt( $ch, CURLOPT_HEADER, false);
        curl_setopt( $ch, CURLOPT_REFERER, 'http://test.com');
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, 'Content='.file_get_contents('./22.flac'));// 这里为待识别的音频

        curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)' );

        $http_header = array();
        $http_header[] = 'Connection: Keep-Alive';
        $http_header[] = 'Pragma: no-cache';
        $http_header[] = 'Cache-Control: no-cache';
        $http_header[] = 'Accept: */*';
        $http_header[] = 'Content-Type: audio/x-flac; rate=16000';
     
    if(isset($option['header']))
        {
            foreach($option['header'] as $header)
            {
                $http_header[] = $header;
            }
        }
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $http_header );

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        if ( !isset($option['timeout']))
        {
            $option['timeout'] = 15;
        }

        curl_setopt( $ch, CURLOPT_TIMEOUT, $option['timeout'] );
        $result = curl_exec( $ch );
        curl_close( $ch );
        return $result;
    }/*}}}*/

?>
