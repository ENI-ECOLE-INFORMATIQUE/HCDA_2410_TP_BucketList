<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709083127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) 
    NOT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), PRIMARY KEY(id)) 
    DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO category (name) VALUES (\'Travel & Adventure\')');
        $this->addSql('INSERT INTO category (name) VALUES (\'Sports\')');
        $this->addSql('INSERT INTO category (name) VALUES (\'Entertainment\')');
        $this->addSql('INSERT INTO category (name) VALUES (\'Human Relations\')');
        $this->addSql('INSERT INTO category (name) VALUES (\'Others\')');
        $this->addSql('ALTER TABLE wish ADD category_id INT NOT NULL');
        $this->addSql('UPDATE wish SET category_id = 1');
        $this->addSql('ALTER TABLE wish ADD CONSTRAINT FK_D7D174C912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D7D174C912469DE2 ON wish (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C912469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_D7D174C912469DE2 ON wish');
        $this->addSql('ALTER TABLE wish DROP category_id');
    }
}
