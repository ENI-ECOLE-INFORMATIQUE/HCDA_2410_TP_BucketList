<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710073021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wish ADD user_id INT NOT NULL, DROP author');
        $this->addSql('ALTER TABLE wish ADD CONSTRAINT FK_D7D174C912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE wish ADD CONSTRAINT FK_D7D174C9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D7D174C912469DE2 ON wish (category_id)');
        $this->addSql('CREATE INDEX IDX_D7D174C9A76ED395 ON wish (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C9A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C912469DE2');
        $this->addSql('DROP INDEX IDX_D7D174C912469DE2 ON wish');
        $this->addSql('DROP INDEX IDX_D7D174C9A76ED395 ON wish');
        $this->addSql('ALTER TABLE wish ADD author VARCHAR(50) NOT NULL, DROP user_id');
    }
}
