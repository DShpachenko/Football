<?php

include 'data.php';
#include 'helper.php';
include 'Football.php';

function match($c1, $c2)
{
    global $data;
    
    $football = new Football($data);

    $football->setFirstCommand($c1);
    $football->setSecondCommand($c2);

    return $football->calculation();
}

#dd(match(1, 2));