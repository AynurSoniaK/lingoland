<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630210857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_availibility (user_id INT NOT NULL, availibility_id INT NOT NULL, INDEX IDX_8C9490DAA76ED395 (user_id), INDEX IDX_8C9490DA72A0C492 (availibility_id), PRIMARY KEY(user_id, availibility_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_communication (user_id INT NOT NULL, communication_id INT NOT NULL, INDEX IDX_172118BBA76ED395 (user_id), INDEX IDX_172118BB1C2D1E0C (communication_id), PRIMARY KEY(user_id, communication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_hobbies (user_id INT NOT NULL, hobbies_id INT NOT NULL, INDEX IDX_60C72A17A76ED395 (user_id), INDEX IDX_60C72A17B2242D72 (hobbies_id), PRIMARY KEY(user_id, hobbies_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_language (user_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_345695B5A76ED395 (user_id), INDEX IDX_345695B582F1BAF4 (language_id), PRIMARY KEY(user_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_language_learned (user_id INT NOT NULL, language_learned_id INT NOT NULL, INDEX IDX_25E318ECA76ED395 (user_id), INDEX IDX_25E318EC5762A838 (language_learned_id), PRIMARY KEY(user_id, language_learned_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_availibility ADD CONSTRAINT FK_8C9490DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_availibility ADD CONSTRAINT FK_8C9490DA72A0C492 FOREIGN KEY (availibility_id) REFERENCES availibility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_communication ADD CONSTRAINT FK_172118BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_communication ADD CONSTRAINT FK_172118BB1C2D1E0C FOREIGN KEY (communication_id) REFERENCES communication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_hobbies ADD CONSTRAINT FK_60C72A17A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_hobbies ADD CONSTRAINT FK_60C72A17B2242D72 FOREIGN KEY (hobbies_id) REFERENCES hobbies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language_learned ADD CONSTRAINT FK_25E318ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language_learned ADD CONSTRAINT FK_25E318EC5762A838 FOREIGN KEY (language_learned_id) REFERENCES language_learned (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD frequency_id INT NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD age INT NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD introduction LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64994879022 FOREIGN KEY (frequency_id) REFERENCES frequency (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64994879022 ON user (frequency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_availibility');
        $this->addSql('DROP TABLE user_communication');
        $this->addSql('DROP TABLE user_hobbies');
        $this->addSql('DROP TABLE user_language');
        $this->addSql('DROP TABLE user_language_learned');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64994879022');
        $this->addSql('DROP INDEX IDX_8D93D64994879022 ON user');
        $this->addSql('ALTER TABLE user DROP frequency_id, DROP name, DROP age, DROP city, DROP created_at, DROP introduction');
    }
}
