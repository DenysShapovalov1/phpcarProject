<?php
$images = array(
    "img/logoCars/AudiQ.png",
    "img/logoCars/MercedesAMG.png",
    "img/logoCars/BMWM.png"
);

$randomImage = $images[array_rand($images)];

readfile($randomImage);
?>