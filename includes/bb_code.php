<?php

class bbParser
{
    public function __construct()
    {
    }

    public function getHtml($str)
    {

        // BBcode array
        $find = array(
            '~\[center\](.*?)\[/center\]~s',
            '~\[b\](.*?)\[/b\]~s',
            '~\[i\](.*?)\[/i\]~s',
            '~\[u\](.*?)\[/u\]~s',
            '~\[quote\](.*?)\[/quote\]~s',
            '~\[size=(.*?)\](.*?)\[/size\]~s',
            '~\[url=((?:ftp|https?)://.*?)\](.*?)\[/url\]~s',
            '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
            '~\[smiley\](.*?)\[/smiley\]~s',
            '~\[color=((?:[a-zA-Z]|#[a-fA-F0-9]{3,6})+)\](.*?)\[/color\]~s',
            '~\[youtube\](.*?)\[/youtube\]~s',
            '~\[tr\](.*?)\[/tr\]~s',
            '~\[spotify\](.*?)\[/spotify\]~s',
            '~\[spotify compact\](.*?)\[/spotify\]~s',
            '~\[left\](.*?)\[/left\]~s',
            '~\[right\](.*?)\[/right\]~s',
        );
        // HTML tags to replace BBcode
        $replace = array(
            '<center>$1</center>',
            '<b>$1</b>',
            '<i>$1</i>',
            '<u>$1</u>',
            '<pre>$1</' . 'pre>',
            '<span style="font-size:$1px;">$2</span>',
            '<a href="$1" target="_blank" style="text-decoration: none; color: white;">$2</a>',
            '<img src="$1" class="bb_code_img" />',
            '<img src="img/emoticons/$1.png" alt="" />',
            '<span style="color:$1;">$2</span>',
            '<embed width="420" height="315" src="https://www.youtube.com/v/$1">',
            '<span id="transparent">$1</span>',
            '<iframe src="https://open.spotify.com/embed/$1" width="100%" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>',
            '<iframe src="https://open.spotify.com/embed/$1" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>',
            '<div style="text-align: left">$1</div>',
            '<div style="text-align: right">$1</div>',
        );
        // Replacing the BBcodes with corresponding HTML tags

        $str = htmlspecialchars($str);
        $str = preg_replace($find, $replace, $str);
        $str = nl2br($str);
        return $str;
    }
}

$bb = new bbParser();
echo $bb->getHtml($_GET["bbcode"]);
