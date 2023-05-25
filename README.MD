* That is An example for use package:
```
<?php
// Examples by chatGPT 

require_once 'base.php'; // import module class

$store = new KeyValueStore('my_database.sqlite'); // add db filename

// Set key-value pairs
$store->set('name', 'John Doe');
$store->set('age', 30);
$store->set('city', 'New York');

// Get the value by key
$value = $store->get('name');
echo "Name: " . $value . PHP_EOL;  

// Check if a key exists
$exists = $store->exists('age');
echo $exists ? 'Age exists' : 'Age does not exist'; 

// Delete a key
$store->delete('city');

// Get all keys
$keys = $store->keys();
echo "Keys: " . implode(', ', $keys) . PHP_EOL;  

// Set a new key-value pair
$store->set('occupation', 'Developer');

// Get all keys again
$keys = $store->keys();
echo "Keys: " . implode(', ', $keys) . PHP_EOL;  

// Get the value of a non-existing key
$value = $store->get('gender');
echo "Gender: " . ($value !== null ? $value : 'Not found') . PHP_EOL; 



//// Its accept to set multi types:
$store->set('name', 'John Doe');       // String value
$store->set('numbers', [1, 2, 3]);     // Array value
$store->set('person', ['name' => 'John', 'age' => 30]);  // Associative array value
//get person

$person = $store->get('person');
echo json_decode($person, true)["age"];

$store->set('is_admin', true);         // Boolean value
$store->set('age', 25);                // Integer value



?>

```
