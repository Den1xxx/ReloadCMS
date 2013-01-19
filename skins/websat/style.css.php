<?php
$css_file = '../../content/skins/'.basename(dirname(__FILE__)).'.ini';
if (is_file($css_file)) $css_config = unserialize(file_get_contents($css_file));
else include('style.default.php');

header("Content-type: text/css; charset: UTF-8"); 
echo'
body {
    margin: 0;
    padding: 0;
    background: '.$css_config['Body background color'].';
    font-family: '.$css_config['Font family'].';
    font-size: '.$css_config['Primary font size, px'].'px;
    color: '.$css_config['Text color'].';
}

h1, h2, h3 {
    margin: 0;
    padding: 0;
    font-weight: normal;
    color: '.$css_config['Title color'].';
}

h1 {
    font-size: 24px;
}

h2 {
    font-size: 20px;
}

h3 {
    font-size: 18px;
}

p, ul, ol {
    margin-top: 0;
    line-height: 180%;
}

ul, ol {
}

a {
    text-decoration: none;
    color: '.$css_config['Link color'].';
}

a:hover {
    color: '.$css_config['Link color when hovering'].';
}

a img{
outline:none;
border:0;
}

#wrapper {
    width: '.($css_config['Skin width, px']+20).'px;
    margin: 0 auto;
    padding: 0;
}

/* Form */
input {
	text-indent: '.$css_config['Input padding, px'].'px;
}

input, textarea, select, button {
	color: '.$css_config['Input text color'].';
    background-color: #FCFCFC;
    border: '.$css_config['Input border thickness, px'].'px solid '.$css_config['Input border color'].';
	outline: none;
    padding: 3px; 
    border-radius: '.$css_config['Input border radius, px'].'px; 
}

input[type="password"]:hover,input[type="text"]:hover,textarea:hover {
	color: '.$css_config['Input text hover color'].';
    background-color: #fff;
    border: '.$css_config['Input border thickness, px'].'px solid '.$css_config['Input border hover color'].';
}

input[type="password"]:focus,input[type="text"]:focus,textarea:focus{
	color: '.$css_config['Input text hover color'].';
    background-color: #fff;
    border: '.$css_config['Input border thickness, px'].'px solid '.$css_config['Input border hover color'].';
	box-shadow: 0 0 5px '.$css_config['Input border hover color'].';
}

input[type="submit"],input[type="reset"],input[type="button"],input[type="file"],button{
	color:'.$css_config['Button text color'].';
	cursor:pointer;
    background: '.$css_config['Button end gradient color'].';
    background: -webkit-gradient(linear, left top, left bottom, from('.$css_config['Button start gradient color'].'), to('.$css_config['Button end gradient color'].')); 
    background: -moz-linear-gradient(top,  '.$css_config['Button start gradient color'].',  '.$css_config['Button end gradient color'].'); 
	background: -o-linear-gradient(top, '.$css_config['Button start gradient color hover'].' 0px, '.$css_config['Button end gradient color'].' 100%);
    background: gradient(linear, top,  '.$css_config['Button start gradient color'].',  '.$css_config['Button end gradient color'].'); 
    border-color: '.$css_config['Button start gradient color'].'; 
	margin: 1px;
	outline: none;
	text-indent: 0;
} 

input[type="submit"]:hover,input[type="reset"]:hover,input[type="button"]:hover,input[type="file"]:hover,button:hover {
    background: '.$css_config['Button end gradient color hover'].';
    background: -webkit-gradient(linear, left top, left bottom, from('.$css_config['Button start gradient color hover'].'), to('.$css_config['Button end gradient color hover'].')); 
    background: -moz-linear-gradient(top,  '.$css_config['Button start gradient color hover'].',  '.$css_config['Button end gradient color hover'].'); 
	background: -o-linear-gradient(top, '.$css_config['Button start gradient color hover'].' 0px, '.$css_config['Button end gradient color hover'].' 100%);
    background: gradient(linear, top,  '.$css_config['Button start gradient color hover'].',  '.$css_config['Button end gradient color hover'].'); 
    border-color: '.$css_config['Button start gradient color hover'].'; 
} 

input[type="submit"]:visited,input[type="reset"]:visited,input[type="button"]:visited,input[type="file"]:visited{
	outline: none;
}

/* Header */

#header {
    width: '.$css_config['Skin width, px'].'px;
    height: '.$css_config['Header height, px'].'px;
    margin: 0 auto;
}

/* Logo */

#logo {
	padding-top: '.$css_config['Logo padding, px'].'px;
    margin-top: 0;
    color: #651262;
}

#logo h1, #logo p {
    margin: 0;
}

#logo h1 {
    float: left;
    padding-left: 20px;
    padding-top: 5px;
    letter-spacing: -1px;
    font-size: 2.9em;
}

#logo p {
    float: right;
    padding: 15px 35px 0 10px;
    font: normal 15px Arial, Helvetica, sans-serif;
    font-style: italic;
    color: #5E4E38;
} 

#logo p a {
    color: #651262;
}

#logo a {
    border: none;
    background: none;
    text-decoration: none;
    color: '.$css_config['Title color'].';
}

/* Search */

#search {
    float: right;
    width: 280px;
    height: 60px;
    padding: 20px 0px 0px 0px;
    background: #E9E3CB;
    border-bottom: 4px solid #FFFFFF;
}

#search form {
    height: 41px;
    margin: 0;
    padding: 10px 0 0 20px;
}

#search fieldset {
    margin: 0;
    padding: 0;
    border: none;
}

#search-text {
    width: 170px;
    padding: 6px 5px 2px 5px;
    border: none;
    background: #FFFFFF;
    text-transform: lowercase;
    font: normal 11px Arial, Helvetica, sans-serif;
    color: #464032;
}

#search-submit {
    width: 50px;
    height: 23px;
    border: 1px solid #525252;
    background: #651262;
    font-weight: bold;
    font-size: 10px;
    color: #FFFFFF;
}

/* Menu */

#menu {
    width: '.$css_config['Skin width, px'].'px;
    height: '.ceil($css_config['Padding links menu, px']*3.6).'px;
    margin: 0 auto;
    padding: 0;
	color: '.$css_config['Menu text color'].'; 
    text-decoration: none; 
    text-shadow: 1px 1px 2px #333; 
    background: '.$css_config['End menu gradient color'].'; 
    background: -webkit-gradient(linear, left top, left bottom, from('.$css_config['Start menu gradient color'].'), to('.$css_config['End menu gradient color'].')); 
    background: -moz-linear-gradient(top, '.$css_config['Start menu gradient color'].',  '.$css_config['End menu gradient color'].'); 
 	background: -o-linear-gradient(top, '.$css_config['Start menu gradient color'].' 0%,'.$css_config['End menu gradient color'].' 100%); 
 	background: -ms-linear-gradient(top, '.$css_config['Start menu gradient color'].' 0%,'.$css_config['End menu gradient color'].' 100%); 
   background: gradient(linear, top,  '.$css_config['Start menu gradient color'].',  '.$css_config['End menu gradient color'].'); 
    border: '.$css_config['The thickness of the border of the menu, px'].'px solid '.$css_config['End menu gradient color'].'; 
    border-radius: '.$css_config['Menu radius, px'].'px; 
}

#menu ul {
    margin-left: '.($css_config['Menu radius, px']-$css_config['The thickness of the border of the menu, px']).'px;
    padding: 0;
    list-style: none;
    line-height: normal;
}

#menu li {
    float: left;
}

#menu a {
    display: block;
    height: '.ceil($css_config['Padding links menu, px']*2.6).'px;
    margin-bottom: 10px;
    margin-right: '.$css_config['The thickness of the border of the menu, px'].'px;
    padding: '.$css_config['Padding links menu, px'].'px '.ceil($css_config['Padding links menu, px']*1.7).'px 0px '.$css_config['Padding links menu, px'].'px;
    text-decoration: none;
    text-align: center;
    vertical-align: middle;
    text-transform: uppercase;
    font-family: Arial, Helvetica, sans-serif;
    font-size: '.$css_config['Menu font size, px'].'px;
    font-weight: bold;
	color: '.$css_config['Menu text color'].'; 
}

#menu a:hover, #menu .current_page_item a {
    text-decoration: none;
}

#menu a:hover, #menu a.active{
    background: '.$css_config['Start menu gradient color'].'; 
    background: -webkit-gradient(linear, left top, left bottom, from('.$css_config['End menu gradient color'].'), to('.$css_config['Start menu gradient color'].')); 
    background: -moz-linear-gradient(top, '.$css_config['End menu gradient color'].',  '.$css_config['Start menu gradient color'].'); 
	background: -o-linear-gradient(top, '.$css_config['End menu gradient color'].' 0%,'.$css_config['Start menu gradient color'].' 100%); 
 	background: -ms-linear-gradient(top, '.$css_config['End menu gradient color'].' 0%,'.$css_config['Start menu gradient color'].' 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient(enabled="true",startColorstr='.$css_config['End menu gradient color'].',endColorstr='.$css_config['Start menu gradient color'].',GradientType=0);
   background: gradient(linear, top,  '.$css_config['End menu gradient color'].',  '.$css_config['Start menu gradient color'].'); 
}

#menu .current_page_item a {
    color: #FFFFFF;
}

.fixed {
	position: fixed;
	top: -'.($css_config['The thickness of the border of the menu, px']-1).'px;
	box-shadow: 0px '.$css_config['Padding links menu, px'].'px '.ceil($css_config['Padding links menu, px']/2).'px '.$css_config['Body background color'].';
	z-index:2;
}
#menu.fixed{
	width:'.(2*$css_config['The thickness of the border of the menu, px']+$css_config['Skin width, px']).'px;
}

a.btn-scroll-top{
background:url(top.png) no-repeat;
display:none;
width:45px;
height:45px;
text-decoration:none;
margin-left:'.round($css_config['Skin width, px']/2).'px;
position:fixed;
top:-2px;
left:50%;
}
a.btn-scroll-top.show{
display:inline;
}
a.btn-scroll-top:hover{
background-position:-44px 0;
}

a.btn-scroll-top.fixed{position:absolute;bottom:-20px;}


/* Page */

#page {
    width: '.$css_config['Skin width, px'].'px;
    margin: 0 auto;
    padding: 0;
}

#page-bgtop {
    padding: 20px 0px;
}

#page-bgbtm {
}

/* Content */

#content {
    float: '.($css_config['Sidebar position']=='right'?'left':'right').';
    width: '.($css_config['Skin width, px']-$css_config['Sidebar width, px']-40).'px;
    padding: 0px 0px 0px 0px;
}

.post {
    margin-bottom: 15px;
}

.post-bgtop {
}

.post-bgbtm {
}

.post .title {
    margin-bottom: 10px;
    padding: 12px 0 0 0px;
    letter-spacing: -.5px;
    color: #000000;
}

.post .title a {
    color: #651262;
    border: none;
}

.post .meta {
    height: 30px;
    border-bottom: 1px solid #DBDBDB;
    background: #F4F4F4;
    margin: 0px;
    padding: 0px 0px 0px 0px;
    text-align: left;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    font-weight: bold;
}

.post .meta .date {
    float: left;
    height: 24px;
    padding: 3px 15px;
    color: #464032;
}

.post .meta .posted {
    float: right;
    height: 24px;
    padding: 3px 15px;
    color: #464032;
}

.post .meta a {
    color: #464032;
}

.post .entry {
    padding: 0px 0px 20px 0px;
    padding-bottom: 20px;
    text-align: justify;
}

.links {
    padding-top: 20px;
    font-size: 12px;
    font-weight: bold;
}

/* Sidebar */

#sidebar {
    float: '.($css_config['Sidebar position']=='right'?'right':'left').';
    width: '.$css_config['Sidebar width, px'].'px;
    padding: 0px;
    color: #787878;
}

#sidebar ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

#sidebar li {
    margin: 0;
    padding: 0;
}

#sidebar li.first_child {
    line-height: 35px;
    border-bottom: 1px dotted #E5E0C6;
    border-right: none;
}

#sidebar li ul {
    margin: 0px 0px;
    padding-bottom: 10px;
}

#sidebar li li {
    line-height: 25px;  
    border-bottom: 1px dotted #E5E0C6;
    margin: 0px 30px;
    border-right: none;
}

#sidebar li a {
    padding-left: 10px;
    background: url(arrow.gif) no-repeat left 5px;
    line-height: 18px;
}

#sidebar li li span {
    display: block;
    margin-top: -20px;
    padding: 0;
    font-size: 11px;
    font-style: italic;
}

#sidebar h2 {
    padding-left: '.($css_config['Sidebar title padding, px']+$css_config['Sidebar title radius, px']).'px;
	padding-top: '.$css_config['Sidebar title padding, px'].'px;
	padding-bottom: '.$css_config['Sidebar title padding, px'].'px;
    font-size: '.$css_config['Sidebar title font size, px'].'px;
    font-weight: bold;
	color: '.$css_config['Menu text color'].'; 
    text-decoration: none; 
    text-shadow: 1px 1px 2px #333; 
    background: '.$css_config['End menu gradient color'].'; 
    background: -webkit-gradient(linear, left top, left bottom, from('.$css_config['Start menu gradient color'].'), to('.$css_config['End menu gradient color'].')); 
    background: -moz-linear-gradient(top, '.$css_config['Start menu gradient color'].',  '.$css_config['End menu gradient color'].'); 
 	background: -o-linear-gradient(top, '.$css_config['Start menu gradient color'].' 0%,'.$css_config['End menu gradient color'].' 100%); 
 	background: -ms-linear-gradient(top, '.$css_config['Start menu gradient color'].' 0%,'.$css_config['End menu gradient color'].' 100%); 
    background: gradient(linear, top,  '.$css_config['Start menu gradient color'].',  '.$css_config['End menu gradient color'].'); 
    border: '.$css_config['The thickness of the border of the menu, px'].'px solid '.$css_config['End menu gradient color'].'; 
    border-radius: '.$css_config['Sidebar title radius, px'].'px; 
}

#sidebar p {
    margin: 0 0px;
    text-align: justify;
}

#sidebar a {
    border: none;
    color: #898989;
}

#sidebar a:hover {
    text-decoration: underline;
    color: #6E6E6E;
}

/* Footer */

#footer {
    width: '.$css_config['Skin width, px'].'px;
    height: 50px;
    margin: 0 auto;
    padding: 0px 0 15px 0;
    border-top: 4px solid #EBE6D1;
    font-family: Arial, Helvetica, sans-serif;
}

#footer p {
    margin: 0;
    padding-top: 20px;
    line-height: normal;
    font-size: 10px;
    text-align: center;
    color: #444444;
}

#footer a {
    color: #464032;
}
';
?>