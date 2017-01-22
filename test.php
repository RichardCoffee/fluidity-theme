<?php

#$str = 'a:5:{s:5:"width";i:1125;s:6:"height";i:677;s:4:"file";s:35:"2017/01/leacroft-business-card3.png";s:5:"sizes";a:4:{s:9:"thumbnail";a:4:{s:4:"file";s:35:"leacroft-business-card3-150x150.png";s:5:"width";i:150;s:6:"height";i:150;s:9:"mime-type";s:9:"image/png";}s:6:"medium";a:4:{s:4:"file";s:35:"leacroft-business-card3-300x181.png";s:5:"width";i:300;s:6:"height";i:181;s:9:"mime-type";s:9:"image/png";}s:12:"medium_large";a:4:{s:4:"file";s:35:"leacroft-business-card3-768x462.png";s:5:"width";i:768;s:6:"height";i:462;s:9:"mime-type";s:9:"image/png";}s:5:"large";a:4:{s:4:"file";s:36:"leacroft-business-card3-1024x616.png";s:5:"width";i:1024;s:6:"height";i:616;s:9:"mime-type";s:9:"image/png";}}s:10:"image_meta";a:12:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}}}';

#print_r( json_decode($str));

$curYir = date("Y");//current year
$holidays = array('MLK' => date('Y-m-d', strtotime("january $curYir third monday")), //marthin luthor king day
						'PD'  => date('Y-m-d', strtotime("february $curYir third monday")), //presidents day
						'Est' =>  date('Y-m-d', easter_date($curYir)), // easter
						'MD'  => date('Y-m-d', strtotime("may $curYir last monday")), // memorial day
						'LD'  => date('Y-m-d', strtotime("september $curYir first monday")),  //labor day
						'CD'  => date('Y-m-d', strtotime("october $curYir second monday")), //columbus day
						'TH'  => date('Y-m-d', strtotime("november $curYir last thursday")), // thanks giving
				);

print_r($holidays);
