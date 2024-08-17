<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240817083008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_report ADD date DATE, CHANGE state_id state_id INT NOT NULL, CHANGE adminComment admin_comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE expense_report ADD CONSTRAINT FK_280A6915D83CC1 FOREIGN KEY (state_id) REFERENCES expense_state (id)');
        $this->addSql('CREATE INDEX IDX_280A6915D83CC1 ON expense_report (state_id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4866A70FE35 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
        $this->addSql('CREATE INDEX IDX_1B80E4866A70FE35 ON vehicle (fuel_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_report DROP FOREIGN KEY FK_280A6915D83CC1');
        $this->addSql('DROP INDEX IDX_280A6915D83CC1 ON expense_report');
        $this->addSql('ALTER TABLE expense_report DROP date, CHANGE state_id state_id INT DEFAULT 1 NOT NULL, CHANGE admin_comment adminComment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4866A70FE35');
        $this->addSql('DROP INDEX IDX_1B80E4866A70FE35 ON vehicle');
    }
}
