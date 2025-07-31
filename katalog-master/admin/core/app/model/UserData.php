<?php
class UserData {
    public static $tablename = "user";

    public function __construct(){
        $this->name = "";
        $this->lastname = "";
        $this->email = "";
        $this->username = "";
        $this->password = "";
        $this->created_at = date("Y-m-d H:i:s");
    }

    // ✅ Agregar un nuevo usuario y devolver su ID
    /*
    public function add(){
        $sql = "INSERT INTO user (name, lastname, username, email, password, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssss", $this->name, $this->lastname, $this->username, $this->email, $this->password, $this->created_at);
        $stmt->execute();
        return $con->insert_id; // Devuelve el ID del usuario recién creado
    }
    */

    // ✅ Asignar un rol a un usuario (Verifica duplicados)
    /*
    public static function assignRole($user_id, $role_id) {
        // Evitar roles duplicados
        $sql_check = "SELECT * FROM user_roles WHERE user_id = ? AND role_id = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param("ii", $user_id, $role_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        
        if ($result->num_rows == 0) { // Si el rol no está asignado, agregarlo
            $sql = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ii", $user_id, $role_id);
            $stmt->execute();
        }
    }
    */

    // ✅ Eliminar todos los roles de un usuario
    /*
    public static function removeRoles($user_id) {
        $sql = "DELETE FROM user_roles WHERE user_id = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    */

    // ✅ Eliminar usuario por ID
    /*
    public static function delById($id){
        $sql = "DELETE FROM user WHERE id=?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    */

    // ✅ Actualizar usuario
    /*
    public function update(){
        $sql = "UPDATE user SET username=?, name=?, lastname=?, email=?, is_active=? WHERE id=?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssii", $this->username, $this->name, $this->lastname, $this->email, $this->is_active, $this->id);
        $stmt->execute();
    }
    */

    // ✅ Actualizar contraseña
    /*
    public function update_passwd(){
        $sql = "UPDATE user SET password=? WHERE id=?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $this->password, $this->id);
        $stmt->execute();
    }
    */

    // ✅ Obtener usuario por ID
    public static function getById($id){
        // No se obtiene de la base de datos, se asume que los datos están en la sesión
        if (isset($_SESSION['user_data']) && $_SESSION['user_data']['id'] == $id) {
            $user = new self();
            $user->id = $_SESSION['user_data']['id'];
            $user->name = $_SESSION['user_data']['username']; // Usar username como name
            $user->lastname = ''; // No disponible en JWT
            $user->email = $_SESSION['user_data']['email'];
            $user->username = $_SESSION['user_data']['username'];
            $user->is_active = 1; // Asumir activo
            return $user;
        }
        return null;
    }
    

    // ✅ Obtener usuario por nombre de usuario o email (para login)
    /*
    public static function getByUsername($username){
        $sql = "SELECT * FROM user WHERE username = ? OR email = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }
    */

    // ✅ Obtener usuario por email
    /*
    public static function getByMail($email){
        $sql = "SELECT * FROM user WHERE email=?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }
    */

    // ✅ Obtener roles de un usuario por ID
    public static function getRolesByUserId($user_id){
        if (isset($_SESSION['user_data']) && $_SESSION['user_data']['id'] == $user_id) {
            $app_access = $_SESSION['user_data']['app_access'];
            $roles = explode(',', $app_access);
            // Mapear los nombres de las aplicaciones a roles si es necesario
            // Por ejemplo, si 'admin' en app_access significa rol 'Administrador'
            $mapped_roles = [];
            foreach ($roles as $role) {
                $mapped_roles[] = trim($role);
            }
            return $mapped_roles;
        }
        return [];
    }

    // ✅ Obtener todos los usuarios con sus roles
    /*
    public static function getAll(){
        $sql = "SELECT u.id, u.name, u.lastname, u.username, u.email, u.is_active, 
                GROUP_CONCAT(r.name SEPARATOR ', ') as roles 
                FROM user u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                GROUP BY u.id";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
    */

    // ✅ Buscar usuarios por nombre
    /*
    public static function getLike($q){
        $sql = "SELECT * FROM user WHERE name LIKE ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $search = "%$q%";
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
    */
}
?>
