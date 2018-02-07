<?php

class Product {

  private $conn;
  private $table_name = "products";

  public $id;
  public $name;
  public $price;
  public $description;
  public $category_id;
  public $timestamp;

  public function __construct($db){
    $this->conn = $db;
  }

  function create(){

    $query = "INSERT INTO " . $this->table_name . " SET name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

    $stmt = $this->conn->prepare($query);

    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));

    $this->timestamp = date('Y-m-d H:i:s');

    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":category_id", $this->category_id);
    $stmt->bindParam(":created", $this->timestamp);

    if($stmt->execute()){
      return true;
    } else{
      return false;
    }
  }

  function readAll($from_record_num, $records_per_page){
    $query = "SELECT id, name, price, description, category_id FROM " . $this->table_name . " ORDER BY name ASC LIMIT {$from_record_num}, {$records_per_page}";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }

  function countAll(){

    $query = "SELECT id FROM ".$this->table_name;

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();

    return $num;
  }

  function readOne(){

    $query = "SELECT
                name, price, description, category_id
              FROM "
                . $this->table_name . "
              WHERE
                id = ?
              LIMIT
                0,1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->name = $row['name'];
    $this->price = $row['price'];
    $this->description = $row['description'];
    $this->category_id = $row['category_id'];
  }

  function update(){

    $query = "UPDATE
                " .$this->table_name . "
              SET
                name = :name,
                price = :price,
                description = :description,
                category_id = :category_id
              WHERE
              id = :id";

      $stmt = $this->conn->prepare($query);

      $this->name=htmlspecialchars(strip_tags($this->name));
      $this->price=htmlspecialchars(strip_tags($this->price));
      $this->description=htmlspecialchars(strip_tags($this->description));
      $this->category_id=htmlspecialchars(strip_tags($this->category_id));
      $this->id=htmlspecialchars(strip_tags($this->id));

      $stmt->bindParam(':name', $this->name);
      $stmt->bindParam(':price', $this->price);
      $stmt->bindParam(':description', $this->description);
      $stmt->bindParam(':category_id', $this->category_id);
      $stmt->bindParam(':id', $this->id);

      if($stmt->execute()){
        return true;
      }

      return false;
  }

  function delete(){

    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(1, $this->id);

    if($stmt->execute()){
      return true;
    } else {
      return false;
    }
  }

  public function search($search_term, $from_record_num, $records_per_page){

    $query = "SELECT
            c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        WHERE
            p.name LIKE ? OR p.description LIKE ?
        ORDER BY
            p.name ASC
        LIMIT
            ?, ?";

      $stmt = $this->conn->prepare($query);

      $search_term = "%{$search_term}%";
      $stmt->bindParam(1, $search_term);
      $stmt->bindParam(2, $search_term);
      $stmt->bindParam(3, $from_record_num, PDO::PARAM_INT);
      $stmt->bindParam(4, $records_per_page, PDO::PARAM_INT);

      $stmt->execute();

      return $stmt;

  }

  public function countAll_BySearch($search_term){

    $query = "SELECT
              COUNT(*) as total_rows
          FROM
              " . $this->table_name . " p
              LEFT JOIN
                  categories c
                      ON p.category_id = c.id
          WHERE
              p.name LIKE ?";

    $stmt = $this->conn->prepare($query);

    $search_term = "%{$search_term}%";
    $stmt->bindParam(1, $search_term);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['total_rows'];
  }
}

 ?>
