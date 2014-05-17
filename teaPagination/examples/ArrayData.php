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
//set array data    
$data = array('a','b','c','d','e','f','g','h','i','j','k','l','l','m','n');
//added the class options
$options = array(
    'beginLoop' => '&lt;blockquote&gt;',
    'endLoop' => '&lt;/blockquote&gt;',
    'maxButtons' => 5,
    'itemsPage' => 5,
    'buttons' => array('class' => 'pagination'),
    'connect_db' => array('user' => 'root', 'database' => 'cakephp', 'password' => 'libertad2')
);
//Instance is started
$pagination = new teaPagination($data, $options);
//Add in the method a function of interaction (create_list_data)
$pagination->loop('', 'create_list_data');
//The result is shown
echo '&lt;div class="row" style="margin-bottom:20px;margin-left:5px"&gt;Showing ' . $pagination->currentPage() . ' - ' . $pagination->TotalPage . ' of ' . $pagination->TotalRecord . ' entries&lt;/div&gt;&lt;hr/&gt;' . $pagination->render() . $pagination->buttons();

function create_list_data($data) {
    return '&lt;p&gt;' . $data . '&lt;/p&gt;';
}
</code>
                </pre>
            </div>
            <?php

            function create_list_data($data) {
                return '<p>' . $data . '</p>';
            }

            require_once('../teaPagination.php');
            $data = array('a','b','c','d','e','f','g','h','i','j','k','l','l','m','n');
            $options = array(
                'beginLoop' => '<blockquote>',
                'endLoop' => '</blockquote>',
                'maxButtons' => 5,
                'itemsPage' => 5,
                'buttons' => array('class' => 'pagination'),
                'connect_db' => array('user' => 'root', 'database' => 'cakephp', 'password' => 'libertad2')
            );
            $pagination = new teaPagination($data, $options);
            $pagination->loop('', 'create_list_data');
            echo '<div class="row" style="margin-bottom:20px;margin-left:5px">Showing ' . $pagination->currentPage . ' - ' . $pagination->TotalPage . ' of ' . $pagination->TotalRecord . ' entries</div><hr/>' . $pagination->render() . $pagination->buttons();
            ?>
    </body>
</html>
