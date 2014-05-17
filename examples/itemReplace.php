<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/highlight/default.css" rel="stylesheet" type="text/css" />
        <script src="assets/js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/highlight.pack.js" type="text/javascript"></script>
        <style>
            .wrapper{
                margin: 0 auto;
                max-width: 800px;
                padding-top: 50px;
            }
            .code{
                display:none;
            }
        </style>
        <script>hljs.initHighlightingOnLoad();</script>
    </head>
    <body>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">teaPagination</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-3">
                    <a href="../index.html" class="btn btn-default navbar-btn"><i class="fa fa-home"></i> Home</a>
                </div>
            </div>    
        </nav>
        <div class="wrapper">
            <div class="alert alert-info"><strong>Please, </strong>Click <a href="javascript:void(0)" onClick="$('.code').toggle()" style="font-weight:bold">here</a> to view the code</div>
            <div class="row">
                <pre class="code php html">
<code>
//create the sql statement to be processed    
$sql = 'SELECT * FROM countries';
//added the class options
$options = array(
    'beginLoop' => '&lt;blockquote&gt;',
    'endLoop' => '&lt;/blockquote&gt;',
    'maxButtons' => 5,
    'nameVar' => 'pg',
    'itemsPage' => 10,
    'buttons' => array('class' => 'pagination'),
    'connect_db' => array('user' => 'root', 'database' => 'example', 'password' => 'passw')
);
//Instance is started
$pagination = new teaPagination($sql, $options);
//It substitutes the words keys by value
$pagination->loop('&lt;p&gt;&lt;a href="javascript:void(0)" onClick="alert(\'Country: {short_name}\')"&gt;{short_name}&lt;/a&gt;&lt;/p&gt;&lt;small&gt;{long_name}&lt;/small&gt;');
//The result is shown
echo '&lt;div class="row" style="margin-bottom:20px;margin-left:5px"&gt;Showing ' . $pagination->currentPage . ' - ' . $pagination->TotalPage . ' of ' . $pagination->TotalRecord . ' entries&lt;/div&gt;&lt;hr/&gt;' . $pagination->render() . $pagination->buttons();
</code>
                </pre>
            </div>
            <?php
                require_once('../teaPagination.php');
				require_once('setting/setting.php');
                $sql = 'SELECT * FROM countries';
                $options = array(
                    'beginLoop' => '<blockquote>',
                    'endLoop' => '</blockquote>',
                    'maxButtons' => 5,
                    'nameVar' => 'pg',
                    'itemsPage' => 10, 
                    'buttons' => array('class' => 'pagination'),
                    'connect_db' => array('user' => USER, 'database' => DATABASE, 'password' => PASSWORD)
                );
                $pagination = new teaPagination($sql, $options);
                $pagination->loop('<p><a href="javascript:void(0)" onClick="alert(\'Country: {short_name}\')">{short_name}</a></p><small>{long_name}</small>');
                echo '<div class="row" style="margin-bottom:20px;margin-left:5px">Showing '.$pagination->currentPage.' - '.$pagination->TotalPage.' of '.$pagination->TotalRecord.' entries</div><hr/>'.$pagination->render().$pagination->buttons();
            ?>
        </div>
    </body>
</html>
