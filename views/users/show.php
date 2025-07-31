<?php 
use App\Enums\Role;
?>

<h1>Username: <span><?= $user['user_name']?></span></h1>
<h1>First Name: <span><?= $user['first_name']?></span></h1>
<h1>Last Name: <span><?= $user['last_name']?></span></h1>
<h1>Email: <span><?= $user['email']?></span></h1>
<h1>Date of Birth: <span><?= $user['date_of_birth']?></span></h1>
<h1>Role: <span><?= Role::from($user['role'])->toString()?></span></h1>