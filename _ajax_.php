<?php

class AjaxModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function updateStatus($status)
    {
        $query = "UPDATE config SET configValue=:status WHERE configName='orderingStatus'";
        return $this->executeQuery($query, ['status' => $status]);
    }

    public function updateReservation($status)
    {
        $query = "UPDATE config SET configValue=:status WHERE configName='reservationStatus'";
        return $this->executeQuery($query, ['status' => $status]);
    }

    public function statusChange($id, $status)
    {
        $query = "UPDATE orders_csr SET order_Status = :status  WHERE order_ID  = :id";
        $params = ['status' => $status, 'id' => $id];
        return $this->executeQuery($query, $params);
    }

    public function statusReservationChange($id, $status)
    {
        $query = "UPDATE reservation_csr SET reservation_Status = :status  WHERE reservation_ID  = :id";
        $params = ['status' => $status, 'id' => $id];
        return $this->executeQuery($query, $params);
    }

    public function orderChecker()
    {
        $query = "SELECT MAX(_id) AS id FROM orders_csr";
        return $this->executeQuery($query);
    }

    public function reserveChecker()
    {
        $query = "SELECT MAX(_id) AS id FROM reservation_csr";
        return $this->executeQuery($query);
    }

    public function cancelOrder($id)
    {
        $query = "UPDATE orders_csr SET order_Status = 'Cancelled' WHERE order_ID = :id";
        $params = ['id' => $id];
        return $this->executeQuery($query, $params);
    }

    public function cancelReservation($id)
    {
        $query = "UPDATE reservation_csr SET reservation_Status = 'Cancelled' WHERE reservation_ID = :id";
        $params = ['id' => $id];
        return $this->executeQuery($query, $params);
    }

    public function updateCat($oldCat, $newCat)
    {
        $query = "UPDATE category_items SET cat_Name = :newCat WHERE cat_Name = :oldCat";
        $params = ['newCat' => $newCat, 'oldCat' => $oldCat];
        return $this->executeQuery($query, $params);
    }

    public function updateCatMenu($oldCat, $newCat)
    {
        $query = "UPDATE menu_items SET category_item = :newCat WHERE category_item = :oldCat";
        $params = ['newCat' => $newCat, 'oldCat' => $oldCat];
        return $this->executeQuery($query, $params);
    }

    public function addMenuItem($name, $description, $price, $tags, $category, $activate, $link, $live)
{
    $query = 'INSERT INTO menu_items (name_item, description_item, price_item, tags_item, activate_item, category_item, photo_item, live_item) 
              VALUES (:name_, :description_, :price_, :tags_, :activate_, :category_, :photo_, :live_)';
    $params = [
        'name_' => $name,
        'description_' => $description,
        'price_' => $price,
        'tags_' => $tags,
        'activate_' => $activate,
        'category_' => $category,
        'photo_' => $link,
        'live_' => $live
    ];
    return $this->executeQuery($query, $params);
}

public function updateMenuItem($id, $name, $description, $price, $tags, $category, $activate, $link_, $live)
{
    $query = 'UPDATE menu_items SET name_item=:name_, description_item=:description_, price_item=:price_, 
              tags_item=:tags_, activate_item=:activate_, category_item=:category_, photo_item=:link_, live_item=:live_ 
              WHERE itemID=:id_';
    $params = [
        'id_' => $id,
        'name_' => $name,
        'description_' => $description,
        'price_' => $price,
        'tags_' => $tags,
        'activate_' => $activate,
        'category_' => $category,
        'link_' => $link_,
        'live_' => $live
    ];
    return $this->executeQuery($query, $params);
}

public function addMenuCat($cat)
{
    $query = 'INSERT INTO category_items (cat_Name) VALUES (:cat_)';
    $params = ['cat_' => $cat];
    return $this->executeQuery($query, $params);
}
public function deleteCat($cat)
{
    $query = 'DELETE FROM category_items WHERE cat_Name = :cat_';
    $params = ['cat_' => $cat];
    return $this->executeQuery($query, $params);
}

public function deleteMenuCat($cat)
{
    $query = 'DELETE FROM menu_items WHERE category_item = :cat_';
    $params = ['cat_' => $cat];
    return $this->executeQuery($query, $params);
}

public function updateMenuCat($category)
{
    $query = 'DROP TABLE IF EXISTS category_items';
    $this->executeQuery($query);
    
    $query = 'CREATE TABLE category_items (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, cat_Name VARCHAR(30) NOT NULL)';
    $this->executeQuery($query);
    
    foreach ($category as $cat) {
        $query = 'INSERT INTO category_items (cat_Name) VALUES (:cat_)';
        $params = ['cat_' => $cat];
        $this->executeQuery($query, $params);
    }
    
    return true;
}

public function addMeat($meat)
{
    $query = 'INSERT INTO category_meat (meatName) VALUES (:meat_)';
    $params = ['meat_' => $meat];
    return $this->executeQuery($query, $params);
}

public function deleteMeat($meat)
{
    $query = 'DELETE FROM category_meat WHERE meatName = :meat_';
    $params = ['meat_' => $meat];
    return $this->executeQuery($query, $params);
}
public function addSalad($salad)
{
    $query = 'INSERT INTO category_salad (vegName) VALUES (:salad_)';
    $params = ['salad_' => $salad];
    return $this->executeQuery($query, $params);
}

public function deleteSalad($salad)
{
    $query = 'DELETE FROM category_salad WHERE vegName = :salad_';
    $params = ['salad_' => $salad];
    return $this->executeQuery($query, $params);
}

public function countOrder($query)
{
    $this->db->query($query);
    $result = $this->db->resultSet();
    return $this->db->rowCount();
}

public function fetchOrder($query)
{
    $this->db->query($query);
    return $this->db->resultSet();
}

public function get_all_orders()
{
    $this->db->query("SELECT * FROM orders_csr");
    return $this->db->rowCount();
}

public function countMenu($query)
{
    $this->db->query($query);
    $result = $this->db->resultSet();
    return $this->db->rowCount();
}

public function fetchMenu($query)
{
    $this->db->query($query);
    return $this->db->resultSet();
}

public function get_all_data()
{
    $this->db->query("SELECT * FROM menu_items");
    return $this->db->rowCount();
}

public function get_all_data_reservation()
{
    $this->db->query("SELECT * FROM reservation_csr");
    return $this->db->rowCount();
}


public function fetchItem()
{
    $this->db->query("SELECT * FROM order_items");
    return $this->db->resultSet();
}

public function updatePrice($price, $id)
{
    $query = 'UPDATE menu_items SET price_item = :price WHERE itemID = :id';
    $params = ['price' => $price, 'id' => $id];
    return $this->executeQuery($query, $params);
}

public function deleteMenuItem($id)
{
    $query = 'DELETE FROM menu_items WHERE itemID = :id';
    $params = ['id' => $id];
    return $this->executeQuery($query, $params);
}

public function mainMenu()
{
    $this->db->query("SELECT * FROM menu_items WHERE live_item = 'live'");
    return $this->db->resultSet();
}

public function fetchMainOrder()
{
    $this->db->query("SELECT * FROM orders_csr");
    return $this->db->resultSet();
}

public function mainCat()
{
    $this->db->query("SELECT cat_Name FROM category_items");
    return $this->db->resultSet();
}

public function meatCat()
{
    $this->db->query('SELECT * FROM category_meat');
    return $this->db->resultSet();
}

public function vegCat()
{
    $this->db->query('SELECT * FROM category_salad');
    return $this->db->resultSet();
}

public function themeUpdate($theme)
{
    $query = "UPDATE config SET configValue = :theme WHERE configName = 'theme'";
    $params = ['theme' => $theme];
    return $this->executeQuery($query, $params);
}

public function fetchUpdate()
{
    $this->db->query("SELECT date_site AS date_, time_site AS time_, update_type AS update_, note_site AS note FROM updates_admin");
    return $this->db->resultSet();
}

public function countUpdate()
{
    $this->db->query("SELECT * FROM updates_admin");
    return $this->db->rowCount();
}





}
