<style type="text/css">
.np_table
{
	border-collapse:collapse;
	width:100%;
}
.np_table tr td
{
	padding:10px;	
	vertical-align:top;	
}
.np_table tr td textarea
{
	padding:1px;
	width:80%;
	height:100px;
}
span.np_help
{
	
	color:#A2A2A2;
	font-size:12px;
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
div.np_attach_ico
{
	background:url(<?php echo PLUGIN_URL; ?>np_images/clip.png) no-repeat left center;
	padding:5px 5px 5px  25px;
	
	margin:10px 0 0 5px;
}
div.np_attach_ico input[type=file]
{
	width:230px;
}
div.np_error
{
	background:url(<?php echo PLUGIN_URL; ?>np_images/error.png) no-repeat  center;
	margin:10px 0 0 5px;
	width:22px;
	height:16px;
	float:right;
	display:none;
}
.np_button
{
	background:#3585A4;
	
	border:#3E83A8;
	
}
.np_button:hover
{
	
	box-shadow:1px 1px 5px #6F6F6F;
}
.np_more_button
{
	background:#36A93C;
	
	border:#45A54D;
	
}
.np_more_button:hover
{
	
	box-shadow:1px 1px 5px #6F6F6F;
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
fieldset.np_fieldset
{
	border:1px solid #CDCDCD;
	padding:5px;
	width:320px;
	border-radius:3px;
}
#np_clear_date
{
	color:#AAA;
	font-size:13px;
	cursor:pointer;
}
#np_clear_date:hover
{
	color:#E13737;
}

table.np_table1
{
	border-collapse:collapse;
	border:1px solid #CFCFCF;
	
}
table.np_table1 thead th
{
	background:#F3F3F3;
	color:#232323;
	padding:5px;
	border-bottom:1px solid #CFCFCF;
}
table.np_table1 tbody tr td
{
	vertical-align:top;
	padding:5px;
	border-bottom:1px solid #CFCFCF;
}
table.np_table1 tbody tr td a
{
	text-decoration:none;
}
.np_img_box
{
	border:1px solid #E8E8E8;
	padding:1px;
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
</style>

<script language="JavaScript" src="<?php echo PLUGIN_URL; ?>np_js/np_jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_URL; ?>thirdparty/tcal/tcal.css" />
<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>thirdparty/tcal/tcal.js"></script> 
<?php
add_action('admin_init', 'editor_admin_init');
add_action('admin_head', 'editor_admin_head');

function editor_admin_init() {
	wp_enqueue_script('word-count');
	wp_enqueue_script('post');
	wp_enqueue_script('editor');
	 // wp_enqueue_script('media-upload');
}

function editor_admin_head() {
	wp_tiny_mce();
}
?>


<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"></div>
	<h2>NEWS POSTER - Add News</h2>
	
	<div class="tablenav">
		<h2>
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster">Home</a>
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=add">Add News / Announcements</a>

			
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=settings">Settings</a>

		</h2>
	</div>
	<br />
	
	<?php
	
	
	
	if(@$_POST['np_update_news'])
	{
		date_default_timezone_set(get_option('timezone_string')); 
		$valid_date=true;
		if($_POST['np_expiry']!="")
		{
			$x=$_POST['np_expiry'];
			$y= date('Y-m-d',time());
			$diff = strtotime($x) - strtotime($y); 
			$len=$diff/(60*60*24);
			
			if($len<0)
			{
				$valid_date=false;
			}
		}
		
		if($_POST['np_content']=="")
		{
			echo '<div class="updated fade below-h2"><p>Error: News Content is empty</p></div>'; 
		}
		if(!$valid_date)
		{
			echo '<div class="updated fade below-h2"><p>Error: News expiry date less than today date.</p></div>'; 
		}
		
		else
		{
				//$data=array();
				//Set Data
				//$wpdb->query('BEGIN');
			
			
			$update_sql="update np_news set ";
			$content=esc_sql($_POST['np_content']);
			$update_sql.=" content='$content' ,";
			$title=esc_sql($_POST['np_title']);
			$update_sql.=" title='$title' ,";
			$category=$_POST['np_category'];
			$sort_order=esc_sql($_POST['np_sort_order']);
			$update_sql.=" category='$category' ,";
			$update_sql.=" sort_order='$sort_order' ,";													
			$url=$_POST['np_url'];
			$update_sql.=" url= '$url' ,";

			$dt = date('Y-m-d',time());
			if($_POST['np_post_date']!=""){				
				$dt = $_POST['np_post_date'];					
			}
			$update_sql.=" post_date= '$dt' ,";	

			

			if($_POST['np_expiry']!="")
			{
				$expiry_date=$_POST['np_expiry'];
				$update_sql.=" expiry_date= '$expiry_date' ,";
			}
			else
			{
				$update_sql.=" expiry_date= NULL ,";
			}
			$disabled=$_POST['np_status'];
			$update_sql.=" disabled= '$disabled' ";
			$newsid=$_POST['np_newsid'];
			$update_sql.=" where news_id='$newsid' ";
			
			
			
			$rows_affected = $wpdb->query($update_sql);
			if($newsid>0)
			{
				
				$all_files=array();
				$msg="";
				ini_set("display_erros",1);
				$uploadfiles = $_FILES['np_images'];
				if (is_array($uploadfiles)) 
				{
					
					foreach ($uploadfiles['name'] as $key => $value) 
					{

						  // look only for uploded files
						if ($uploadfiles['error'][$key] == 0)
						{
							
							$filetmp = $uploadfiles['tmp_name'][$key];
							
							//clean filename and extract extension
							$filename = $uploadfiles['name'][$key];
							
							// get file info
							//  wp checks the file extension....
							$filetype = wp_check_filetype( basename( $filename ), null );
							$filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
							$filename = $filetitle . '.' . $filetype['ext'];
							$upload_dir = wp_upload_dir();
							
							/**
							 * Check if the filename already exist in the directory and rename the
							 * file if necessary
							 */
							$i = 0;
							while ( file_exists( $upload_dir['path'] .'/' . $filename ) ) 
							{
								$filename = $filetitle . '_' . $i . '.' . $filetype['ext'];
								$i++;
							}
							$filedest = $upload_dir['path'] . '/' . $filename;
							$fileurl= $upload_dir['url'] . '/' . $filename;
							
							/**
							 * Check write permissions
							 */
							if ( !is_writeable( $upload_dir['path'] ) )
							{
								$msg.='Unable to write to directory %s. Is this directory writable by the server?';
							}
							
							/**
							 * Save temporary file to uploads dir
							 */
							if ( !@move_uploaded_file($filetmp, $filedest) )
							{
								$msg.="Error, the file $filetmp could not moved to : $filedest ";
								
							}
							
							$rows_affected = $wpdb->insert( "np_newsimage", 
								array( 'news_id'=>$newsid,
									'image_path' => $fileurl));
							
							
						  }//end if
						}//end foreach
						//print_r($all_files);
						
					}//end if
					if($msg!="")
					{
						echo '<div class="updated fade below-h2"><p>Sorry !! News Updation Failed by Follwing Errors<br>'.$msg.'</p></div>'; 
					//$wpdb->query('ROLLBACK');
					}
					else
					{
						echo '<div class="updated fade below-h2"><p>News Updated Succesfully<br></p></div>';
					//$wpdb->query('COMMIT'); 
					}
				}
				else
				{
					echo '<div class="updated fade below-h2"><p>Sorry !! News Updation Failed </p></div>'; 
				}
				
				
			}
			
		}
		else if ($_REQUEST['npaction']=="deleteimage")
		{
			$imageid=$_REQUEST['imageid'];
			$rows_affected = $wpdb->query("delete from np_newsimage where news_imageid=$imageid"); 
			
		}
		else  if($_REQUEST['npaction']=="updateimage")
		{
			$imageid=$_REQUEST['imageid'];
			$status=$_REQUEST['status'];
			if($status==1)
				$status=0;
			else
				$status=1;
			$rows_affected = $wpdb->query("update np_newsimage set disabled=$status where news_imageid=$imageid"); 
		}
		
		
		?>
		<?php 
		if(isset($_REQUEST['newsid']))
		{
			$newsid=$_REQUEST['newsid'];
			$results=$wpdb->get_results("select * from np_news a,np_category b where a.category=b.cat_id and a.news_id=$newsid ");
			foreach($results as $newsresults);
		}
		?>
		
		
		<form method="post" name="news-add" enctype="multipart/form-data" onsubmit="return np_validate_add_news();">
			<input type="hidden" value="<?php echo $newsid; ?>" name="np_newsid" />
			<table border="0"  class="np_table">
				
					
					<td>
						Title<br>
						<input name="np_title" value="<?php echo $newsresults->title; ?>" type="text" id="np_title" style="width:80%; padding:5px;">
						<br>
						<span class="np_help">Enter your news title here</span>
					</td>
				</tr>
				<tr>

					<tr>
					
					<td>
						News / Announcement Content<br>
						<?php  wp_editor("$newsresults->content", "np_content", "", false);?>
						<br>
						<span class="np_help">Enter your news and announcement content here</span>
					</td>
				</tr>
				<tr>
					
					<td>Category<br>
						<?php  $rows = $wpdb->get_results("select * from np_category where status  = 0"); ?>
						<select name="np_category" style="width:300px; ;">
							<?php foreach($rows as $obj){?>
							<option value="<?php echo $obj->cat_id;?>"  <?php if($newsresults->category== $obj->cat_id) echo 'selected="selected"'; ?> ><?php echo $obj->category_name;?></option>
							<?php }?>
						</select>
						<br>
						<span class="np_help">Select Your News Category</span>
					</td>
				</tr>
				<tr>
					
					<td>Url<br>
						<input type="text" value="<?php echo $newsresults->url; ?>"  name="np_url" style="width:300px; padding:5px;">
						<br>
						<span class="np_help">Your news have any urls please enter here</span>
					</td>
				</tr>
				<tr>
					
					<td>
						News Post Date<br>
						<input name="np_post_date"  value="<?php echo $newsresults->post_date; ?>" id="np_post_date" type="text" class="tcal" > 
						
						<br>
						<span class="np_help">Give post date for your news Format: yyyy-mm-dd , Eg: 2013-02-16 </span>
					</td>
				</tr>
				<tr>
					
					<td>
						News Expiry Date<br>
						<input name="np_expiry" value="<?php echo $newsresults->expiry_date; ?>" id="np_expiry" type="text" class="tcal" readonly="readonly"> 
						<span  id="np_clear_date">clear</span>
						<br>
						<span class="np_help">Give Expiry date for your news Format: yyyy-mm-dd , Eg: 2013-02-16 </span>
					</td>
				</tr>
				<tr>
					
					<td>
						Status<br>
						<select name="np_status" style="width:300px; ;">
							<option value="0" <?php if($newsresults->disabled==0) echo 'selected="selected"'; ?> >Enabled</option>
							<option value="1"  <?php if($newsresults->disabled==1) echo 'selected="selected"'; ?>>Disabled</option>
						</select>
						<br>
						<span class="np_help">When status is disabled news will not show in website  </span>
					</td>    
				</tr>
				<tr>
				<td>
					Sort Order<br>
					<input name="np_sort_order"  value="<?php  echo $newsresults->sort_order; ?>"  type="number" class="tcal" > 
					
					<br>
					<span class="np_help">Sort Order will help you to sort news</span>
				</td>  

			</tr>

			</table>

			<br />
			<?php
			$rows = $wpdb->get_results("select * from np_newsimage where news_id=$newsid");
			$no=0;
			if(count($rows)>0)
			{
				?>
				<table width="40%" class="np_table1">
					<thead>             
						<th scope="col" width="7%" align="center"  >No</th>
						<th scope="col" align="left">Image</th>
						<th scope="col" align="left">Path</th>
						<th scope="col" width="10%" align="left" >Delete</th>   
						<th scope="col" width="15%" align="left" >Status</th>   
					</thead>
					<tbody>
						<?php 
						foreach ($rows as $obj) {
							$img_st=$obj->disabled;	
							$show_img_st="Enabled";		
							$img_link_class="np_enabled";
							if($img_st==1)
							{
								$show_img_st="Disabled";		
								$img_link_class="np_disabled";
							}
							?>
							<tr>
								<td width="5%" align="center"><?php echo ++$no; ?></td>
								<td><img class="np_img_box" src="<?php echo $obj->image_path; ?>" width="58px" height="58px" /></td>
								<td><?php echo $obj->image_path; ?></td>
								<td><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=edit&npaction=deleteimage&imageid=<?php echo $obj->news_imageid; ?>&newsid=<?php echo $obj->news_id; ?>">delete</a></td>
								<td><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=edit&npaction=updateimage&imageid=<?php echo $obj->news_imageid; ?>&status=<?php echo $img_st;?>&newsid=<?php echo $obj->news_id; ?>" class="<?php echo $img_link_class; ?>"><?php echo $show_img_st; ?></a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php }?>
					<br />

					<?php if(get_option('UploadImage')==1){ ?>
					<fieldset class="np_fieldset">
						<legend>Attach Images</legend>
						<div class="np_attach_img" id="np_attach_img">
							<div class="np_attach_ico"><input class="np_file" type="file" accept="image/*" name="np_images[]" onchange="isValidImage($(this),1)" /> <div class="np_error"></div></div>
						</div><br />
						<input type="button" class="np_button_style np_more_button" value=" + Add More" onclick="np_add_more_attachment()" style="margin-right:20px;"/>
						<input type="button" id="np_image_remove" class="np_button_style np_cancel_button"  style="display:none;" value=" - Remove  Last" onclick="np_remove_attachment()"/>
					</fieldset>
					<?php }?>
					<input type="submit" class="np_button np_button_style" name="np_update_news" value="Update News" style="margin-top:25px;"  />

					<a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster" style="text-decoration:none;">
						<input type="button" class="np_cancel_button np_button_style"  value="Cancel" style="margin-top:25px; margin-left:30px;"  /></a>

					</form>
				</div>
				<script language="javascript">
				function np_add_more_attachment()
				{
					$('#np_attach_img').append('<div class="np_attach_ico"><input class="np_file" type="file" accept="image/*" name="np_images[]" onchange="isValidImage($(this),1)" /><div class="np_error"></div></div>');
					if($('#np_attach_img input[type=file]').length>1)
						$('#np_image_remove').show();
				}
				function np_remove_attachment()
				{
					if($('#np_attach_img input[type=file]').length>1);
					$('#np_attach_img div.np_attach_ico:last').remove();
					if($('#np_attach_img input[type=file]').length==1)
						$('#np_image_remove').hide();
				}
				</script> 
				
				<script language="javascript">
				function np_validate_add_news()
				{
					
					er="";
		/*if($('#np_content').val().trim()=="")
		{
			er+="News Content is Empty\n";
		}*/
		if($('#np_title').val().trim()=="")
		{
			er+="Please Enter News Title";
		}
		img_er="";
		if($('#np_attach_img input[type=file]').length>0)
		{
			
			$.each($('#np_attach_img input[type=file]'),function(){
				if(!isValidImage($(this),0))
				{
					img_er+=$(this).val()+' - invalid image file \n';
					$(this).closest('div').find('.np_error').show();
				}
			});
		}
		if(img_er!="")
		{
			er+="Please Select Valid Image File\n";
		}
		
		if(er!="")
		{
			alert("Correct Following \n"+er);
			return false;
		}
		return true;
	}
	function isValidImage(id,st) {
		filename=id.val();
		//alert(filename);
		var _validFileExtensions = [".jpg", ".jpeg", ".gif", ".png",".bmp"];
		var sFileName =filename;
		if (sFileName.length > 0) 
		{
			var blnValid = false;
			for (var j = 0; j < _validFileExtensions.length; j++)
			{
				var sCurExtension = _validFileExtensions[j];
				if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
					blnValid = true;
					
					id.closest('div').find('.np_error').hide();
						//alert(filename);
						break;
					}
				}

				if (!blnValid) {
					if(st)
						alert("Sorry, " + sFileName + " is an invalid file, \nAllowed extensions are: " + _validFileExtensions.join(", "));
					id.closest('div').find('.np_error').show();
					return false;
				}
			}

			return true;
		}
		$(document).ready(function(){
			
			$('#np_clear_date').click(function(){
				$('#np_expiry').val('');
			})
			
		});
		
		</script>