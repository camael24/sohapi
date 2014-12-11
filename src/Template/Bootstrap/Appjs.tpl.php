<?php

$uri = function ($c) {
    if($c[0] === '/' or $c[0] === '\\')
        $c = substr($c, 1);

    return urlencode(utf8_decode(str_replace(['/', '\\'], '_', $c))).'.html';
};

$tree = function ($list) {
    $tree = array();
    foreach ($list as $strNamespace) {
        $current = & $tree;
        $tabns = explode('\\', $strNamespace);
        foreach ($tabns as $ns) {
            if (empty($ns))
                continue;
            if (!isset($current[$ns]))
                $current[$ns] = array();
                $current = & $current[$ns];
        }
    }
    return $tree;
};
$c = $tree($allclass);


$a = function($tree, $parent = '') use(&$a, &$uri) {
    $store = array();
    foreach ($tree as $item => $subarray) {

        $element = array();
        $element['text'] = $item;

        if(count($subarray) > 0){
            $element['nodes'] = $a($subarray, $parent.'\\'.$item);
        }
        else  {
            $element['icon'] = 'fa fa-angle-right';
            $element['href'] = $uri($parent.'\\'.$item);
        }

        $store[] = $element;

    }

    return $store;
};

echo 'var tree = '.json_encode($a($c)).';'."\n";
echo '$(\'#tree\').treeview({data: tree, enableLinks: true, levels: 1, expandIcon: "fa fa-folder-o", collapseIcon: "fa fa-folder-open-o", nodeIcon: ""});';