# adding a procedure in laravel migration

```
<?php
use Illuminate\Database\Migrations\Migration;

class CreateStringSplit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure="
        DROP TEMPORARY TABLE IF EXISTS temp_table;
        DELIMITER //
      
        CREATE PROCEDURE splitColumnToTable(
          in_table_name VARCHAR(255),
          in_column_name VARCHAR(255),
          in_delimiter CHAR(1)
        )
        BEGIN
          SET @sql = CONCAT(
            'CREATE TEMPORARY TABLE temp_table (value LongText NULL); ',
            'INSERT INTO temp_table (value) ',
            'SELECT ',
            in_column_name,
            ' FROM ',
            in_table_name
          );
          
          SET @sql = CONCAT(
            'SELECT value ',
            'FROM temp_table ',
            'WHERE value = ',
            QUOTE(CONCAT('%', in_delimiter, '%'))
          );
          
          PREPARE stmt FROM @sql;
          EXECUTE stmt;
          DEALLOCATE PREPARE stmt;
        END //
        
        DELIMITER ;
        

     
        ";

  
        \DB::unprepared($procedure);
    }
  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::unprepared("DROP PROCEDURE IF EXISTS `splitColumnToTable`");
    }
}

```
the work directory is as follow 

You can use procedure like a function

if you want to use procedure that has been built
![Alt text](image.png)

use call method

call splitColumnToTable()