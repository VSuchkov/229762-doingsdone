<?php
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
?>
