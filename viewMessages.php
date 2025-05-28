<?php
    include_once("config.php");

    if(empty($_SESSION['username']) || $_SESSION['is_admin'] != "true"){
        header("Location: login.html");
        exit();
    }

    $sql = "SELECT * FROM contact";
    $selectMessages = $conn->prepare($sql);
    $selectMessages->execute();
    $messages_data = $selectMessages->fetchAll();

    if (isset($_GET['delete_id'])) {
        $messageId = $_GET['delete_id'];
        $deleteSql = "DELETE FROM contact WHERE id = :id";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bindParam(':id', $messageId);
        $deleteStmt->execute();
        header("Location: viewMessages.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<nav class="navbar bg-dark navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Welcome, <?php echo $_SESSION['username']; ?></a>
            <a href="logout.php" class="btn btn-danger justify-content-end d-flex">
                Logout  <i class="fa-solid fa-right-from-bracket ms-2 d-flex align-items-center"></i>
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <?php include_once("sidebar.php"); ?>

            <div class="col-10">
                <div class="container mt-5">
                    <h3>Contact Messages</h3>
                    <table class="table table-striped border">
                        <thead>
                            <tr class="bg-dark">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($messages_data as $message_data) { ?>
                                <tr>
                                    <td><?php echo $message_data['firstlastname']; ?></td>
                                    <td><?php echo $message_data['email']; ?></td>
                                    <td><?php echo $message_data['subject']; ?></td>
                                    <td><?php echo $message_data['message']; ?></td>
                                    <td>
                                    <a href="viewMessages.php?reply_id=<?php echo $message_data['id']; ?>" class="btn btn-warning"><i class="fas fa-reply"></i> Reply</a> |
                                    <a href="viewMessages.php?delete_id=<?php echo $message_data['id']; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>