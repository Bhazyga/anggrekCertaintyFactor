<?php
include 'navbar.php';
include 'crud.php';

$crudGejala = new crud();

// Handle form submissions
if (isset($_POST['submit_gejala'])) {
    $nama_gejala = $_POST['nama_gejala'];

    if ($_POST['id']) {
        $id = $_POST['id'];
        if ($crudGejala->updateGejala($id, $nama_gejala)) {
            echo "<p style='color: green;'>Gejala updated successfully.</p>";
        } else {
            echo "<p style='color: red;'>Failed to update gejala.</p>";
        }
    } else {
        if ($crudGejala->insertGejala($nama_gejala)) {
            echo "<p style='color: green;'>Gejala inserted successfully.</p>";
        } else {
            echo "<p style='color: red;'>Failed to insert gejala.</p>";
        }
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    if ($crudGejala->deleteGejala($id)) {
        echo "<p style='color: green;'>Gejala deleted successfully.</p>";
    } else {
        echo "<p style='color: red;'>Failed to delete gejala.</p>";
    }
}

// Fetch all gejala data
$gejalaData = $crudGejala->fetchAllGejala();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Gejala</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .forms {
            flex: 1;
            margin-right: 20px;
        }
        .tables {
            flex: 1;
        }
        form {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
    <script>
        function editRow(id, nama_gejala) {
            document.getElementById('id').value = id;
            document.getElementById('nama_gejala').value = nama_gejala;
        }
    </script>
</head>
<body>
    <h1>Admin - Manage Gejala</h1>
    
    <div class="container">
        <div class="forms">
            <h2>Add/Edit Gejala</h2>
            <form action="crudGejala.php" method="POST">
                <label for="nama_gejala">Nama Gejala:</label>
                <input type="text" id="nama_gejala" name="nama_gejala" required>
                <input type="hidden" id="id" name="id">
                <input type="submit" name="submit_gejala" value="Submit">
            </form>
        </div>
        
        <div class="tables">
            <h2>Gejala Table</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama Gejala</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($gejalaData as $gejala) : ?>
                <tr>
                    <td><?php echo $gejala['id_gejala']; ?></td>
                    <td><?php echo $gejala['nama_gejala']; ?></td>
                    <td class="action-buttons">
                        <button onclick="editRow('<?php echo $gejala['id_gejala']; ?>', '<?php echo $gejala['nama_gejala']; ?>')">Edit</button>
                        <a href="crudGejala.php?delete=true&id=<?php echo $gejala['id_gejala']; ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
