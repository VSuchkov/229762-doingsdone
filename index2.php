<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

$con = mysqli_connect("127.0.0.1", "root", "", "doingsdone");
$name = 2;
$password = 2;
$sql = 'SELECT * FROM projects';
$data= [];
function db_get_prepare_stmt($con, $sql, $data = []) {
    $stmt = mysqli_prepare($con, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'd';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;

}
function get_data($con, $sql, $data) {
    $data_array = [];
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data_array[] = $row;
        }
    var_dump($data_array);
    return $data_array;
    }
}
/*
function get_data($con, $sql, $data) {
    $data_array = [];
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    if ($stmt == false) {
        print false;
        return $data_array;

    } else {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $data_array[] = $row;
        }
    var_dump($data_array);
    return $data_array;

    }
}
*/
function include_data($con, $sql, $data) {
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    if ($stmt == false) {
        print false;
        return false;

    } else {
        mysqli_stmt_execute($stmt);
        $last_id = mysqli_insert_id($con);
        print($last_id);
        return $last_id;
    }
}
function update_data($con, $table_name, $update_data, $update_condition) {
    $update_string = "";
    $condition_string = "";

    $data_keys = array_keys($update_data);
    $update_string = implode(" = ?,", $data_keys);
    $update_string .= " = ? ";

    $condition_keys = array_keys($update_condition);
    $condition_string = implode(" = ?,", $condition_keys);
    $condition_string .= " = ? ";

    $sql = "UPDATE $table_name SET $update_string WHERE $condition_string";
    $merge_update = array_merge($update_data, $update_condition);/*объединяем массив условий и массив данных для обновления*/
    $stmt = db_get_prepare_stmt($con, $sql, $merge_update);/*получаем подготовленное выражение*/
    mysqli_stmt_execute($stmt);/*выполняем подготовленное выражение*/

    if ($stmt == false) {
        print false;
        return false;

    } else {
        $records_count = mysqli_stmt_affected_rows($stmt);
        var_dump($records_count);
        return $records_count;

    }
}
/*
update_data($con, 'users', ['password' => '123', 'name' => 'petya'], ['id' => 1]);

include_data($con, $sql, $data);
*/


get_data($con, $sql, $data);
/*

get_data($con, $sql, $data);
*/

?>
