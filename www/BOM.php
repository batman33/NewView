<?PHP 
searchFile('admin/');
function searchFile($path, $pattern = '*', $flags = 0, $depth = 0) {

    $matches = array();

    $folders = array(rtrim($path, DIRECTORY_SEPARATOR));

 

    while($folder = array_shift($folders)) {

        $matches = array_merge($matches, glob($folder.DIRECTORY_SEPARATOR.$pattern, $flags));

        if($depth != 0) {

            $moreFolders = glob($folder.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);

            $depth   = ($depth < -1) ? -1: $depth + count($moreFolders) - 2;

            $folders = array_merge($folders, $moreFolders);

        }

    }

    return $matches;

}

 

$f = searchFile(dirname(__FILE__), "*.php", 0, 10000);

 

foreach ($f as $key => $val) {

    $f = fopen($val,'rb');

    $t = fread($f, filesize($val));

    

    if (preg_match('|\xEF\xBB\xBF|', $t) !== 0) {

        if (is_writable($val) === true) {

            $data = preg_replace('|\xEF\xBB\xBF|', "", $t);

            $file = fopen($val,'w+b');

            if (fwrite($file, $data) === FALSE) {

                echo "$file :: Не могу произвести изменение";

            }

            echo "$val :: Успешно отредактирован";

            fclose($file);

        }

        else{

            echo $val." :: Присутствует метка BOM, файл доступен только для чтения<br />";

        }

    }

}

?>