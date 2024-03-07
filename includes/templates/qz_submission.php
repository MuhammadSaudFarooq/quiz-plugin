<?php
global $wpdb;
$tablename = PLUGIN_PREFIX . '_entries';
$query = "SELECT * FROM $tablename ORDER BY DESC";
$result = $wpdb->get_results($query);

echo "<pre>";
echo "</pre>";
?>
<div class="container-fluid">
    <div class="mt-4">
        <h3>Quiz Entries</h3>
    </div>
    <div class="mt-4">
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Body Part</th>
                    <th>Date</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <!-- <tr>
                    <td>John Doe</td>
                    <td>johndoe@gmail.com</td>
                    <td>123456789</td>
                    <td>Shoulder</td>
                    <td>07-03-2024</td>
                    <td><a href="javascript:void(0)"><i class="fa fa-external-link"></i></a></td>
                </tr> -->
            </tbody>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Body Part</th>
                    <th>Date</th>
                    <th>View</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>