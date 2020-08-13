<?php header('Content-Type: text/html; ');
require_once('core/database.class.php');
require_once('core/company.class.php');
$bd = new db_class();
$db_link = $bd->db_connect();

//echo $str ="<table border=1> <tr><td>State</td><td>State Link</td><td>City</td><td>Link</td></tr>";
echo $str ="<table border=1> <tr><td>State </td><td>State Link</td><td>City</td><td>Company</td><td>Link</td><td>No of Reviews</td></tr>";


$sql2 = mysql_query( "SELECT state_code,lower(name) as name FROM states where usa_state=1 GROUP BY name");

while($row = mysql_fetch_array($sql2))
{
        $sta=$row['state_code'];
        $sname = strtolower($row['name']);
        $stname = str_replace(' ', '-', $sname);


        $sql = "SELECT companies.title,companies.id,companies.city,companies.address,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies     where  state='$sta'   ";
        $reslt = mysql_query($sql);
        
            while($res_comp_city=mysql_fetch_array($reslt))
            {
                $companiesid = $res_comp_city['id'];

                $sql_reviewcount=mysql_query("select * from reviews where company_id= '$companiesid'");
                $res_reviewcount=mysql_num_rows($sql_reviewcount);
                
                $compnay_address=explode(",",$res_comp_city['address']);
                $countarray=count($compnay_address);
                $compnay_address_zip=explode(" ",$compnay_address[$countarray-2]);
                $comp_name = str_replace('/','-',str_replace(' ', '-', $res_comp_city["title"]));
            ?><tr>
                <td><?php echo $row['name']; ?> </td>
                <td>https://www.topmovingreviews.com/usa/<?php echo str_replace(" ","-",$stname); ?>-movers-<?php echo $sta; ?>/</td>
                <td><?php echo $res_comp_city["city"];?></td>
                <td><?php echo $comp_name;?></td>
                <td><?php echo 'https://www.topmovingreviews.com/movers/'.$res_comp_city["title"] .'-'.$res_comp_city["id"].'/' ;?></td>
                <td><?php echo $res_reviewcount ;?></td>
              </tr>

        <?php   
        
            } 
        } 
        ?>

