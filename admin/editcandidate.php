
<script type="text/javascript">   
$(document).ready(function() {   
$('#categ').change(function(){   
if($('#categ').val() === '2')   
   {   $('#enable1').show(); $('#enable2').hide();    }   
else if ($('#categ').val() === '1') 
   {   $('#enable1').show(); $('#enable2').show();      }
   else {   $('#enable1').hide(); $('#enable2').hide();      }      
});   
});   
</script>
<?php $user_candi = mysqli_query($condb,"select * from candidate_tb c LEFT JOIN student_tb s ON c.regno = s.RegNo WHERE c.candid  = '".safee($condb,$_GET['id'])."'")or die(mysqli_error($condb));
$row_cand = mysqli_fetch_array($user_candi);?>
<?php
if(isset($_POST['editcandidate'])){
$cMatno= ($_POST['regno']);
$cfname = trim($_POST['fname']);
$clname = $_POST['lname'];
$cgpa = $_POST['cgpa'];
$elect = $_POST['elect']; $elect2 = getecated($elect);
$position = $_POST['position'];
$Mingp = getmingp($position);
$faculty= $_POST['fac'];
$department = $_POST['dept1'];
$elesession = $_POST['session'];
  $opost =  getelectpost($position);
  $electid = getefd($elect);
$query = mysqli_query($condb,"select * from student_tb where RegNo = '".safee($condb,$cMatno)."' AND reg_status = '1' AND verify_Data = 'TRUE' ")or die(mysqli_error($condb));
$query2 = mysqli_query($condb,"select * from candidate_tb where regno = '".safee($condb,$cMatno)."' AND session = '".safee($condb,$elesession)."' ")or die(mysqli_error($condb));
$row_course = mysqli_num_rows($query);$countcand = mysqli_num_rows($query2);

if ($row_course < 1){
message("The Candidate  Reg / Matric No [$cMatno] cannot be verified as Our Student Please Try Again.", "error");
		        redirect('election.php?view=editcand&id='.$_GET['id']);}elseif ($countcand > 1){
			message("Candidate with Reg / Mat No $cMatno Has Been Registered !", "error");
		        redirect('election.php?view=editcand&id='.$_GET['id']);}elseif($cgpa < $Mingp ){
			message("Candidate GP is Not Qualified to Run for Election Minimum CGPA required is <strong>$Mingp</strong> point (s).", "error");
		        redirect('election.php?view=editcand&id='.$_GET['id']);
		
		        
			}else{
			if ($_FILES['pic']['size'] == Null){
			mysqli_query($condb,"UPDATE candidate_tb SET regno ='".safee($condb,$cMatno)."' ,fname ='".safee($condb,$cfname)."',lname = '".safee($condb,$clname)."',cgpa = '".safee($condb,$cgpa)."',post = '".safee($condb,$position)."',ecate = '".safee($condb,$elect)."',fac = '".safee($condb,$faculty)."',dept = '".safee($condb,$department)."',session = '".safee($condb,$elesession)."' WHERE candid = '".safee($condb,$_GET['id'])."' ")or die(mysqli_error($condb));
			mysqli_query($condb,"insert into activity_log (date,username,action) values(NOW(),'$admin_username','Candidate with Matric No $cMatno Running For $opost was updated')")or die(mysqli_error($condb));
			message("Candidate details was Successfully Updated", "success");
		        redirect('election.php?view=candidates');
		        }elseif($elect2 == "2" and $electid !== $faculty){  redirect('election.php?view=add_cand');
			message("Candidate is Not a Student of ".getfacultyc($electid)." !", "error");    }
		        elseif($elect2 == "1" and $electid !== $department){  redirect('election.php?view=add_cand');
		message("Candidate is Not a Student of  ".getdeptc($electid)." Department !", "error");    
			}else{
			$images = uploadProductImage('pic','./voteimg/');
$thumbnail = "voteimg/".$images['thumbnail'];
	if($images['thumbnail'] == '1'){
message("ERROR:  Image width should not be less than 180px .", "error");
		        redirect('election.php?view=editcand&id='.$_GET['id']);}else{
mysqli_query($condb,"UPDATE candidate_tb SET regno ='".safee($condb,$cMatno)."' ,fname ='".safee($condb,$cfname)."',lname = '".safee($condb,$clname)."',cgpa = '".safee($condb,$cgpa)."',post = '".safee($condb,$position)."',ecate = '".safee($condb,$elect)."',fac = '".safee($condb,$faculty)."',dept = '".safee($condb,$department)."',session = '".safee($condb,$elesession)."',image = '$thumbnail' WHERE candid = '".safee($condb,$_GET['id'])."' ")or die(mysqli_error($condb));

mysqli_query($condb,"insert into activity_log (date,username,action) values(NOW(),'$admin_username','Candidate with Matric No $cMatno Running For $opost was updated')")or die(mysqli_error($condb));
unlink("$row_cand[image]");
 message("Candidate details was Successfully Updated", "success");
		        redirect('election.php?view=candidates');}
				
				}
				}}

?>
<div class="x_panel">
                
             
                <div class="x_content">

                    		<form name="register" method="post" enctype="multipart/form-data" id="register">
<input type="hidden" name="insid" value="<?php echo $_SESSION['insid'];?> " />
<input type="hidden" class="form-control fac"    name='fac' id="fac_1"  placeholder="<?php echo $SCategory; ?>" value="<?php echo $row_cand['fac']; ?>" >
<input type="hidden" class="form-control dept1"    name='dept1' id="dept1_1" value="<?php echo $row_cand['dept']; ?>"  placeholder="" >

<div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span>
                    </button>
          Enter Candidate Reg / Matric Number To Comfirm candidate before validating candidate.
                  </div>
<div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"  >
 <label for="email">Candidate Reg / Matric Number*</label>
<div class="form-group"><input type="text" class="form-control regno" id='regno_1' name='regno'  placeholder="Enter Reg / Matric No" value="<?php echo $row_cand['regno']; ?>" required > </div></div>
<div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"  >
 <label for="email">Firstname</label>
<div class="form-group"><input type="text" class="form-control fname"    name='fname' id="fname_1" required value="<?php echo $row_cand['fname']; ?>" placeholder="Candidate Firstname"  ></div></div>
<div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"  >
 <label for="email">Lastname</label>
<div class="form-group"><input type="text" class="form-control lname"    name='lname' id="lname_1" value="<?php echo $row_cand['lname']; ?>"  placeholder="Candidate Lastname" ></div></div>
<div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"  >
 <label for="email">Gender</label>
<div class="form-group"><input type="text" class="form-control sex"    name='sex' id="sex_1" value="<?php echo $row_cand['Gender']; ?>"  placeholder="Candidate Gender" readonly></div></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback"  >
 <label for="email"><?php echo $SCategory; ?></label>
<div class="form-group"><input type="text" class="form-control fac2"    name='fac2' id="fac2_1" required value="<?php echo getfacultyc($row_cand['fac']); ?>"  placeholder="<?php echo $SCategory; ?>" readonly ></div></div>
<div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"  >
 <label for="email">Department</label>
<div class="form-group"><input type="text" class="form-control dept2"    name='dept2' id="dept2_1" required value="<?php echo getdeptc($row_cand['dept']); ?>"  placeholder="" readonly ></div></div>
 
<div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"  >
 <label for="email">CGPA</label>
<div class="form-group"><input type="text" class="form-control "    name='cgpa' id="cgpa_1"  value="<?php echo $row_cand['cgpa']; ?>" readonly required ></div></div> 
<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback"><label for="heard">Election</label>
						  	  <select name='elect' id="elect" onchange='loadposition(this.name);return false;' class="form-control"  required >
<option value="<?php echo $row_cand['ecate']; ?>"><?php echo getecate($row_cand['ecate']); $ecat = getecated($row_cand['ecate']); ?><?php if($ecat == "2"){ echo $nelect = " (".getfacultyc($row_cand['fac']).")"; }elseif($ecat == "1"){ echo $nelect = " (".getdeptc($row_cand['dept']).")";}else{}  ?></option>
                                                  <?php  $resultblocks = mysqli_query($condb,"SELECT * FROM electiontb ORDER BY ecate ASC");
while($rsblocks = mysqli_fetch_array($resultblocks)){
if($_GET['loadp'] ==$rsblocks['id'] ){
echo "<option value='$rsblocks[id]' selected >$rsblocks[title]";?> <?php if($rsblocks['ecate'] == "2"){ echo $nelect = " (".getfacultyc($rsblocks['fac']).")"; }elseif($rsblocks['ecate'] == "1"){ echo $nelect = " (".getdeptc($rsblocks['dept']).")";}else{}  ?> <?php echo"</option>";}else{
echo "<option value='$rsblocks[id]'  >$rsblocks[title]";?> <?php if($rsblocks['ecate'] == "2"){ echo $nelect = " (".getfacultyc($rsblocks['fac']).")"; }elseif($rsblocks['ecate'] == "1"){ echo $nelect = " (".getdeptc($rsblocks['dept']).")";}else{}  ?> <?php echo"</option>";
 }} ?></select></div>
  <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"><label for="heard">Position</label>
						  	  <select name='position' id="position"  class="form-control" required  >
                            <option value="<?php echo $row_cand['post']; ?>"><?php echo getelectpost($row_cand['post']); ?></option>
                            </select></div>                   
 
            <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
						  	  <label for="heard">Academic Session</label>
							   <select class="form-control"   name="session" id="session"  required="required">
  <option value="<?php echo $row_cand['session']; ?>"><?php echo $row_cand['session']; ?></option>
<?php  $resultsec = mysqli_query($condb,"SELECT * FROM session_tb  ORDER BY session_name ASC");
while($rssec = mysqli_fetch_array($resultsec)){echo "<option value='$rssec[session_name]'>$rssec[session_name]</option>";	}?></select>
                      </div>
           <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
						  	  <label for="heard">Candidate Picture </label>
                            	<input name="pic" class="input-file uniform_on" id="fileInput" type="file" >
                      </div>
                      
            
               
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                        
                        </div>
                      </div>
                    
                                     
                                       
                      <div  class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback>
                        <div class="col-md-6 col-md-offset-3">
                         <?php   if (authorize($_SESSION["access3"]["emanag"]["velect"]["create"])){ ?> 
                        <button  name="editcandidate"  id="editcandidate"  class="btn btn-primary col-md-4" title="Click Here to Save Candidate Details" ><i class="fa fa-save"></i> Save </button><?php } ?>   <?php   if (authorize($_SESSION["access3"]["emanag"]["velect"]["view"])){ ?> 
                        <button  name="goback"  id="goback" type='button' onClick="window.location.href='election.php?view=candidates';" class="btn btn-primary col-md-4" title="Click Here View Candidate" ><i class="fa fa-eye"></i> View Candidates </button><?php } ?> 
                        	<script type="text/javascript">
	                                            $(document).ready(function(){
	                                            $('#addelection').tooltip('show');
	                                            $('#addelection').tooltip('hide');$('#goback').tooltip('show');
	                                            $('#goback').tooltip('hide');
	                                            });
	                                            </script>
	                                            <div class='imgHolder2' id='imgHolder2'><img src='uploads/tabLoad.gif'></div>
                        </div>
                        
                        	
									
                        </form>
                       </div> 
                           