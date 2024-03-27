<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322141741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, is_salary TINYINT(1) DEFAULT NULL, INDEX IDX_73F2F77BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE month (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, balance DOUBLE PRECISION DEFAULT NULL, total_amount_spent DOUBLE PRECISION DEFAULT NULL, total_amount_earned DOUBLE PRECISION DEFAULT NULL, date DATE NOT NULL, is_locked TINYINT(1) DEFAULT NULL, INDEX IDX_8EB61006A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE month_budget (month_id INT NOT NULL, budget_id INT NOT NULL, INDEX IDX_78C61BE5A0CBDE4 (month_id), INDEX IDX_78C61BE536ABA6B8 (budget_id), PRIMARY KEY(month_id, budget_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, budget_category_id INT DEFAULT NULL, month_id INT DEFAULT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_723705D1C54C8C93 (type_id), INDEX IDX_723705D1644CDBBD (budget_category_id), INDEX IDX_723705D1A0CBDE4 (month_id), INDEX IDX_723705D1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, associated_number SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE month ADD CONSTRAINT FK_8EB61006A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE month_budget ADD CONSTRAINT FK_78C61BE5A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE month_budget ADD CONSTRAINT FK_78C61BE536ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1C54C8C93 FOREIGN KEY (type_id) REFERENCES transaction_type (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1644CDBBD FOREIGN KEY (budget_category_id) REFERENCES budget (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE budget DROP FOREIGN KEY FK_73F2F77BA76ED395');
        $this->addSql('ALTER TABLE month DROP FOREIGN KEY FK_8EB61006A76ED395');
        $this->addSql('ALTER TABLE month_budget DROP FOREIGN KEY FK_78C61BE5A0CBDE4');
        $this->addSql('ALTER TABLE month_budget DROP FOREIGN KEY FK_78C61BE536ABA6B8');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1C54C8C93');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1644CDBBD');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A0CBDE4');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP TABLE month');
        $this->addSql('DROP TABLE month_budget');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
