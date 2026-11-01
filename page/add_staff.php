<div class="row">
    <div class="col-md-12">
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3>Add Staff</h3>
        		<div class="d-item-tools">
        		    <a href="manage_staff" class="btn btn-info btn-sm">Manage Staff</a>
        		</div>
        	</div>
        	<div class="d-item-content">
        		
        		<form action="" method="POST">

        <h5>Employee Information</h5>
        
          <section class="card card-body border mb-0">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Employee ID *</label>
                  <input type="text" name="id_no" value="" class="form-control" required>
              </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Employee Name *</label>
                  <input type="text" name="employee_name" value="" class="form-control" required>
              </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Company Name *</label>
                  <input type="text" name="company_name" value="" class="form-control" required>
              </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Branch Name *</label>
                  <select name="employee_branch" class="form-control select2" required>
                    <option value="">Select Branch</option>
                  </select>
              </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Job Location *</label>
                  <input type="text" name="job_location" value="" class="form-control" required>
              </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Department *</label>
                  <select name="department" class="form-control select2" required>
                    <option value="">Select Department</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Designation *</label>
                  <select name="designation" class="form-control select2" required>
                    <option value="">Select Designation</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Employee Type *</label>
                  <select name="employee_type" class="form-control select2" required>
                    <option value="">Select Employee Type</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Join Date *</label>
                  <input type="date" name="join_date" value="" class="form-control" required>
                </div>
              </div>
              <div class="col-md-4 d-none">
                <div class="form-group">
                  <label>Employee Grade *</label>
                  <select name="employee_grade" class="form-control">
                    <option value="">Select Employee Grade</option>
                  </select>
                </div>
              </div>
            </div>
        </section>


        <h5 class="mt-3">Personal Information</h5>
        <section class="card card-body border mb-0">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Blood Group</label>
                  <select name="blood_group" class="form-control select2">
                    <option value="">Select</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Gender</label>
                  <div class="form-control">
                  <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="radiomale" value="male" name="gender" class="custom-control-input" required>
                      <label class="custom-control-label" for="radiomale">Male</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="radiofemale" value="female" name="gender" class="custom-control-input" required>
                      <label class="custom-control-label" for="radiofemale">Female</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="radioothers" value="others" name="gender" class="custom-control-input" required>
                      <label class="custom-control-label" for="radioothers">Others</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Religion *</label>
                  <select name="religion" class="form-control select2" required>
                    <option value="muslims">Muslims</option>
                    <option value="hindus"></option>Hindus</option>
                    <option value="buddhists">Buddhists</option>
                    <option value="christians">Christians</option>
                    <option value="others">Other's</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Marital Status *</label>
                  <select name="marital_status" class="form-control select2" required>
                    <option value="married">Married</option>
                    <option value="unmarried">>Unmarried</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Father's Name *</label>
                  <input type="text" name="father_name" value="" class="form-control" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Mother's Name *</label>
                  <input type="text" name="mother_name" value="" class="form-control" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Contact Number *</label>
                  <input type="text" name="contact_number" value="" class="form-control" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Date of Birth *</label>
                  <input type="date" name="birth_date" value="" class="form-control" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Country *</label>
                  <select name="country" class="form-control select2" required>
                    <option value="">Select Country</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>National ID No.</label>
                  <input type="text" name="national_id" value="" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>E-mail Address</label>
                  <input type="email" name="email" value="" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="profile_image">Attested Photograph</label>
                <div class="custom-file">
                <input type="file" class="custom-file-input" name="profile_image" id="profile_image">
                  <label class="custom-file-label" for="profile_image">Choose file</label>
              </div>
                </div>
              </div>
            </div>
        </section>


        <h5 class="mt-3">Present Address</h5>
        <section class="card card-body border mb-0">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Village / House *</label>
                  <input type="text" name="present_village_house" value="" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Post Office *</label>
                  <input type="text" name="present_post_office" value="" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Thana *</label>
                  <select name="present_thana" class="form-control select2">
                      <option value="">Select</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>District *</label>
                  <select name="present_district" class="form-control select2">
                      <option value="">Select</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Division *</label>
                  <select name="present_division" class="form-control select2">
                      <option value="">Select</option>
                  </select>
                </div>
              </div>
            </div>
        </section>

        </br>
        <h5>Parmanent Address</h5>
        <section class="card card-body border mb-0">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Village / House *</label>
                  <input type="text" name="parmanent_village_house" value="" class="form-control" >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Post Office *</label>
                  <input type="text" name="parmanent_post_office" value="" class="form-control" >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Thana *</label>
                  <select name="parmanent_thana" class="form-control select2" >
                      <option value="">Select</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>District *</label>
                  <select name="parmanent_district" class="form-control select2" >
                      <option value="">Select</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Division *</label>
                  <select name="parmanent_division" class="form-control select2" >
                      <option value="">Select</option>
                  </select>
                </div>
              </div>
            </div>
        </section>
        <br/>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
    

        		</form>
        		
        	</div>
        </div>
    </div>
</div>