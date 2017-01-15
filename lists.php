<html>
<head>
    <script type="text/javascript">
        function getStates(f1, f2) {
            var f1 = document.getElementById(f1);
            var f2 = document.getElementById(f2);
            f2.innerHTML = "";


        }
    </script>
</head>
<body>
<p>
    <select id="formV" name="formV" onchange="window.getStates(this.id, 'formC')">
        <option value="">Wybierz województwo</option>
        <?php
        include 'read_database.php';
        $db = new SQLite3('ludność.db');
        $voivodehips = getAllVoivedeships($db);
        while ($x = $voivodehips->fetchArray()) { ?>
            <option value="<?php $x[0] ?>"><?php   echo $x[0]; ?></option>
            <?php
        }
        ?>
    </select>
</p>

<p>
    <select id="formC" name="formC">
        <option value="">Wybierz powiat</option>
        <?php
        if (isset($_POST['form'])) {
            $voivodeship = $_POST['form'];
            echo $voivodeship;
            $all = selectAllVoivodeship($voivodeship, $db);
            while ($x = $all->fetchArray()) { ?>
                <option value="<?php $x['Powiat'] ?>"><?php echo $x['Powiat']; ?></option>
                <?php
            }
        }
        ?>
    </select>
</p>
</body>
</html>
