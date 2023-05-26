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
* Using the points system and subscriptions with the package:
```
<?php
// Examples by chatGPT with Ali

require_once 'base.php'; // import module class

$store = new KeyValueStore('my_database.sqlite'); // add db filename

date_default_timezone_set("Asia/Baghdad");

function get2point2days($user) {
    global $store;
  
    $person = $store->get($user);
    echo $person;
    $person=json_decode($person, true);
    
    if ($person !== null) {
        $earlier = new DateTime("now");
        $later = new DateTime($person['subscription_date']);
        $abs_diff = $later->diff($earlier)->format("%a");

        if ($person['points'] < 1) {
            $store->delete("points_$user");
            return [0, 0];
        } elseif ($abs_diff < 1) {
            $store->delete($user);
            return [0, 0];
        } else {
            return [$person['points'], $abs_diff + 1];
        }
    } else {
        return [0, 0];
    }
}

function del2point2days($user) {
    global $store;

    $person = $store->get($user);
    $person=json_decode($person, true);
    if ($person !== null) {
        $apoints = $person['points'] - 1;
        $person['points'] = $apoints;

        $earlier = new DateTime("now");
        $later = new DateTime($person['subscription_date']);
        $abs_diff = $later->diff($earlier)->format("%a");

        $store->set($user, $person);

        return [$apoints, $abs_diff + 1];
    }
    
    return [0, 0];
}

function add2point2days($user, $points, $days   ,$name) {
    global $store;

    $person = $store->get($user);
    $person=json_decode($person, true);

    $earlier = new DateTime("now");
    $later = new DateTime();
    $abs_diff = 0;
    $subscription_count=1;
    if ($person !== null) {
        $later = new DateTime($person['subscription_date']);
        $abs_diff = $later->diff($earlier)->format("%a");
        $subscription_count=$person['subscription_count']  +  1;
    };

    $adays = $days + $abs_diff + 1;
    $apoints = $points;

    if ($person !== null) {
        $apoints += $person['points'];
    }

    $dt = date("Y-m-d");
    $subscription_date = date("Y-m-d", strtotime("$dt +$adays day"));

    $person = [
        'id' => $user,
        'points' => $apoints,
        'days_remaining' => $adays,
        'subscription_date' => $subscription_date,
        'name' => $name,
        'subscription_count' => $subscription_count,
        'last_subscription' => $dt
    ];

    $store->set($user, $person);

    return [$apoints, $adays];
}




$user=123;

//echo get2point2days($user) ;
//[0,0]   ,   
// {"id":123,"points":9,"days_remaining":18,"subscription_date":"2023-06-13","name":"Ali","subscription_count":2,"last_subscription":"2023-05-26"}

//del2point2days($user);

//{"id":123,"points":8,"days_remaining":18,"subscription_date":"2023-06-13","name":"Ali","subscription_count":2,"last_subscription":"2023-05-26"}


//add2point2days($user,2, 5   ,"Ali");
//{"id":123,"points":11,"days_remaining":23,"subscription_date":"2023-06-18","name":"Ali","subscription_count":3,"last_subscription":"2023-05-26"}



?>
```
