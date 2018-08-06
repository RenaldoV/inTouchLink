<div class="col-sm-12">
    <h1 class="text-center">Admin</h1>
    <h4 class="text-center" style="color:#757575">Store History</h4>
</div>
<div class="col-sm-8 col-sm-offset-2">
    <table class="table table-condensed table-striped table-bordered">
        <tr>
            <th>strname</th>
            <th>strVersion2</th>
            <th>mSumDate</th>
        </tr>

        <?php
        $result = GetStoreHistory(1); // Get user's stores
        while($row = mysql_fetch_array($result)) {
            echo "<tr>";
            echo "<th>".$row["strname"]."</th>";
            echo "<th>".$row["strVersion2"]."</th>";
            echo "<th>".$row["mSumDate"]."</th>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
