<?php

if (isset($_GET['name']) && isset($_GET['email'])) {
    $name = $_GET['name'];
    $email = $_GET['email'];
    echo "Name: $name <br> Email: $email";
}

?>
<form action="">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Name">
    <br>
    <label for="email">Email</label>
    <input type="text" name="email" id="email" placeholder="Email">
    <br>
    <button type="submit">Submit</button>
</form>