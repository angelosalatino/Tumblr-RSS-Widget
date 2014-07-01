<?php
/*
 * Php Section
 */


function htmlallentities($str){
  $res = '';
  $strlen = strlen($str);
  for($i=0; $i<$strlen; $i++){
    $byte = ord($str[$i]);
    if($byte < 128) // 1-byte char
      $res .= $str[$i];
    elseif($byte < 192); // invalid utf8
    elseif($byte < 224) // 2-byte char
      $res .= '&#'.((63&$byte)*64 + (63&ord($str[++$i]))).';';
    elseif($byte < 240) // 3-byte char
      $res .= '&#'.((15&$byte)*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
    elseif($byte < 248) // 4-byte char
      $res .= '&#'.((15&$byte)*262144 + (63&ord($str[++$i]))*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
  }
  return $res;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            #tumblr{
                /*height: 600px; */
                width: 520px; 
                /*padding-left: 10px;*/
                border: 1px solid;
                border-radius: 5px;
                border-color: #e8e8e8;
                /*color:#00ccff;*/
            }
            .title{
                color: black;
                font-size: 18pt;
                font-weight: bold;
            }
            .title a{font-weight: bold; color:black;}
            .title a:link {text-decoration: none}
            .title a:visited {text-decoration: none}
            .title a:active {text-decoration: none}
            .title a:hover {text-decoration: underline;}
            #description{
                /*color:#005791;*/
                text-align: justify;
            }
            .posts{
                margin-left:10px;
            }
            .posts a{font-weight: bold; color:#0066cc;}
            .posts a:link {text-decoration: none}
            .posts a:visited {text-decoration: none}
            .posts a:active {text-decoration: none}
            .posts a:hover {text-decoration: underline;}
            .elempost{
                padding:3px;
                border:1px solid #e8e8e8;
                
            }
            #super{
                height: 27px; 
                /*width: 550px;*/
                margin-left:10px; 
                padding:5px;
                color: black;
                font-size: 18pt;
                font-weight: bold;
            }
            #inside{
                height: 530px; 
                /*width: 550px; */
                border-top: 1px solid #e8e8e8;
                overflow:auto;
                border-bottom: 1px solid #e8e8e8;
            }
            #bottom{
                height:46px;
                /*width:550px;*/
            }
            #cssimg{
                height: 28px;
                margin-top: 10px;
                margin-left: 15px;
            }
        </style>
    </head>
    <body>
        <div id="tumblr">
<?php
$website = "http://aas88.tumblr.com/rss"; // my Tumblr

/*
 * Acquiring some elements
 */
$documento = new DOMDocument();
$documento->load($website);
$title = $documento->getElementsByTagName("title");
$titolo = $title->item(0)->nodeValue;
$description = $documento->getElementsByTagName("description");
$descrizione = $description->item(0)->nodeValue;
$descrizione = htmlallentities($descrizione);
$elementi = $documento->getElementsByTagName( "item" );

/*
 * Dynamic creation of divs in order to contain feeds
 */

//echo "<p id=\"description\">".$descrizione."</p>";
echo "<div id=\"super\" class=\"title\"><a href=\"".$website."\" target=\"_blank\" alt=\"".$descrizione."\" title=\"".$descrizione."\">".$titolo."</a></div>";
echo "<div id=\"inside\">";
foreach( $elementi as $elemento ){
  $nomelemento = $elemento->getElementsByTagName( "title" );
  $nome = $nomelemento->item(0)->nodeValue;
  $nome = htmlallentities($nome);
  $linklemento = $elemento->getElementsByTagName( "link" );
  $link = $linklemento->item(0)->nodeValue;
  $dataelemento = $elemento->getElementsByTagName( "pubDate" );
  $data = $dataelemento->item(0)->nodeValue;
  //$oDateA = new DateTime($data);
 //$oDateA->format('g:ia \o\n D, jS M Y').
    //printing
    echo "<div class=\"elempost\"><p class=\"posts\"><a href=\"".$link."\" target=\"_blank\" alt=\"".$nome."\" title=\"".$nome."\">".$nome."</a><br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;At ".date('g:ia \o\n l jS F Y', strtotime($data))."</p></div>";
}
echo "</div>
<div id=\"bottom\"><img id=\"cssimg\" src=\"./images/logotumb.png\" alt=\"Tumblr\"/></div>";
?>
            
        </div>
    </body>
</html>
