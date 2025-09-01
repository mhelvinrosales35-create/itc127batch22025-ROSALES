<?php
session_start();
//check if there is a session recorded
if(!isset($_SESSION['username'])) {
    //redirect the user back to the login page
    header("location: login.php");
    exit;
}
?>
<html>
<head>
<title>Accounts Management Page - Equipment Management System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
         background: url('gbg.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: Arial, sans-serif;
    }
    .management-container {
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 1000px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table th, table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    table th {
        background: #198754;
        color: white;
    }
</style>
</head>
<body>
    <div class="management-container">
        <?php
            echo "<h1 class='text-center'>Welcome, " . $_SESSION['username'] . "</h1>";
            echo "<h4 class='text-center mb-4'>Account type: " . $_SESSION['usertype'] . "</h4>";
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <a href="create-account.php" class="btn btn-success btn-sm">Create New Account</a>
                <a href="logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
            <div class="d-flex">
                <input type="text" name="txtsearch" class="form-control form-control-sm me-2" placeholder="Search...">
                <input type="submit" name="btnsearch" value="Search" class="btn btn-primary btn-sm">
            </div>
        </form>

        <?php
        function buildtable($result) {
            if(mysqli_num_rows($result) > 0) {
                echo "<table class='table table-bordered table-striped'>";
                echo "<tr>";
                echo "<th>Username</th><th>Usertype</th><th>Status</th><th>Created by</th><th>Date created</th><th>Actions</th>";
                echo "</tr>";
                while($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" .  $row['username'] . "</td>";
                    echo "<td>" .  $row['usertype'] . "</td>";
                    echo "<td>" .  $row['status'] . "</td>";
                    echo "<td>" .  $row['createdby'] . "</td>";
                    echo "<td>" .  $row['datecreated'] . "</td>";
                    echo "<td>";
                    echo "<a href='update-account.php?username=" . $row['username'] . "' class='btn btn-sm btn-primary'>Edit</a> ";
                    echo "<button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-username='" . $row['username'] . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            else {
                echo "<div class='alert alert-warning text-center'>No record/s found.</div>";
            }
        }

        require_once "config.php";
        if(isset($_POST['btnsearch'])) {
            $sql = "SELECT * FROM tblaccounts WHERE username LIKE ? OR usertype LIKE ? ORDER BY username";
            if($stmt = mysqli_prepare($link, $sql)) {
                $searchvalue = '%' . $_POST['txtsearch'] . '%';
                mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);
                } else {
                    echo "<div class='alert alert-danger'>ERROR on loading the data from table.</div>";
                }
            }
        } else {
            $sql = "SELECT * FROM tblaccounts ORDER BY username";
            if($stmt = mysqli_prepare($link, $sql)) {
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);
                } else {
                    echo "<div class='alert alert-danger'>ERROR on loading the data from table.</div>";
                }
            }
        }
        ?>
    </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="delete-account.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <b id="deleteUsername"></b>?
          <input type="hidden" name="txtusername" id="deleteInputUsername">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="btnsubmit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const deleteModal = document.getElementById('deleteModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  const username = button.getAttribute('data-username');
  document.getElementById('deleteUsername').textContent = username;
  document.getElementById('deleteInputUsername').value = username;
});
</script>
</body>
</html>