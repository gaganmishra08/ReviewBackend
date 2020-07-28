<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Feedback</th>
            <th>Rate</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php
            foreach ($data as $row) {
                # code...
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['feedback']."</td>";
                echo "<td>".$row['rated']."</td>";
                echo "<td>
                      <a href='user/feedback_read/".$row['id']."'><button type='button' class='btn btn-inline btn-warning'>Mark Read</button></a>
                  </td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>
