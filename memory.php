<?php


print_mem();





function print_mem(){
   /* Currently used memory */
   $mem_usage = memory_get_usage();
   $mem_usage_allo = memory_get_usage(true);
   
   /* Peak memory usage */
   $mem_peak = memory_get_peak_usage();
   
   echo "Using => " . round($mem_usage / 1024) . "kb<br/>";
   echo "But PHP allocated: ". round($mem_usage_allo / 1024) . "kb<br/>";
   echo "And the Peak of memory usage is: ". round($mem_peak / 1024) . "kb";
   
}
?>