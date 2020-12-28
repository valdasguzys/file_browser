<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- TODO -->
<!-- mygtukas katalogams trinti -->
<!-- nuresetint paskutinio sukurto katalogo kintamuosius -->
<!-- neteisingai rodo direktorija kurioje esame -->
<!-- back mygtukas -->
<!-- autentifikacija login passw -->


<form action="" method="GET">
<label for="new_folder">Create New Folder</label><br>
<input type="text" id="mass" name="new_folder" value="New Folder"><br>
<input type="submit" value="Submit" name="submit">
</form>

<?php 
$new_folder = $_GET['new_folder'];
if (is_dir($new_folder)) {
        print('Folder ' . $new_folder . ' already exists!');
    } else {
        $create_folder = mkdir($new_folder);
        print('Folder ' . $new_folder . ' was succesfuly created!');
    }
?>


<?php 
    $path = './' . $_GET['path']; 
    $scan = scandir($path);                      
?>

<h3>
    <?php 
    print($_SERVER['REQUEST_URI'] . " katalogo turinys");
    ?>
</h3>

<table>
    <tr>
    <th>Name</th>
    <th>Type</th>
    <th>Action</th>

        <?php 
            for ($i = 0; $i <= count($scan) - 1; $i++ ) {
                if (is_dir($scan[$i])) {
                    print('<tr><td><a href="' . $_SERVER['REQUEST_URI'] . '?path=' . $scan[$i] . '/' . '">' . $scan[$i] . '</a></td><td>Folder</td><td></td></tr>');
                } else {
                    print('<tr><td>
                    <a href="' . $_SERVER['REQUEST_URI'] . $scan[$i] . '">' . $scan[$i] . '</a></td><td>File</td><td><button>delete</button></td></tr>');
                    }
            }                 
        ?>
        
    </tr>
</table>

</body>
</html>