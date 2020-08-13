<?php header('Content-Type: text/html; ');
require_once('core/database.class.php');
require_once('core/company.class.php');
$bd = new db_class();
$db_link = $bd->db_connect();
// Turn off error reporting
error_reporting(0);
//echo $str ="<table border=1> <tr><td>State</td><td>State Link</td><td>City</td><td>Link</td></tr>";
echo $str ="<table border=1> <tr><td>State </td><td>State Link</td><td>City</td><td>City Link</td><td>Population</td><td>No of Reviews</td></tr>";


$sql2 = mysql_query( "SELECT state_code, lower(name) as name FROM states where usa_state=1 GROUP BY name");

while($row = mysql_fetch_array($sql2))
{
        $sta=$row['state_code'];
        $sname = strtolower($row['name']);
        $stname = str_replace(' ', '-', $sname);


//        $sql = "SELECT city ,population FROM `us_city_population`  where  state_code = '$sta'
//                ORDER BY `us_city_population`.`density`  DESC   ";
        
        $sql_city="SELECT  city,state_name,zips,population FROM `us_city_population` where state_code='$sta' order by population DESC ";
        
        $query_city=mysql_query($sql_city);
        while($res_city=mysql_fetch_assoc($query_city))
        {

                $city           = $res_city['city'];
                $population     = $res_city['population'];
                
                if(strlen($res_city['zips'])==4)
                        $res_city['zips']="0".$res_city['zips'];
                if(strlen($res_city['zips'])==3)
                        $res_city['zips']="00".$res_city['zips'];
/*echo "
                                    select 
                                        count(*) as reviewcnt  
                                    from 
                                        reviews  
                                    inner join 
                                        compnaies 
                                    on 
                                        reviews.company_id =companies.id   
                                    where city like '%$city%' and state =  '$sta' ";die;*/
                $sql_reviewcount=mysql_query("
                                    select 
                                        count(*) as reviewcnt  
                                    from 
                                        reviews  
                                    inner join 
                                        companies  
                                    on 
                                        reviews.company_id =companies .id   
                                    where city like '%$city%' and state =  '$sta' ");
                $res_reviewco=mysql_fetch_array($sql_reviewcount);
                $res_reviewcount = $res_reviewco['reviewcnt'];
                
            ?><tr>
                <td><?php echo $row['name']; ?> </td>
                <td>https://www.topmovingreviews.com/usa/<?php echo str_replace(" ","-",$stname); ?>-movers-<?php echo $sta; ?>/</td>
                <td> <?php echo $city; ?></td>
                <td>https://www.topmovingreviews.com/moving-companies/<?php echo str_replace(" ","-",ltrim($res_city['city']))."-".$sta."-".$res_city['zips']; ?>/</td>
                <td><?php echo $population;?></td>
                <td><?php echo $res_reviewcount ;?></td>
              </tr>

        <?php   
        
            } 
        } 
        ?>

