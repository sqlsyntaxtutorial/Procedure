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
        
        CREATE PROCEDURE IF NOT EXISTS addColumnIfNotExists(
        tb_name VARCHAR(255),
        column_name VARCHAR(255))
        BEGIN
        DECLARE columnCount INT;
         SET @query = CONCAT(
       'SELECT COUNT(*) INTO @columnCount FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = \'',tb_name,'\' AND ------`table_schema` = DATABASE() AND `COLUMN_NAME` = \'', column_name, '\';');
          PREPARE stmt FROM @query;
          EXECUTE stmt;
          DEALLOCATE PREPARE stmt;
      
        --    SET @resultQuery=CONCAT('SELECT * FROM INFORMATION_SCHEMA.COLUMNS
        --     	WHERE `table_name` = \'intlpakabouts\'
        --      AND `table_schema` = DATABASE()
        --        AND `COLUMN_NAME` = \'',column_name,'\';');
        --     PREPARE stmt FROM @resultQuery;
        --      EXECUTE stmt;
        --      DEALLOCATE PREPARE stmt;
        
        --   If the column does not exist, add it to the table
       IF @columnCount = 0 THEN
              SET @alterQuery = CONCAT(
                  'ALTER TABLE `',tb_name,'` ADD `', column_name, '` VARCHAR(191) NULL DEFAULT NULL;'
              );
              PREPARE alterStmt FROM  @alterQuery;
              EXECUTE alterStmt;
              DEALLOCATE PREPARE alterStmt;
        END IF;
      
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
