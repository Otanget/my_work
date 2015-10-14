<?php
//perform the command
function dosql($sql)
{
    global $mysqli;
    $cmd = "INSERT INTO log (date,entry) VALUES (NOW(), unhex('" . bin2hex($sql) . "'))";
    $res = $mysqli->query($cmd);
    $res = $mysqli->query($sql);
    if (!$res) {
        $array = debug_backtrace();
        if (isset($array[1])) { $a = $array[1]['line']; }
        else if (isset($array[0])) { $a = $array[0]['line']; }
        else { $a = "???"; }
        echo "ERROR @ ", $a, " : (", $mysqli->errno, ")\n", $mysqli->error, "\n\n";
        echo "SQL = $sql\n";
        exit;
    }
    if (preg_match("/INSERT/i", $sql)) { return $mysqli->insert_id; }
    if (preg_match("/DELETE/i", $sql)) { return null; }
    if (!is_object($res)) { return null; }
    $count = -1;
    $array = array();
    $res->data_seek(0);
    while ($row = $res->fetch_assoc()) {
        $count++;
        foreach ($row as $k => $v) { $array[$count][$k] = $v; }
    }
    return $array;
}
