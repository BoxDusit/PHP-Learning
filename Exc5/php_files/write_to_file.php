<?php
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = "โก๊ยโต่ยออนเดอะมีน\n";
fwrite($myfile, $txt);
$txt = "ซีบองซองดูจิม\n";
fwrite($myfile, $txt);
fclose($myfile);
echo"บัณทึกข้อมูลเรียบร้อย";
?>