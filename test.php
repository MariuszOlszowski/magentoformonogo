<?php

require_once 'app/Mage.php';
Mage::app();

$Core = new Mariusz_Demo_Model_Index;
$Core->showForm();