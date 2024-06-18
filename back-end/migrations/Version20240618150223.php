<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240618150223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expense_report (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_280A6919D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fuel_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, fuel_type_id_id INT NOT NULL, user_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, fiscal_horses INT NOT NULL, ratio_per20000 DOUBLE PRECISION NOT NULL, INDEX IDX_1B80E4869EDB5F2E (fuel_type_id_id), INDEX IDX_1B80E4869D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expense_report ADD CONSTRAINT FK_280A6919D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4869EDB5F2E FOREIGN KEY (fuel_type_id_id) REFERENCES fuel_type (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4869D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_report DROP FOREIGN KEY FK_280A6919D86650F');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4869EDB5F2E');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4869D86650F');
        $this->addSql('DROP TABLE expense_report');
        $this->addSql('DROP TABLE expense_type');
        $this->addSql('DROP TABLE fuel_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vehicle');
    }
}
