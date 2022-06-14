<?php
header("Cache-Control : no-cache, must-revalidate");
ini_set('session.gc_maxlifetime',3600);
session_start();

require 'myDatabase.php';

$searched_Item='';


if($_SERVER['REQUEST_METHOD']=='POST'){
  $GLOBALS['searched_Item'] = $_POST['searchedItem'];
}

?>



<html>
  <head>
    <title>Flowers</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div  class="header">
      <div class="rhDiv">
        <h3 class="h3y">Prestige</h3>
        <h3 class="h3x">flowers</h3>
      </div>
      <img src="images/clockhead.png" alt="clock" />
      <img src="images/pic.png" alt="women" />
      <div class="insideHead">
        <div class="xinsidehead">
          <div style="height: 50%; width: 100%">
            <p style="margin-left: 50%">
              welcome to prestige flower sign in or register
            </p>
            <div style="width: 100%">
              <a  class='h' href="#">My Account</a>| <a class='h' href="#">Order Tracing</a>|
              <a class='h' href="#">Customer Service</a>
            </div>
          </div>
          <br clear="left" />
          <div style="height: 50%; width: 100%">
            <img src="images/xgoogle-logo-small.png" alt="google" />
            <img src="images/nb-vcO.gif" alt="cart" />
            <?php echo count($_SESSION['ids'])?> ITEM -> TOTAL : <?php echo $_SESSION['total'] ?>
          </div>
        </div>
      </div>
    </div>
    <table class="navBar">
      <tr class="navrow">
        <td>Authuman</td>
        <td>Birthday</td>
        <td>By Occassion</td>
        <td>Offers</td>
        <td>Sempathy & Funeral</td>
        <td>Next Day Flowers</td>
        <td>Same Day</td>
        <td>Best Sellers</td>
        <td>Gifts</td>
      </tr>
      <tr>
        <td colspan="2">
          <form method="post" action=<?php echo $go ?>>
            <input type="text" name='searchedItem' placeholder="Search for..." required/>
            <button type="submit">Search</button>
          </form>
        </td>
        <td class="question" colspan="2">
          need flowers today? <a href="#">Click Here</a>
        </td>
        <td></td>
        <td colspan="2">
          <select name="flowersType">
            <option value="int">International Flowers</option>
            <option value="noint">Not International Flowers</option>
          </select>
        </td>
        <td></td>
        <td colspan="3">tel : 088 310 5555</td>
      </tr>
    </table>
    <?php
    $res = bestSeller();
    echo $res;
    ?>
    <div>
      <p>
        Order by 9am wueghouewh owfoweh ofuwh oweuhfouew * fuewhfouqhofu
        <a href="#">View All Flowers>></a>
      </p>
    </div>
    <hr />
    <br clear='both'>
    <?php
      if($_SERVER['REQUEST_METHOD']=='POST')
      {
          search($searched_Item); 
      }else{
        getAllFowers();
        echo  "<br clear='left'>";
        echo "<div style='clear:both;margin-top:5%;margin-bottom:5%;'></div>";
        recentlyAdd();
      }
      
   
    ?>
  </body>
</html>
