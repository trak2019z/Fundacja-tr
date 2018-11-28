<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 10.11.2018
 * Time: 12:02
 */
?>

<html>
<head><meta charset="utf-8"></head>
<body>
<form action="../dodanie_test.php" method="POST">
    <table>
        <tr>
            <td><label> IMIĘ </label><br/><br/></td>
            <td><input style="width:300px" name="name_of_worker" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> NAZWISKO </label><br/><br/></td>
            <td><input style="width:300px" name="surname_of_worker" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> NUMER PRACOWNIKA </label><br/><br/></td>
            <td><input style="width:300px" name="id_of_worker"><br/><br/></td>
        </tr>
        <tr>
            <td><label> DATA URODZENIA: </label><br/><br/></td>
            <td><input style="width:300px" name="date_of_birth_of_worker" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> EMAIL: </label><br/><br/></td>
            <td><input style="width:300px" name="email_of_worker" type="email" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> HASŁO: </label><br/><br/></td>
            <td><input style="width:300px" name="password_of_worker" type="password" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> HASŁO POWTÓRZONE: </label><br/><br/></td>
            <td><input style="width:300px" name="password2_of_worker" type="password" required="required"><br/><br/></td>
        </tr>
        <tr>
            <td><label> TYP KONTA </label><br/><br/></td>
            <td>
                <select name="type_of_account_of_worker">
                    <option>ADMINISTRATOR</option>
                    <option>GOŚĆ</option>
                    <option>PRACOWNIK</option>
                </select><br/><br/>
            </td>
        </tr>
        <tr>
            <td><label> STANOWISKO </label><br/><br/></td>
            <td>
                <select name="position_of_worker">
                    <option>KIEROWNIK</option>
                    <option>ZASTĘPCA KIEROWNIKA</option>
                    <option>STARSZY PRACOWNIK</option>
                    <option>PRACOWNIK</option>
                    <option>STAŻYSTA</option>
                    <option>WOLONTARIUSZ</option>
                </select><br/><br/>
            </td>
        </tr>
        <tr>
            <td><label> UPRAWNIENIA: </label></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="checkbox" name = "add_pets">
                <label>DODAWANIE PUPILI</label>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="checkbox" name = "edit_pets">
                <label>EDYTOWANIE PUPILI</label>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="checkbox" name = "delete_pets">
                <label>USUWANIE PUPILI</label>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="checkbox" name = "move_pets">
                <label>PRZENOSZENIE PUPILI (ADOPTOWANE/ODESZŁY)</label>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="checkbox" name = "add_debt">
                <label>DODAWANIE DŁUGÓW PUPILI</label>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="checkbox" name = "change_debt">
                <label>UZUPEŁNIANIE/ZMIANA DŁUGÓW PUPILI</label>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="checkbox" name = "accept_reservation">
                <label>AKCEPTACJA REZERWACJI PUPILI</label>
            </td>
        </tr>
        <tr>
            <td><br/><br/><td><input type="submit" id="button" name="add_worker_button" value="DODAJ PRACOWNIKA"></td></td>
        </tr>
    </table>
</form>
</body>
</html>
