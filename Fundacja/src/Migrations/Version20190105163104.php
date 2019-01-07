<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190105163104 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE my_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, type_of_account VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_4DB4FF1DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservations CHANGE guest_id guest_id INT DEFAULT NULL, CHANGE pet_id pet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos CHANGE pet_id pet_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE my_user');
        $this->addSql('ALTER TABLE photos CHANGE pet_id pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservations CHANGE pet_id pet_id INT NOT NULL, CHANGE guest_id guest_id INT NOT NULL');
    }
}
