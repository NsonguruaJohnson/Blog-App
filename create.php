<?php
// Include config file
require_once "connect.php";
 
// Define variables and initialize with empty values
$title = $blogpost = $author = "";
$title_err = $blogpost_err = $author_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

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
        // if (!preg_match("/^[a-zA-Z]*$/", $title)){
        //     $title_err = "Only letters and white spaces allowed";
        // }  
        
        # This will be for author(Will be displayed when signin)
        if (empty($_POST['author'])){
            $author_err = "Author is required";			
        } else {
            $author = test_input($_POST['author']);
        }
        // if (!preg_match("/^[a-zA-Z]*$/", $author)){
        //     $author_err = "Only letters and white spaces allowed";
        // }
 
        // Validate BlogPost
        if (empty($_POST['blogpost'])){
            $blogpost_err = "Cannot be empty";			
        } else {
            $blogpost = test_input($_POST['blogpost']);
        }
        // if (!preg_match("/^[a-zA-Z]*$/", $stitle)){
        //     $snameErr = "Only letters and white spaces allowed";
        // }

        // We'll also validate images(I guess)
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty( $author_err) && empty($blogpost_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO blog (title, author, body, created) VALUES (:title, :author, :blogpost, :created)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":title", $param_title);
            $stmt->bindParam(":author", $param_author);
            $stmt->bindParam(":blogpost", $param_blogpost);
            
            // Set parameters
            $param_title = $title;
            $param_author = $author;
            $param_blogpost = $blogpost;

            // specify when this record was inserted to the database
            $created=date('Y-m-d H:i:s');
            $stmt->bindParam(':created', $created);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                echo "<div class='alert alert-success'>Record was saved.</div>";
            }else{
                echo "<div class='alert alert-danger'>Unable to save record.</div>";
            }
            //     // Records created successfully. Redirect to landing page
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog</title>
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
                        <h2>Create Blog</h2>
                    </div>
                    <p>Please fill this form and submit to add blog records to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <div class="form-group <?php echo (!empty($blogpost_err)) ? 'has-error' : ''; ?>">
                            <label>Blog Post</label>
                            <textarea name="blogpost" class="form-control"><?php echo $blogpost; ?></textarea>
                            <span class="help-block"><?php echo $blogpost_err;?></span>
                        </div>
                        <!-- image -->
                        <input type="submit" class="btn btn-primary" value="Save">
                        <a href="index.php" class="btn btn-default">Back to read blog</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    
</body>
</html>