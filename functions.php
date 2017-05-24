<?php
require_once("./mysql_helper.php");
function calculateTasks($task_list, $name_of_project) {
    $task_counter = 0;
    if ($name_of_project == "Все") {
        $task_counter = count($task_list);
    } else {
        foreach ($task_list as $key => $val) {
            if ($val["categories"] == $name_of_project) {
                $task_counter++;
            }
        }
    }
return $task_counter;
}

function includeTemplate($filename, $array) {
    if (file_exists($filename)) {
        include_once($filename);
        ob_start();
        ob_end_flush();
    } else {
    return "";
    }
}
function searchUserByEmail($email, $users) {
     $result = null;
     foreach ($users as $user) {
         if ($user['email'] == $email) {
             $result = $user;
             break;
         }
     }
     return $result;
}
/*
$con = mysqli_connect("localhost", "root", "", "doingsdone");
$name = $_POST['name'];
$password = $_POST['password'];
$sql = 'SELECT users ('name', 'password') VALUES (?, ?)';
*/

function get_data($con, $sql, $data) {
    $data_array = [];
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data_array[] = $row;
        }
    /*var_dump($data_array);*/
    return $data_array;
    }
}

function include_data($con, $sql, $data) {
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        /*var_dump(mysqli_insert_id($con));*/
        return mysqli_insert_id($con);
    }
}

function update_data($con, $table_name, $update_data, $update_condition) {
    $data_keys = array_keys($update_data);
    $update_string = implode(" = ?,", $data_keys);
    $update_string .= " = ? ";
    $condition_keys = array_keys($update_condition);
    $condition_string = implode(" = ? AND ", $condition_keys);
    $condition_string .= " = ? ";
    $sql = "UPDATE $table_name SET $update_string WHERE $condition_string";
    $merge_update = array_merge($update_data, $update_condition);/*объединяем массив условий и массив данных для обновления*/
    $stmt = db_get_prepare_stmt($con, $sql, $merge_update);/*получаем подготовленное выражение*/
    mysqli_stmt_execute($stmt);/*выполняем подготовленное выражение*/
    if ($stmt) {
        /*var_dump(mysqli_stmt_affected_rows($stmt));*/
        return mysqli_stmt_affected_rows($stmt);
    }
}
?>
