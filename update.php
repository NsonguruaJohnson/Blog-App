<?php
// Include config file
require_once "connect.php";
 
// Define variables and initialize with empty values
$title = $body = $author = "";
$title_err = $body_err = $author_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
        }		

        # Validate Title
        if (empty($_POST['title'])){
            $title_err = "Title is required";			
        } else {
            $title = test_input($_POST['title']);
        }
    
        # This will be for author(Will be displayed when signin)
        if (empty($_POST['author'])){
            $author_err = "Author is required";			
        } else {
            $author = test_input($_POST['author']);
        }
    
        // Validate body
        if (empty($_POST['body'])){
            $body_err = "Cannot be empty";			
        } else {
            $body = test_input($_POST['body']);
        }
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty( $author_err) && empty($body_err)){
        // Prepare an update statement
        $sql = "UPDATE blog SET title=:title, author=:author, body=:body WHERE id=:id";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":title", $param_title);
            $stmt->bindParam(":author", $param_author);
            $stmt->bindParam(":body", $param_body);
            $stmt->bindParam(":id", $param_id);
            
            // Set parameters
            $param_title = $title;
            $param_author = $author;
            $param_body = $body;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                echo "<div class='alert alert-success'>Blog was updated.</div>";
            }else{
                echo "<div class='alert alert-danger'>Unable to update Blog. Please try again.</div>";
            }
            // if($stmt->execute()){
            //     // Records updated successfully. Redirect to landing page
            //     header("location: index.php");
            //     exit();
            // } else{
            //     echo "Something went wrong. Please try again later.";
            // }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM blog WHERE id = :id";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    // Retrieve individual field value
                    $title = $row["title"];
                    $author = $row["author"];
                    $body = $row["body"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
        
        // Close connection
        unset($pdo);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Blog</h2>
                    </div>
                    <p>Please edit the input values and submit to update the blog.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
                            <span class="help-block"><?php echo $title_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($author_err)) ? 'has-error' : ''; ?>">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control" value="<?php echo $author; ?>">
                            <span class="help-block"><?php echo $author_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($body_err)) ? 'has-error' : ''; ?>">
                            <label>Body</label>
                            <textarea name="body" class="form-control"><?php echo $body; ?></textarea>
                            <span class="help-block"><?php echo $body_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Save Changes">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>