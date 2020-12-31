
<!-- to do -->
<!-- readme.md -->
<!-- styles -->


<?php
// COOKIES
if(!isset($_COOKIE['times_visited']))
    $times_visited = 1;
else
    $times_visited = $_COOKIE['times_visited'] + 1;
    setcookie('times_visited', $times_visited, time()+86400); //// 86400 = 1 day

// FILE DOWNLOAD LOGIC
if(isset($_POST['download'])){
    $file='./' . $_GET["path"] . $_POST['download'];
    $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
    ob_clean();
    ob_start();
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf'); // mime type → ši forma turėtų veikti daugumai failų, su šiuo mime type. Jei neveiktų reiktų daryti sudėtingesnę logiką
    header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileToDownloadEscaped)); // kiek baitų browseriui laukti, jei 0 - failas neveiks nors bus sukurtas
    ob_end_flush();
    readfile($fileToDownloadEscaped);
    exit;
}

// FILE UPLOAD 
if(isset($_FILES['image'])){
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    // check extension (and only permit jpegs, jpgs and pngs)
    $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
    $extensions = array("jpeg","jpg","png");
    if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }
    if($file_size > 2097152) {
        $errors[]='File size must be exactly 2 MB';
    }
    if(empty($errors)==true) {
        move_uploaded_file($file_tmp,"./".$file_name);
        $message = ('Image ' . $file_name . ' uploaded');
    }else{
        print_r($errors);
    }
}



$path = './' . $_GET['path']; 
$scan = scandir($path);  
//  PRINTS CURRENT FOLDER
function display_current_dir($current_folder) { 
    // $current_folder = str_replace('?path=', '' ,$_SERVER['REQUEST_URI']);
    $current_folder = str_replace(array('?path=', '%20', 'index.php'), array('', ' ', '') ,$_SERVER['REQUEST_URI']);  
    print('<h3>' . $current_folder . ' folder contents' . '</h3>');
}

//   DISPLAYS FOLDER CONTENT
function display_contents ($scan) {
    if ( isset($_GET)) {
    $path = './' . $_GET['path']; 
    $scan = scandir($path);  
}
print('<table>
    <tr>
    <th>Name</th>
    <th>Type</th>
    <th>Action</th>');
        
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
            <td><div class="action_buttons">
            <form action="index.php" method="POST">
                <input type="hidden" name="delete" value="' . $scan[$i] . '">
                <button class="del_btn" type="submit">Delete</button>
            </form>
            <form action="index.php" method="POST">
                <input type="hidden" name="download" value="' . $scan[$i] . '">
                <button class="downl_btn" type="submit">Download</button>
            </form></div>
            </td>
            </tr>');
        }
    }                 
    print('</tr></table>');
}

// CREATES NEW FOLDER
if (isset($_POST['new_folder'])) {
    $new_folder = $_POST['new_folder'];
        if (is_dir($new_folder)) {
            $message = ('Folder ' . $new_folder . ' already exists!');
        } else if ($new_folder == "") {
            $message = ('Enter name for new folder.');
        } else {    
            mkdir($new_folder);
            $message = ('Folder ' . $new_folder . ' was succesfuly created!');
    }
}

// 'BACK' BUTTON

    // explode doesn't work properly on subfolder content. possibly missing "/" at the end off the path of the subfolder 

    // $current_dir_array = explode("/", $_SERVER['REQUEST_URI']);
    // $ending = array_splice($current_dir_array, count($current_dir_array) - 2);   
    // $url_back = implode("/", $current_dir_array);
    // print("<button><a href='$url_back'>BACK</a></button>");

function back_button($url_back_2) {
    $url_back_2 = dirname($_SERVER['REQUEST_URI']);
    print("<div class=\"back_button\"><button><a href='$url_back_2'>GO BACK</a></button></div>");
}

// FILE DELETION
//protection to delete .php, .css and git files 
    
    if (isset($_POST['delete'])) {
        $delete_file = $_POST['delete'];
        $file_ext = strtolower(end(explode('.',$_POST['delete'])));
        $extensions = array("php","css","gitattributes","gitignore","gitkeep");
        if(in_array($file_ext,$extensions) === true){
        
            $message = ('Cannot delete ' . $delete_file . ' file!'); 
            } else {
                unlink($delete_file);
                $message = ('File ' . $delete_file . ' succesfuly deleted!');
            }
    }
// LOGOUT LOGIC
    session_start();
    if(isset($_GET['action']) and $_GET['action'] == 'logout'){
        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        unset($_SESSION['logged_in']);
    }
?>

<!-- //////////////// HTML //////////////// -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- LOGIN LOGIC -->
<?php
    $msg = '';
    if (isset($_POST['login']) 
        && !empty($_POST['username']) 
        && !empty($_POST['password'])
    ) {	
        if ($_POST['username'] == 'Valdas' && 
            $_POST['password'] == '1234'
        ) {
            $_SESSION['logged_in'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = 'Valdas';
        } else {
            $msg = 'Wrong username or password';
        }
    }
?>

<!-- LOGGED IN -->

<?php

    if($_SESSION['logged_in'] == true){    
            display_current_dir($current_folder);
            display_contents($scan);
            back_button($url_back_2);            

?>
        <!-- outputs message after an action -->
        <div class="message"><?php echo $message; ?></div>
    
    <div class="wrapper">
  
        <!-- new folder form -->
        <div class="new_folder">
            <form action="" method="POST">
                <label for="new_folder">Create New Folder</label><br>
                <input type="text" name="new_folder" value="Folder name"><br>
                <button type = "submit">Create</button>
            </form>
        </div>

        <!-- image upload form-->

            <div><form action ="" method = "POST" enctype = "multipart/form-data">
                <input class="chose_file" type = "file" name = "image"><br>
                <button type = "submit">Upload</button>
            </form></div>
            <ul>
                <li>Sent file: <?php echo $_FILES['image']['name'];  ?>
                <li>File size: <?php echo $_FILES['image']['size'];  ?>
                <li>File type: <?php echo $_FILES['image']['type']; ?>
            </ul>

    </div>

    <!-- logout and cookie counter          -->
    <div class="footer">
        <?php  
        print('<a href = "index.php?action=logout"><button>Click here to logout </button></a>');  
        print('You have visited ' . $times_visited . ' times'); ?>
    </div>    
  

    <?php            


        } else { ?>


        <!-- login form  -->
        <h3>Enter Username and Password</h3>

        <div class="login">
            <form action="index.php" method="post">
                <h4><?php echo $msg; ?></h4>
                <input type="text" name="username" placeholder="username = Valdas" required autofocus></br>
                <input type="password" name="password" placeholder="password = 1234" required></br>
                <button type="submit" name="login">Login</button>
            </form>
        </div>     
<?php

    }
    
?>

</body>
</html>