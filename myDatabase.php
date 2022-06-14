<?php



require 'DB.php';
$dB = DB::connect('mysql://root:root@localhost');
if(DB::isError($dB)){
    die('Connection to database failed' . $dB->getMessage());
}
$dB->setErrorHandling(PEAR_ERROR_DIE);
$go=$_SERVER['PHP_SELF'];


$dB->query('CREATE DATABASE IF NOT EXISTS flower');

$dB->query('USE flower');

$dB->query("CREATE TABLE  IF NOT EXISTS category
(
  id INT AUTO_INCREMENT ,
  catNAme varchar (255),
 PRIMARY KEY(id)
)
");

$dB->query(
  "CREATE TABLE IF NOT EXISTS flowers 
  (
  ID int(11)  AUTO_INCREMENT,
  flowerName varchar(55),
  oldPrice decimal(4,2),
  newPrice decimal(4,2),
  image varchar(255),
  description varchar(255),
  categoryNAme varchar(255),
  primary key(ID),
  CONSTRAINT cat_flower FOREIGN KEY(categoryNAme) REFERENCES category (catNAme)
)
"
);
/*
$dB->query(
  "CREATE TABLE IF NOT EXISTS customer 
  (
  ID int(11)  AUTO_INCREMENT,
  custname varchar(255),
  street varchar(255),
  town varchar(255),
  telephone varchar(255),
  primary key(ID)
)
"
);
*/



$dB->query(
  "CREATE TABLE IF NOT EXISTS orders 
  (
  ID int(11)  AUTO_INCREMENT,
  flowerid int(11),
  price decimal(4,2),
  cstreet VARCHAR(255),
  ctown VARCHAR(255),
  cpostalcode VARCHAR(255),
  cphone VARCHAR(255),
  primary key(ID),
  CONSTRAINT flower_ord FOREIGN KEY(flowerid) REFERENCES flowers (ID)
)
"
);



# REMOVE THE COMMENTS FROM THE NEXT LINES IN THE FIRST TIME AND THEN COMMNET THEM AGAIN.

#$dB->query("INSERT INTO category (catNAme )  VALUES ('Birthday flowers')");
#$dB->query("INSERT INTO category (catNAme )  VALUES ('Next day flowers')");
#$dB->query("INSERT INTO category (catNAme )  VALUES ('Sympathy Flowers')");
#$dB->query("INSERT INTO category (catNAme )  VALUES ('Best sellers')");
#$dB->query("INSERT INTO  flowers (flowerName,oldPrice,newPrice,image,description,categoryNAme) VALUES('Glorious',41.99,26.99,'NF1019_2.jpg','This flowers is best flowers in france','Next day flowers')");
#$dB->query("INSERT INTO  flowers (flowerName,oldPrice,newPrice,image,description,categoryNAme) VALUES('Joyful',37.00,24.99,'NF1015_2.jpg','zgrzgiya ouwhuf hw oiaj oawzh f oahzrgo uza ohagw','Sympathy Flowers')");
#$dB->query("INSERT INTO  flowers (flowerName,oldPrice,newPrice,image,description,categoryNAme) VALUES('Rose Calore',36.99 ,26.99,'dd180416.jpg','zgrzgiyzsflnSOIFhozisgozgbozluza ohagw','Best sellers')");
#$dB->query("INSERT INTO  flowers (flowerName,oldPrice,newPrice,image,description,categoryNAme) VALUES('Rose $ Lily Hand Tied',34.99 ,19.99,'NF1010_1.jpg','zgrzshezer ghwahita hzrgo uza ohagw','Birthday flowers')");
#$dB->query("INSERT INTO  flowers (flowerName,oldPrice,newPrice,image,description,categoryNAme) VALUES('Paris',37.00,24.99,'HF0315.jpg','zgrzggzrgzr zr gzr hseh rd thjdrhagw','Best sellers')");
#dB->query("INSERT INTO  flowers (flowerName,oldPrice,newPrice,image,description,categoryNAme) VALUES('Onyx',37.00,24.99,'HF0119_1.jpg','zgr 6u sdyhdsy dsy6sr ut hxrt hrtx uza ohagw','Next day flowers')");
#$dB->query("INSERT INTO  flowers (flowerName,oldPrice,newPrice,image,description,categoryNAme) VALUES('new york',37.00,24.99,'HF0815.jpg','zgrse 5ys5 tiuahf iuaw iuwge friwga za ohagw','Birthday flowers')");
#$dB->query("UPDATE flowers set newPrice = 0 WHERE flowerName = 'new york' OR flowerName = 'Onyx' OR flowerName = 'Paris'");
#$dB->query(" INSERT INTO  orders (flowerid,price,cstreet,ctown,cpostalcode,cphone) VALUES( 4,19.99,'schools','elbadari','ass088','123-14-285215' )");

function search($search)
{
    $search = strtr($search,array('_' => '\_', '%' => '\%'));
    $sql = "SELECT * FROM flowers WHERE flowerName LIKE  '%$search%' or description LIKE  '%$search%'";
    #return $sql;

    $rows = $GLOBALS['dB']->getAll($sql);


    if(count($rows) == 0)
    {
       echo "<h1 style='color:red' align='center'>No Results found for $search<br>:(</h1>";
    }else{      
        foreach($rows as $row){
            echo "<div class='item'>";
            echo "<h2 align='center'>$row[6]</h2>";
            echo "<div class='im'>";
            echo "<img src='images/$row[4]' style='width: 100%' />";
            echo "<img src='images/free-chocs.png' alt='f' />";
            if($row[3] != 0){
                echo "<img src='images/save.png' alt='b' />";
            }
            echo "</div>";
            echo "<div style='text-align: center'>";
            echo "<h1>$row[1]</h1>";
            echo "<div style='overflow: hidden'><p>$row[5]</p></div>";
            if($row[3] !=0){
                echo "<s>$row[2]</s> <b>$row[3]</b><br /><br />";
            }else{
                echo "<b>$row[2]</b><br /><br />";
            }
            echo " <button><a style='color:black;text-decoration:none' href=order.php?id=$row[0]>Order Now</a></button><br />";
            echo "</div>";
            echo"</div> ";   
        }
    }
}

function getAllFowers(){
    $sql = "SELECT * FROM flowers limit 4";
    #return $sql;

    $rows = $GLOBALS['dB']->getAll($sql);



    if(count($rows) == 0)
    {
        echo "<h1 style='color:red' align='center'>No Results Found.<br>:(</h1>";
    }else{      
        foreach($rows as $row){
            echo "<div class='item'>";
            echo "<h2 align='center'>$row[6]</h2>";
            echo "<div class='im'>";
            echo "<img src='images/$row[4]' style='width: 100%' />";
            echo "<img src='images/free-chocs.png' alt='f' />";
            if($row[3] != 0){
                echo "<img src='images/save.png' alt='b' />";
            }
            echo "</div>";
            echo "<div style='text-align: center'>";
            echo "<h1>$row[1]</h1>";
            echo "<div style='overflow: hidden'><p>$row[5]</p></div>";
            if($row[3] !=0){
                echo "<s>$row[2]</s> <b>$row[3]</b><br /><br />";
            }else{
                echo "<b>$row[2]</b><br /><br />";
            }
            echo " <button><a style='color:black;text-decoration:none' href=order.php?id=$row[0]>Order Now</a></button><br />";
            echo "</div>";
            echo"</div> ";   
        }
    }
}

function recentlyAdd(){
    
    $sql = "SELECT * FROM flowers ORDER BY id desc limit 3";
    #return $sql;

    $rows = $GLOBALS['dB']->getAll($sql);


    if(count($rows) == 0)
    {
        echo "<h1 style='color:red' align='center'>No Results Found.<br>:(</h1>";
    }else{      
        foreach($rows as $row){
            echo "<div class='item' style='width:30%; margin-right: 2%;margin-bottom: 10%;'>";
            echo "<div class='im'>";
            echo "<img src='images/$row[4]' style='width: 100%' />";
            echo "<img src='images/free-chocs.png' alt='f' />";
            if($row[3] != 0){
                echo "<img src='images/save.png' alt='b' />";
            }
            echo "</div>";
            echo "<div style='text-align: center'>";
            echo "<h1><font color='orange' size='4' style='margin-right:8px' >NEW</font>$row[1]</h1>";
            echo "<div style='overflow: hidden'><p>$row[5]</p></div>";
            if($row[3] !=0){
                echo "<s>$row[2]</s> <b>$row[3]</b><br /><br />";
            }else{
                echo "<b>$row[2]</b><br /><br />";
            }
            
            echo " <button><a style='color:black;text-decoration:none' href=order.php?id=$row[0]>Order Now</a></button><br />";
            echo "</div>";
            echo"</div> ";  
        }
    }



}


function bestSeller(){
    $best = $GLOBALS['dB']->getOne("SELECT flowerid FROM orders GROUP BY flowerid ORDER BY COUNT(*) desc limit 1");

    $row = getOrderedItem($best);
      print
      "
        <div class='aleft'>
          <img src='images/free-chocs.png' alt='f' />
          <img src='images/best-seller.png' alt='b' />
          <img src=images/$row[4] style='width:30%' height='100%'/>
          <div class='ialeft'>
            <p>$row[6]</p>
            <h1>
                 $row[1]<br><s>$row[2]</s><span style='font-size: larger; color: #b667c2;margin-left:10px'>$row[3]</span>
            </h1>
            <p>
               $row[5]
            </p>
            <button style='background-color: #b667c2'><a style='color:black;text-decoration:none' href=order.php?id=$row[0]>Order Now</a></button>
            <img src='images/236x304x40_roses_callout.jpg' alt='c' />
            <img src='images/209x133xsb_top_number_one_florist.png' alt='a' />
            <img src='images/209x63xsb_bottom_tomorrow.png' alt='ac' />
            <img src='images/209x63xsb_bottom_tomorrow.png' alt='ab' />
          </div>
        </div>";
}
function getOrderedItem($id){

   $sql = "SELECT * FROM flowers where id = $id ";
    #return $sql;

    $rows = $GLOBALS['dB']->getAll($sql);

    return $rows[0];

}


?>