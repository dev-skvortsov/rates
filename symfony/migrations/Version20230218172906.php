<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230218172906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table to store rates';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE rates (
                code VARCHAR( 3 ) NOT NULL COMMENT 'Currency code',
                date DATE NOT NULL COMMENT 'Rate received date',
                trading_date DATE NOT NULL COMMENT 'Trading date (can be equal \"to received date\" or not)',
                value DECIMAL ( 10, 4 ) UNSIGNED NOT NULL COMMENT 'Rate value',
                nominal INT ( 10 ) UNSIGNED NOT NULL COMMENT 'Rate nominal',
                base_code VARCHAR( 3 ) NOT NULL DEFAULT 'RUR' COMMENT 'Currency code',
                PRIMARY KEY (date, code),
                INDEX (trading_date)
            ) COMMENT 'Table to store rates'
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE rates');
    }
}
