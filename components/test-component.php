<?php

$testarray = [
    "0" => 'zero',
    "1" => 'one',
    "2" => 'two'
];

$testjson = json_encode($testarray);

$testserialize = serialize($testarray);

$decode = json_decode($testjson);


// foreach( json_decode($testjson) as $k=>$val ) {
//     echo "<pre>";
//     echo $k . ' : ' . $val;
//     echo "</pre>";
// }
foreach( $decode as $key => $value ) {
    echo "<pre>";
    echo $key . " : " . $value;
    echo "</pre>";
}


echo $decode;
?>

<!--

<pre>
    <?php echo $testjson; ?>
</pre>

<pre>
    <?php echo $testserialize; ?>
</pre> -->

