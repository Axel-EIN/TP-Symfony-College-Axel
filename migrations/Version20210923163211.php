<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923163211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe ADD prof_principal_id INT NOT NULL');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF968553BF90 FOREIGN KEY (prof_principal_id) REFERENCES prof (id)');
        $this->addSql('CREATE INDEX IDX_8F87BF968553BF90 ON classe (prof_principal_id)');
        $this->addSql('ALTER TABLE eleve ADD classe_id INT NOT NULL');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F78F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_ECA105F78F5EA509 ON eleve (classe_id)');
        $this->addSql('ALTER TABLE note ADD matiere_id INT NOT NULL, ADD eleve_id INT NOT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14F46CD258 ON note (matiere_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A6CC7B2 ON note (eleve_id)');
        $this->addSql('ALTER TABLE prof ADD matiere_id INT NOT NULL');
        $this->addSql('ALTER TABLE prof ADD CONSTRAINT FK_5BBA70BBF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE INDEX IDX_5BBA70BBF46CD258 ON prof (matiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF968553BF90');
        $this->addSql('DROP INDEX IDX_8F87BF968553BF90 ON classe');
        $this->addSql('ALTER TABLE classe DROP prof_principal_id');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F78F5EA509');
        $this->addSql('DROP INDEX IDX_ECA105F78F5EA509 ON eleve');
        $this->addSql('ALTER TABLE eleve DROP classe_id');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14F46CD258');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A6CC7B2');
        $this->addSql('DROP INDEX IDX_CFBDFA14F46CD258 ON note');
        $this->addSql('DROP INDEX IDX_CFBDFA14A6CC7B2 ON note');
        $this->addSql('ALTER TABLE note DROP matiere_id, DROP eleve_id');
        $this->addSql('ALTER TABLE prof DROP FOREIGN KEY FK_5BBA70BBF46CD258');
        $this->addSql('DROP INDEX IDX_5BBA70BBF46CD258 ON prof');
        $this->addSql('ALTER TABLE prof DROP matiere_id');
    }
}
