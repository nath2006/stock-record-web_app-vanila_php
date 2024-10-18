<?php
session_start();
include 'data/koneksi.php';

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password === $row["password"]) {
            $_SESSION["login"] = true;
            $_SESSION["user_id"] = $row["id"]; // Store user_id in session
            $_SESSION["username"] = $row["username"];
            header("Location: index.php");
            exit;
        } else {
            header("location:login.php?message=NIP atau Password salah!");
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>Login</title>
    </head>
    <body>
        <!-- card Login Start-->
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <div class="mb-md-5 mt-md-4 pb-5">
                                    <h2 class="fw-bold mb-2 text-uppercase">Login Halaman</h2>
                                    <?php
                                        if (isset($_GET['message'])) {
                                            $msg = $_GET['message'];
                                            echo "
                                            <p class='text-white-50 mb-5'>$msg</p>
                                            ";
                                        }
                                        ?>
                                    <p class="text-white-50 mb-5">Silakan masukan Username dan Password anda!</p>
                                    <form action="" method="POST">
                                        <div class="form-outline form-white mb-4">
                                            <input type="text" class="form-control form-control-lg" placeholder="Username" name="username" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Tolong masukan data yang valid!')" required/>
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                            <input type="password" id="typePasswordX" class="form-control form-control-lg" placeholder="Password" name="password" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Tolong masukan data yang valid!')" required/>
                                        </div>
                                        <button class="btn btn-outline-light btn-lg px-5" type="submit" name="login">Login</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- card Login End-->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </body>
</html>