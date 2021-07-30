<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210703104316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE language_learned ADD level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE language_learned ADD CONSTRAINT FK_58F65A315FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('CREATE INDEX IDX_58F65A315FB14BA7 ON language_learned (level_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE language_learned DROP FOREIGN KEY FK_58F65A315FB14BA7');
        $this->addSql('DROP INDEX IDX_58F65A315FB14BA7 ON language_learned');
        $this->addSql('ALTER TABLE language_learned DROP level_id');
    }
}
