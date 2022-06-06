<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            'DROP VIEW IF EXISTS `comments_report`;'
        );

        DB::unprepared(
            'CREATE VIEW `laravel`.`comments_report` AS
            SELECT 
                `laravel`.`sweetwater_test`.`orderid` AS `orderid`,
                `laravel`.`sweetwater_test`.`comments` AS `comments`,
                `laravel`.`sweetwater_test`.`shipdate_expected` AS `shipdate_expected`,
                (CASE
                    WHEN (LOWER(`laravel`.`sweetwater_test`.`comments`) REGEXP \'(candy|smarties|bit o honey)\') THEN \'candy\'
                    WHEN (LOWER(`laravel`.`sweetwater_test`.`comments`) REGEXP \'(call|contact me)\') THEN \'callme\'
                    WHEN (LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%refer%\') THEN \'referral\'
                    WHEN
                        ((LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%sign%\')
                            OR (LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%signature%\'))
                    THEN
                        \'signature\'
                    ELSE \'misc\'
                END) AS `comment_type`
            FROM
                `laravel`.`sweetwater_test`'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared(
            'DROP VIEW IF EXISTS `comments_report`;'
        );

                DB::unprepared(
            'CREATE VIEW `laravel`.`comments_report` AS
            SELECT 
                `laravel`.`sweetwater_test`.`orderid` AS `orderid`,
                `laravel`.`sweetwater_test`.`comments` AS `comments`,
                `laravel`.`sweetwater_test`.`shipdate_expected` AS `shipdate_expected`,
                (CASE
                    WHEN (LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%candy%\') THEN \'candy\'
                    WHEN (LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%call me%\') THEN \'callme\'
                    WHEN (LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%refer%\') THEN \'referral\'
                    WHEN
                        ((LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%sign%\')
                            OR (LOWER(`laravel`.`sweetwater_test`.`comments`) LIKE \'%signature%\'))
                    THEN
                        \'signature\'
                    ELSE \'misc\'
                END) AS `comment_type`
            FROM
                `laravel`.`sweetwater_test`'
        );
    }
};
