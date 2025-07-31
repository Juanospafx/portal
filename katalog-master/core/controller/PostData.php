<?php
class PostData {
	public static $tablename = "post";


	public function __construct(){
		$this->title = "";
		$this->content = "";
		$this->image = "";
		$this->link = "";
		$this->category_id = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}

	public function getUnit(){ return UnitData::getById($this->unit_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (short_name,code,name,description,image,link,category_id,is_public,is_featured,created_at) ";
		$sql .= "value (\"$this->short_name\",\"$this->code\",\"$this->name\",\"$this->description\",\"$this->image\",\"$this->link\",$this->category_id,$this->is_public,$this->is_featured,$this->created_at)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto PostData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set code=\"$this->code\",name=\"$this->name\",description=\"$this->description\",link=\"$this->link\",is_public=\"$this->is_public\",is_featured=\"$this->is_featured\",category_id=\"$this->category_id\" where id=$this->id";
		Executor::doit($sql);
	}
    public static function getRelatedBySubcategory($current_id, $subcategory_id, $limit = 6){
    $sql = "SELECT p.* FROM " . self::$tablename . " p
            INNER JOIN product_subcategories ps ON p.id = ps.post_id
            WHERE ps.subcategory_id = $subcategory_id
              AND p.id != $current_id
              AND p.is_public = 1
            ORDER BY p.created_at DESC
            LIMIT $limit";
    $query = Executor::doit($sql);
    return Model::many($query[0], new PostData());
}


	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PostData());
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PostData());
	}

	public static function getPublicsByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id and is_public=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PostData());
	}

	public static function get4News(){
		$sql = "select * from ".self::$tablename." where is_new=1 and is_public=1 order by created_at desc limit 4";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PostData());
	}

	public static function get4Offers(){
		$sql = "select * from ".self::$tablename." where is_offer=1 and is_public=1 order by created_at desc limit 4";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PostData());
	}

	public static function getNews(){
		$sql = "select * from ".self::$tablename." where is_new=1 and is_public=1 order by created_at desc limit 4";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PostData());
	}

	public static function getFeatureds(){
		$sql = "select * from ".self::$tablename." where is_featured=1 and is_public=1 order by created_at desc limit 6";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PostData());
	}

    public static function getRelated($post_id, $limit=6){
    $sql = "SELECT p.* FROM ".self::$tablename." p
            INNER JOIN post_relations r ON p.id = r.related_id
            WHERE r.post_id = $post_id
              AND p.is_public = 1
            LIMIT $limit";
    $query = Executor::doit($sql);
    return Model::many($query[0], new PostData());
}

public static function getRelatedByCategory($current_id, $category_id, $limit=6){
    $sql = "SELECT * FROM ".self::$tablename." 
            WHERE category_id = $category_id 
              AND id != $current_id 
              AND is_public = 1 
            ORDER BY created_at DESC 
            LIMIT $limit";
    $query = Executor::doit($sql);
    return Model::many($query[0], new PostData());
}

public static function getRelatedBySimilarity($current_id, $name, $limit=6){
    // separar palabras
    $tokens = explode(" ", $name);
    $tokens = array_map('trim', $tokens);

    $sql = "SELECT * FROM ".self::$tablename."
            WHERE id != $current_id
              AND is_public = 1";

    // Para cada palabra, agregamos un OR
    $sql .= " AND (";
    $parts = array();
    foreach($tokens as $t){
        $t = mysqli_real_escape_string(Database::getCon(), $t);
        $parts[] = "name LIKE '%$t%' OR description LIKE '%$t%'";
    }
    $sql .= implode(" OR ", $parts) . ")";

    $sql .= " ORDER BY created_at DESC LIMIT $limit";

    $query = Executor::doit($sql);
    return Model::many($query[0], new PostData());
}



public static function getLike($q){
    $con = Database::getCon();

    $q_raw = trim($q);
    $q = strtolower($q_raw);

    // Solo agregar comillas si el número va seguido de palabras como emt/conduit/pipe
    if (preg_match('/\b(\d+(\/\d+)?)(?=\s+(emt|conduit|pipe|tubo))/', $q, $matches)) {
        $measure = $matches[1];
        // Reemplazar solo la primera instancia
        $q = preg_replace('/\b' . preg_quote($measure, '/') . '\b/', $measure . '"', $q, 1);
    }

    // Limpiar caracteres especiales (excepto / y ")
    $q = preg_replace("/[^a-z0-9\/\"\s]/", " ", $q);
    $tokens = array_filter(explode(" ", $q));

    $relevance = [];

    // BONUS 1: Coincidencia exacta del nombre completo
    $relevance[] = "CASE WHEN LOWER(name) = '".mysqli_real_escape_string($con, $q)."' THEN 100 ELSE 0 END";

    // BONUS 2: Frase completa contenida en el nombre
    $relevance[] = "CASE WHEN LOWER(name) LIKE '%".mysqli_real_escape_string($con, $q)."%' THEN 50 ELSE 0 END";

    // BONUS 2.5: El nombre empieza con los primeros dos tokens
    $firstTwo = implode(" ", array_slice($tokens, 0, 2));
    if (!empty($firstTwo)) {
        $relevance[] = "CASE WHEN LOWER(name) LIKE '".mysqli_real_escape_string($con, $firstTwo)."%' THEN 40 ELSE 0 END";
    }

    // BONUS 3: Tokens individuales
    foreach ($tokens as $t) {
        $t = mysqli_real_escape_string($con, $t);
        $relevance[] = "CASE WHEN LOWER(name) LIKE '%$t%' THEN 10 ELSE 0 END";
        $relevance[] = "CASE WHEN LOWER(short_name) LIKE '%$t%' THEN 8 ELSE 0 END";
        $relevance[] = "CASE WHEN LOWER(code) LIKE '%$t%' THEN 6 ELSE 0 END";
        $relevance[] = "CASE WHEN LOWER(description) LIKE '%$t%' THEN 4 ELSE 0 END";
    }

    // Construir consulta principal
    $sql = "SELECT *, (" . implode(" + ", $relevance) . ") AS relevance
            FROM ".self::$tablename."
            WHERE is_public = 1";

    // Filtros: los tokens deben aparecer en algún campo
    foreach ($tokens as $t) {
        $t = mysqli_real_escape_string($con, $t);
        $sql .= " AND (
            LOWER(name) LIKE '%$t%' OR
            LOWER(description) LIKE '%$t%' OR
            LOWER(code) LIKE '%$t%' OR
            LOWER(short_name) LIKE '%$t%'
        )";
    }

    $sql .= " ORDER BY relevance DESC, created_at DESC";

    $query = Executor::doit($sql);
    return Model::many($query[0], new PostData());
}







}

?>