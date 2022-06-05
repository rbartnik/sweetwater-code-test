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
        // This stored procedure examines the table of comments for records that have no expected ship date set,
        // and has a comment with Expected Ship Date: MM/DD/YY present in it.
        // All such records will have their shipdate_expected column updated with the extracted date.
        // This will only happen once per comment, as this sproc is intended to run every time the report is loaded
        // to catch new records. Changing this to execute on all comments every time the report is loaded is as
        // simple as removing the first where clause, on shipdate_expected = zero date.
        //
        // The first block of sets is to deal with a config issue in MySQL where zero dates are not allowed by default.
        // Instead of making a global change on the DB, it temporarily disables the zero date settings for the current
        // session. Without this, the procedure errors out saying the where clause is invalid.
        DB::unprepared(
            'CREATE PROCEDURE `update_comments_shipdate_expected`()
            BEGIN
                -- save current setting of sql_mode
                SET @old_sql_mode := @@sql_mode ;
            
                -- derive a new value by removing NO_ZERO_DATE and NO_ZERO_IN_DATE
                SET @new_sql_mode := @old_sql_mode ;
                SET @new_sql_mode := TRIM(BOTH \',\' FROM REPLACE(CONCAT(\',\',@new_sql_mode,\',\'),\',NO_ZERO_DATE,\'  ,\',\'));
                SET @new_sql_mode := TRIM(BOTH \',\' FROM REPLACE(CONCAT(\',\',@new_sql_mode,\',\'),\',NO_ZERO_IN_DATE,\',\',\'));
                SET @@sql_mode := @new_sql_mode ;
                
                update laravel.sweetwater_test
                set shipdate_expected = STR_TO_DATE(substring(substring_index(LOWER(comments), \'expected ship date: \', -1), 1, 8), \'%m/%d/%y\')
                where shipdate_expected = \'0000-00-00 00:00:00\'
                and LOWER(comments) like \'%expected ship date%\';
            END'
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
            'DROP PROCEDURE `laravel`.`update_comments_shipdate_expected`;'
        );
    }
};
