<?php
session_start();
include 'navbar.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'Crud.php';
$crud = new Crud();
$arrayName = $crud->readGejala();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periksa Gejala Menggunakan (Certainty Factor)</title>
    <style>
    body {
        background-color: #f0f0f0;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .container {
        width: 80%;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle box shadow */
    }
    th, td {
        padding: 12px; /* Increased padding for better spacing */
        text-align: left; /* Left-align text in cells */
        border: 1px solid #ddd;
    }
    th {
        background-color: #DBEAF5; /* Light blue background for header cells */
    }
    tr:nth-child(even) {
        background-color: #f2f2f2; /* Alternating row background color */
    }
    select {
        width: calc(100% - 10px); /* Adjust width to fit in table cell with padding */
        padding: 5px;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
</style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function EnableDisableTextBox(gejalaId) {
            var kondisi = document.getElementById("kondisi" + gejalaId);
            var isChecked = document.getElementById('gejala' + gejalaId).checked;

            kondisi.disabled = !isChecked;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Periksa Gejala Menggunakan (Certainty Factor)</h1>
        <form name="form1" method="post" action="hasil.php">
            <table>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>GEJALA</th>
                        <th>KONDISI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arrayName as $r) { ?>
                    <tr>
                        <td><?php echo $r['id_gejala']; ?></td>
                        <td>
                            <label for="gejala<?php echo $r['id_gejala']; ?>">
                                <?php echo $r['nama_gejala']; ?>
                            </label>
                        </td>
                        <td>
                            <input id="gejala<?php echo $r['id_gejala']; ?>" name="gejala[]" type="checkbox" value="<?php echo $r['id_gejala']; ?>" onclick="EnableDisableTextBox(<?php echo $r['id_gejala']; ?>)">
                            <select id="kondisi<?php echo $r['id_gejala']; ?>" name="kondisi[]" disabled>
                                <option value="1.0">PASTI IYA</option>
                                <option value="0.8">HAMPIR PASTI IYA</option>
                                <option value="0.6">KEMUNGKINAN BESAR IYA</option>
                                <option value="0.4">MUNGKIN IYA</option>
                                <option value="0">TIDAK TAHU</option>
                                <option value="-0.4">MUNGKIN TIDAK</option>
                                <option value="-0.6">KEMUNGKINAN BESAR TIDAK</option>
                                <option value="-0.8">HAMPIR PASTI TIDAK</option>
                                <option value="-1.0">PASTI TIDAK</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3" align="center">
                            <input type="submit" name="button" value="Proses">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
