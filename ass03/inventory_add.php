<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php


require_once('myProPerty_session.php');
require_once('myProPerty_header.php');
require_once('adverts.php');

// Function to sanitize user input
function validate($value)
{
    return htmlspecialchars($value);
}

// Function to echo sticky form value
function stickyValue($value)
{
    echo (isset($_POST[strval($value)]) ? $_POST[strval($value)] : "");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $propertyID = isset($_POST['propertyID']) ? validate($_POST['propertyID']) : null;

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE propertyID = ?");
    if (!$stmt) {
        die("Error preparing SQL query: " . $conn->error);
    }

    // Bind parameter and execute query
    $stmt->bind_param("i", $propertyID);
    if (!$stmt->execute()) {
        die("Error executing SQL query: " . $stmt->error);
    }

    // Get result set
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Inventory items found, display them
        echo "<div class='container'>";
        echo "<h2 class='mt-5'>Inventory Items</h2>";
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>Inventory ID</th><th>Property ID</th><th>Attributes</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['inventoryID'] . "</td>";
            echo "<td>" . $row['propertyID'] . "</td>";
            echo "<td>";
            echo "<ul>";
            foreach ($row as $key => $value) {
                if ($key !== 'inventoryID' && $key !== 'propertyID') {
                    echo "<li>$key: " . ($value ? 'Yes' : 'No') . "</li>";
                }
            }
            echo "</ul>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        // No inventory items found
        echo "<div class='alert alert-warning mt-5' role='alert'>No inventory items found for the given property ID.</div>";
    }

    // Close prepared statement
    $stmt->close();
}
?>
<div class="container dashboard-container">
    <h1 class="text-center">Search Property and View Inventory</h1>
    <!-- Filtering Form -->
    <div class="container container-sm">
        <div class="row justify-content-center">
            <form class="container col-sm-8 col-lg-8 my-5" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" novalidate>
                <div class="mb-3">
                    <label for="propertyID" class="form-label">Property ID</label>
                    <input type="text" name="propertyID" id="propertyID" class="form-control" placeholder="Property ID" value="<?php stickyValue('propertyID'); ?>">
                </div>
                <button type="submit" name="search" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($inventoryItems) && !empty($inventoryItems)) : ?>
    <div class='container col-lg-8 card p-3 mb-3'>
        <h2 class="text-center">Inventory Items</h2>
        <div class="row row-cols-2">
            <?php foreach ($inventoryItems as $key => $item) : ?>
                <?php if ($key % 2 == 0) : ?>
                    </div>
                    <div class="row row-cols-2">
                <?php endif; ?>
                <div class="col">
                    <div class="property-entry">
                        <table class='table table-striped' style='width:100%'>
                            <tbody>
                                <tr>
                                    <th scope='col'>Inventory ID</th>
                                    <td scope='row'><?php echo $item['inventoryID']; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>Electricity</th>
                                    <td scope='row'><?php echo $item['electricity'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>Internet</th>
                                    <td scope='row'><?php echo $item['internet'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>Oven</th>
                                    <td scope='row'><?php echo $item['oven'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>TV</th>
                                    <td scope='row'><?php echo $item['TV'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>Heater</th>
                                    <td scope='row'><?php echo $item['heater'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>Bills</th>
                                    <td scope='row'><?php echo $item['bills'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>Washing Machine</th>
                                    <td scope='row'><?php echo $item['washingMachine'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th scope='col'>Drying Machine</th>
                                    <td scope='row'><?php echo $item['dryingMachine'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php elseif (isset($inventoryItems) && empty($inventoryItems)) : ?>
    <div class='alert alert-warning' role='alert'>
        <?php echo $errors[0]; ?>
    </div>
<?php endif; ?>

</body>
</html>
