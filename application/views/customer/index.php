  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
    <div class="col-md-8">
      <h3 style="margin-top: 5px">
        Customer Management
      </h3>
    </div>
    <div class="col-md-4">
      <div class="pull-right">
      <a href="#" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle"></i> Add New</a>
      </div>
    </div>
  </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         <!-- Modal -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">Add New Customer </h4>
                    </div>
                    <div class="modal-body">
 
                      <div class="row">
                        <div class="col-sm-6">
                                        <!-- general form elements -->
                              <div class="box">
                                <!-- form start -->
                                <form role="form" action="" method="post">
                                  <div class="box-body">
                                    <div class="form-group">
                                      <label for="first name">First Name</label>
                                      <input type="text" name="first_name" class="form-control" id="" placeholder="Enter First Name"  required>
                                    </div>
                                    <div class="form-group">
                                      <label for="last name">Last Name</label>
                                      <input type="text" name="last_name" class="form-control" id="" placeholder="Enter Last Name"  required>
                                    </div>
                                    <div class="form-group">
                                      <label for="email id">Email address</label>
                                      <input type="email"  name="email"  class="form-control" id="" placeholder="Enter email"  required>
                                    </div>
                                    <div class="form-group">
                                      <label for="password">Password</label>
                                      <input type="password" name="password" class="form-control" id="" placeholder="Enter Password"  required>
                                    </div>
                                    <div class="form-group">
                                      <label for="mobile number">Mobile Number</label>
                                      <input type="text" name="phone" class="form-control" id="" placeholder="Mobile Number"  required>
                                    </div>
                                    <div class="form-group">
                                      <label for="zip code">Zip code</label>
                                      <input type="text" name="zip_code" class="form-control" id="" placeholder="Enter PinCode"  required>
                                    </div>
                                    
                                  </div>
                                  <!-- /.box-body -->
                              </div>

                        </div>
                        <div class="col-sm-6">
              <!-- general form elements -->
                              <div class="box">
                                  <div class="box-body">
                                    <div class="form-group">
                                      <label for="Company Name">Company Name</label>
                                      <input type="text" name="company" class="form-control" id="" placeholder="Company Name">
                                    </div>
                                    <div class="form-group">
                                      <label for="address">Address 1</label>
                                      <input type="text" name="address1" class="form-control" id="" placeholder="Enter Address"  required>
                                    </div>
                                    <div class="form-group">
                                      <label for="address">Address 2</label>
                                      <input type="text" name="address2" class="form-control" id="" placeholder="Enter Address">
                                    </div>
                                    <div class="form-group">
                                      <label for="exampleInputEmail1">City</label>
                                      <input type="text" name="city" class="form-control" id="" placeholder="Enter City"  required>
                                    </div>
                                    <div class="form-group">
                                     <label for="state" class="control-label">State/Region</label>
                                      <select name="state" class="form-control" id="stateselect" required="required"><option value="">Choose One...</option><option>Andaman and Nicobar Islands</option><option>Andhra Pradesh</option><option>Arunachal Pradesh</option><option>Assam</option><option>Bihar</option><option>Chandigarh</option><option>Chattisgarh</option><option>Dadra and Nagar Haveli</option><option>Daman and Diu</option><option>Delhi</option><option>Goa</option><option>Gujarat</option><option>Haryana</option><option>Himachal Pradesh</option><option>Jammu and Kashmir</option><option>Jharkhand</option><option>Karnataka</option><option>Kerala</option><option>Lakshadweep</option><option>Madhya Pradesh</option><option>Maharashtra</option><option>Manipur</option><option>Meghalaya</option><option>Mizoram</option><option>Nagaland</option><option>Orissa</option><option>Puducherry</option><option>Punjab</option><option>Rajasthan</option><option>Sikkim</option><option>Tamil Nadu</option><option>Telangana</option><option>Tripura</option><option>Uttaranchal</option><option>Uttar Pradesh</option><option>West Bengal</option></select></div> 
                                    <div class="form-group">
                                      <label for="country" class="control-label">Country</label>
                                      <select id="country" name="country" class="form-control"  required="required">
                                      <option value="AF"> Aland Islands</option><option value="AL"> Albania</option><option value="DZ"> Algeria</option><option value="AS"> American Samoa</option><option value="AD"> Andorra</option><option value="AO"> Angola</option><option value="AI"> Anguilla</option><option value="AQ"> Antarctica</option><option value="AG"> Antigua And Barbuda</option><option value="AR"> Argentina</option><option value="AM"> Armenia</option><option value="AW"> Aruba</option><option value="AU"> Australia</option><option value="AT"> Austria</option><option value="AZ"> Azerbaijan</option><option value="BS"> Bahamas</option><option value="BH"> Bahrain</option><option value="BD"> Bangladesh</option><option value="BB"> Barbados</option><option value="BY"> Belarus</option><option value="BE"> Belgium</option><option value="BZ"> Belize</option><option value="BJ"> Benin</option><option value="BM"> Bermuda</option><option value="BT"> Bhutan</option><option value="BO"> Bolivia</option><option value="BA"> Bosnia And Herzegovina</option><option value="BW"> Botswana</option><option value="BV"> Bouvet Island</option><option value="BR"> Brazil</option><option value="IO"> British Indian Ocean Territory</option><option value="BN"> Brunei Darussalam</option><option value="BG"> Bulgaria</option><option value="BF"> Burkina Faso</option><option value="BI"> Burundi</option><option value="KH"> Cambodia</option><option value="CM"> Cameroon</option><option value="CA"> Canada</option><option value="CV"> Cape Verde</option><option value="KY"> Cayman Islands</option><option value="CF"> Central African Republic</option><option value="TD"> Chad</option><option value="CL"> Chile</option><option value="CN"> China</option><option value="CX"> Christmas Island</option><option value="CC"> Cocos (Keeling) Islands</option><option value="CO"> Colombia</option><option value="KM"> Comoros</option><option value="CG"> Congo</option><option value="CD"> Congo, Democratic Republic</option><option value="CK"> Cook Islands</option><option value="CR"> Costa Rica</option><option value="CI"> Cote D'Ivoire</option><option value="HR"> Croatia</option><option value="CU"> Cuba</option><option value="CW"> Curacao</option><option value="CY"> Cyprus</option><option value="CZ"> Czech Republic</option><option value="DK"> Denmark</option><option value="DJ"> Djibouti</option><option value="DM"> Dominica</option><option value="DO"> Dominican Republic</option><option value="EC"> Ecuador</option><option value="EG"> Egypt</option><option value="SV"> El Salvador</option><option value="GQ"> Equatorial Guinea</option><option value="ER"> Eritrea</option><option value="EE"> Estonia</option><option value="ET"> Ethiopia</option><option value="FK"> Falkland Islands (Malvinas)</option><option value="FO"> Faroe Islands</option><option value="FJ"> Fiji</option><option value="FI"> Finland</option><option value="FR"> France</option><option value="GF"> French Guiana</option><option value="PF"> French Polynesia</option><option value="TF"> French Southern Territories</option><option value="GA"> Gabon</option><option value="GM"> Gambia</option><option value="GE"> Georgia</option><option value="DE"> Germany</option><option value="GH"> Ghana</option><option value="GI"> Gibraltar</option><option value="GR"> Greece</option><option value="GL"> Greenland</option><option value="GD"> Grenada</option><option value="GP"> Guadeloupe</option><option value="GU"> Guam</option><option value="GT"> Guatemala</option><option value="GG"> Guernsey</option><option value="GN"> Guinea</option><option value="GW"> Guinea-Bissau</option><option value="GY"> Guyana</option><option value="HT"> Haiti</option><option value="HM"> Heard Island & Mcdonald Islands</option><option value="VA"> Holy See (Vatican City State)</option><option value="HN"> Honduras</option><option value="HK"> Hong Kong</option><option value="HU"> Hungary</option><option value="IS"> Iceland</option><option value="IN" selected="selected"> India</option><option value="ID"> Indonesia</option><option value="IR"> Iran, Islamic Republic Of</option><option value="IQ"> Iraq</option><option value="IE"> Ireland</option><option value="IM"> Isle Of Man</option><option value="IL"> Israel</option><option value="IT"> Italy</option><option value="JM"> Jamaica</option><option value="JP"> Japan</option><option value="JE"> Jersey</option><option value="JO"> Jordan</option><option value="KZ"> Kazakhstan</option><option value="KE"> Kenya</option><option value="KI"> Kiribati</option><option value="KR"> Korea</option><option value="KW"> Kuwait</option><option value="KG"> Kyrgyzstan</option><option value="LA"> Lao People's Democratic Republic</option><option value="LV"> Latvia</option><option value="LB"> Lebanon</option><option value="LS"> Lesotho</option><option value="LR"> Liberia</option><option value="LY"> Libyan Arab Jamahiriya</option><option value="LI"> Liechtenstein</option><option value="LT"> Lithuania</option><option value="LU"> Luxembourg</option><option value="MO"> Macao</option><option value="MK"> Macedonia</option><option value="MG"> Madagascar</option><option value="MW"> Malawi</option><option value="MY"> Malaysia</option><option value="MV"> Maldives</option><option value="ML"> Mali</option><option value="MT"> Malta</option><option value="MH"> Marshall Islands</option><option value="MQ"> Martinique</option><option value="MR"> Mauritania</option><option value="MU"> Mauritius</option><option value="YT"> Mayotte</option><option value="MX"> Mexico</option><option value="FM"> Micronesia, Federated States Of</option><option value="MD"> Moldova</option><option value="MC"> Monaco</option><option value="MN"> Mongolia</option><option value="ME"> Montenegro</option><option value="MS"> Montserrat</option><option value="MA"> Morocco</option><option value="MZ"> Mozambique</option><option value="MM"> Myanmar</option><option value="NA"> Namibia</option><option value="NR"> Nauru</option><option value="NP"> Nepal</option><option value="NL"> Netherlands</option><option value="AN"> Netherlands Antilles</option><option value="NC"> New Caledonia</option><option value="NZ"> New Zealand</option><option value="NI"> Nicaragua</option><option value="NE"> Niger</option><option value="NG"> Nigeria</option><option value="NU"> Niue</option><option value="NF"> Norfolk Island</option><option value="MP"> Northern Mariana Islands</option><option value="NO"> Norway</option><option value="OM"> Oman</option><option value="PK"> Pakistan</option><option value="PW"> Palau</option><option value="PS"> Palestine, State of</option><option value="PA"> Panama</option><option value="PG"> Papua New Guinea</option><option value="PY"> Paraguay</option><option value="PE"> Peru</option><option value="PH"> Philippines</option><option value="PN"> Pitcairn</option><option value="PL"> Poland</option><option value="PT"> Portugal</option><option value="PR"> Puerto Rico</option><option value="QA"> Qatar</option><option value="RE"> Reunion</option><option value="RO"> Romania</option><option value="RU"> Russian Federation</option><option value="RW"> Rwanda</option><option value="BL"> Saint Barthelemy</option><option value="SH"> Saint Helena</option><option value="KN"> Saint Kitts And Nevis</option><option value="LC"> Saint Lucia</option><option value="MF"> Saint Martin</option><option value="PM"> Saint Pierre And Miquelon</option><option value="VC"> Saint Vincent And Grenadines</option><option value="WS"> Samoa</option><option value="SM"> San Marino</option><option value="ST"> Sao Tome And Principe</option><option value="SA"> Saudi Arabia</option><option value="SN"> Senegal</option><option value="RS"> Serbia</option><option value="SC"> Seychelles</option><option value="SL"> Sierra Leone</option><option value="SG"> Singapore</option><option value="SK"> Slovakia</option><option value="SI"> Slovenia</option><option value="SB"> Solomon Islands</option><option value="SO"> Somalia</option><option value="ZA"> South Africa</option><option value="GS"> South Georgia And Sandwich Isl.</option><option value="ES"> Spain</option><option value="LK"> Sri Lanka</option><option value="SD"> Sudan</option><option value="SR"> Suriname</option><option value="SJ"> Svalbard And Jan Mayen</option><option value="SZ"> Swaziland</option><option value="SE"> Sweden</option><option value="CH"> Switzerland</option><option value="SY"> Syrian Arab Republic</option><option value="TW"> Taiwan</option><option value="TJ"> Tajikistan</option><option value="TZ"> Tanzania</option><option value="TH"> Thailand</option><option value="TL"> Timor-Leste</option><option value="TG"> Togo</option><option value="TK"> Tokelau</option><option value="TO"> Tonga</option><option value="TT"> Trinidad And Tobago</option><option value="TN"> Tunisia</option><option value="TR"> Turkey</option><option value="TM"> Turkmenistan</option><option value="TC"> Turks And Caicos Islands</option><option value="TV"> Tuvalu</option><option value="UG"> Uganda</option><option value="UA"> Ukraine</option><option value="AE"> United Arab Emirates</option><option value="GB"> United Kingdom</option><option value="US"> United States</option><option value="UM"> United States Outlying Islands</option><option value="UY"> Uruguay</option><option value="UZ"> Uzbekistan</option><option value="VU"> Vanuatu</option><option value="VE"> Venezuela</option><option value="VN"> Viet Nam</option><option value="VG"> Virgin Islands, British</option><option value="VI"> Virgin Islands, U.S.</option><option value="WF"> Wallis And Futuna</option><option value="EH"> Western Sahara</option><option value="YE"> Yemen</option><option value="ZM"> Zambia</option><option value="ZW"> Zimbabwe</option>
                                      </select>
                                    </div>                         
                                   </div>
                                  <!-- /.box-body -->
                               
                              </div>
                              <div class="box-footer">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; <button type="submit" class="btn btn-primary">Add New Client</button>
                              </div>
                            </form>
                        </div>
                      </div>
                               


                    </div>
                    <div class="modal-footer">
                      <!--<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>-->                      
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">webhost Client List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="customer" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Customer Name</th>
                  <th>Customer Email</th>
                  <th>Company</th>
                 <!--  <th>Phone</th> -->
                  <th>Manage</th>
                </tr>
                </thead>
                <tbody>
                <?php

                if ($query->num_rows() > 0)
                {
                   foreach ($query->result() as $row)
                   {
                    echo"<tr>";
                    echo"<td> $row->id </td>";
                    echo"<td> $row->first_name $row->last_name </td>";
                    echo"<td> $row->email </td>";
                    echo"<td> $row->company </td>";
                    //echo"<td> $row->phone </td>";
                    
                    echo <<<EOL
                  <td><div class='btn-group'>
                  <button type='button' class='btn btn-info'>Manage</button>
                  <button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                    <span class='caret'></span>
                    <span class='sr-only'>Toggle Dropdown</span>
                  </button>
                  <ul class='dropdown-menu' role='menu'>
                    <li><a href='#'Maagae</a></li>
                    <li><a href='#'>view</a></li>
                    <li class='divider'></li>
                    <li><a href='#'>Edit</a></li>
                  </ul>
                </div></td>
EOL;


                    echo"</tr>";
                   }
                }
                

                ?>
                
                             
                
                </tbody>
                <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Customer Name</th>
                  <th>Customer Email</th>
                  <th>Company</th>
                  <!-- <th>Phone</th> -->
                  <th>Manage</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->