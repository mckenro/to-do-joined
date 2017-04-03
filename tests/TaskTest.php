<?php

  /**
  * @backupGlobals disabled
  * @backupStaticAttributes disabled
  */

  require_once "src/Task.php";

  $server = 'mysql:host=localhost:8889;dbname=to_do_test';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server, $username, $password);

  class TaskTest extends PHPUnit_Framework_TestCase
  {

    protected function tearDown()
    {
      Task::deleteAll();
      Category::deleteAll();
    }

    function test_getDescription()
    {
      //Arrange
      $description = "Do dishes.";
      $test_task = new Task($description);

      //Act
      $result = $test_task->getDescription();

      //Assert
      $this->assertEquals($description, $result);
    }

    function test_setDescription()
    {
      //Arrange
      $description = "Do dishes.";
      $test_task = new Task($description);

      //Act
      $test_task->setDescription("Drink coffee.");
      $result = $test_task->getDescription();

      //Assert
      $this->assertEquals("Drink coffee.", $result);
    }

    function test_deleteAll()
    {
        $description = "Wash Dog";
        $description2 = "Water Lawn";
        $test_task = new Task($description);
        $test_task->save();

        $test_task2 = new Task($description2);
        $test_task2->save();

        Task::deleteAll();

        $result = Task::getAll();
        $this->assertEquals([], $result);
    }

    function test_save()
    {
        //Arrange
        $description = "Eat breakfast";
        $test_task = new Task($description);

        //Act
        $executed = $test_task->save();

        //Assert
        $this->assertTrue($executed, "Task not successfully saved to database");
    }

    function test_getId(){
      $description = "Wash Dog";
      $test_Task = new Task($description);
      $test_Task->save();

      $result = $test_Task->getId();

      $this->assertTrue(is_numeric($result));
    }

    function test_getAll()
    {
      //Arrange
      $description = "Wash the dog";
      $test_task = new Task($description);
      $test_task->save();

      $description2 = "Water the lawn";
      $test_task2 = new Task($description2);
      $test_task2->save();

      //Act
      $result = Task::getAll();

      //Assert
      $this->assertEquals([$test_task, $test_task2], $result);
    }

    function test_find(){
      $description = "Wash Dog";
      $description2 = "Water Lawn";
      $test_task = new Task($description);
      $test_task->save();

      $test_task2 = new Task($description2);
      $test_task2->save();

      $result = Task::find($test_task->getId());

      $this->assertEquals($test_task, $result);
    }

    function test_deleteTask()
    {
      //Arrange
      $description = "Wask the dog";
      $test_task = new Task($description);
      $test_task->save();

      $description2 = "Water the lawn";
      $test_task2 = new Task($description2);
      $test_task2->save();

      //Act
      $test_task->delete();

      //Assert
      $this->assertEquals([$test_task2], Task::getAll());
    }

    function test_addCategory()
    {
      //Arrange
      $name = "Work stuff";
      $test_Category = new Category($name);
      $test_Category->save();

      $description = "File reports";
      $test_task = new Task($description);
      $test_task->save();

      //Act
      $test_task->addCategory($test_Category);

      //Assert
      $this->assertEquals($test_task->getCategories(), [$test_Category]);
      }

      function test_getCategories()
      {
        //Arrange
        $name = "Work stuff";
        $test_category = new Category($name);
        $test_category->save();

        $name2 = "Volunteer stuff";
        $test_Category2 = new Category($name2);
        $test_Category2->save();

        $description = "File reports";
        $test_task = new Task($description);
        $test_task->save();

        //Act
        $test_task->addCategory($test_category);
        $test_task->addCategory($test_Category2);


        //Assert
        $this->assertEquals($test_task->getCategories(), [$test_category, $test_Category2]);
      }

}
 ?>
