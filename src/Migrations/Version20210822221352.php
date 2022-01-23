<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210822221352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'initial migrations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gamelist (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(300) NOT NULL, os VARCHAR(30) NOT NULL COMMENT \'(DC2Type:gamelist_os)\', purchase_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', cost DOUBLE PRECISION NOT NULL, exchange_rate DOUBLE PRECISION NOT NULL, notes LONGTEXT DEFAULT NULL, format VARCHAR(30) NOT NULL COMMENT \'(DC2Type:gamelist_format)\', deleted TINYINT(1) NOT NULL, owned TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hru (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(300) NOT NULL, prefix VARCHAR(300) NOT NULL, entity_id INT NOT NULL, UNIQUE INDEX prefix_value (prefix, value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, section_id INT NOT NULL, author_id INT NOT NULL, hru_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(30) NOT NULL COMMENT \'(DC2Type:post_status)\', teaser TEXT DEFAULT NULL, INDEX IDX_885DBAFAD823E37A (section_id), INDEX IDX_885DBAFAF675F31B (author_id), INDEX IDX_885DBAFA1396D3B7 (hru_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_tags (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_D5ECAD9F4B89032C (post_id), INDEX IDX_D5ECAD9FBAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sections (id INT AUTO_INCREMENT NOT NULL, machine_name VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, enabled TINYINT(1) NOT NULL, hidden TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_6FBC94265E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAD823E37A FOREIGN KEY (section_id) REFERENCES sections (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA1396D3B7 FOREIGN KEY (hru_id) REFERENCES hru (id)');
        $this->addSql('ALTER TABLE posts_tags ADD CONSTRAINT FK_D5ECAD9F4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE posts_tags ADD CONSTRAINT FK_D5ECAD9FBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA1396D3B7');
        $this->addSql('ALTER TABLE posts_tags DROP FOREIGN KEY FK_D5ECAD9F4B89032C');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAD823E37A');
        $this->addSql('ALTER TABLE posts_tags DROP FOREIGN KEY FK_D5ECAD9FBAD26311');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAF675F31B');
        $this->addSql('DROP TABLE gamelist');
        $this->addSql('DROP TABLE hru');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE posts_tags');
        $this->addSql('DROP TABLE sections');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
    }
}
