<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224124250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE difficulties (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8892266A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposals (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, proposal VARCHAR(255) NOT NULL, INDEX IDX_A5BA3A8F1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, theme_id INT NOT NULL, difficulty_id INT NOT NULL, number INT NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, anecdote VARCHAR(255) NOT NULL, INDEX IDX_8ADC54D559027487 (theme_id), INDEX IDX_8ADC54D5FCFA9DAE (difficulty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE themes (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, difficulty INT NOT NULL, statut VARCHAR(50) NOT NULL, picture VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_154232DE5E237E06 (name), INDEX IDX_154232DE61220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D559027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulties (id)');
        $this->addSql('ALTER TABLE themes ADD CONSTRAINT FK_154232DE61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F1E27F6BF');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D559027487');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5FCFA9DAE');
        $this->addSql('ALTER TABLE themes DROP FOREIGN KEY FK_154232DE61220EA6');
        $this->addSql('DROP TABLE difficulties');
        $this->addSql('DROP TABLE proposals');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE themes');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
