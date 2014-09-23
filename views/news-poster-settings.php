<style type="text/css">
.np_button
{
	background:#3585A4;
	padding:5px 8px 5px 8px;
	border:#3E83A8;
	color:#FFF;
	cursor:pointer;
	text-align:center;
	border-radius:3px;
	box-shadow:1px 1px 3px #9B9B9B;
}
.np_button:hover
{
	
	box-shadow:1px 1px 5px #6F6F6F;
}
table.np_table
{
	border-collapse:collapse;
	border:1px solid #CFCFCF;
	
}
table.np_table thead th
{
	background:#F3F3F3;
	color:#232323;
	padding:5px;
	border-bottom:1px solid #CFCFCF;
}
table.np_table tbody tr td
{
	
	padding:5px;
	border-bottom:1px solid #CFCFCF;
}
a.np_enabled
{
	text-decoration:none;
	color:#090;
	font-size:12px;
	
}
a.np_disabled
{
	text-decoration:none;
	color:#C41515;
	font-size:12px;
	
}
span.np_help
	{
		
		color:#A2A2A2;
		font-size:12px;
	}
	.np_modal
	{
		background:#FFF;
		opacity:.5;
		position:fixed;
		width:100%;
		height:100%;
		top:0;
		display:none;
		z-index:5554;
	}
	.np_window
	{
		width:300px;
		height:150px;
		background:#FFF;
		border:1px solid #C5C5C5;
		border-radius:3px;
		box-shadow:1px 1px 2px #AEAEAE;
		position:absolute;
		z-index:5555;
		left:35%;
		top:30%;
		padding:1px;
		display:none;
	}
	.np_window h4
	{
		margin:0;
		padding:7px;
		background:#F3F3F3;
		color:#232323;
		
	}
	.np_window p
	{
		padding:5px;
		margin:0;
		margin-top:5px;
		
	}
.np_cancel_button
{
	background:#7E8487;
	
	border:#737373;
	
}
.np_cancel_button:hover
{
	
	box-shadow:1px 1px 5px #6F6F6F;
}
	.np_button_style
	{
		padding:5px 8px 5px 8px;
		color:#FFF;
	cursor:pointer;
	text-align:center;
	border-radius:3px;
	box-shadow:1px 1px 3px #9B9B9B;
	}
	span.np_edit
	{
		cursor:pointer;
		color:#06C;
		
	}
	span.np_edit:hover
	{
		cursor:pointer;
		color:#E01F1F;
		
	}
	table.np_table td a
{
	text-decoration:none;
}
</style>
<script language="JavaScript" src="<?php echo PLUGIN_URL; ?>np_js/np_jquery.js"></script>
<div class="wrap">
  <div id="icon-options-general" class="icon32"></div>
    <h2>NEWS POSTER - Settings</h2>
    <div class="tablenav">
	  <h2>
      <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster">Home</a>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=add">Add News / Announcements</a>


	  </h2>
	  </div>
    <br />
    
    <?php
	if(isset($_REQUEST['np_img_func']))
	{
		 update_option('UploadImage',$_REQUEST['np_img_func']);  
		 echo '<div class="updated fade below-h2"><p>Settings Updated</p></div>'; 
		
	}
	 ?>
     <?php
	if(@$_POST['np_new_cat_submit'])
	{
		
		$rows_affected = $wpdb->insert("np_category",
										array("category_name"=>$_REQUEST['np_new_cat']),
										array('%s'));
		 if($rows_affected>0)
		 {
		 echo '<div class="updated fade below-h2"><p>New Category Added</p></div>'; 
		 }
		 else
		  echo '<div class="updated fade below-h2"><p>New Category Adding Failed</p></div>';
		 
	}
	else if(@$_POST['np_upd_cat'])
	{
		$catid=$_POST['np_upd_catid'];
		$cat_name=$wpdb->escape($_POST['np_upd_cat_name']);
		$sql="update np_category set category_name='$cat_name' where cat_id='$catid'";
		$rs=$wpdb->query($sql);
	}
	 ?>
     <?php 
	 if(isset($_REQUEST['catid'],$_REQUEST['npaction'],$_REQUEST['status']))
	   {
		   if($_REQUEST['npaction']=="update")
		   {
			   $catid=$_REQUEST['catid'];
			   $status=$_REQUEST['status'];
			   if($status==1)
			   $status=0;
			   else
			   $status=1;
		   	   $rows_affected = $wpdb->query("update np_category set status=$status where cat_id=$catid"); 
		   }
		   else if($_REQUEST['npaction']=="delete")
		   {
			   $catid=$_REQUEST['catid'];
			   $status=$_REQUEST['status'];
			   $res=$wpdb->get_results("select count(category) as num from np_news where category='$catid'");
			   foreach($res as $val)
			   if($val->num==0)
			   {
		   	   $rows_affected = $wpdb->query("delete from np_category where cat_id=$catid"); 
			   }
			   else
			   {
				    echo '<div class="updated fade below-h2"><p>Category  used in News/Announcements. Plese delete all news with same category.</p></div>';
			   }
		   }
	   }
	 ?>
    
    <br />
    <form method="post" name="np_funct">
     Image Upload Fuctionality 
    <select name="np_img_func" style="margin-left:15px; width:300px">
    <option value="1" <?php if(get_option('UploadImage')==1)echo 'selected="selected"'; ?>>Enabled</option>
    <option value="0" <?php if(get_option('UploadImage')==0)echo 'selected="selected"'; ?>>Disabled</option>
    </select>
    
    <input type="submit" style="margin-left:20px;" class="np_button" value="Update" />
    <br />
     
    <span class="np_help">When image upload functionality is enabled admin can upload images for news and announcement. </span>
    </form>
   
       <br />
       <br />
      
     <form method="post" name="np_cat_add" onsubmit="return np_validate_new_cat();">
    Enter Category Name<br />
    <input type="text" name="np_new_cat" id="np_new_cat" style="padding:5px; width:250px; margin-right:20px; " />
    <input type="submit" class="np_button" name="np_new_cat_submit" value="Add News Category" />
 
    </form>
       <br />
       <?php
	   
	   
	   $rows = $wpdb->get_results("select * from np_category");
	$no=0;
	    ?>
     <table width="40%" class="np_table">
        <thead>             
			<th scope="col" width="7%" align="center"  >No</th>
			<th scope="col" align="left">Name</th>
            <th scope="col" align="left" width="10%" >Edit</th>
            <th scope="col" align="left" width="12%">delete</th>
            <th scope="col" width="15%" align="left" >Status</th>         
        </thead>
		<tbody>
        <?php 
			foreach ($rows as $obj) {				
			$cat_st=$obj->status;	
			$show_cat_st="Enabled";		
			$cat_link_class="np_enabled";
			if($cat_st==1)
			{
				    $show_cat_st="Disabled";		
					$cat_link_class="np_disabled";
			}
		?>
        <tr>
        	<td width="5%" align="center"><?php echo ++$no; ?></td>
            <td><?php echo $obj->category_name; ?></td>
            <td ><span  class="np_edit" catid="<?php echo $obj->cat_id; ?>" onclick="np_cat_inline_edit($(this))">Edit</span></td>
            <td><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=settings&npaction=delete&catid=<?php echo $obj->cat_id; ?>&status=<?php echo $cat_st;?>" >Delete</a></td>
            <td><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=settings&npaction=update&catid=<?php echo $obj->cat_id; ?>&status=<?php echo $cat_st;?>" class="<?php echo $cat_link_class; ?>"><?php echo $show_cat_st; ?></a></td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    <span class="np_help">Click on each status to change the category status</span>
    <div id="np_modal" class="np_modal"></div>
    <div class="np_window" id="np_cat_edit_window">
    	<h4>Edit Category Name</h4>
        <p>
        	<form method="post" name="np_cat_edit_form" >
            <input type="hidden" value="" name="np_upd_catid" id="np_edit_cat_id" />
            
            <div align="center"><input id="np_edit_old_name" old="" style="width:250px; padding:5px;" type="text" value="" name="np_upd_cat_name" />
            
            <br />
              <span class="np_help">Edit category name</span>
              <br />
             <input type="submit" class="np_button np_button_style" name="np_upd_cat" value="Update Category" style="margin-top:15px;"  />

<input type="button" class="np_cancel_button np_button_style"  value="Cancel" style="margin-top:15px; margin-left:10px;" onclick="np_hide_np_window($(this))"  />
             </div>
            </form>
        </p>
    </div>
    </div>
    
    <script language="javascript">
    function np_validate_new_cat()
	{
		val=document.getElementById('np_new_cat').value;
		if(val.trim()=="")
		{
			alert("Enter category Name");
			return false;
		}
		return true;
	}
	function np_cat_inline_edit(id)
	{
		cat_name=id.closest('tr').find('td:eq(1)').text();
		cat_id=id.attr('catid');
		$('#np_edit_old_name').val(cat_name);
		$('#np_edit_old_name').attr('old',cat_name);
		$('#np_edit_cat_id').val(cat_id);	
		$('#np_modal').fadeIn('fast');
		$('#np_cat_edit_window').fadeIn('fast');
	}
	function np_hide_np_window(id)
	{
		$('#np_modal').fadeOut('fast');
		$('#np_cat_edit_window').fadeOut('fast');
	}
    </script>