<?php
session_start();

// variable declaration
$username = "";
$email    = "";
$errors = array(); 
$_SESSION['success'] = "";

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'registration');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database
  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }

}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}


// SEARCH USER
if (isset($_POST['action'])) {
    $action = mysqli_real_escape_string($db, $_POST['action']);
    if($action == "search"){
        // receive all input values from the form
        $searchText = mysqli_real_escape_string($db, $_POST['searchText']);

        $query = "SELECT * FROM users WHERE name='$username' or age='$username'";
        //$query = "SELECT * FROM users WHERE username like '%$username%' or age='%$username%'";

        $results = mysqli_query($db, $query);
        $users = array();
        while ($row = mysqli_fetch_assoc($results)){
        $user = array(
            "id"=>$row["id"],
            "name"=>$row["name"],
            "age"=>$row["age"]
        );
        array_push($users,$user);
        }

        header('Content-Type: application/json');
        echo json_encode($users);
    }

    if($action == "edit"){
        $id = mysqli_real_escape_string($db, $_POST['id']);
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $age = mysqli_real_escape_string($db, $_POST['age']);

        $query = "UPDATE users set name='$name',age='$age' where $id='$id' ;
        $result = mysqli_query($db, $query);
        if(mysqli_affected_rows($result) == 1 ){ // Successful
           echo("successful");
        }
        else{
            echo("fail");
        }
    }

    if($action == "searchById"){
        $id = mysqli_real_escape_string($db, $_POST['id']);

        $query = "SELECT * FROM users where $id='$id' ;
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($results);

        $user = array(
                    "id"=>$row["id"],
                    "name"=>$row["name"],
                    "age"=>$row["age"]
                );
        echo json_encode($user);
    }
}

?>
