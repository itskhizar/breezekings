<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script src="https://kit.fontawesome.com/3c8af4c8e3.js" crossorigin="anonymous"></script>
  <style>
    .login{
      width: 200px;
      color: white;
      background-color: #47b2e4;

    }
    .login:hover{
      color: white;
    }
  </style>
</head>
<body>
   <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">
          

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>Filenod</h3>
            <p>
              Filenod is an IT Services Provider company. We strive to convert your great ideas into reality. Since its inception in 2018, Filenod has been providing 360 degree IT services to its clients.
            </p><br>

           
            <!-- <a class="login btn  btn-md" href="login.php">Login</a> -->
          
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="about-us.php">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="index.php#services">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="terms-conditions.php">Terms and Conditions</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="privacy-policy.php">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <?php $database->groupdata("services_foot", ""); ?>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Social Networks</h4>
            <p>
              <i class="fas fa-map-marker-alt"></i> &nbsp;&nbsp;Filenod office opposite UBL Mandian, Abbottabad, 22044.<br>
              <br>
              <i class="fas fa-phone-alt"></i> &nbsp;&nbsp;+92 345 4955590<br><br>
              <i class="fas fa-envelope"></i> &nbsp;&nbsp;info@filenod.com<br>
            </p>
            <div class="social-links mt-3">
              <a href="https://www.facebook.com/FilenodIt" target="_blank" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="https://www.instagram.com/filenod_" target="_blank" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="https://linkedin.com/in/asad-hussain-shah-7b1220187" target="_blank" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
          </div>

        
        </div>
      </div>
    </div>
    <div class="container footer-bottom clearfix">
      <div class="copyright">
        &copy; Copyright <strong><span>Filenod</span></strong>. All Rights Reserved
      </div>
    </div>
  </footer>
</body>
</html>