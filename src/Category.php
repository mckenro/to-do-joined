<?php
  class Category
  {
    private $name;
    private $id;

    function __construct($name, $id = null)
    {
      $this->name = $name;
      $this->id = $id;
    }
    function setName($new_name)
    {
      $this->name = (string) $new_name;
    }
    function getName()
    {
      return $this->name;
    }
    function getId()
    {
      return $this->id;
    }
    function save()
    {
      $executed = $GLOBALS['DB']->exec("INSERT INTO categories (name) VALUES('{$this->getName()}')");
      if($executed){
        $this->id=$GLOBALS['DB']->lastInsertId();
        return true;
      }else{
        return false;
      }
    }

    function update($new_name)
    {
      $executed = $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");
      if ($executed) {
        $this->setName($new_name);
        return true;
      } else {
        return false;
      }
    }

    function delete()
    {
      $executed = $GLOBALS['DB']->exec("DELETE FROM categories WHERE id = {$this->getId()};");
      if ($executed) {
        return false;
      } $executed = $GLOBALS['DB']->exec("DELETE FROM categories_id WHERE category_id = {$this->getId()};");
      if ($executed){
        return false;
      }else {
        return true;
      }
    }

    static function getAll()
    {
      $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
      $categories = array();
      foreach($returned_categories as $category) {
        $name = $category['name'];
        $id = $category['id'];
        $new_category = new Category($name, $id);
        array_push($categories, $new_category);
      }
      return $categories;
    }
    static function deleteAll()
    {
      $GLOBALS['DB']->exec("DELETE FROM categories;");
    }
    static function find($search_id)
    {
      $found_category = null;
      $returned_categories = $GLOBALS['DB']->prepare("SELECT * FROM categories WHERE id = :id");
      $returned_categories->bindParam(':id', $search_id, PDO::PARAM_STR);
      $returned_categories->execute();
      foreach($returned_categories as $category){
        $category_name = $category['name'];
        $category_id = $category['id'];
        if($category_id == $search_id){
          $found_category = new Category($category_name, $category_id);
        }
      }
      return $found_category;
    }

    function addTask($task)
    {
      $executed = $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$this->getId()}, {$task->getId()});");
      if ($executed) {
        return true;
      } else {
        return false;
      }
    }

    function getTasks()
    {
      $query = $GLOBALS['DB']->query("SELECT task_id FROM categories_tasks WHERE category_id = {$this->getId()};");
      $task_ids = $query->fetchAll(PDO::FETCH_ASSOC);

      $tasks = array();
      foreach($task_ids as $id) {
        $task_id = $id['task_id'];
        $result = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE id = {$task_id};");
        $returned_task = $result->fetchAll(PDO::FETCH_ASSOC);

        $description= $returned_task[0]['description'];
        $id = $returned_task[0]['id'];
        $new_task = new Task($description, $id);
        array_push($tasks, $new_task);
      }
      return $tasks;
    }
  }
 ?>
