<div class="page-header">
          <h1>Humans vs Zombies <small> Dead of Winter </small></h1>
        </div>
        <div class="row">
          <div class="span10">
            <h2> Sign Safety Waiver (Required) </h2>
              <div class="span12">
                <form>
                  <fieldset>
                  <div class="clearfix">
                  <label id = test >Safety Waiver</label>
                      <form>
                      <input type = button class="btn primary" VALUE="READ ME" ONCLICK="window.location.href='http://www.computerhope.com'" ></button>
                      </form>
                  </div> 
               <div class="clearfix">
                      <label>Agreements</label>
                      <div class="input">
                          <ul class="inputs-list">
                              <li>
                                  <label>
                                      <input type="checkbox" />
                                      I accept the terms of the Safety Waiver
                                  </label>
                                  <label>
                                      <input type="checkbox" />
                                      I am awesome
                                  </label>
                              </li>
                          </ul>
                      </div>
                  </div>
                  </fieldset>
                </form>
              </div> 
            <h2> Profile Information (Optional) </h2>
              <div class="span12">
                <form>
                  <fieldset>
                    <div class="clearfix">
                        <label>Age</label>
                        <div class="input">
                            <select>
                              <option></option>
                                <?php
                                  for($i = 10; $i < 114; $i = $i + 1 ){
                                      echo "<option> ";
                                      echo $i;
                                      echo "</option> ";
                                  }
                                ?>
                            </select>
                      </div>
                    </div>
                    <div class="clearfix">
                        <label>Gender</label>
                        <div class="input">
                            <select>
                                <option></option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                      </div>
                    </div>
                    <div class="clearfix">
                        <label>Major</label>
                        <div class="input">
                            <input type="text" />
                        </div>
                    </div>
                    <div class="clearfix">
                        <label> Profile Picture </label>
                        <div class="input">
                            <input type="file" class="input-file" />
                        </div>
                    </div>

                    <div class="actions">
                        <button id = test class="btn success"> Save and Finish </button>
                    </div>                   
                  </fieldset>
                </form>
              </div>  
          </div>
        </div>