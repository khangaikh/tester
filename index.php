<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once 'includes/simple_html_dom.php';

    require_once "config.php";

    session_start();
    //register autoloader
    Twig_Autoloader::register();
    //loader for template files
    $loader = new Twig_Loader_Filesystem('templates');
    //twig instance
    $twig = new Twig_Environment($loader, array(
        'cache' => 'cache',
    ));
    //load template file
    $twig->setCache(false);

    class Event {}

    $events = array();
    $measures = array();

    if(isset($_SESSION['user'])){

        $lines = file('resources/1.txt');
        $t = null;
        $parts = array();
        // Loop through our array, show HTML source as HTML source; and line numbers too.
        foreach ($lines as $line_num => $line) {
           
            if (strpos($line, '.') !== false && $t==null) {
                $e = new Event();
                $e->topic = $line;
                $t=$e;
            }else  if (strpos($line, '.') !== false && $t!=null) {
                $t->items = $parts;
                $events[] = $t; 
                unset($parts);
                $parts = array();
                unset($e);
                $e = new Event();
                $e->topic = $line;
                $t=$e;
            }
            else{
                array_push($parts,$line);
            }    
        }

        //echo "Unicode: ", json_encode($events, JSON_UNESCAPED_UNICODE), "\n";

    
        $dir    = 'files/hangai';
        $files1 = scandir($dir);

        $c = count($files1);

        class Measure {}
        class Person {}
     
        $f = $dir."/".$files1[2];
        $html = file_get_html($f);
        $tables = $html->find('table');
        $trs = $tables[0]->find('tr'); 

        $tds = $trs[2]->find("td");

        $p = new Person();

        $pieces = explode(": ", $tds[2]->plaintext);
        $p->age = html_entity_decode($pieces[1]);

        //echo html_entity_decode($pieces[1]);
        //return;
        

        $pieces = explode(": ", $tds[1]->plaintext);




        $p->sex = html_entity_decode($pieces[1]);

        
        $pieces = explode(": ", $tds[0]->plaintext);


        $p->name = $pieces[1];


        $trs = $tables[1]->find('tr'); 
        $tds = $trs[0]->find("td");

        $pieces = explode(": ", $tds[0]);

        $p->weight = $pieces[1];

        $pieces = explode(": ", $tds[1]->plaintext);
        $p->date = html_entity_decode($pieces[1]);


        for($i =2; $i<($c-4);$i++ ){

            $f = $dir."/".$files1[$i];
            $html = file_get_html($f);

            $tables = $html->find('table'); 
            //echo $tables[4];

            $trs = $tables[4]->find('tr');

           
            /*
            $tds = $trs[1]->find("td");
            echo $tds[1];
            echo $tds[2];
            echo $tds[3];
            echo "<br />";
            */
           
            

            for ($j = 1; $j<count($trs); $j++){
                $m = new Measure();
                $tds = $trs[$j]->find("td");
                //echo $i;
                //echo $trs[$j];
                //echo $tds[3];
                $m->set1 = $tds[1]->plaintext;
                $m->set2 = $tds[2]->plaintext;
                if($i!=10){
                    $m->set3 = $tds[3]->plaintext ;
                }
                $measures[] = $m; 
            }

        }

        $template = $twig->loadTemplate('index.html');
        echo $template->render(array('title' => 'Tester Demo', 'user' => 'admin', 'topics'=>$events, 'measures'=>$measures, 'person' => $p));
    }
    else{
        $template = $twig->loadTemplate('login.html');
        echo $template->render(array('title' => 'Tester Demo'));
    }
    
?>
