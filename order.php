<?php
ini_set('session.gc_maxlifetime',3600);
session_start();


require 'myDatabase.php';

$defaults = array(
  'nm' => '',
  'pc' =>'',
  'cm'=>'',
  'st'=>'',
  'ln'=>'',
  'tw'=>'',
  'cs'=>'',
  'tl'=>''
);


$id;
$pprice;

if($_SERVER['REQUEST_METHOD']=='POST')
{
      $id = $_POST['id'];
}else{
      $id = $_GET['id'];
}

$myItem = getOrderedItem($_GET['id']);

if (empty($_SESSION['total']))
{

 if($myItem[3] !=0)
     {
        $_SESSION['total']= $myItem[3];
    }else{
        $_SESSION['total'] = $myItem[2];
    }
}else{
   if($myItem[3] !=0)
     {
        $_SESSION['total'] += $myItem[3];
    }else{
        $_SESSION['total'] += $myItem[2];
    }
}

if(!isset($_SESSION['ids']))
{
  $_SESSION['ids'] = array();
}

if(!in_array($id,$_SESSION['ids']))
{
  $_SESSION['ids'][]=$id;
}


$x;
if($_SERVER['REQUEST_METHOD']=='POST')
{
  $cname = strip_tags($_POST['Cname']);
  $postCode = strip_tags($_POST['pcode']);
  $street = strip_tags($_POST['street']);
  $town=strip_tags($_POST['town']);
  $phone = strip_tags($_POST['telephone']);

    $defaults['nm']=$cname;
    $defaults['pc']=$postCode;
    $defaults['cm']= strip_tags($_POST['company']);
    $defaults['st'] =$street;
    $defaults['ln']= strip_tags($_POST['line']);
    $defaults['tw']=$town;
    $defaults['cs'] = strip_tags($_POST['counState']);
    $defaults['tl']=$phone;


  if($frmerror = vaildateForm($cname,$postCode,$street,$town,$phone))
  {
    $GLOBALS['x']=$frmerror;
  }else{
    foreach($_SESSION['ids'] as $d)
    {
      $product = getOrderedItem($d);


      $productPrice;
      if($product[3] !=0)
      {
        $productPrice= $myItem[3];
      }else{
        $productPrice= $myItem[2];
      }

      $dB->query("INSERT INTO  orders (flowerid,price,cstreet,ctown,cpostalcode,cphone) VALUES($d,$productPrice,'$street','$town','$postCode','$phone')");



    }

    session_unset();
    session_destroy();
  }
}



function vaildateForm($cname, $postCode, $street, $town ,$phone)
{
    
    if (!strlen(trim($cname))) {
        $errors['NoName'] = 'Please enter Your Name.';
    }
    if (!strlen(trim($postCode))) {
        $errors['nocode'] = 'Please enter your Code.';
    }else{
        if (!preg_match('/\d+/',$postCode) || !preg_match('/[a-z]+/',$postCode)) {
        $errors['wrongcode'] = 'Please enter a valid post code';
    } 
    }
    if (!strlen(trim($street))) {
        $errors['nostreet'] = 'Please enter your street name';
    }
    if (! strlen(trim($town))) {
        $errors['notown'] = 'Please enter your town.';
    }
    if (!strlen(trim($phone))) {
        $errors['nophone'] = 'Please enter your Telephone.';
    }else
    {
        if (!preg_match("/\d{3}-\d{2}-\d{6}/",$phone))
        {
          $errors['wrongphone'] = 'Please enter a valid phone number';
        }
    }

    return $errors ;
} 


function printItem($row)
{
      print
      "
        <div class='orderedItem' style='width: 25%'>
            <img src=images/$row[4] >
            <h1 align='center'>$row[1]</h1>
            <p> $row[5]</p>";
            if($row[3] !=0){
                echo  "<h3>Surprise is costs $row[3] instead of $row[2]</h3>";
                $GLOBALS['pprice'] = $row[3];
            }else{
                echo "<h3>Price : $row[2]</h3>";
                $GLOBALS['pprice'] = $row[2];
            }
        print "</div>";

}

function showForm($defaults)
{

    $errors = $GLOBALS['x'];
    $Noname;
    $nocode;
    $nostreet;
    $notown;
    $wrongcode;
    $wrongphone;
    $nophone;
    if ($errors) {
        if (array_key_exists('NoName', $errors)) {
            $Noname= $errors['NoName'];
        } else {
            $Noname= '';
        }
        if (array_key_exists('nocode', $errors)) {
            $nocode= $errors['nocode'];
        } else {
          $nocode='';
          if(array_key_exists('wrongcode', $errors))
            {
             $wrongcode= $errors['wrongcode'];

            }else{
              $wrongcode='';
            }
        }
        if (array_key_exists('nophone', $errors)) {
            $nophone= $errors['nophone'];
        } else {
          $nophone='';
          if(array_key_exists('wrongphone', $errors))
            {
             $wrongphone= $errors['wrongphone'];

            }else{
              $wrongphone='';
            }
        }
        if (array_key_exists('nostreet', $errors)) {
            $nostreet= $errors['nostreet'];
        } else {
            $nostreet= '';
        }
        if (array_key_exists('notown', $errors)) {
            $notown= $errors['notown'];
        } else {
            $notown= '';
        }
    }

    print
    "
      <div class='daddress'>
        <table>
          <tr>
            <td>
              <label>name:*</label>
            </td>
            <td>
              <input type='text' name='Cname' value=$defaults[nm]> <font color=red>$noname</font>
            </td>
          </tr>
          <tr>
            <td>
              <label>post code:*</label>
            </td>
            <td>
              <input type='text' name='pcode'value=$defaults[pc] > <font color=red>$nocode  $wrongcode&emsp;</font>
              <button>Find Address</button>
            </td>
          </tr>
          <tr>
            <td>
              <label>Unsure of the post code ?click here</label>
            </td>
            <td>
              <input type='checkbox' name='unsure' value='y' >
            </td>
          </tr>
          <tr>
            <td>
              <label>Company:</label>
            </td>
            <td>
              <input type='text' name='company' value=$defaults[cm]>
            </td>
          </tr>
          <tr>
            <td>
              <label>Steet:*</label>
            </td>
            <td>
              <input type='text' name='street' value=$defaults[st]><font color=red>$nostreet</font>
            </td>
          </tr>
          <tr>
            <td>
              <label>Line2:</label>
            </td>
            <td>
              <input type='text' name='line' value=$defaults[ln]>
            </td>
          </tr>
          <tr>
            <td>
              <label>Town:*</label>
            </td>
            <td>
              <input type='text' name='town' value=$defaults[tw]><font color=red>$notown</font>
            </td>
          </tr>

          <tr>
            <td>
              <label>Countety/state:</label>
            </td>
            <td>
              <input type='text' name='counState' value=$defaults[cs]>
            </td>
          </tr>
          <tr>
            <td>
              <label>Telephone:</label>
            </td>
            <td>
              <input type='text' name='telephone' value=$defaults[tl]><font color=red>$nophone $wrongphone</font>
            </td>
          </tr>
          <input type='hidden' name=id value=$GLOBALS[id]>
        </table>
      </div>
    ";
}

?>

<html>
  <head>
    <title>Prestige Folwers</title>
    <link rel="stylesheet" href="style.css" />
    <style>
      .orderedItem {
        float: left;
        border-radius: 5%;

        border: 1px solid rgb(187, 184, 184);
        padding: 10px;
        height: 50%;
      }
      .orderedItem img {
        width: 100%;
        height: 60%;
      }
      .orderedItem h3 {
        color: #ad59b9;
        font-size: larger;
      }
      .ddetails {
        float: left;
        border-radius: 5%;

        margin-left: 5%;
        margin-right: 5%;
        width: 30%;
        background-color: #ad59b9;
        padding: 10px;
        height: 50%;
        color: white;
      }
      .ddetails label {
        margin-right: 20%;
      }
      .daddress {
        float: left;
        border-radius: 5%;
        width: 30%;
        background-color: #9cd14b;
        padding: 10px;
        height: 49%;
        color: white;
        padding: 20px;
      }
      .daddress label {
        color: white;

        font-size: x-large;
      }
      .daddress button {
        background-color: white;
      }
      .secCheck table {
        width: 100%;
      }
      .xtd {
        width: 47%;
      }
    </style>
  </head>
  <body>
    <div class="header">
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
              <a class="h" href="#">My Account</a>|
              <a class="h" href="#">Order Tracing</a>|
              <a class="h" href="#">Customer Service</a>
            </div>
          </div>
          <br clear="left" />
          <div style="height: 50%; width: 100%">
            <img src="images/xgoogle-logo-small.png" alt="google" />
            <img src="images/nb-vcO.gif" alt="cart" />
            <?php echo count($_SESSION['ids'])?> ITEM -> TOTAL : <?php echo $_SESSION['total'] ?>|
            <form action="website.php" style="float: right">
              <button
                type="submit"
                style="background-color: #ad59b9; color: white"
              >
                Continue shopping
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <table class="navBar" style="color: white">
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
    </table>
    <br clear="left" />
    <?php
    printItem($myItem);
    ?>
    <div class="ddetails">
      <h1>Delivery Details</h1>
      <label>Date</label>
      <select name="dd">
        <option value="tuesday">Tuseday, 13,oct</option>
      </select>
      <br />
      <label>Delivery</label> 5.8 <br /><br /><br /><br />
      <label>Type</label>Next Day Delivery <br /><br />
      <label>item</label>Pure Delight <br /><br /><br />
      <hr />
      <textarea name="tdd" cols="70" rows="8"></textarea>
    </div>
    <form method='post' action=<?php $_SERVER['PHP_SELF']?>>
      <?php
        showForm($defaults);
      ?>
      <div class="secCheck">
        <table>
          <tr>
            <td class="xtd">
              <p>Product Size</p>
              <br />
              <select name="size">
                <option value="st">standart</option>
              </select>
            </td>
            <td class="xtd">
              <button type="submit" style="color: white">
                <img src="images/pad-lock-symbol.gif" /> Secure Checkout
              </button>
            </td>
            <td class="xtd">TOTAL :<?php echo $GLOBALS['pprice'] ?></td>
          </tr>
        </table>
      </div>
    </form>
 
  </body>
</html>
