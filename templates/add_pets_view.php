<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 10.11.2018
 * Time: 11:10
 */
?>

<html>
<body>
<form action="" method="POST">
    <table>
        <tr>
            <td><label> IMIĘ </label><br/><br/></td>
            <td><input style="width:300px" name="name_of_pet" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> NUMER </label><br/><br/></td>
            <td><input style="width:300px" name="number_of_pet" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> ZDJĘCIE GŁÓWNE </label><br/><br/></td>
            <td><input type='file' name="main_photo_of_pet"><br/><br/></td>
        </tr>
        <tr>
            <td><label> DATA URODZENIA: </label><br/><br/></td>
            <td><input style="width:300px" name="data_of_birth_of_pet" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> OPIS </label><br/><br/></td>
            <td><textarea rows="10" cols="40" name="description_of_pet" required="required"></textarea><br/><br/></td>
        </tr>
        <tr>
            <td><label> STAN </label><br/><br/></td>
            <td>
                <select name="state_of_pet">
                    <option>DOSTĘPNY</option>
                    <option>ZAREZERWOWANY</option>
                    <option>ADOPTOWANY</option>
                    <option>ODSZEDŁ</option>
                </select><br/><br/>
            </td>
        </tr>
        <tr>
            <td><td><input type="submit" id="button" name="add_pet_button" value="DODAJ PUPILA"></td></td>
        </tr>
    </table>
</form>
</body>
</html>
