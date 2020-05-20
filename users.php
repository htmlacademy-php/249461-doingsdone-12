<?php

if (isset($_SESSION['user'])) {

    $user = $_SESSION['user'];

    $user_profile = $user['id'];
    $user_name = $user['user_name'];
}
?>
