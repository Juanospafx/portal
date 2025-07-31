<?php
class ConfigurationData {
    public static $tablename = "configuration";

    public function __construct() {
        $this->title = "";
        $this->content = "";
        $this->image = "";
        $this->user_id = "";
        $this->is_public = 0;
        $this->created_at = date("Y-m-d H:i:s");
    }

    // ✅ Agregar nueva configuración
    public function add() {
        $sql = "INSERT INTO ".self::$tablename." (name, short_name, is_active) VALUES (?, ?, ?)";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $this->name, $this->short_name, $this->is_active);
        $stmt->execute();
        $stmt->close();
    }

    // ✅ Eliminar configuración por ID
    public static function delById($id) {
        $sql = "DELETE FROM ".self::$tablename." WHERE id = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // ✅ Eliminar configuración por objeto
    public function del() {
        self::delById($this->id);
    }

    // ✅ Actualizar configuración completa
    public function update() {
        $sql = "UPDATE ".self::$tablename." SET name = ?, short_name = ?, is_active = ? WHERE id = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssii", $this->name, $this->short_name, $this->is_active, $this->id);
        $stmt->execute();
        $stmt->close();
    }

    // ✅ **Actualizar un valor de configuración por nombre**
    public static function updateValFromName($name, $val) {
        $sql = "UPDATE ".self::$tablename." SET val = ? WHERE name = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        
        // 🔹 Escapar caracteres especiales para evitar problemas con comillas
        $clean_val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');

        $stmt->bind_param("ss", $clean_val, $name);
        $stmt->execute();
        $stmt->close();
    }

    // ✅ Obtener configuración por ID
    public static function getById($id) {
        $sql = "SELECT * FROM ".self::$tablename." WHERE id = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_object(self::class);
    }

    // ✅ Obtener configuración por nombre
    public static function getByPreffix($name) {
        $sql = "SELECT * FROM ".self::$tablename." WHERE name = ?";
        $base = new Database();
        $con = $base->connect();
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_object(self::class);
    }

    // ✅ Obtener todas las configuraciones
    public static function getAll() {
        $sql = "SELECT * FROM ".self::$tablename;
        $base = new Database();
        $con = $base->connect();
        $result = $con->query($sql);
        $data = [];
        while ($row = $result->fetch_object(self::class)) {
            $data[] = $row;
        }
        return $data;
    }

    // ✅ Obtener configuraciones activas
    public static function getPublics() {
        $sql = "SELECT * FROM ".self::$tablename." WHERE is_active = 1";
        $base = new Database();
        $con = $base->connect();
        $result = $con->query($sql);
        $data = [];
        while ($row = $result->fetch_object(self::class)) {
            $data[] = $row;
        }
        return $data;
    }
}
?>
