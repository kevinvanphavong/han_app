<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106135213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE month_budget (month_id INT NOT NULL, budget_id INT NOT NULL, INDEX IDX_78C61BE5A0CBDE4 (month_id), INDEX IDX_78C61BE536ABA6B8 (budget_id), PRIMARY KEY(month_id, budget_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE month_budget ADD CONSTRAINT FK_78C61BE5A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE month_budget ADD CONSTRAINT FK_78C61BE536ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP created_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE month_budget DROP FOREIGN KEY FK_78C61BE5A0CBDE4');
        $this->addSql('ALTER TABLE month_budget DROP FOREIGN KEY FK_78C61BE536ABA6B8');
        $this->addSql('DROP TABLE month_budget');
        $this->addSql('ALTER TABLE user ADD created_at DATE DEFAULT NULL');
    }
}
