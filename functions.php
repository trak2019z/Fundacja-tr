<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 14.11.2018
 * Time: 15:45
 */

//connect with database
function getpolaczenie(){
    $dbname = 'mjaw_fundacja';
    $dbuser = 'mjaw_fundacja';
    $dbpassword = '!Fundacja123';
    $dbserver = 'localhost';
    $con = new PDO("mysql:dbname=$dbname;host=$dbserver", $dbuser,
                    $dbpassword, array(PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
    $con -> query ('SET NAMES utf8');
    //echo "Pomyślnie utworzono połączenie z bazą danych";
    return $con;
}

//check max id in table
function check_id($table_name, $table_id){
    //$query = "select $table_id from $table_name order by $table_id dsc limit 1;";
    $query = "select max($table_id) as maks from $table_name";
    $con = getpolaczenie();
    $id_from_database = $con->query($query);
    $row = $id_from_database->fetch(PDO::FETCH_ASSOC);
    if($row[$table_id] == 1) $id = $row['maks'];
    else $id = 0;
    $id += 1;
    return $id;
}

//ABOUT PETS
/*************************************/
//add new pet
function add_pet($table){
    $id = check_id("PETS");
    $query = "insert into PETS (pet_id, name, number, description, main_pic, date_of_birth, state) 
              values ($id, \"$table[0]\", \"$table[1]\", \"$table[2]\", \"$table[3]\", \"$table[4]\", \"$table[5]\");";

    try{
    }catch(Exception $e) {
        echo "Wystąpił błąd: \"$e\"";
    }
}

//edit information about pet
function edit_pet(){}

//what animals are to adoption
function show_pets($limit){
    $query = "select * from PETS where state = \"avaliable\" or state = \"reserved\" limit ;";

}

//show info about one pet
/*function show_pet($pet_id){
    $query = "select * from PETS where pet_id = $pet_id";
    $state = ;
    $query = "select * from ADDITIONAL where pet_id = $pet_id";
    $query = "select * from PHOTOS where pet_id = $pet_id";
    $query = "select * from DEBT where pet_id = $pet_id";
    if($state === "dead")
        $query = "select * from DEAD where pet_id = $pet_id";
    else if($state === "adopted")
        $query = "select * from ADOPTED where pet_id = $pet_id";

}
*/
//worker can delete pet from table
/*function delete_pet($pet_id){
    $query = "select state from PETS where pet_id = $pet_id";
    $state = ;
    $query = "delete from FAVOURITES where pet_id = $pet_id;";
    $query = "delete from DEBT where pet_id = $pet_id;";
    $query = "delete from PHOTOS where pet_id = $pet_id;";
    $query = "delete from ADDITIONAL where pet_id = $pet_id;";
    $query = "delete from RESERVATION where pet_id = $pet_id;";
    if($state === "adopted")
        $query = "delete from ADOPTED where pet_id = $pet_id;";
    else if($state === "dead")
        $query = "delete from DEAD where pet_id = $pet_id;";
    $query = "delete from PETS where pet_id = $pet_id";

    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}
*/
//move to adopted
function adopt_pet($table){
    $id = check_id("ADOPTED");
    $query = "insert into ADOPTED (adoption_id, pet_id, date_of_adoption) values \"$id\",\"$table[0]\", \"$table[1]\";";
    $query = "update PETS set state = \"adopted\" where pet_id = \"$table[0]\";";
    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//move to dead
function dead_pet($table){
    $id = check_id("DEAD");
    $query = "insert into DEAD (death_id, pet_id, date_of_pass_away) values \"$id\",\"$table[0]\", \"$table[1]\";";
    $query = "update PETS set state = \"dead\" where pet_id = \"$table[0]\";";
    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//add more pictures of pet
function add_pictures($table){
    $id = check_id("PHOTOS");
    $query = "insert into PHOTOS (photo_id, pet_id, photo) values \"$id\",\".$table[0]\", \"$table[1]\";";

    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//add more info about pets
function add_info($table){
    $id = check_id("ADDITIONAL");
    $query = "insert into ADDITIONAL (news_id, pet_id, news_date, description, title)
                values \"$id\",\".$table[0]\", \"$table[1]\", \"$table[2]\", \"$table[3]\";";

    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//edit info about pets
function edit_info(){}

//delete info about pets
function remove_info($id){
    $query = "delete from ADDITIONAL where news_id = $id;";

    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//delete pictures of pet
function remove_picture($id){
    $query = "delete from PHOTOS where photo_id = $id;";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//add info about pet's debt
function add_debt($table){
    $id = check_id("DEBT");
    $query = "insert into DEBT (debt_id, pet_id, debt_value, paid)
                values \"$id\",\".$table[0]\", \"$table[1]\", \"$table[2]\";";

    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//edit info about pet's debt
function edit_debt(){}

//delete info about debt
function remove_debt($id){
    $query = "delete from DEBT where debt_id = $id;";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//ABOUT WORKERS
/*************************************/
//add workers (admin)
function add_workers($table){
    $con = getpolaczenie();
    $logging_id = check_id("LOGGING", "logging_id");
    $query = "insert into LOGGING(logging_id, email, password, type_of_account)
              values (\"$logging_id\", \"$table[11]\", \"$table[12]\", \"$table[13]\");";
    $save = $con->query($query);
    if($table[10] != 0) $id = $table[10];
    else $id = check_id("WORKERS", "worker_id");
    $query = "insert into WORKERS (worker_id, name, surname, position, logging_id)
                values (\"$id\",\"$table[0]\", \"$table[1]\", \"$table[2]\", \"$logging_id\");";
    try{
        $save = $con->query($query);
        $permission_id = check_id("PERMISSIONS", "permission_id");
        $query = "insert into PERMISSIONS (permission_id, worker_id, add_pets, edit_pets, delete_pets, move_pets, 
                    add_debt, change_debt, accept_reservation) values(\"$permission_id\", \"$id\",  \"$table[3]\",
                     \"$table[4]\", \"$table[5]\", \"$table[6]\", \"$table[7]\", \"$table[8]\", \"$table[9]\");";
        $save = $con->query($query);
        echo "Pomyślnie dodano do bazy danych";
    }catch(Exception $e){
        echo "Wystąpił błąd \"$e\"";
    }



}

//delete workers (admin)
function remove_workers($id){
    $query = "delete from WORKERS where worker_id = $id;";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//show list of workers
function show_workers(){
    $query = "select * from workers";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//worker can accept reservation of pet
/*function accept_reservation($table){
    if($table[1] === true)
    {
        $query = "update RESERVATION set accepted = \"$table[1]\", to_when = \"$table[2]\" where  reservation_id = $table[0];";
        $query = "select pet_id from RESERVATION where reservation_id = $table[0]";
        $pet_id = ;
        $query = "update PETS set state = \"reserved\" where pet_id = $pet_id;";
    } else
        $query = "update RESERVATION set accepted = \"$table[1]\" where  reservation_id = $table[0];";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}
*/
//show permission for worker
function show_permission($id){
    $query = "select * from PERMISSIONS where worker_id = $id";
    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//worker can add reservation for pet
function add_reservation($table){
    $id = check_id("RESERVATION");
    $query = "insert into RESERVATION (reservation_id, guest_id, pet_id, accepted, to_when)
                values \"$id\",\".$table[0]\", \"$table[1]\", \"$table[2]\", \"$table[2]\";";

    $query = "update PETS set state = \"reserved\" where pet_id = $table[1]";
    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//show reservation of pets
function show_reservation(){
    $query = "select * from RESERVATION order by accepted ";
    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }

}

//set permissions for workers (admin)
function set_permissions(){}

//USERS
/*************************************/
//edit your information like name, surname
function edit_data(){}

//eveey user without admin can look at personal information
/*function show_data($id){
    $query = "select type_of_account from LOGGING where logging_id = $id";
    $type_of_account = ;
    if($type_of_account === "worker")
         $query = "select * from WORKERS where logging_id = $id";
    else if($type_of_account === "guest")
        $query = "select * from GUEST where logging_id = $id";
    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}*/

// guest can add pet to favourite
function add_favourite($table){
    $id = check_id("FAVOURITES");
    $query = "insert into FAVOURITES (favourite_id, guest_id, pet_id)
                values \"$id\",\".$table[0]\", \"$table[1]\";";

    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//guest can look at pets, which he add to favourite
/*function show_favourite($id){
    $query = "select pet_id from FAVOURITES where guest_id = $id";
    $pet_id ;
    //wyświetlanie informacji z tabeli pets, additional, photos, adopted, dead
    $query = "select * from PETS where pet_id = $pet_id";
    $query = "select * from PHOTOS where pet_id = $pet_id";
    $query = "select * from ADDITIONAL where pet_id = $pet_id";
    $query = "select * from ADOPTED where pet_id = $pet_id";
    try {

    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}
*/
//guest can remove pet from favourite
function remove_favourite($id){
    $query = "delete from FVOURITES where favourite_id = $id;";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//guest can reserve pet that they want to adopt
function reserve_pet($table){
    $id = check_id("RESRVATION");
    $query = "insert into RESERVATION(reservation_id, guest_id, pet_id) 
              values $id, \"$table[0]\", \"$table[1]\"; ";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//show pets which are adopted
function show_adopted_pets(){
    $query = "select * from PETS where state = \"adopted\"";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

//show pets, which passed away
function show_passed_away_pets(){
    $query = "select * from PETS where state = \"adopted\"";
    try{
    }catch(Exception $e){
        echo "Wystąpił błąd: \"$e\"";
    }
}

