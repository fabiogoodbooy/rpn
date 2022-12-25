<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221224221408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE value (id INT AUTO_INCREMENT NOT NULL, stack_id INT NOT NULL, value INT NOT NULL, INDEX IDX_1D77583437C70060 (stack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE value ADD CONSTRAINT FK_1D77583437C70060 FOREIGN KEY (stack_id) REFERENCES stack (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE value DROP FOREIGN KEY FK_1D77583437C70060');
        $this->addSql('DROP TABLE value');
    }
}
