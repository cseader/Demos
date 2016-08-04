<html>
 <head>
  <title>OpenStack Days</title>
 </head>
 <body>
 <?php

if (version_compare(PHP_VERSION, '5.4.0') <= 0) {
   
    echo '<table width="100%" align="center">'; 
    echo '<tr> '; 
    echo '<td align="center"><img src="/openstack-days-SV.png" border=0 /></td>' . "\n";
    echo '</tr> ';
    echo '</table>';
    echo '' . "\n";
    echo '<font size="6"><center> ';
    echo 'I am at least PHP version 5.3.0, my version: ' . PHP_VERSION . "\n";
    echo '</center></font> ';
}

if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
    
    echo '<table width="100%" align="center">'; 
    echo '<tr> '; 
    echo '<td align="center"><img src="/suse_logo_color_lrg.png" border=0 height=150px /></td> ' . "\n";
    echo '</tr> ';
    echo '<tr> ';
    echo '<td height="65"> '; 
    echo '</tr> ';
    echo '<tr> '; 
    echo '<td align="center"><img src="/green-heart-md.png" border=0 height=100px /></td> ' . "\n";
    echo '</tr> ';
    echo '<tr> '; 
    echo '<td align="center"><img src="/openstack-days-SV.png" border=0 /></td>' . "\n";
    echo '</tr> ';
    echo '</table>';
    echo '' . "\n";
    echo '<font size="6"><center> ';
    echo 'I am at least PHP version 7.0.0, my version: ' . PHP_VERSION . "\n";
    echo '</center></font> ';
}

 ?> 
 </body>
</html>
