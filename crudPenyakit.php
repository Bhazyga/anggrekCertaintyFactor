<?php
include 'navbar.php';
include 'crud.php';

$crud = new Crud();

if (isset($_POST['submit_penyakit'])) {
    $current_kode_penyakit = $_POST['current_kode_penyakit'];
    $new_kode_penyakit = $_POST['kode_penyakit']; // new_kode_penyakit should be fetched from the form
    $nama_penyakit = $_POST['nama_penyakit'];

    if ($crud->penyakitExists($current_kode_penyakit)) {
        // Update existing penyakit
        if ($crud->updatePenyakit($current_kode_penyakit, $new_kode_penyakit, $nama_penyakit)) {
            echo "<p style='color: green;'>Penyakit data updated successfully.</p>";
        } else {
            echo "<p style='color: red;'>Failed to update penyakit data.</p>";
        }
    } else {
        // Insert new penyakit
        if ($crud->insertPenyakit($new_kode_penyakit, $nama_penyakit)) {
            echo "<p style='color: green;'>Penyakit data inserted successfully.</p>";
        } else {
            echo "<p style='color: red;'>Failed to insert penyakit data.</p>";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['kode_penyakit'];
    if ($crud->deletePenyakitById($id)) {
        echo "<p style='color: green;'>Penyakit data deleted successfully.</p>";
    } else {
        echo "<p style='color: red;'>Failed to delete penyakit data.</p>";
    }
}

$penyakitData = $crud->fetchAllPenyakit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Penyakit Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-top: 100px;
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
        input[type="text"], input[type="number"] {
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
        function editRow(kode_penyakit, nama_penyakit) {
            document.getElementById('current_kode_penyakit').value = kode_penyakit;
            document.getElementById('kode_penyakit').value = kode_penyakit;
            document.getElementById('nama_penyakit').value = nama_penyakit;
        }
    </script>
</head>
<body>
    <h1>Admin Penyakit Data</h1>
    
    <div class="container">
        <div class="forms">
            <h2>Insert or Update Penyakit Data</h2>
            <form action="crudPenyakit.php" method="POST">
                <input type="hidden" id="current_kode_penyakit" name="current_kode_penyakit">
                <label for="kode_penyakit">Kode Penyakit:</label>
                <input type="text" id="kode_penyakit" name="kode_penyakit" required>
                
                <label for="nama_penyakit">Nama Penyakit:</label>
                <input type="text" id="nama_penyakit" name="nama_penyakit" required>
                
                <input type="submit" name="submit_penyakit" value="Submit">
            </form>
        </div>
        
        <div class="tables">
            <h2>Data Penyakit Table</h2>
            <table>
                <tr>
                    <th>Kode Penyakit</th>
                    <th>Nama Penyakit</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($penyakitData as $penyakit) : ?>
                <tr>
                    <td><?php echo $penyakit['kode_penyakit']; ?></td>
                    <td><?php echo $penyakit['nama_penyakit']; ?></td>
                    <td class="action-buttons">
                        <button onclick="editRow('<?php echo $penyakit['kode_penyakit']; ?>', '<?php echo $penyakit['nama_penyakit']; ?>')">Edit</button>
                        <a href="crudPenyakit.php?delete=true&kode_penyakit=<?php echo $penyakit['kode_penyakit']; ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
