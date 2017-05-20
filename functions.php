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

$con = mysqli_connect("localhost", "root", "", "doingsdone");
$name = $_POST['name'];
$password = $_POST['password'];
$sql = 'SELECT users ('name', 'password') VALUES (?, ?)';
function get_data($con, $sql, $data) {
    $result = db_get_prepare_stmt ($con, $sql, $data);
    if ($result == false) {
        return [];
    } else {
        mysqli_stmt_execute($result);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC) {
            $data_array[] = $row;
        return $data_array;
        }
    }
}

function include_data($con, $sql, $data) {
    $result = db_get_prepare_stmt($con, $sql, $data);
    mysqli_stmt_execute($result);
    $last_id = mysql_insert_id($con);
    if ($result == false) {
        return false;
    } else {
        return $last_id;
    }
}

function update_data($con, $table_name, $update_data, $update_condition) {
    $sql = "UPDATE $table_name SET $update_data WHERE $update_condition VALUES ?, ?, ?, ?";
    $merge_update = array_merge($update_data, $update_condition);
    $result = db_get_prepare_stmt($con, $sql, $merge_update);
    $records_count = mysqli_num_rows($result);
    if ($result == false) {
        return false;
    } else {
        return $records_count;
    }
}

?>
