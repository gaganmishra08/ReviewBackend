<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Name</th>
            <th>Company</th>
            <th>Email</th>
        </tr>
    </thead>

    <tbody>
        <?php
            foreach ($data as $row) {
                # code...
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['username']."</td>";
                echo "<td>".$row['first_name'].' '.$row['last_name']."</td>";
                echo "<td>".$row['company']."</td>";
                echo "<td>".$row['email']."</td>";
                // echo "<td>
                //       <a href='user/feedback_read/".$row['id']."'><button type='button' class='btn btn-inline btn-warning'>Mark Read</button></a>
                //   </td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>
