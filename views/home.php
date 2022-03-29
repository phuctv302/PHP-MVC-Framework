<?php
    $this->title = 'Home';
?>

<h1>True Platform</h1><br>
<?php
if (!$user){
    echo '<p>You are not logged in yet! Please login to continue</p> <br>';
    echo '<a class="btn btn--auth btn--green" href="/login">Login</a>';
    echo '<a class="btn btn--auth btn--green" href="/register">Register</a>';
} else {
    echo "<h1>Welcome {$user->getDisplayName()} </h1>";
    echo '<a href="/profile">Click here to see your profile</a>';
}
?>

