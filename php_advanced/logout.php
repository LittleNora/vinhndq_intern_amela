<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    session_destroy();
    setcookie('username', '', time() - 3600);
    setcookie('id', '', time() - 3600);
    header('location: /');
}