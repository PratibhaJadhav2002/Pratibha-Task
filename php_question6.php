/*
Writing a Custom PHP Function
Write a function in PHP that converts a string like "camelCaseString" to "camel case string".
*/


<?PHP
function camelCaseToWords($input) {
return strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $input));
}

echo camelCaseToWords("camelCaseString");

?>