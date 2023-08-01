<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createProcedure="
        CREATE PROCEDURE IF NOT EXISTS addColumnToAllTable(
        column_name VARCHAR(255))
        BEGIN
            DECLARE var_table_name VARCHAR(255);


            DECLARE result CURSOR FOR
            SELECT DISTINCT `table_name`
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE `table_schema` = DATABASE();

            -- SET column_name variable to the desired value

            OPEN result;
            result_loop:LOOP
                FETCH result INTO var_table_name;

                -- Check for the end of the cursor
                IF (var_table_name IS NULL) THEN
                    LEAVE result_loop;
                END IF;

                -- Call the procedure using the variables as parameters
                CALL addColumnIfNotExists(var_table_name, column_name);

            END LOOP;
            CLOSE result;
        END
        

     
        ";
        DB::unprepared($createProcedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS `addColumnToAllTable`");
    }
};
