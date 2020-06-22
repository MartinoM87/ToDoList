<?php
  $errors = "";
  //connessione al db
  $db = mysqli_connect('localhost', 'root', '', 'todo');

//inserisco record con stato to do
  if (isset($_POST['submit'])) {
      $task = $_POST['task'];
      if (empty($task)) {
        $errors = "Inserisci un task";
      }else {
        mysqli_query($db, "INSERT INTO tasks (tasks, idStatus, status) VALUES ('$task', '0', 'Nuovo')");
        header('location: index.php');

      }
  }

  //cancella task
  if (isset($_GET['del_task'])) {
     $id = $_GET['del_task'];
     mysqli_query($db, "DELETE FROM tasks WHERE id =$id");
     header('location: index.php');
  }

  //cambia stato Nuovo -> to do
   if (isset($_GET['todo_task'])) {
      $id = $_GET['todo_task'];
      /*UPDATE table SET status=(id+1)*2 WHERE id IN (1,2,3);*/
      mysqli_query($db, "UPDATE tasks SET idStatus = (idStatus+1), status='Da Fare' WHERE id=$id and idStatus = '0'; ");
      header('location: index.php');
   }

//cambia stato to do -> running
 if (isset($_GET['running_task'])) {
    $id = $_GET['running_task'];
    /*UPDATE table SET status=(id+1)*2 WHERE id IN (1,2,3);*/
    mysqli_query($db, "UPDATE tasks SET idStatus = (idStatus+1), status='In Corso' WHERE id=$id and idStatus = '1'; ");
    header('location: index.php');
 }

 //cambia stato running -> done
  if (isset($_GET['done_task'])) {
     $id = $_GET['done_task'];
     /*UPDATE table SET status=(id+1)*2 WHERE id IN (1,2,3);*/
     mysqli_query($db, "UPDATE tasks SET idStatus = (idStatus+1), status='Completato' WHERE id=$id and idStatus = '2'; ");
     header('location: index.php');
  }

    $where=""; //condizione iniziale

    if (isset($_POST['submit2']))     //> To do
      $where = "WHERE idStatus = '1'; ";
    else
    {
       if (isset($_POST['submit3']))   //> Running
        $where = "WHERE idStatus = '2'; ";
      else {
        if (isset($_POST['submit4']))   //> done
         $where = "WHERE idStatus = '3'; ";
      }
    }

    $tasks = mysqli_query($db, "SELECT * FROM tasks $where");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>To do list</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
  </head>
  <body style="background-color: #DCDCDC;"> <!--GAINSBORO-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <div class="heading">
      <img class="img_title" src="toDoList_icon.png"><p8>To Do List</p8>
    </div>

    <form method="POST" action="index.php">
      <?php if (isset($errors)) {?>
        <p><?php echo $errors; ?></p>
    <?php }?>
      <input type="text" name="task" class="task_input">
      <button type="submit" class="add_btn" name="submit">Aggiungi Task</button> <br> <br>
      <div class="t_btn" style="text-align:center;">
        <p2>Visualizza:</p2>
        <button type="submit1" class="add_btn" name="submit1" style="margin-right:20px;">Tutti</button>
        <button type="submit2" class="add_btn" name="submit2" style="margin-right:20px;" >Da Fare</button>
        <button type="submit3" class="add_btn" name="submit3" style="margin-right:20px;">In Corso</button>
        <button type="submit4" class="add_btn" name="submit4">Completati</button>
      </div>
    </form>

    <table>
      <thead>
        <tr>
          <th>Nr</th>
          <th>Task</th>
          <th>Stato</th>
          <th style="text-align:center">Da Fare</th>
          <th style="text-align:center">In Corso</th>
          <th style="text-align:center">Completato</th>
          <th style="text-align:center">Cancella</th>
        </tr>
      </thead>

      <tbody>
      <?php $i = 1; while ($row = mysqli_fetch_array($tasks)) { ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td class="task"><?php echo $row['tasks']; ?></td>
          <td class="status"> <?php echo $row['status']; ?></td>

          <td class="toDo">
          <a href="index.php?todo_task=<?php echo $row['id']; ?>"><i class="fa fa-pencil"></i></a>
          </td>
          <td class="running">
            <a href="index.php?running_task=<?php echo $row['id']; ?>"><i class="fa fa-ellipsis-h"></i></a>
          </td>
          <td class="done">
            <a href="index.php?done_task=<?php echo $row['id']; ?>"><i class="fa fa-check"></i></a>
          </td>
          <td class="delete">
            <a href="index.php?del_task=<?php echo $row['id']; ?>"><i class="fa fa-trash"></i></a>
          </td>
        </tr>
      <?php $i++; } ?>

      </tbody>
    </table>
  </body>

  <!-- chiudo connessione al DB -->
  <?php mysqli_close($db) ?>

</html>
