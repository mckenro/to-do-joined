<?php
  /**
  * @backupGlobals disabled
  * @backupStaticAttributes disabled
  */

  require_once "src/Category.php";

  $server = 'mysql:host=localhost:8889;dbname=to_do_test';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server, $username, $password);

  class CategoryTest extends PHPUnit_Framework_TestCase
  {
    protected function tearDown()
    {
      Category::deleteAll();
    }
    function test_getName()
    {
      $name = "Work Stuff";
      $test_Category = new Category($name);
      $result = $test_Category->getName();
      $this->assertEquals($name,$result);
    }
    function test_save()
    {
      $name = "Work Stuff";
      $test_Category = new Category($name);
      $test_Category->save();

      $executed = $test_Category->save();

      $this->assertTrue($executed, "Category not saved to database");
    }

    function test_update()
    {
      //Arrange
      $name = "Work stuff";
      $test_Category = new Category($name);
      $test_Category->save();

      $new_name = "Home stuff";

      //Act
      $test_Category->update($new_name);

      //Assert
      $this->assertEquals("Home stuff", $test_Category->getName());
    }

    function testDeleteCategory()
    {
      //Arrange
      $name = "Work stuff";
      $test_Category = new Category($name);
      $test_Category->save();

      $name2 = "Home stuff";
      $test_Category2 = new Category($name2);
      $test_Category2->save();

      //Act
      $test_Category->delete();

      //Assert
      $this->assertEquals([$test_Category2], Category::getAll());
    }

    function test_getID(){
      $name = "Work Stuff";
      $test_Category = new Category($name);
      $test_Category->save();
      $result = $test_Category->getId();
      $this->assertEquals(true, is_numeric($result));
    }

    function test_getAll()
    {
      $name = "Work Stuff";
      $name2 = "Home Stuff";
      $test_Category= new Category($name);
      $test_Category->save();
      $test_Category2 = new Category($name2);
      $test_Category2->save();
      $result = Category::getAll();
      $this->assertEquals([$test_Category, $test_Category2], $result);
    }
    function test_deleteAll()
    {
      $name = "Wash Dog";
      $name2 = "Home Stuff";
      $test_Category = new Category($name);
      $test_Category->save();
      $test_Category2 = new Category($name2);
      $test_Category->save();
      Category::deleteAll();
      $result = Category::getAll();
      $this->assertEquals([], $result);
    }
    function test_find()
    {
      $name = "Wash Dog";
      $name2 = "Home Stuff";
      $test_Category = new Category($name);
      $test_Category->save();
      $test_Category2 = new Category($name2);
      $test_Category->save();
      $result = Category::find($test_Category->getId());
      $this->assertEquals($test_Category, $result);
    }

    function test_addTask()
    {
      //Arrange
      $name = "Work stuff";
      $test_Category = new Category($name);
      $test_Category->save();

      $description = "File reports";
      $test_task = new Task($description);
      $test_task->save();

      //Act
      $test_Category->addTask($test_task);

      //Assert
      $this->assertEquals($test_Category->getTasks(), [$test_task]);
    }

    function test_getTasks()
    {
      //Arrange
      $name = "Home stuff";
      $test_Category = new Category($name);
      $test_Category->save();

      $description = "Wash the dog";
      $test_task = new Task($description);
      $test_task->save();

      $description2 = "Take out the trash";
      $test_task2 = new Task($description2);
      $test_task2->save();

      //Act
      $test_Category->addTask($test_task);
      $test_Category->addTask($test_task2);

      //Assert
      $this->assertEquals($test_Category->getTasks(), [$test_task, $test_task2]);
    }
  }

 ?>
