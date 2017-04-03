<?php
  class Task
  {
    private $description;
    private $id;

    function __construct($description, $id = null)
    {
      $this->description = $description;
      $this->id = $id;
    }

    function setDescription($new_description)
    {
      $this->description = (string) $new_description;
    }

    function getDescription()
    {
      return $this->description;
    }

    function save()
    {
      $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}');");
      if($executed){
        $this->id = $GLOBALS['DB']->lastInsertID();
        return true;
      }else{
        return false;
      }
    }

    static function getAll()
    {
      $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
      $task = array();
      foreach($returned_tasks as $tasks){
        $description = $tasks['description'];
        $id = $tasks['id'];
        $new_task = new Task($description, $id);
        array_push($task, $new_task);
      }
      return $task;
    }

    static function deleteAll()
    {
      $executed = $GLOBALS['DB']->exec('DELETE FROM tasks;');
    }

    static function find($search_id)
    {
        $returned_task = $GLOBALS['DB']->prepare('SELECT * From tasks WHERE id = :id');
        $returned_task->bindParam(':id'  , $search_id, PDO::PARAM_STR);
        $returned_task->execute();
        foreach($returned_task as $tasks){
          $task_description = $tasks['description'];
          $task_id = $tasks['id'];
          if($task_id == $search_id){
            $found_task = new Task($task_description, $task_id);
            return $found_task;
          }
        }
    }

    function getId(){
      return $this->id;
    }

    function update($new_description)
    {
      $executed = $GLOBALS['DB']->exec("UPDATE tasks SET name = '{$new_description}' WHERE id = {$this->getId()};");
      if ($executed) {
        $this->setDescription($new_description);
        return true;
      } else {
        return false;
      }
    }

    function delete()
    {
      $executed = $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()};");
      if (!$executed) {
        return false;
      } $executed = $GLOBALS['DB']->exec("DELETE FROM categories_tasks WHERE task_id = {$this->getId()};");
      if (!$executed) {
        return false;
      } else{
        return true;
      }
    }

    function addCategory($category)
  {
      $executed = $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$category->getId()}, {$this->getId()});");
       if ($executed) {
          return true;
       } else {
          return false;
       }
  }
  
    function getCategories()
    {
      $query = $GLOBALS['DB']->query("SELECT category_id FROM categories_tasks WHERE task_id = {$this->getId()};");
      $category_ids = $query->fetchAll(PDO::FETCH_ASSOC);

      $categories = array();
      foreach($category_ids as $id) {
          $category_id = $id['category_id'];
          $result = $GLOBALS['DB']->query("SELECT * FROM categories WHERE id = {$category_id};");
          $returned_category = $result->fetchAll(PDO::FETCH_ASSOC);

          $name = $returned_category[0]['name'];
          $id = $returned_category[0]['id'];
          $new_category = new Category($name, $id);
          array_push($categories, $new_category);
      }
      return $categories;
    }

  }
 ?>
