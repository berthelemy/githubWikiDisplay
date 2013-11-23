<?php
include 'config.php';

require_once 'php-markdown-lib/Michelf/Markdown.php';


// Get list of files in wiki directory - to show in menu

$files = array();
$dir = opendir($wikiDir); // open the cwd..also do an err check.
while(false != ($file = readdir($dir))) {
        if(($file != ".") and ($file != "..") and ($file != "index.php")) {
                $files[] = $file; // put in array.
        }   
}

natsort($files); // sort.

// Get file to display from URL

$fileName = $_GET['file'];

// Put contents of file into string

$fileContents = file_get_contents($wikiDir.'/'.$fileName);

// Translate from markdown to HTML

$fileHtml = \Michelf\Markdown::defaultTransform($fileContents);

// Create page title

$pageTitle = substr($fileName,0,-3);

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $application; ?> > Documentation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link href="css/simple-sidebar.css" rel="stylesheet">
</head>

<body>
    <!--<div class="container theme-showcase">-->
        <div id="wrapper">
            <!-- Sidebar -->
      <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
          <li class="sidebar-brand"><a href="#">GitHub Wiki Player</a></li>
            <?php
          // print list of files in wiki
            foreach($files as $file) {
                echo('<li><a href="index.php?file='.$file.'"">'.$file.'</a></li>');
                }
            ?>
          <li><a href="#">Category 1</a></li>
          <li><a href="#">Category 2</a></li>
          <li><a href="#">Category 3</a></li>
          <li><a href="#">Category 4</a></li>
          <li><a href="#">Category 5</a></li>
          <li><a href="#">Category 6</a></li>
          <li><a href="#">Category 7</a></li>
        </ul>
      </div>

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="well">
        <h1><?php echo $application; ?> > Documentation</h1>
        <p>These documents are automatically created from the application wiki on GitHub.</p>
        <p>If they are wrong please make a suggestion for the change via the <a href="#">Issues page</a>, or, if you're a project contributor, please edit the <a href="#">wiki</a> directly. 
      </div>


    <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Category</a></li>
        <li class="active"><?php echo $pageTitle;?></li>
    </ol>


      
      
          
      <!-- Page content -->
      <div id="page-content-wrapper">
        <div class="content-header">
          <h1>
            <a id="menu-toggle" href="#" class="btn btn-default"><i class="icon-reorder"></i></a>
            <?php
                echo $pageTitle;
                ?>
          </h1>
        </div>
        <!-- Keep all page content within the page-content inset div! -->
        <div class="page-content inset">
            <?php
                echo $fileHtml; // print out markdown file contents
                ?>

        </div>
      </div>
      
    
        
    

    





      



</div> <!-- Wrapper-->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>

    <!-- Custom JavaScript for the Menu Toggle -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });
    </script>

  </body>


</html>