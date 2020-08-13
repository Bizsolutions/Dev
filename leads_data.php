<?php
require_once('core/database.class.php');
require_once('core/company.class.php');
$bd = new db_class();
$db_link = $bd->db_connect();

$sql=mysql_query("SELECT * FROM lead_history order by lead_history_id desc"); 

echo "<table border=1>";
echo "<thead>";
echo "<tr>";
    echo "<td>ID</td>";
    echo "<td>LEAD STATUS</td>";
    echo "<td>ERROR</td>";
    echo "<td>FIRST NAME</td>";
    echo "<td>LAST NAME</td>";
    echo "<td>PHONE</td>";
    echo "<td>EMAIL</td>";
    echo "<td>IP</td>";
    echo "<td>FROM CITY</td>";
    echo "<td>FROM STATE</td>";
    echo "<td>FROM ZIP</td>";
    echo "<td>TO CITY</td>";
    echo "<td>TO STATE</td>";
    echo "<td>TO ZIP</td>";
    echo "<td>MOVE SIZE</td>";
    echo "<td>MOVE DATE</td>";
    echo "<td>MESSAGE</td>";
    echo "<td>POST DATETIME</td>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while($result=mysql_fetch_array($sql))
{
echo "<tr>";
    echo "<td>".$result['lead_history_id']."</td>";
    echo "<td>".$result['lead_history_post_status']."</td>";
    echo "<td>".$result['lead_history_ping_error']."</td>";
    echo "<td>".$result['first_name']."</td>";
    echo "<td>".$result['last_name']."</td>";
    echo "<td>".$result['phone_home']."</td>";
    echo "<td>".$result['email_address']."</td>";
    echo "<td>".$result['ip_address']."</td>";
    echo "<td>".$result['city']."</td>";
    echo "<td>".$result['state']."</td>";
    echo "<td>".$result['zip_code']."</td>";
    echo "<td>".$result['to_city']."</td>";
    echo "<td>".$result['to_state']."</td>";
    echo "<td>".$result['to_zip_code']."</td>";
    echo "<td>".$result['move_size']."</td>";
    echo "<td>".$result['move_date']."</td>";
    echo "<td>".$result['message']."</td>";
    echo "<td>".$result['lead_history_datetime']."</td>";
echo "</tr>";

}
echo "<tbody>";
echo "</table>";

?>