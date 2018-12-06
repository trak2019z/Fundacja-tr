<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181205120725 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE additional CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adopted CHANGE adoption_id adoption_id INT AUTO_INCREMENT NOT NULL, CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dead CHANGE death_id death_id INT AUTO_INCREMENT NOT NULL, CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE debt CHANGE debt_id debt_id INT AUTO_INCREMENT NOT NULL, CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favourites CHANGE favourite_id favourite_id INT AUTO_INCREMENT NOT NULL, CHANGE guest_id guest_id INT DEFAULT NULL, CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE guest CHANGE guest_id guest_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE logging CHANGE logging_id logging_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE permission_id permission_id INT AUTO_INCREMENT NOT NULL, CHANGE add_pets add_pets TINYINT(1) DEFAULT NULL, CHANGE edit_pets edit_pets TINYINT(1) DEFAULT NULL, CHANGE delete_pets delete_pets TINYINT(1) DEFAULT NULL, CHANGE move_pets move_pets TINYINT(1) DEFAULT NULL, CHANGE add_debt add_debt TINYINT(1) DEFAULT NULL, CHANGE change_debt change_debt TINYINT(1) DEFAULT NULL, CHANGE accept_reservation accept_reservation TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE pets CHANGE pet_id pet_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE photos CHANGE photo_id photo_id INT AUTO_INCREMENT NOT NULL, CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE reservation_id reservation_id INT AUTO_INCREMENT NOT NULL, CHANGE guest_id guest_id INT DEFAULT NULL, CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE workers ADD date_of_birth DATE NOT NULL, CHANGE worker_id worker_id INT AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE additional CHANGE pet_id pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE adopted CHANGE adoption_id adoption_id INT NOT NULL, CHANGE pet_id pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE dead CHANGE death_id death_id INT NOT NULL, CHANGE pet_id pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE debt CHANGE debt_id debt_id INT NOT NULL, CHANGE pet_id pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE favourites CHANGE favourite_id favourite_id INT NOT NULL, CHANGE pet_id pet_id INT NOT NULL, CHANGE guest_id guest_id INT NOT NULL');
        $this->addSql('ALTER TABLE guest CHANGE guest_id guest_id INT NOT NULL');
        $this->addSql('ALTER TABLE logging CHANGE logging_id logging_id INT NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE permission_id permission_id INT NOT NULL, CHANGE add_pets add_pets TINYINT(1) DEFAULT \'0\', CHANGE edit_pets edit_pets TINYINT(1) DEFAULT \'0\', CHANGE delete_pets delete_pets TINYINT(1) DEFAULT \'0\', CHANGE move_pets move_pets TINYINT(1) DEFAULT \'0\', CHANGE add_debt add_debt TINYINT(1) DEFAULT \'0\', CHANGE change_debt change_debt TINYINT(1) DEFAULT \'0\', CHANGE accept_reservation accept_reservation TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE pets CHANGE pet_id pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE photos CHANGE photo_id photo_id INT NOT NULL, CHANGE pet_id pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE reservation_id reservation_id INT NOT NULL, CHANGE pet_id pet_id INT NOT NULL, CHANGE guest_id guest_id INT NOT NULL');
        $this->addSql('ALTER TABLE workers DROP date_of_birth, CHANGE worker_id worker_id INT NOT NULL');
    }
}
