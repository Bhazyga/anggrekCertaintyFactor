<?php
include 'navbar.php';
include 'crud.php';

$crud = new Crud();

if (isset($_POST['submit_pengetahuan'])) {
    $id_gejala = $_POST['id_gejala'];
    $kode_penyakit = $_POST['kode_penyakit'];
    $mb = $_POST['mb'];
    $md = $_POST['md'];

    if ($_POST['id']) {
        $id = $_POST['id'];
        if ($crud->updatePengetahuan($id, $id_gejala, $kode_penyakit, $mb, $md)) {
            echo "<p style='color: green;'>Pengetahuan data updated successfully.</p>";
        } else {
            echo "<p style='color: red;'>Failed to update pengetahuan data.</p>";
        }
    } else {
        if ($crud->insertPengetahuan($id_gejala, $kode_penyakit, $mb, $md)) {
            echo "<p style='color: green;'>Pengetahuan data inserted successfully.</p>";
        } else {
            echo "<p style='color: red;'>Failed to insert pengetahuan data.</p>";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    if ($crud->deleteById('pengetahuan', $id)) {
        echo "<p style='color: green;'>Data deleted successfully.</p>";
    } else {
        echo "<p style='color: red;'>Failed to delete data.</p>";
    }
}

$pengetahuanData = $crud->fetchAll('pengetahuan');
$gejalaData = $crud->fetchAll('gejala');
$penyakitData = $crud->fetchAll('penyakit');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Data Entry</title>
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
        function editRow(id, id_gejala, kode_penyakit, mb, md) {
            document.getElementById('id').value = id;
            document.getElementById('id_gejala').value = id_gejala;
            document.getElementById('kode_penyakit').value = kode_penyakit;
            document.getElementById('mb').value = mb;
            document.getElementById('md').value = md;
        }
    </script>
</head>
<body>
    <h1>Admin Data Entry</h1>
    
    <div class="container">
        <div class="forms">
            <h2>Add Pengetahuan</h2>
            <form action="crudPengetahuan.php" method="POST">
                <label for="id_gejala">ID Gejala:</label>
                <select id="id_gejala" name="id_gejala" required>
                    <?php foreach ($gejalaData as $gejala) : ?>
                        <option value="<?php echo $gejala['id_gejala']; ?>"><?php echo $gejala['nama_gejala']; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label for="kode_penyakit">Kode Penyakit:</label>
                <select id="kode_penyakit" name="kode_penyakit" required>
                    <?php foreach ($penyakitData as $penyakit) : ?>
                        <option value="<?php echo $penyakit['kode_penyakit']; ?>"><?php echo $penyakit['kode_penyakit']; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label for="mb">MB:</label>
                <input type="number" step="0.01" id="mb" name="mb" required>
                <label for="md">MD:</label>
                <input type="number" step="0.01" id="md" name="md" required>
                <input type="hidden" id="id" name="id">
                <input type="submit" name="submit_pengetahuan" value="Submit">
            </form>
        </div>
        
        <div class="tables">
            <h2>Pengetahuan Table</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>ID Gejala</th>
                    <th>Kode Penyakit</th>
                    <th>MB</th>
                    <th>MD</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($pengetahuanData as $pengetahuan) : ?>
                <tr>
                    <td><?php echo $pengetahuan['id_pengetahuan']; ?></td>
                    <td><?php echo $pengetahuan['id_gejala']; ?></td>
                    <td><?php echo $pengetahuan['kode_penyakit']; ?></td>
                    <td><?php echo $pengetahuan['mb']; ?></td>
                    <td><?php echo $pengetahuan['md']; ?></td>
                    <td class="action-buttons">
                        <button onclick="editRow('<?php echo $pengetahuan['id_pengetahuan']; ?>', '<?php echo $pengetahuan['id_gejala']; ?>', '<?php echo $pengetahuan['kode_penyakit']; ?>', '<?php echo $pengetahuan['mb']; ?>', '<?php echo $pengetahuan['md']; ?>')">Edit</button>
                        <a href="crudPengetahuan.php?delete=true&id=<?php echo $pengetahuan['id_pengetahuan']; ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
