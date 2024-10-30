<?php
/*
Plugin Name: Lampen News (german)
Plugin URI: http://wordpress.org/extend/plugins/lampen-news-german/
Description: Adds a customizeable widget which displays the latest news by http://www.lampe.de/
Version: 1.0
Author: Daniel Mack
Author URI: http://www.lampe.de/
License: GPL3
*/

function lampennews()
{
  $options = get_option("widget_lampennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Lampen News (german)',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://news.google.de/news?pz=1&cf=all&ned=de&hl=de&q=lampen&cf=all&output=rss'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_lampennews($args)
{
  extract($args);
  
  $options = get_option("widget_lampennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Lampen News (german)',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  lampennews();
  echo $after_widget;
}

function lampennews_control()
{
  $options = get_option("widget_lampennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Lampen News (german)',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['lampennews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['lampennews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['lampennews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['lampennews-CharCount']);
    update_option("widget_lampennews", $options);
  }
?> 
  <p>
    <label for="lampennews-WidgetTitle">Widget Title: </label>
    <input type="text" id="lampennews-WidgetTitle" name="lampennews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="lampennews-NewsCount">Max. News: </label>
    <input type="text" id="lampennews-NewsCount" name="lampennews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="lampennews-CharCount">Max. Characters: </label>
    <input type="text" id="lampennews-CharCount" name="lampennews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="lampennews-Submit"  name="lampennews-Submit" value="1" />
  </p>
  
<?php
}

function lampennews_init()
{
  register_sidebar_widget(__('Lampen News (german)'), 'widget_lampennews');    
  register_widget_control('Lampen News (german)', 'lampennews_control', 300, 200);
}
add_action("plugins_loaded", "lampennews_init");
?>