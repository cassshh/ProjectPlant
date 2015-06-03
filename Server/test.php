<?php

$date = '2015-5-22 14:17:20';

$date = explode(" ", $date);

var_dump($date);

$date = explode(":", $date[1]);

dump($date[1]);

function dump($date){
    var_dump($date);
}
