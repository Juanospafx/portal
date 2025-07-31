<div class="row">
    <div class="col-md-12">
        <h1>Agregar Usuario</h1>
        <br>
        <form class="form-horizontal" method="post" id="adduser" action="index.php?action=adduser" role="form">

            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Name*</label>
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="lastname" class="col-lg-2 control-label">Last name*</label>
                <div class="col-md-6">
                    <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Last name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="username" class="col-lg-2 control-label">User name*</label>
                <div class="col-md-6">
                    <input type="text" name="username" class="form-control" id="username" placeholder="User name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-lg-2 control-label">Email*</label>
                <div class="col-md-6">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-lg-2 control-label">Password*</label>
                <div class="col-md-6">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                </div>
            </div>

            <!-- Selección de Roles -->
            <div class="form-group">
                <label for="roles" class="col-lg-2 control-label">Rol*</label>
                <div class="col-md-6">
                    <select name="roles[]" class="form-control" multiple required>
                        <?php
                        $roles = RoleData::getAll();
                        foreach ($roles as $role) {
                            echo "<option value='{$role->id}'>{$role->name}</option>";
                        }
                        ?>
                    </select>
                    <p class="help-block">You can select multiple roles by holding down the Ctrl (Windows) or Cmd (Mac) key.</p>
                </div>
            </div>

            <p class="alert alert-info">* Required fields</p>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </div>

        </form>
    </div>
</div>
