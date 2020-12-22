<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 

//katalogo adresas
$my_folder = "./";
//nuskanuojamas katalogo turinys
$scan = scandir($my_folder);

//----------------------------------
// $folder_array = array();
// $file_array = array();

// foreach ($scan as $folder) { 
//     if (is_dir($folder)) { 
//     array_push($folder_array, $folder);
//     } else {
//     array_push($file_array, $folder);
//     }
// } 

// for ($i = 0; $i <= count($scan) - 1; $i++ ) {
//     print("<td>" . $scan[$i] . "</td>");
// }
//----------------------------------------


?>

<!-- adresas kuriame randames -->
<h3>
    <?php 
    print($my_folder . " katalogo turinys");
    ?>
</h3>

<!-- sukuriu lentele atvaizduoti katalogo turini -->
<table>
    <tr>
    <th>Name</th>
    <th>Type</th>
    <?php 
        for ($i = 0; $i <= count($scan) - 1; $i++ ) {
            if (is_dir($scan[$i])) {
                print("<tr><td><a href='$scan[$i]'>" . $scan[$i] . "</a></td>" . "<td>Folder</td></tr>");
            } else {
                print("<tr><td><a href='$scan[$i]'>" . $scan[$i] . "</a></td>" . "<td>File</td></tr>");
                }
        }    
    ?>
    </tr>
</table>


    
</body>
</html>