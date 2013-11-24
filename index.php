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


natsort($files); // sort files

// Go through each file and create a multi-dimensional array of files, page titles and categories

$pages = array();
foreach ($files as $file) {
    
                $category = current(explode("--", $file)); // extract category name

//                echo $category;

                $page['category']= $category;


                // Extract page name (removing category)
                $arr = explode('--', $file);
                $pageName = $arr[1];

                $pageName = str_replace(".md", "", $pageName); // remove extension
                $pageName = str_replace("-", " ", $pageName); // replace - with space
                
//                echo $pageName;

                $page['pageName'] = $pageName;

                $fileName = $file;

 //               echo $fileName;

                $page['fileName'] = $fileName;

                $pages[] = $page;


                
                // Add details to array of pages
                //$pages['category'][] = $category;
                // $pages['name'][] = $name;
                

}






// Get markdown file to display from URL

$URLfileName = $_GET['file'];
$URLcategory = current(explode("--", $URLfileName)); // extract category name to display in breadcrumb trail
// Tidy up file name to display in page title
$arr = explode('--', $URLfileName);
                $URLpageName = $arr[1];

                $URLpageName = str_replace(".md", "", $URLpageName); // remove extension
                $URLpageName = str_replace("-", " ", $URLpageName); // replace - with space

// Put contents of file into string

$fileContents = file_get_contents($wikiDir.'/'.$URLfileName);

// Translate from markdown to HTML

$fileHtml = \Michelf\Markdown::defaultTransform($fileContents);

// Create page title

$pageTitle = substr($URLfileName,0,-3);

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
          <li class="sidebar-brand"><a href=".">GitHub Wiki Player</a></li>
            <?php
          // print list of files in wiki

            $categoryTemp = '';

            foreach($pages as $page) { 
                $category = $page['category'];
                if($category != $categoryTemp) {
                    echo($page['category']);
                    $categoryTemp = $category; // TODO: Change this to collapsible list
                }
                
                echo('<li><a href="index.php?file='.$page['fileName'].'">'.$page['pageName'].'</a></li>');

                }
            ?>
          
        </ul>
      </div>

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="well">
        <h1><?php echo $application; ?> > Documentation</h1>
        <p>These documents are automatically created from the application wiki on GitHub.</p>
        <p>If they are wrong please make a suggestion for the change via the <a href="#">Issues page</a>, or, if you're a project contributor, please edit the <a href="#">wiki</a> directly. 
      </div>
      
      
          
      <!-- Page content -->
      <div id="page-content-wrapper">
        <div class="content-header">
          <h1>
            <a id="menu-toggle" href="#" class="btn btn-default"><i class="icon-reorder"></i></a>
            <?php
                echo $URLcategory.' / '.$URLpageName;
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