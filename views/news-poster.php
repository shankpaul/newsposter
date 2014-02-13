<style type="text/css">
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
table.np_table  tr td
{
	vertical-align:top;
	padding:5px;
	border-bottom:1px solid #CFCFCF;
}
table.np_table td a
{
	text-decoration:none;
}
.np_table	th.headerSortUp { 
    background-image: url(<?php echo PLUGIN_URL; ?>/np_images/asc.gif); 
    
} 
.np_table th.headerSortDown { 
    background-image: url(<?php echo PLUGIN_URL; ?>/np_images/desc.gif); 
    
} 
.np_table th.header { 
    background-image: url(<?php echo PLUGIN_URL; ?>/np_images/bg.gif);     
    cursor: pointer; 
    font-weight: bold; 
    background-repeat: no-repeat; 
    background-position: center left; 
    padding-left: 20px; 
    border-right: 1px solid #dad9c7; 
    margin-left: -1px; 
} 
.pagination a
{
	padding:2px 4px 2px 4px;
	border:1px solid #747474;
	background:#666;;	
	color:#FFF;
	margin:5px;
	text-decoration:none;
	text-align:center;
	box-shadow:1px 2px 4px #A0A7A4;	
}
.pagination span.current,.pagination a:hover
{
	background:#F45F04;
	border:1px solid #E35917;
	padding:2px 4px 2px 4px;
	margin:5px;
	color:#FFF;
	text-decoration:none;
	text-align:center;
	box-shadow:1px 2px 4px #A0A7A4;	
}
.pagination span.disabled
{
	background:#EAEAEA;
	border:1px solid #DDD;
	padding:2px 4px 2px 4px;
	margin:5px;
	color:#BBBCB8;
	text-decoration:none;
	text-align:center;
}
/*tbody tr 
    {
      counter-increment : rownum ; 
    }
    tbody 
    { 
      counter-reset: rownum; 
    }
    table#straymanage td:nth-child(2):before 
    { 
      content: counter(rownum) " " ; 
    }
    table#straymanage td.rownums:before 
    { 
      content: counter(rownum) " "; 
    }*/
	.np_button_style
{
	padding:5px 8px 5px 8px;
	color:#FFF;
	cursor:pointer;
	text-align:center;
	border-radius:3px;
	box-shadow:1px 1px 3px #9B9B9B;
}
.np_cancel_button:hover
{	
	box-shadow:1px 1px 5px #6F6F6F;
}
.np_cancel_button
{
	background:#7E8487;	
	border:#737373;	
}
	
</style>
<div class="wrap">
	<script language="JavaScript" src="<?php echo PLUGIN_URL; ?>np_js/np_jquery.js"></script>
	<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>thirdparty/sort/jquery.tablesorter.min.js"></script> 
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2>NEWS POSTER - Home</h2>
    
         <?php 
	 if(isset($_REQUEST['newsid'],$_REQUEST['npaction'],$_REQUEST['status']))
	   {
		   if($_REQUEST['npaction']=="update")
		   {
			   $newsid=$_REQUEST['newsid'];
			   $status=$_REQUEST['status'];
			   if($status==1)
			   $status=0;
			   else
			   $status=1;
		   	   $rows_affected = $wpdb->query("update np_news set disabled=$status where news_id=$newsid"); 
		   }
		   else if ($_REQUEST['npaction']=="delete")
		   {
			   $newsid=$_REQUEST['newsid'];
			   if($status==1)
			   $status=0;
			   else
			   $status=1;
		   	   $rows_affected = $wpdb->query("delete from np_news where news_id=$newsid"); 
		   }
	   }
	   if(@$_POST['np_delete'])
	   {
		  
		   if(isset($_POST['np_del']))
		   {
		   	$list_id= implode($_POST['np_del'],',');
			$wpdb->query("delete from np_news where news_id in($list_id)");
		   }
			
			
	   }
	 ?>
      
    <div class="tool-box">
	
		<?php wp_enqueue_script('jquery'); ?>
		<div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=add">Add News / Announcements</a>

	  
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=settings">Settings</a>

	  </h2>
	  </div>
      <br>
      <h2>News Listing</h2>
      <?php 
	   $rows = $wpdb->get_results("select * from np_news a,np_category b where a.category=b.cat_id");
	   $items=count($rows);
	   $limit="";
	   if($items > 0)
			{				
			
					$p = new pagination;
					$p->items($items);
					$p->limit(5); // Limit entries per page
					$p->target(get_permalink()."admin.php?page=news-poster");
					//$p->urlFriendly();
					$p->currentPage($_GET[$p->paging]); // Gets and validates the current page
					$p->calculate(); // Calculates what to show
					$p->parameterName('paging');
					$p->nextLabel('');//removing next text
					$p->prevLabel('');//removing previous text
					$p->nextIcon('&#9658;');//Changing the next icon
					$p->prevIcon('&#9668;');//Changing the previous icon
					$p->adjacents(1); //No. of page away from the current page
					 
					if(!isset($_GET['paging'])) 
					{
						$p->page = 1;
					} else {
						$p->page = $_GET['paging'];
					}
			 
					//Query for limit paging
					$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
					 
			}
	  ?>
      
      
		<div style="text-align:right;">
        
          	<?php if($items > 0) echo $p->show(); ?>
        </div>
        <br style="clear:both;" />
        <form method="post" >
      <table width="100%" class="np_table" id="straymanage">
        <thead>
        <th  width="3%" align="center">

<input id="np_select_all" type="checkbox" title="Select All">
</th>
           <!-- <th  width="2%"><div align="center">No</div></th>-->
			<th  width="50%" align="left" >News Title</th>
            <th  width="10%" align="left">Category</th>
            <th width="8%" align="left">Post Date</th>		
            <th width="8%" align="left">Expiration</th> 
            <th width="6%" align="left">Status</th>
        </thead>
		
		<tbody>
		
				<?php
	   $rows = $wpdb->get_results("select * from np_news a,np_category b where a.category=b.cat_id order  by news_id desc $limit");
	$no=0;
	$nodata=false;
	if(count($rows)>0)
	{
	foreach($rows as $obj){
		$cat_st=$obj->disabled;	
			$show_cat_st="Enabled";		
			$cat_link_class="np_enabled";
			if($cat_st==1)
			{
				    $show_cat_st="Disabled";		
					$cat_link_class="np_disabled";
			}
	    ?>
				<tr>
                <td align="center"><input type="checkbox" name="np_del[]" value="<?php echo $obj->news_id; ?>" /></td>
					<!--<td align="center" width="7%"></th>-->
					<td><?php echo $obj->title; ?>					  <div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=edit&newsid=<?php echo $obj->news_id; ?>">Edit</a> | </span>
						<span class="trash"><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=news-poster&npaction=delete&newsid=<?php echo $obj->news_id; ?>&status=<?php echo $cat_st;?>" >Delete</a></span> 
					</div>
					</td>
					<td> <?php echo $obj->category_name; ?>	</td>
					
					<td> <?php echo  date("F j, Y", strtotime($obj->post_date)); ; ?>	</td>
					<td> <?php if(  $obj->expiry_date!="") echo  date("F j, Y", strtotime($obj->expiry_date)); ?>	</td>
                    <td><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=news-poster&npaction=update&newsid=<?php echo $obj->news_id; ?>&status=<?php echo $cat_st;?>" class="<?php echo $cat_link_class; ?>"><?php echo $show_cat_st; ?></a></td>
				</tr>
                <?php }}else{ $nodata=true; }?>
		</tbody>
        </table>
        
       
         <?php if($nodata){ ?>
  <div style="margin:auto; text-align:center;padding:5px;border:1px solid #CFCFCF; border-top:none;">No Records Found</div><?php }
  else{
  ?>
  <span class="np_help">
            All the News/Announcement are listed here
        </span>
   <div style="margin-top:5px;">
        	<input type="submit" class="np_button_style np_cancel_button" name="np_delete" value="Delete Selected" />
        </div>
        <?php }?>
        </form>
        
		
	</div>
</div>
<script language="javascript">
 $("#straymanage").tablesorter({ 
        // pass the headers argument and assing a object 
        headers: { 
            // assign the secound column (we start counting zero) 
			0: { 
                // disable it by setting the property sorter to false 
                sorter: false 
            },
			1: { 
                // disable it by setting the property sorter to false 
                sorter: false 
            }
           
			
        } 
    });
	$('#np_select_all').click(function(){
	
	 if(document.getElementById('np_select_all').checked)
		{
			
			//$('.chk_box').attr('checked',true);
			checkAll( true);
		}
		else
		{
		//	$('.chk_box').attr('checked',false);
		checkAll( false);
				
		}
	
	 })
	function checkAll( checktoggle)
{
  var checkboxes = new Array(); 
  checkboxes = document.getElementsByTagName('input');
 
  for (var i=0; i<checkboxes.length; i++)  {
    if (checkboxes[i].type == 'checkbox')   {
      checkboxes[i].checked = checktoggle;
    }
  }
}
</script>