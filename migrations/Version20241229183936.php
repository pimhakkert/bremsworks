<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229183936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE isotope (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, shorthand VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE isotope_product (isotope_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(isotope_id, product_id))');
        $this->addSql('CREATE INDEX IDX_9AEF6B218332B8A0 ON isotope_product (isotope_id)');
        $this->addSql('CREATE INDEX IDX_9AEF6B214584665A ON isotope_product (product_id)');
        $this->addSql('CREATE TABLE product (id SERIAL NOT NULL, reserved_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, quantity INT NOT NULL, price INT NOT NULL, category VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, depth INT NOT NULL, weight INT NOT NULL, color VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, condition VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADBCDB4AF4 ON product (reserved_by_id)');
        $this->addSql('ALTER TABLE isotope_product ADD CONSTRAINT FK_9AEF6B218332B8A0 FOREIGN KEY (isotope_id) REFERENCES isotope (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE isotope_product ADD CONSTRAINT FK_9AEF6B214584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBCDB4AF4 FOREIGN KEY (reserved_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE isotope_product DROP CONSTRAINT FK_9AEF6B218332B8A0');
        $this->addSql('ALTER TABLE isotope_product DROP CONSTRAINT FK_9AEF6B214584665A');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADBCDB4AF4');
        $this->addSql('DROP TABLE isotope');
        $this->addSql('DROP TABLE isotope_product');
        $this->addSql('DROP TABLE product');
    }
}
