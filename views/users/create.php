<form action="/users/store" method="post">
    <label for="user_name">User Name</label>
    <input type="text" name="user_name" id="user_name" placeholder="User Name"><br><br>
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name" placeholder="First Name"><br><br>
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" placeholder="Last Name"><br><br>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Email"><br><br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Password"><br><br>
    <label for="date_of_birth">Date of Birth</label>
    <input type="date" name="date_of_birth" id="date_of_birth" placeholder="Date of Birth"><br><br>
    <label for="role">Role</label>
    <select name="role" id="role" required>
        <option value="">-- Select Role --</option>
        <option value="<?php echo App\Enums\Role::ADMIN->value?>">Admin</option>
        <option value="<?php echo App\Enums\Role::USER->value?>" selected>User</option>
    </select><br><br>
    <button type="submit">Create</button>

</form>