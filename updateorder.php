<?php
session_start();

include_once 'include/config.php';
if(strlen($_SESSION['alogin'])==0)
  { 
header('location:index.php');
}
else{
$oid=intval($_GET['oid']);
if(isset($_POST['submit2'])){
$status=$_POST['status'];
$remark=$_POST['remark'];//space char

$query=mysql_query("insert into ordertrackhistory(orderId,status,remark) values('$oid','$status','$remark')");
$sql=mysql_query("update orders set orderStatus='$status' where id='$oid'");


switch ($status) {
  case 'Delivered':
    # code...
        $sql="SELECT * FROM `orders` WHERE `id`='$oid'";
        $res = mysql_query($sql);

        $row=mysql_fetch_assoc($res);
        $quantity = $row['quantity'];
        $productID  = $row['productId'];

        $sql ="UPDATE `tblinventory` SET `STOCKOUT`= `STOCKOUT`+'$quantity',REMAINING=REMAINING-'$quantity' WHERE `PRODUCTID`='$productID'";
        mysql_query($sql);

        $sql ="SELECT * FROM `tblinventory` WHERE `PRODUCTID` =$productID";
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res); 

        if ($row['REMAINING']<1){
        # code...
         $sql="UPDATE `products` SET `productAvailability`='Out of Stock' WHERE `id`=$productID";
         mysql_query($sql);
        }
    break;

   // case 'Declined Order':
   //  # code...
   //      $sql="SELECT * FROM `orders` WHERE `id`='$oid' AND orderStatus='in Process'";
   //      $res = mysql_query($sql);

   //      $maxrow = mysql_num_rows($res);
   //      if ($maxrow > 0) {
   //        # code...
   //          $row=mysql_fetch_assoc($res);
   //          $quantity = $row['quantity'];
   //          $productID  = $row['productId'];

   //          $sql ="UPDATE `tblinventory` SET `STOCKOUT`= `STOCKOUT`+'$quantity',REMAINING=REMAINING-'$quantity' WHERE `PRODUCTID`='$productID'";
   //          mysql_query($sql);

   //          $sql ="SELECT * FROM `tblinventory` WHERE `PRODUCTID` =$productID";
   //          $res = mysql_query($sql);
   //          $row = mysql_fetch_assoc($res); 

   //          if ($row['REMAINING']<1){
   //          # code...
   //           $sql="UPDATE `products` SET `productAvailability`='Out of Stock' WHERE `id`=$productID";
   //           mysql_query($sql);
   //          }
   //      }
       
   //  break;
  
  default:
    # code...
    break;
}




echo "<script>alert('Order updated sucessfully...');</script>";
//}
}

 ?>
<script language="javascript" type="text/javascript">
function f2()
{
window.close();
}ser
function f3()
{
window.print(); 
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Update Compliant</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="anuj.css" rel="stylesheet" type="text/css">
</head>
<body>

<div style="margin-left:50px;">
 <form name="updateticket" id="updateticket" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">

    <tr height="50">
      <td colspan="2" class="fontkink2" style="padding-left:0px;"><div class="fontpink2"> <b>Update Order !</b></div></td>
      
    </tr>
    <tr height="30">
      <td  class="fontkink1"><b>order Id:</b></td>
      <td  class="fontkink"><?php echo $oid;?></td>
    </tr>
    <?php 
$ret = mysql_query("SELECT * FROM ordertrackhistory WHERE orderId='$oid'");
     while($row=mysql_fetch_array($ret))
      {
     ?>
		
    
    
      <tr height="20">
      <td class="fontkink1" ><b>At Date:</b></td>
      <td  class="fontkink"><?php echo $row['postingDate'];?></td>
    </tr>
     <tr height="20">
      <td  class="fontkink1"><b>Status:</b></td>
      <td  class="fontkink"><?php echo $row['status'];?></td>
    </tr>
     <tr height="20">
      <td  class="fontkink1"><b>Remark:</b></td>
      <td  class="fontkink"><?php echo $row['remark'];?></td>
    </tr>

   
    <tr>
      <td colspan="2"><hr /></td>
    </tr>
   <?php } ?>
   <?php 
$st='Delivered';
   $rt = mysql_query("SELECT * FROM orders WHERE id='$oid'");
     while($num=mysql_fetch_array($rt))
     {
     $currrentSt=$num['orderStatus'];
   }
     if($st==$currrentSt)
     { ?>
   <tr><td colspan="2"><b>
      Product Delivered </b></td>
   <?php }else  {
      ?>
   
    <tr height="50">
      <td class="fontkink1">Status: </td>
      <td  class="fontkink"><span class="fontkink1" >
        <select name="status" class="fontkink" required="required" >
          <option value="">Select Status</option>
                 <option value="in Process">In Process</option>
                  <option value="Delivered">Delivered</option>
				   <option value="Declined">Declined Order</option>
        </select>
        </span></td>
    </tr>

     <tr style=''>
      <td class="fontkink1" >Remark:</td>
      <td class="fontkink" align="justify" ><span class="fontkink">
        <textarea cols="50" rows="7" name="remark"  required="required" ></textarea>
        </span></td>
    </tr>
    <tr>
      <td class="fontkink1">&nbsp;</td>
      <td  >&nbsp;</td>
    </tr>
    <tr>
      <td class="fontkink">       </td>
      <td  class="fontkink"> <input type="submit" name="submit2"  value="update"   size="40" style="cursor: pointer;" /> &nbsp;&nbsp;   
      <input name="Submit2" type="submit" class="txtbox4" value="Close this Window " onClick="return f2();" style="cursor: pointer;"  /></td>
    </tr>
<?php } ?>
</table>
 </form>
</div>

</body>
</html>
<?php } ?>

     