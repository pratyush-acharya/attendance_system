
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>Login</title>
    <meta name="google-signin-client_id" content="848005554800-hq4fu22o449tu1ff2g9tsgdbaksljs9c.apps.googleusercontent.com">
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css"
    />

    <!--  -->
    <link rel="stylesheet" href="/assets/css/loginStyle.css" />

    <!-- fontawesome -->
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    />

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png">
  </head>
  <body>
  <div class="container-fluid" style="max-height: 100vh">
    <div class="row">
      <div class="col-md-6">
        <img src="assets/images/login_image.png" class="responsive" />
      </div>

      <div class="col-md-5 col-lg-4 col-10 pt-5 welcome-container" style="height:500px">
        <h2>WELCOME BACK!</h2>
          @error('email')
              <span class="@error('title', 'password','role') is-invalid @enderror alert alert-danger" style="justify-content:start;" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror

          @error('role')
              <span class="@error('role') is-invalid @enderror alert alert-danger" style="justify-content:start;" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
          <br />

        <form action="{{route('login')}}" method="POST">
          @csrf
          <div class="mb-3">
            <h5>Please select role to login.</h5>
            <select id="role" name="role" class="form-select" onchange="toggleForm(this.value)">
              <option value="student" selected>Student</option>
              <option value="teacher">Teacher</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div id="teacher-admin-form" class="d-none">
            <div class="mb-3">
              <h5>Email</h5>
              <input
              class="email"
              type="email"
              name="email"
              placeholder="Enter Your Email Address"
              />
            </div>
            
            <div class="mb-3">
  
              <h5>Password</h5>
              <div class="unsee-icon">
                <input id="password" class="password" type="password" name="password" placeholder="********" />
                <i id="password_visibility" class="fa fa-eye-slash" onclick="togglePasswordVisibility()"></i>
              </div>
            </div>
            
            <button class="button button3" >Login</button>
            <span><a href="/forgot-password">Forgot Password?</a></span>
          </div>
          <div class="flex items-center justify-end mt-4 text-center" id="student-form">
            <hr class="mt-5">
            <h3 class="text-center">Student Login</h3>
            <h5 class="text-center my-4">Please use college's email to login</h5>
            <a href="{{ route('redirectToGoogle')}}">
              <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" class="text-center">
            </a>
          </div>
        </form>
    </div>
  </div>
</body>
@include('sweetalert::alert')
<script>
  function togglePasswordVisibility()
  {
    let password = document.getElementById('password');
    let passwordVisibility = document.getElementById('password_visibility');

    passwordVisibility.classList.toggle("fa-eye");
    passwordVisibility.classList.toggle("fa-eye-slash");

    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
  }

  function toggleForm(role)
  {
    let teacherAdminForm = document.getElementById('teacher-admin-form');
    let studentForm = document.getElementById('student-form');

    if(role === 'student')
    {
      studentForm.classList.toggle('d-none');
      if(!teacherAdminForm.classList.contains('d-none'))
      {
        teacherAdminForm.classList.add('d-none');
      }
    }
    else
    {
      if(teacherAdminForm.classList.contains('d-none'))
      {
        teacherAdminForm.classList.remove('d-none');
      }
      if(!studentForm.classList.contains('d-none'))
      {
        studentForm.classList.add('d-none');
      }
    }
  }

</script>
</html>
