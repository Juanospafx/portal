<?php 
$user = UserData::getById($_GET["id"]); 
$roles = RoleData::getAll(); // Obtener roles disponibles
$userRoles = UserData::getRolesByUserId($user->id); // Obtener roles del usuario
?>

<div class="row">
    <div class="col-md-12">
        <h1>Edit user</h1>
        <br>
        <form class="form-horizontal" method="post" id="edituser" action="index.php?action=updateuser" role="form">

            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Name*</label>
                <div class="col-md-6">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user->name); ?>" 
                           class="form-control" id="name" placeholder="Name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="lastname" class="col-lg-2 control-label">Last name*</label>
                <div class="col-md-6">
                    <input type="text" name="lastname" value="<?php echo htmlspecialchars($user->lastname); ?>" 
                           class="form-control" id="lastname" placeholder="Last name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="username" class="col-lg-2 control-label">Username*</label>
                <div class="col-md-6">
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user->username); ?>" 
                           class="form-control" id="username" placeholder="Username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-lg-2 control-label">Email*</label>
                <div class="col-md-6">
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" 
                           class="form-control" id="email" placeholder="Email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-lg-2 control-label">Password</label>
                <div class="col-md-6">
                    <input type="password" name="password" class="form-control" id="password" 
                           placeholder="Leave blank to not change">
                    <p class="help-block">The password will only be changed if you enter a new one.</p>
                </div>
            </div>

            <!-- Activar/Desactivar usuario -->
            <div class="form-group">
                <label class="col-lg-2 control-label">Status</label>
                <div class="col-md-6">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="is_active" <?php echo ($user->is_active) ? "checked" : ""; ?>> Active
                    </label>
                </div>
            </div>

            <!-- Selección de Roles -->
            <div class="form-group">
                <label class="col-lg-2 control-label">Rol*</label>
                <div class="col-md-6">
                    <select name="roles[]" class="form-control" multiple required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role->id; ?>" 
                                    <?php echo (in_array($role->name, $userRoles)) ? "selected" : ""; ?>>
                                <?php echo htmlspecialchars($role->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="help-block">You can select multiple roles using CTRL + Click.</p>
                </div>
            </div>

            <p class="alert alert-info">* Required fields</p>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </div>
        </form>
    </div>
</div>
