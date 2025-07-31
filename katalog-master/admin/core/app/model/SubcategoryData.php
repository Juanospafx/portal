<?php
class SubcategoryData {
    // Declaramos las propiedades explícitamente
    public $id;
    public $name;
    public $short_name;
    public $category_id;
    public $is_active;
    public $created_at;

    public static $tablename = "subcategories";

    public function __construct(){
        $this->name = "";
        $this->short_name = "";
        $this->category_id = 0;
        $this->is_active = 0;
        $this->created_at = "NOW()";
    }

    public function add(){
        // Usamos comillas simples para los valores de tipo string
        $sql = "INSERT INTO " . self::$tablename . " (name, short_name, category_id, is_active) ";
        $sql .= "VALUES ('{$this->name}', '{$this->short_name}', {$this->category_id}, {$this->is_active})";
        Executor::doit($sql);
    }

    public static function delById($id){
        $sql = "DELETE FROM " . self::$tablename . " WHERE id=$id";
        Executor::doit($sql);
    }
    
    public function del(){
        $sql = "DELETE FROM " . self::$tablename . " WHERE id=$this->id";
        Executor::doit($sql);
    }

    public function update(){
        $sql = "UPDATE " . self::$tablename . " SET 
                    name='{$this->name}', 
                    short_name='{$this->short_name}', 
                    category_id={$this->category_id}, 
                    is_active={$this->is_active} 
                WHERE id=$this->id";
        Executor::doit($sql);
    }

    public static function getById($id){
        $sql = "SELECT * FROM " . self::$tablename . " WHERE id=$id";
        $query = Executor::doit($sql);
        return Model::one($query[0], new SubcategoryData());
    }

    public static function getByPreffix($short_name){
        $sql = "SELECT * FROM " . self::$tablename . " WHERE short_name='$short_name'";
        $query = Executor::doit($sql);
        return Model::one($query[0], new SubcategoryData());
    }

    public static function getAll(){
        $sql = "SELECT * FROM " . self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new SubcategoryData());
    }

    public static function getPublics(){
        $sql = "SELECT * FROM " . self::$tablename . " WHERE is_active=1";
        $query = Executor::doit($sql);
        return Model::many($query[0], new SubcategoryData());
    }
    
    // Método extra: obtener subcategorías por categoría principal
    public static function getByCategory($category_id){
        $sql = "SELECT * FROM " . self::$tablename . " WHERE category_id=$category_id";
        $query = Executor::doit($sql);
        return Model::many($query[0], new SubcategoryData());
    }
}
?>
