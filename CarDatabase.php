<?php
class CarDatabase {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "car_db");
        if ($this->conn->connect_error) {
            die("Błąd połączenia: " . $this->conn->connect_error);
        }
    }

    public function addCar($brand, $model, $price, $mileage, $registration, $vin) {
        $query = "INSERT INTO cars (brand, model, price, mileage, registration, vin) 
                  VALUES ('$brand', '$model', '$price', '$mileage', '$registration', '$vin')";
        
        $result = mysqli_query($this->conn, $query);
    
        if (!$result) {
            die("Błąd MySQL: " . mysqli_error($this->conn));
        }
    
        return $result;
    }    
    

    public function searchCars($brand, $model, $price, $mileage, $registration, $vin) {
        $query = "SELECT * FROM cars WHERE 1=1";
        
        if (!empty($brand)) {
            $query .= " AND brand LIKE '%$brand%'";
        }
        if (!empty($model)) {
            $query .= " AND model LIKE '%$model%'";
        }
        if (!empty($price)) {
            $query .= " AND price <= $price";
        }
        if (!empty($mileage)) {
            $query .= " AND mileage <= $mileage";
        }
        if (!empty($registration)) {
            $query .= " AND registration LIKE '%$registration%'";
        }
        if (!empty($vin)) {
            $query .= " AND vin LIKE '%$vin%'";
        }


        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            die("Błąd MySQL: " . mysqli_error($this->conn));
        }
        $cars = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $cars[] = $row;
        }
        return $cars;
    }

    public function deleteCar($id) {
        $id = intval($id); 
        $query = "DELETE FROM cars WHERE id = $id";
        
        $result = mysqli_query($this->conn, $query);
       return $result;
    }
      
    
}

?>