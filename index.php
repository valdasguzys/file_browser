<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<!-- back button php-->
<?php 

    // explode doesn't work properly on subfolder content. possibly missing "/" at the end off the path of the subfolder 

    $current_dir_array = explode("/", $_SERVER['REQUEST_URI']);
    $ending = array_splice($current_dir_array, count($current_dir_array) - 2);   
    $url_back = implode("/", $current_dir_array);

    print("<button><a href='$url_back'>BACK</a></button>");
    
    // //other way to get parrent folder. works ok

    // $url_back2 = dirname($_SERVER['REQUEST_URI']);
    // print("<button><a href='$url_back2'>BACK2</a></button>");
?>

<!-- new folder -->
<form action="" method="POST">
    <label for="new_folder">Create New Folder</label><br>
    <input type="text" id="mass" name="new_folder" value="New Folder"><br>
    <input type="submit" value="Submit" name="submit">
</form>

<!-- new folder php -->
<?php
    if (isset($_POST['new_folder'])) {
        $new_folder = $_POST['new_folder'];
            if (is_dir($new_folder)) {
                print('Folder ' . $new_folder . ' already exists!');
            } else if ($new_folder == "") {
                print('Enter name for new folder.');
            } else {    
                $create_folder = mkdir($new_folder);
                print('Folder ' . $new_folder . ' was succesfuly created!');
        }
    }
    
?>

<!-- file deletion -->
<?php
    if (isset($_POST['delete'])) {
        $delete_file = $_POST['delete'];
            if (is_file($delete_file) == "") {
                print('false');
            } else {
                unlink($delete_file);
                print('File ' . $delete_file . ' succesfuly deleted!');
            }
    }
?>

<!-- current folder -->
<h3>
    <?php 
    $current_folder = str_replace('?path=', '' ,$_SERVER['REQUEST_URI']); 
    print($current_folder . ' folder contents');
    ?>
</h3>

<!-- display folder contents -->
<?php
    $path = './' . $_GET['path']; 
    $scan = scandir($path);                     
?>

<table>
    <tr>
    <th>Name</th>
    <th>Type</th>
    <th>Action</th>

        <?php 
            for ($i = 2; $i <= count($scan) - 1; $i++ ) {
                if (is_dir($scan[$i])) {
                    print('<tr><td>
                    <a href="' . $_SERVER['REQUEST_URI'] . '?path=' . $scan[$i] . '/' . '">' . $scan[$i] . '</a></td>
                    <td>Folder</td>
                    <td></td>
                    </tr>');
                } else {
                    print('<tr><td>
                    <a href="' . $_SERVER['REQUEST_URI'] . $scan[$i] . '">' . $scan[$i] . '</a></td>
                    <td>File</td>
                    <td>
                    <form action="index.php" method="POST">
                    <input type="hidden" name="delete" value="' . $scan[$i] . '">
                    <button type="submit">Delete</button>
                    </form>
                    </td>
                    </tr>');
                }
            }                 
        ?>        
    </tr>
</table>




</body>
</html>