<?php 
use App\Enums\Role;
?>

<style>
    table {
        border-collapse: collapse;
        border: 1px solid #ddd;
    }
    table {
        border: 1px solid #ddd;
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #ddd;
        text-align: left;
        padding: 8px;
    }

    .color-red {
        color: red;
    }

    .color-blue {
        color: blue;
    }
</style>

<table>
    <tr>
        <th>Username</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Date of Birth</th>
        <th>Role</th>
    </tr>
    <tbody>
        <?php foreach($users as $user): ?>
            <tr>
                <td><a href="/users/<?= $user['id'] ?>"><?= $user['user_name'] ?></a></td>
                <td><?= $user['first_name'] ?></td>
                <td><?= $user['last_name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['date_of_birth'] ?></td>
                <td class="<?= Role::tryFrom($user['role'])->color()->getColor() ?>">
                    <?= Role::tryFrom($user['role'])->toString() ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
