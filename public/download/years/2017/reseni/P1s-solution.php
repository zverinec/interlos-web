<?php

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_connect($socket, '127.0.0.1', '5123');

socket_set_nonblock($socket);

function send($socket, $m) {
    check($socket);
    socket_write($socket, $m."\n", strlen($m)+1);
    echo "> ".$m."\n";
    check($socket);
}

function recieve($socket) {
    check($socket);
    $m = socket_read($socket, 2048, PHP_NORMAL_READ);
    echo "< ".$m;
    check($socket);
    return $m;
}

function check($socket) {
    $e = socket_last_error($socket);
    if($e) {
        echo 'error: '.socket_strerror($e)."\n";
        exit();
    }
}

$kills = [
    [1, 0, 0, 0, 0, 1],
    [1, 1, 0, 0, 0, 0],
    [1, 1, 1, 0, 0, 0],
    [1, 1, 1, 1, 0, 0],
    [1, 1, 1, 1, 1, 0],
    [0, 1, 1, 1, 1, 1]
];

send($socket, "hraji mouka");

recieve($socket);
recieve($socket);

$used = [0, 0, 0, 0, 0, 0];
$prob = [0, 0, 0, 0, 0, 0];

function readUsed($line, &$prob, $all) {
    $lp = explode(":", $line);
    if(!$line || count($lp) != 2) {
        return;
    }
    $his = substr($lp[1], $all ? 1 : 5, 6);
    $ps = explode(" ", $his);
    foreach($ps as $p) {
        if(intval($p) > 0) {
            $prob[intval($p) - 1]++;
        }
    }
}

send($socket, '6'); $used[5]++;
send($socket, '1'); $used[0]++;
send($socket, '6'); $used[5]++;

$line = recieve($socket);
readUsed($line, $prob, true);
recieve($socket);
recieve($socket);

for($i=0; $i<33; $i++) {

    $p = [0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
    for($i=0; $i<6; $i++) {
        if($used[$i] == 6) {
            continue;
        }
        $a = 1.0;
        $k = 0.0;
        for($j=0; $j<6; $j++) {
            $a += 6 - $prob[$j];
            if($kills[$i][$j]) {
                $k += 6 - $prob[$j];
            }
        }
        $p[$i] = $k / $a;
    }

    $max = 0.0;
    $maxIndex = 5;
    for($i=0; $i<6; $i++) {
        if($p[$i] > $max) {
            $max = $p[$i];
            $maxIndex = $i;
        }
    }

    send($socket, "".($maxIndex+1));
    $used[$maxIndex]++;

    $line = recieve($socket);
    readUsed($line, $prob, true);
    recieve($socket);
    recieve($socket);
}
