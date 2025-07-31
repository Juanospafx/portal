<div class="row">
    <div class="col-md-12">
        <a href="index.php?view=newuser" class="btn btn-default pull-right">
            <i class='glyphicon glyphicon-user'></i> New User
        </a>
        <h1>User list</h1>
        <br>

        <?php
        $users = UserData::getAll();
        if (count($users) > 0) {
            ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Rol</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($users as $user) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name'] . " " . $user['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="text-center">
                            <?php if ($user['is_active']): ?>
                                <i class="glyphicon glyphicon-ok text-success"></i>
                            <?php else: ?>
                                <i class="glyphicon glyphicon-remove text-danger"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            // Mostrar roles del usuario
                            echo (!empty($user['roles'])) ? htmlspecialchars($user['roles']) : "<span class='text-muted'>Sin Rol</span>";
                            ?>
                        </td>
                        <td style="width: 120px;">
                            <a href="index.php?view=edituser&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-xs">
                                <i class="glyphicon glyphicon-pencil"></i> Edit
                            </a>
                            <a href="index.php?action=deluser&id=<?php echo $user['id']; ?>" 
                               class="btn btn-danger btn-xs" 
                               onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        } else {
            echo "<p class='alert alert-warning'>There are no registered users.</p>";
        }
        ?>
    </div>
</div>
