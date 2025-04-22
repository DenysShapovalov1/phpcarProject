<?php
require_once "CarDatabase.php";

$carDB = new CarDatabase();
$resultsHtml = "";

$searchBrand = $_POST['brand'] ?? '';
$searchModel = $_POST['model'] ?? '';
$searchPrice = $_POST['price'] ?? 0;
$searchMileage = $_POST['mileage'] ?? 0;
$searchRegistration = trim($_POST['registration'] ?? '');
$searchVin = trim($_POST['vin'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteCar'])) {
    $carId = intval($_POST['deleteCar']);
    $carDB->deleteCar($carId);

    $cars = $carDB->searchCars($searchBrand, $searchModel, $searchPrice, $searchMileage, $searchRegistration, $searchVin);

    if (empty($cars)) {
        $resultsHtml = "<p style='color: red;'>Takiego pojazdu nie ma.</p>";
    } else {
        if (count($cars) === 1) {
            $car = $cars[0];
            $resultsHtml = "
                <h2>Wyniki usuwania</h2>
                <p class='car-item'>
                    <strong> Marka:</strong> {$car['brand']}  
                    <strong> Model:</strong> {$car['model']}  <br />
                    <strong> Cena:</strong> {$car['price']} zł  <br />
                    <strong> Przebieg:</strong> {$car['mileage']} km <br />
                    <strong> Nr.rejestracyjny:</strong> {$car['registration']} <br />
                </p>
                <form method='POST' class='delete-form'>
                    <input type='hidden' name='deleteCar' value='{$car['id']}'>
                    <input type='hidden' name='brand' value='{$searchBrand}'>
                    <input type='hidden' name='model' value='{$searchModel}'>
                    <input type='hidden' name='price' value='{$searchPrice}'>
                    <input type='hidden' name='mileage' value='{$searchMileage}'>
                    <input type='hidden' name='registration' value='{$searchRegistration}'>
                    <input type='hidden' name='vin' value='{$searchVin}'>
                    <button type='submit' class='delete-btn'>🗑️ Usuń</button>
                </form>";
        } else {
            $resultsHtml = " <h2>Wyniki usuwania</h2>
                                <div class='carousel-container'>
                                <button class='carousel-button prev'>&#10094;</button>
                                <div class='carousel'>";
            foreach ($cars as $car) {
                $resultsHtml .= "
                    <div class='carousel-item'>
                        <p class='car-item'>
                            <strong> Marka:</strong> {$car['brand']}  
                            <strong> Model:</strong> {$car['model']}  <br />
                            <strong> Cena:</strong> {$car['price']} zł  <br />
                            <strong> Przebieg:</strong> {$car['mileage']} km <br />
                            <strong> Nr.rejestracyjny:</strong> {$car['registration']} <br />
                        </p>
                        <form method='POST' class='delete-form'>
                            <input type='hidden' name='deleteCar' value='{$car['id']}'>
                            <input type='hidden' name='brand' value='{$searchBrand}'>
                            <input type='hidden' name='model' value='{$searchModel}'>
                            <input type='hidden' name='price' value='{$searchPrice}'>
                            <input type='hidden' name='mileage' value='{$searchMileage}'>
                            <input type='hidden' name='registration' value='{$searchRegistration}'>
                            <input type='hidden' name='vin' value='{$searchVin}'>
                            <button type='submit' class='delete-btn'>🗑️ Usuń</button>
                        </form>
                    </div>";
            }
            $resultsHtml .= "</div>
                            <button class='carousel-button next'>&#10095;</button>
                        </div>";
        }
    }            
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $price = $_POST['price'] ?? 0;
    $mileage = $_POST['mileage'] ?? 0;
    $registration = trim($_POST['registration'] ?? '');
    $vin = trim($_POST['vin'] ?? '');

    if (isset($_POST['action']) && $_POST['action'] === 'add')  { 
        if (empty($vin) && empty($registration)) {
            $resultsHtml = "<p style='color: red;'> VIN lub Nr. rejestracyjny jest wymagany!</p>";
        } 
        else {
            $carDB->addCar($brand, $model, $price, $mileage, $registration, $vin);
            $resultsHtml = "
            <h2>Wyniki dodawania</h2>
            <p style='color: lightgreen;'>Pojazd dodany!</p>";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'search')  {  
        $cars = $carDB->searchCars($brand, $model, $price, $mileage, $registration, $vin);
        
        if (empty($cars)) {
            $resultsHtml = "<p style='color: red;'>Takiego pojazdu nie ma.</p>";
        } else {
            if (count($cars) === 1) {
                $car = $cars[0];
                $resultsHtml = "
                    <h2>Wyniki wyszukiwania</h2>
                    <p class='car-item'>
                        <strong> Marka:</strong> {$car['brand']}  
                        <strong> Model:</strong> {$car['model']}  <br />
                        <strong> Cena:</strong> {$car['price']} zł  <br />
                        <strong> Przebieg:</strong> {$car['mileage']} km <br />
                        <strong> Nr.rejestracyjny:</strong> {$car['registration']} <br />
                    </p>
                    <form method='POST' class='delete-form'>
                        <input type='hidden' name='deleteCar' value='{$car['id']}'>
                        <input type='hidden' name='brand' value='{$brand}'>
                        <input type='hidden' name='model' value='{$model}'>
                        <input type='hidden' name='price' value='{$price}'>
                        <input type='hidden' name='mileage' value='{$mileage}'>
                        <input type='hidden' name='registration' value='{$registration}'>
                        <input type='hidden' name='vin' value='{$vin}'>
                        <button type='submit' class='delete-btn'>🗑️ Usuń</button>
                    </form>";
            } else {
                $resultsHtml = " <h2>Wyniki wyszukiwania</h2>
                                    <div class='carousel-container'>
                                    <button class='carousel-button prev'>&#10094;</button>
                                    <div class='carousel'>";
                foreach ($cars as $car) {
                    $resultsHtml .= "
                        <div class='carousel-item'>
                            <p class='car-item'>
                                <strong> Marka:</strong> {$car['brand']}  
                                <strong> Model:</strong> {$car['model']}  <br />
                                <strong> Cena:</strong> {$car['price']} zł  <br />
                                <strong> Przebieg:</strong> {$car['mileage']} km <br />
                                <strong> Nr.rejestracyjny:</strong> {$car['registration']} <br />
                            </p>
                            <form method='POST' class='delete-form'>
                                <input type='hidden' name='deleteCar' value='{$car['id']}'>
                                <input type='hidden' name='brand' value='{$brand}'>
                                <input type='hidden' name='model' value='{$model}'>
                                <input type='hidden' name='price' value='{$price}'>
                                <input type='hidden' name='mileage' value='{$mileage}'>
                                <input type='hidden' name='registration' value='{$registration}'>
                                <input type='hidden' name='vin' value='{$vin}'>
                                <button type='submit' class='delete-btn'>🗑️ Usuń</button>
                            </form>
                        </div>";
                }
                $resultsHtml .= "</div>
                                <button class='carousel-button next'>&#10095;</button>
                            </div>";
            }
        }            
    }  
    //echo $resultsHtml;  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="parent">
        <div class="div1">
            <div class="image top-image"></div>
            <h2>Baza danych samochodów</h2>
            <div class="image bottom-image"></div>
        </div>
        <div class="div2">
            <div class="search-box">
                <h2 id="form-title">Wyszukiwarka</h2>
                <img src="r.php" alt="Car Logo">
                <p id="form-undertitle">Wyszukiwanie pojazdu</p>
                <form id="car-form" method="POST" action="">
                <input type="hidden" id="form-action" name="action" value="search">
                <input type="text" name="brand" placeholder="Napisz markę pojazdu" value="<?php echo htmlspecialchars($searchBrand); ?>" required>
                <input type="text" name="model" placeholder="Napisz model pojazdu" value="<?php echo htmlspecialchars($searchModel); ?>">
                <div class="input-group">
                    <input type="text" name="price" placeholder="Cena" value="">
                    <input type="text" name="mileage" placeholder="Przebieg" value="">
                </div>
                <input type="text" name="registration" placeholder="Nr. rejestracyjny" value="<?php echo htmlspecialchars($searchRegistration); ?>">
                <p>lub</p>
                <input type="text" placeholder="VIN" name="vin" id="vin" value="<?php echo htmlspecialchars($searchVin); ?>">
                <button type="submit" id="form-submit-btn" name="searchCar">Wyszukaj</button>
            </form>         
            </div>
        </div>
        <div class="div3">
            <div id="default-panel">
                <h2>Zarządzanie Bazą Samochodów</h2>
                <p>Tutaj możesz przeglądać, edytować i zarządzać bazą samochodów. Wyszukaj pojazd według marki, modelu, numeru rejestracyjnego lub VIN.</p>
                <div class="button-group">
                    <button onclick="switchToAdd()" class="add-car">+ Dodaj pojazd</button>
                    <button onclick="switchToSearch()" class="search-button">
                        <img src="img/icons/FindIcon.png" alt="Search"> Wyszukaj
                    </button>
                </div>
            </div>

            <div id="result-panel" style="display: none;">
                <div id="search-results">
                    <?php echo $resultsHtml; ?>
                </div>
                <button onclick="hideResults()" class="back-button">Powrót</button>
            </div>
        
            <div class="car-images">
                <img src="img/bgCars/BGCarSecond.png" alt="Car 1">
                <img src="img/bgCars/BGCatWithSecond.png" alt="Car 2">
            </div>
            <p class="copy">©Copyright 2025</p>
        </div>
    </div>

    <script>
        function showResults() {
            document.getElementById("result-panel").style.display = "block";
            document.getElementById("default-panel").style.display = "none";
        }
        function hideResults() {
            document.getElementById("result-panel").style.display = "none";
            document.getElementById("default-panel").style.display = "block";
        }

        function switchToSearch() {
            document.getElementById("form-title").innerText = "Wyszukiwarka";
            document.getElementById("form-action").value = "search";
            document.getElementById("form-submit-btn").innerText = "Wyszukaj";
            document.getElementById("form-undertitle").innerText = "Wyszukiwanie pojazdu";
            document.getElementById("form-submit-btn").setAttribute("name", "searchCar");
        }
    
        function switchToAdd() {
            document.getElementById("form-title").innerText = "Dodaj pojazd";
            document.getElementById("form-action").value = "add";
            document.getElementById("form-submit-btn").innerText = "Dodaj";
            document.getElementById("form-undertitle").innerText = "Dodawanie pojazdu";
            document.getElementById("form-submit-btn").setAttribute("name", "addCar");
        }
        document.addEventListener("DOMContentLoaded", () => {
            const carousel = document.querySelector(".carousel");
            const prevButton = document.querySelector(".carousel-button.prev");
            const nextButton = document.querySelector(".carousel-button.next");

            if (carousel && prevButton && nextButton) {
                prevButton.addEventListener("click", () => {
                    carousel.scrollBy({
                        left: -300, 
                        behavior: "smooth"
                    });
                });

                nextButton.addEventListener("click", () => {
                    carousel.scrollBy({
                        left: 300, 
                        behavior: "smooth"
                    });
                });
            }

            if (document.getElementById("search-results").innerHTML.trim() !== "") {
                showResults();
            }
        });
    </script>
</body>
</html>