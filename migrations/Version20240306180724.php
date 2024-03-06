<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306180724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F6353B48');
        $this->addSql('DROP INDEX IDX_A5BA3A8F6353B48 ON proposals');
        $this->addSql('ALTER TABLE proposals CHANGE id_question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_A5BA3A8F1E27F6BF ON proposals (question_id)');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D542D24CCB');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D59D99812');
        $this->addSql('DROP INDEX IDX_8ADC54D59D99812 ON questions');
        $this->addSql('DROP INDEX IDX_8ADC54D542D24CCB ON questions');
        $this->addSql('ALTER TABLE questions ADD theme_id INT NOT NULL, ADD difficulty_id INT NOT NULL, DROP id_theme_id, DROP id_difficulty_id');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D559027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulties (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D559027487 ON questions (theme_id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D5FCFA9DAE ON questions (difficulty_id)');
        $this->addSql('ALTER TABLE themes RENAME INDEX idx_154232def05788e9 TO IDX_154232DE61220EA6');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D559027487');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5FCFA9DAE');
        $this->addSql('DROP INDEX IDX_8ADC54D559027487 ON questions');
        $this->addSql('DROP INDEX IDX_8ADC54D5FCFA9DAE ON questions');
        $this->addSql('ALTER TABLE questions ADD id_theme_id INT NOT NULL, ADD id_difficulty_id INT NOT NULL, DROP theme_id, DROP difficulty_id');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D542D24CCB FOREIGN KEY (id_difficulty_id) REFERENCES difficulties (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D59D99812 FOREIGN KEY (id_theme_id) REFERENCES themes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8ADC54D59D99812 ON questions (id_theme_id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D542D24CCB ON questions (id_difficulty_id)');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F1E27F6BF');
        $this->addSql('DROP INDEX IDX_A5BA3A8F1E27F6BF ON proposals');
        $this->addSql('ALTER TABLE proposals CHANGE question_id id_question_id INT NOT NULL');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F6353B48 FOREIGN KEY (id_question_id) REFERENCES questions (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A5BA3A8F6353B48 ON proposals (id_question_id)');
        $this->addSql('ALTER TABLE themes RENAME INDEX idx_154232de61220ea6 TO IDX_154232DEF05788E9');
    }
}
