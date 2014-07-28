<?php
/*
 * Php Section
 */
$blogname = "aas88"; // my Tumblr
$website = "http://" . $blogname . ".tumblr.com";
$RSSwebsite = $website . "/rss";

/*
 * Acquiring some elements
 */
$xmldocument = new DOMDocument();
$xmldocument->load($RSSwebsite);
$title = $xmldocument->getElementsByTagName("title");
$blogTitle = $title->item(0)->nodeValue;
$description_temp = $xmldocument->getElementsByTagName("description");
$description = $description_temp->item(0)->nodeValue;
$description = htmlallentities($description);
$posts = $xmldocument->getElementsByTagName("item");

function htmlallentities($str) {
    $res = '';
    $strlen = strlen($str);
    for ($i = 0; $i < $strlen; $i++) {
        $byte = ord($str[$i]);
        if ($byte < 128) // 1-byte char
            $res .= $str[$i];
        elseif ($byte < 192)
            ; // invalid utf8
        elseif ($byte < 224) // 2-byte char
            $res .= '&#' . ((63 & $byte) * 64 + (63 & ord($str[++$i]))) . ';';
        elseif ($byte < 240) // 3-byte char
            $res .= '&#' . ((15 & $byte) * 4096 + (63 & ord($str[++$i])) * 64 + (63 & ord($str[++$i]))) . ';';
        elseif ($byte < 248) // 4-byte char
            $res .= '&#' . ((15 & $byte) * 262144 + (63 & ord($str[++$i])) * 4096 + (63 & ord($str[++$i])) * 64 + (63 & ord($str[++$i]))) . ';';
    }
    return $res;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet"  type="text/css" href="css/tumblr-widget-style.css">
    </head>
    <body>
        <div id="tumblr">
            <?php
            /*
             * Dynamic creation of divs in order to contain feeds
             */
            echo "<div id=\"super\">"
            . "<div id=\"super_in_left\" class=\"title\"><div id=\"super_in_left-content\"><a href=\"" . $website . "\" target=\"_blank\" alt=\"" . $description . "\" title=\"" . $description . "\">" . $blogTitle . "</a></div></div>"
            . "<div id=\"super_in_right\">"
            . "<iframe class=\"btn\" frameborder=\"0\" border=\"0\" scrolling=\"no\" allowtransparency=\"true\" height=\"27\" width=\"114\" src=\"http://platform.tumblr.com/v1/follow_button.html?button_type=2&tumblelog=" . $blogname . "&color_scheme=light\"></iframe>"
            . "</div>"
            . "<div class\"clear\"></div>"
            . "</div>";
            echo "<div id=\"inside\">";
            foreach ($posts as $post) {
                $post_title = $post->getElementsByTagName("title");
                $title = $post_title->item(0)->nodeValue;
                $title = htmlallentities($title);
                $post_link = $post->getElementsByTagName("link");
                $link = $post_link->item(0)->nodeValue;
                $post_date = $post->getElementsByTagName("pubDate");
                $date = $post_date->item(0)->nodeValue;

                echo "<div class=\"elempost\"><p class=\"posts\"><a href=\"" . $link . "\" target=\"_blank\" alt=\"" . $title . "\" title=\"" . $title . "\">" . $title . "</a><br>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;At " . date('g:ia \o\n l jS F Y', strtotime($date)) . "</p></div>";
            }
            echo "</div>";
            echo "<div id=\"bottom\"><img id=\"cssimg\" src=\"./images/tumblr_logotype_gray_32.png\" alt=\"Tumblr\"/></div>";
            ?>

        </div>
    </body>
</html>
