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


# sql procedure

 **剛剛的做法是在laravel 建立 procedure的方法**

 **現在介紹的是sql 如何使用procedure， 也就是說在laravel建立完procedure後 的用法**



#### DELIMITER 改變預設命令列 statement結語

``delimiter``命令指定了``mysql``直譯器命令列的結束符,預設為``;``

說白了就是告知命令到哪兒結束,可以執行此命令了

 

但一般在儲存過程中會有多個分號,我們並不希望一遇到分號就執行命令,因此可以用delimiter命令指定其他結束符來代替``;``

這個結束符可以自己定義,常用的是``//`` 和 ``$$``
