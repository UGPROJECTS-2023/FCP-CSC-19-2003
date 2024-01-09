<?php
require('dbconn.php');
?>

<?php

                              $rollno = $_SESSION['RollNo'];
                                $sql="select * from LMS.user where RollNo='$rollno'";
                                $result=$conn->query($sql);
                                $row=$result->fetch_assoc();
                                
                                $type = $row['Type'];

                                // $name=$row['Name'];
                                // $category=$row['Category'];
                                // $email=$row['EmailId'];
                                // $mobno=$row['MobNo'];
                               

if ($type == 'Student')

{

echo header("Location:../admin/index.php");

}

if($rollno == NULL){
echo header("location:../");
}

  ?>







<?php  

// Connect to the Database 
include('config.php');

$insert = false;
$update = false;
$empty = false;
$delete = false;
$already_card = false;



if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `cards` WHERE `staff_no` = $sno";
  $result = mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset( $_POST['snoEdit'])){
      // Update the record
        $sno = $_POST["snoEdit"];
        $surname = $_POST["nameEdit"];
        $staff_no = $_POST["id_noEdit"];

      // Sql query to be executed
      $sql = "UPDATE `cards` SET `surname` = '$surname' , `staff_no` = '$staff_no' WHERE `cards`.`staff_no` = '$sno'";
      $result = mysqli_query($conn, $sql);

if ($result === false) {
    // Query execution failed, handle the error
    echo "Error: " . mysqli_error($conn);
} else {
    // Query executed successfully
    echo "Update successful!";
}

}
else{
  
  $staff_no = filter_input(INPUT_POST, 'staff_no', FILTER_SANITIZE_STRING);
  $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
  $other = filter_input(INPUT_POST, 'other', FILTER_SANITIZE_STRING);
  $nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_STRING);
  $type_of = filter_input(INPUT_POST, 'type_of', FILTER_SANITIZE_STRING);
  $purpose = filter_input(INPUT_POST, 'purpose', FILTER_SANITIZE_STRING);
  $post = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_STRING);
  $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
  

    if($surname == '' || $staff_no == ''){
        $empty = true;
    }
    else{
        //Check that Card no. is Already Registerd or not.
        $querry = mysqli_query($conn, "SELECT * FROM cards WHERE staff_no = '$staff_no' ");
        if(mysqli_num_rows($querry)>0)
        {
             $already_card = true;
        }
        else{


          // image upload 
          $uploaddir = 'assets/uploads/';
          $uploadfile = $uploaddir . basename($_FILES['image']['name']);

      
          if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
              
          } else {
              echo "Possible file upload attack!\n";
          }
  // Sql query to be executed
  $query = "INSERT INTO `cards`(`staff_no`,`surname`, `other`, `nationality`, `type_of`, `purpose`, `post`, `phone`, `image`) VALUES ('$staff_no','$surname','$other','$nationality','$type_of','$purpose','$post','$phone','$uploadfile')";

  $result = mysqli_query($conn, $query);

  if ($result === false) {
      // Query execution failed, handle the error
      echo "Error: " . mysqli_error($conn);
  } else {
      // Query executed successfully, proceed further
      
  }
  
  // $sql = "INSERT INTO `cards` (`name`, `id_no`) VALUES ('$name', '$id_no')";
 



   
  if($result){ 
      $insert = true;
  }
  else{
    echo $query;

      echo "The record was not inserted successfully because of this error ---> ". mysqli_error($conn);
  } 
}
}
}

 }
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/png" href="images/favicon.png"/>
  <title>FUD STAFF ID CARD GENERATOR</title>

</head>

<body>
 

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit This Card</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="name">Staff Name</label>
              <input type="text" class="form-control" id="nameEdit" name="nameEdit">
            </div>

            <div class="form-group">
              <label for="desc">ID Card Number:</label>
              <input class="form-control" id="id_noEdit" name="id_noEdit" rows="3"></input>
            </div> 
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<!-- Navigation bar start  -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-image: linear-gradient(to right, rgb(0,300,255), rgb(93,4,217));">
  <a href="logout.php" class="btn btn-primary downloadtable">
  <i class="fa fa-power"></i> Logout</a>
  <a class="navbar-brand" href="#"><img src="assets/images/codingcush-logo.png" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

</p>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
     
   
      
    </ul>
  
  </div>
</nav>
<!-- Navigation bar end  -->

  <?php
  if($insert){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your Card has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($delete){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your Card has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your Card has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
   <?php
  if($empty){
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
    <strong>Error!</strong> The Fields Cannot Be Empty! Please Give Some Values.
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
     <?php
  if($already_card){
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
    <strong>Error!</strong> This Card is Already Added.
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <div class="container my-4">
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
  <i class="fa fa-plus"></i> Add New Card
  </button>
  <a href="id-card.php" class="btn btn-primary">
  <i class="fa fa-address-card"></i> Generate ID Card
</a>
<!-- 
  <button data-toggle="collapse" data-target="#faculty" aria-expanded="false" aria-controls="faculty" class="btn btn-primary">
  <i class="fa fa-home"></i> Manage Faculty
</button>

<button class="btn btn-primary" data-toggle="collapse" data-target="#department" aria-expanded="false" aria-controls="department">
  <i class="fa fa-list"></i> Manage Department
</button>
<button class="btn btn-primary" data-toggle="collapse" data-target="#course" aria-expanded="false" aria-controls="course
">
  <i class="fa fa-database"></i> Manage Course
</button>
-->
</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body">

    <form method="POST" enctype="multipart/form-data">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputCity">Staff No:</label>
        <input type="text" name="staff_no" class="form-control" id="inputCity">
      </div>
      <div class="form-group col-md-4">
        <label for="inputState">Type of appointment:</label>
        <select name="type_of" class="form-control">
          <option selected>Choose...</option>
          <option value="Permanent">Permanent</option>
              <option value="Contract">Contract</option>
              <option value="visiting">visiting</option>
              <option value="sabbatical">sabbatical</option>
              <option value="Others">Others</option>
               
        </select>
      </div>
      <div class="form-group col-md-2">
        <label for="inputZip">Purpose</label>
         <select name="purpose" class="form-control">
          <option selected>Choose...</option>
          <option value="Employment">Employment</option>
              <option value="Promotion">Promotion</option>
              <option value="cc">Change of Cadre</option>
              <option value="Lost">Lost</option>
              <option value="Others">Others</option>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputCity">Surname:</label>
        <input type="text" name="surname" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label for="inputState">Other'ss Name:</label>
        <input type="text" name="other" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <label for="inputZip">Nationality:</label>
        <input type="input" name="nationality" class="form-control">
      </div>
    </div>
      
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="id_no">Post/Rank</label>
          <input class="form-control" id="post" name="post" ></input>
        </div>
        <div class="form-group col-md-3">
          <label for="phone">Staff Phone No.</label>
          <input class="form-control" id="phone" name="phone" ></input>
        </div>
        <div class="form-group col-md-4">
          <label for="photo">Photo</label>
          <input type="file" name="image" required />
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Card</button>
    </form>
  </div>
</div>
<div class="collapse" id="faculty">
  <div class="card card-body">

    <form method="POST" enctype="multipart/form-data">
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="inputCity">Faculty</label>
        <input type="text" name="name" class="form-control" id="inputCity">
      </div>
     </div>
     
    
      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>Submit</button>
    </form>
  </div>
</div>
<div class="collapse" id="department">
  <div class="card card-body">

    <form method="POST" enctype="multipart/form-data">
      <div class="form-row">
         <div class="form-group col-md-12">
        <label for="inputState">Faculty</label>
        <select name="grade" class="form-control">
          <option selected>Choose...</option>
          <option value="Computer Studies">Computer Science</option>
              <option value="Education">Education</option>
              <option value="Agriculture">Argiculture</option>
               
        </select>
      </div>
     
    </div>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="inputCity">Department</label>
        <input type="text" name="name" class="form-control" id="inputCity">
      </div>
     </div>
     
    
      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>Submit</button>
    </form>
  </div>
</div>
<div class="collapse" id="course">
  <div class="card card-body">

    <form method="POST" enctype="multipart/form-data">
       <div class="form-row">
         <div class="form-group col-md-12">
        <label for="inputState">Department</label>
        <select name="grade" class="form-control">
          <option selected>Choose...</option>
          <option value="Computer Studies">Computer Science</option>
              <option value="Education">Education</option>
              <option value="Agriculture">Argiculture</option>
               
        </select>
      </div>
     
    </div>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="inputCity">Course</label>
        <input type="text" name="name" class="form-control" id="inputCity">
      </div>
     </div>
     
    
      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>Submit</button>
    </form>
  </div>
</div>
  <div class="container my-4">


    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Name</th>
          <th scope="col">ID Card No.</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $sql = "SELECT * FROM `cards` order by 1 DESC";
          $result = mysqli_query($conn, $sql);
          $staff_no = 0;
          while($row = mysqli_fetch_assoc($result)){
            $staff_no = $staff_no + 1;
            echo "<tr>
            <th scope='row'>". $staff_no . "</th>
            <td>". $row['surname'] . "</td>
            <td>". $row['staff_no'] . "</td>
            <td> <button class='edit btn btn-sm btn-primary' id=".$row['staff_no'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['staff_no'].">Delete</button>  </td>
          </tr>";
        } 
          ?>


      </tbody>
    </table>
  </div>
  <hr>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();

    });
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        name = tr.getElementsByTagName("td")[0].innerText;
        id_no = tr.getElementsByTagName("td")[1].innerText;
        console.log(name, id_no);
        nameEdit.value = name;
        id_noEdit.value = id_no;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        sno = e.target.id.substr(1);

        if (confirm("Are you sure you want to delete this card!")) {
          console.log("yes");
          window.location = `index.php?delete=${sno}`;
          // TODO: Create a form and use post request to submit a form
        }
        else {
          console.log("no");
        }
      })
    })
  </script>
</body>

</html>
