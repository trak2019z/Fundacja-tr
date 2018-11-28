<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 15.11.2018
 * Time: 12:36
 */
?>
<html>
<body>
    <form>
        <table>
            <tr>
                <td>
                    <label>IMIĘ</label>
                </td>
                <td>
                    <input style="width:300px" name="name_of_guest" required="required">
                </td>
            </tr>
            <tr>
                <td>
                    <label>NAZWISKO</label>
                </td>
                <td>
                    <input style="width:300px" name="surname_of_guest" required="required">
                </td>
            </tr>
            <tr>
                <td>
                    <label>E-MAIL</label>
                </td>
                <td>
                    <input type="email" style="width:300px" name="email_of_guest" required="required">
                </td>
            </tr>
            <tr>
                <td>
                    <label>HASŁO</label>
                </td>
                <td>
                    <input type="password" style="width:300px" name="password_of_guest" required="required">
                </td>
            </tr>
            <tr>
                <td>
                    <label>HASŁO POWTÓRZONE</label>
                </td>
                <td>
                    <input type="password" style="width:300px" name="password2_of_guest" required="required">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="register_button" value="ZAREJESTRUJ">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
