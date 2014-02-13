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
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=news-poster&amp;ac=settings">Settings</a>

	  </h2>
	  </div>
      <br />
    <?php
	$content="";
	$title="";
	$category="";
	$expdate="";
	$disab="";
	$url="";
	if(@$_POST['np_create_news'])
	{
				$content=$_POST['np_content'];
				$title=$_POST['np_title'];
				$category=$_POST['np_category'];
				$url=$_POST['np_url'];				
				$expdate=$_POST['np_expiry'];
				$disab=$_POST['np_status'];
			
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
				 echo '<div class="updated fade below-h2"><p>Error: News expiry date is less than today date.</p></div>'; 
			}
			else
			{
				//$data=array();
				//Set Data
				//$wpdb->query('BEGIN');
				
				$data['content']=$wpdb->escape($_POST['np_content']);
				$data['title']=$wpdb->escape($_POST['np_title']);
				$data['category']=$_POST['np_category'];
				$data['post_date']=date('y-m-d',time());
				$data['post_time']=time();
				$data['url']=$wpdb->escape($_POST['np_url']);
				
				if(trim($_POST['np_expiry'])!="")
				{
				$data['expiry_date']=$_POST['np_expiry'];				
				
				
				//Set Format
				$format=array('%s','%s','%d','%s','%s','%s','%s','%d');
				}
				else
				$format=array('%s','%s','%d','%s','%s','%s','%d');
				$data['disabled']=$_POST['np_status'];
				$rows_affected = $wpdb->insert("np_news",
										$data,
										$format);
				if($rows_affected>0)
				{
					$newsid=$wpdb->insert_id;
					$all_files=array();
					$uploadfiles = $_FILES['np_images'];
					if (is_array($uploadfiles)) 
					{
						$msg="";
						foreach ($uploadfiles['name'] as $key => $value) 
						{

						  // look only for uploded files
						  if ($uploadfiles['error'][$key] == 0) {
					
							$filetmp = $uploadfiles['tmp_name'][$key];
					
							//clean filename and extract extension
							$filename = $uploadfiles['name'][$key];
					
							// get file info
							// @fixme: wp checks the file extension....
							$filetype = wp_check_filetype( basename( $filename ), null );
							$filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
							$filename = $filetitle . '.' . $filetype['ext'];
							$upload_dir = wp_upload_dir();
					
							/**
							 * Check if the filename already exist in the directory and rename the
							 * file if necessary
							 */
							$i = 0;
							while ( file_exists( $upload_dir['path'] .'/' . $filename ) ) {
							  $filename = $filetitle . '_' . $i . '.' . $filetype['ext'];
							  $i++;
							}
							$filedest = $upload_dir['path'] . '/' . $filename;
							$fileurl= $upload_dir['url'] . '/' . $filename;
					
							/**
							 * Check write permissions
							 */
							if ( !is_writeable( $upload_dir['path'] ) ) {
							  $msg.='Unable to write to directory %s. Is this directory writable by the server?';
							}
					
							/**
							 * Save temporary file to uploads dir
							 */
							if ( !@move_uploaded_file($filetmp, $filedest) ){
							  $msg.="Error, the file $filetmp could not moved to : $filedest ";
							 
							}
					
							$rows_affected = $wpdb->insert( "np_newsimage", 
											array( 'news_id'=>$newsid,
											'image_path' => $fileurl));
					
							
						  }
						}
						//print_r($all_files);
						
					}
					if($msg!="")
					{
					echo '<div class="updated fade below-h2"><p>Sorry !! News Adding Failed by Follwing Error<br>'.$msg.'</p></div>'; 
					//$wpdb->query('ROLLBACK');
					}
					else
					{
						
						$content="";
						$title="";
						$category="";
						$expdate="";
						$disab="";
						$url="";
					echo '<div class="updated fade below-h2"><p>News Created Succesfully<br></p></div>';
					//$wpdb->query('COMMIT'); 
					}
				}
				else
				{
					echo '<div class="updated fade below-h2"><p>Sorry !! News Adding Failed </p></div>'; 
				}
										
				
			}
			
	}
	 ?>
    
    
    
<form method="post" name="news-add" enctype="multipart/form-data" onsubmit="return np_validate_add_news();">
<table border="0"  class="np_table">
<tr>
  
    <td>
    News / Announcement Content<br>
     <?php  the_editor("$content", "np_content", "", false);?>
     <br>
        <span class="np_help">Enter your news and announcemenet content here</span>
    </td>
</tr>
<tr>
    
    <td>
    Title<br>
    <input name="np_title" value="<?php  echo $title;?>" type="text" id="np_title" style="width:300px; padding:5px;">
     <br>
        <span class="np_help">Enter your news title here</span>
    </td>
</tr>
<tr>
    
    <td>Category<br>
    <?php  $rows = $wpdb->get_results("select * from np_category where status  = 0"); ?>
    	<select name="np_category" id="np_category" style="width:300px; ;">
        	<?php foreach($rows as $obj){?>
            <option value="<?php echo $obj->cat_id;?>" <?php  if($category!=""){ if($obj->cat_id==$category) { echo 'selected="selected"';}}?>><?php echo $obj->category_name;?></option>
            <?php }?>
        </select>
         <br>
        <span class="np_help">Select Your News Category</span>
    </td>
</tr>
<tr>
  
    <td>Url<br>
    <input type="text" name="np_url" value="<?php  echo $url;?>" style="width:300px; padding:5px;">
     <br>
        <span class="np_help">Your news have any urls please enter here</span>
    </td>
</tr>
<tr>
    
    <td>
    News Expiry Date<br>
    <input name="np_expiry"  value="<?php  echo $expdate;?> " id="np_expiry" type="text" class="tcal" readonly="readonly"> 
    <span  id="np_clear_date">clear</span>
     <br>
        <span class="np_help">Give Expiry date for your news Format: yyyy-mm-dd , Eg: 2013-02-16 </span>
    </td>
</tr>
<tr>
  
    <td>
    Status<br>
   <select name="np_status" style="width:300px; ;">
        	<option value="0" <?php  if($disab!=""){ if($disab==0) { echo 'selected="selected"';}}?>>Enabled</option>
            <option value="1" <?php  if($disab!=""){ if($disab==1) { echo 'selected="selected"';}}?>>Disabled</option>
        </select>
        <br>
    <span class="np_help">When status is disabled news will not show in website  </span>
    </td>    
</tr>

</table>
<?php if(get_option('UploadImage')==1){ ?>
<fieldset class="np_fieldset">
	<legend>Attach Images</legend>
    <div class="np_attach_img" id="np_attach_img">
    	 <div class="np_attach_ico"><input class="np_file" type="file" name="np_images[]" accept="image/*" onchange="isValidImage($(this),1)" /> <div class="np_error"></div></div>
    </div><br />
<input type="button" class="np_button_style np_more_button" value=" + Add More" onclick="np_add_more_attachment()" style="margin-right:20px;"/>
<input type="button" class="np_button_style np_cancel_button" id="np_image_remove" value=" - Remove  Last" style="display:none;"  onclick="np_remove_attachment()"/>
</fieldset>
  <?php }?>
<input type="submit" class="np_button np_button_style" name="np_create_news" value="Create News" style="margin-top:25px;"  />
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
		//return true;
		er="";
		/*if($('#np_content').val().trim()=="")
		{
			er+="News Content is Empty\n";
		}*/
		if($('#np_title').val().trim()=="")
		{
			er+="Please Enter News Title\n";
		}
		if($('#np_category option').length==0)
		{
			er+="Category is Empty\n";
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
			alert("Corrections: \n"+er);
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