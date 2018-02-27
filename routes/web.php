<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Input;

Route::any('update_xml', function(){
    $nodesToDelete=array();

    $dom=new DOMDocument();

    $xml = file_get_contents(app('request')->file('file'));
    $dom->loadXML($xml);

    $root = $dom->documentElement;

    $items = $root->getElementsByTagName('item');

    foreach ($items as $item) {
        if (!checkNode($item)) {
            $nodesToDelete[] = $item;
            continue;
        }
        $guid = $item->getElementsByTagName('guid')->item(0);
        $url = $guid->textContent;
        if (!checkRemoteFile($url)) {
            $nodesToDelete[] = $item;
        }
    }

    foreach ($nodesToDelete as $node) $node->parentNode->removeChild($node);

    $dom->saveXML();

    $dom->save('xx.xml');
    echo "convert successfully!";die;
});

Route::get('/', function () {

    return view('importxml');
});
function checkNode($x) {
    foreach ($x->childNodes as $p)
        if ($p->nodeName == 'wp:postmeta') {
            foreach ($p->childNodes as $k) {
                if($k->nodeValue == '_wp_attached_file' && $k->nodeName == 'wp:meta_key') {
                    return true;
                }
            }
        }
//        if (hasChild($p)) {
//            echo $p->nodeName.' -> CHILDNODES<br>';
//            shownode($p);
//        } elseif ($p->nodeType == XML_ELEMENT_NODE)
//            echo $p->nodeName.' '.$p->nodeValue.'<br>';

}

function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    if($result!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}
