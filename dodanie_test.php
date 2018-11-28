<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 15.11.2018
 * Time: 13:08
 */
require_once 'functions.php';
$name = $_POST['name_of_worker'];
$surname = $_POST['surname_of_worker'];
if($_POST['id_of_worker']!= null) $numer = $_POST['id_of_worker'];
else $numer = 0;
$date_of_birth = $_POST['date_of_birth_of_worker'];
$position = $_POST['position_of_worker'];
if($_POST['add_pets'] != null) $add_pets = true;
else $add_pets = false;
if($_POST['edit_pets'] != null) $edit_pets = true;
else $edit_pets = false;
if($_POST['delete_pets'] != null) $delete_pets = true;
else $delete_pets = false;
if($_POST['move_pets'] != null) $move_pets = true;
else $move_pets = false;
if($_POST['add_debt'] != null) $add_debt = true;
else $add_debt = false;
if($_POST['change_debt'] != null) $change_debt = true;
else $change_debt = false;
if($_POST['accept_reservation'] != null) $accept_reservation = true;
else $accept_reservation = false;
$email = $_POST['email_of_worker'];
$password = $_POST['password_of_worker'];
$type_of_account = $_POST['type_of_account_of_worker'];
$password2 = $_POST['password2_of_worker'];

$table[0] = $name;
$table[1] = $surname;
$table[2] = $position;
$table[3] = $add_pets;
$table[4] = $edit_pets;
$table[5] = $delete_pets;
$table[6] = $move_pets;
$table[7] = $add_debt;
$table[8] = $change_debt;
$table[9] = $accept_reservation;
$table[10] = $numer;
$table[11] = $email;
$table[12] = $password;
$table[13] = $type_of_account;

if($password === $password2) add_workers($table);
else echo "Hasła nie są takie same";