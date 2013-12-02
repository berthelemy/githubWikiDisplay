<?php
include 'config.php'; // Include configuration settings

$homePageURL = "./index.php?file=".$homePage;

require_once 'php-markdown-lib/Michelf/Markdown.php'; // Include Markdown parser

function createPageName($fname) {

    $name = str_replace(".md", "", $fname); // remove extension
    $name = str_replace("-", " ", $name); // replace - with space

    return $name;

}


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

// Check if file has a category
    if (strpos($file, '--') != true) {

        $category = 'Uncategorized';
        $pageName = $file;
        } else {
// if file doesn't have a category, then extract it, leaving the page name
        $arr = explode('--', $file);
        $category = $arr[0];
        $pageName = $arr[1];
        }

// Now setup the $page array
    $page['category']= $category; // Setup $page array: category                

    $page['pageName'] = createPageName($pageName); // Make name to go in menu - put in $page array: pageName

    $page['fileName'] = $file; // Put filename in $page array: fileName

    $pages[] = $page; // Add this set of page items to the $pages array

} // end of Foreach


// Get markdown file to display from URL

$URLfileName = $_GET['file']; // Pick up the filename from the URL
if (strpos($URLfileName, '--') != true) {
    $URLcategory = '';
    $URLpageName = createPageName($URLfileName);
} else {
    $URLcategory = current(explode("--", $URLfileName)); // extract category name to display in breadcrumb trail
// Tidy up file name to display in page title
    $arr = explode('--', $URLfileName);
    $URLpageName = $arr[1]; // Page name = 2nd element in the exploded array
    $URLpageName = createPageName($URLpageName);
}


// Put contents of file into string

$fileContents = file_get_contents($wikiDir.'/'.$URLfileName);

// Translate from markdown to HTML

$fileHtml = \Michelf\Markdown::defaultTransform($fileContents);

// Create page title
$pageTitle = $application.' Documentation > '.$URLcategory.' > '.$URLpageName;

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle ?></title>
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
          <li class="sidebar-brand"><a href="<?php $homePageURL;?>">GitHub Wiki Player</a></li>
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