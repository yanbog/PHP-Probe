<?php

echo "<pre>";

// print_r(get_loaded_extensions());

print_r(get_defined_functions());

$func = get_defined_functions();
print_r($func['internal']);

?>
