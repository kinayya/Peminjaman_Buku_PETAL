<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PETAL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <style>

        * {
            box-sizing: border-box;
            font-family: 'Garamond', serif;
        }

        body {
            background: linear-gradient(
                180deg,
                #b4dbf7 -10%,
                #feffd6 40%,
                #feffd6 65%,
                #b4dbf7 130%
            );

            min-height: 100vh;
            margin: 0;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 0 5%;
        }

        /* Container utama */

        .container {
            display: flex;

            width: 100%;
            max-width: 1100px;

            align-items: center;
            justify-content: space-between;

            gap: 40px;
        }

        /* Bagian Kiri */

        .left {
            flex: 1;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .left img {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
        }

        /* Bagian Kanan */

        .right {
            flex: 1;

            display: flex;
            flex-direction: column;
            align-items: center;

            max-width: 450px;
            width: 100%;
        }

        /* Quote */

        .quote {
            color: #2c4c81;

            font-size: 1.2rem;

            margin-bottom: 25px;

            text-align: center;

            font-family: "Changa One", sans-serif;

            font-weight: 400;
            font-style: italic;
        }

        /* Form */

        .right form {
            width: 100%;
        }

        /* Input */

        .right input {
            width: 100%;
            padding: 16px 24px;
            margin-bottom: 20px;
            border: 2px solid #6b96da;
            border-radius: 50px;
            font-size: 1rem;
            text-align: center;
            outline: none;
        }

        .right input:focus {
            border-color: #456cae;
        }

        /* =========================
           PASSWORD
           ========================= */

        .password-box {
            position: relative;
            width: 100%;
        }

        .password-box input {
            padding-right: 55px;
        }

        /* Ikon Mata */

        .toggle-password {
            position: absolute;
            right: 50px;
            top: 35%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #2c4c81;
            font-size: 17px;
            cursor: pointer;
            width: auto;
        }

        .toggle-password:hover {
            color: #456cae;
        }

        /* Tombol Masuk */

        .right button[type="submit"] {
            width: 100%;
            padding: 16px 24px;
            margin-bottom: 20px;
            border-radius: 50px;
            border: solid 2px #6b96da;
            font-size: 1rem;
            text-align: center;
            background-color: #6b96da;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .right button[type="submit"]:hover {
            background-color: #b4dbf7;
            color: #2c4c81;
        }

        /* Link Register */

        .register-link {
            color: #2c4c81;
            text-decoration: none;
            font-size: 0.95rem;
            text-align: center;
            transition: color 0.2s ease;
        }

        .register-link:hover {
            text-decoration: underline;
        }

    </style>

</head>


<body>

    <div class="container">

        <!-- Bagian Kiri -->

        <div class="left">

            <img src="assets/logo.svg" alt="logo-apk">

        </div>


        <!-- Bagian Kanan -->

        <div class="right">

            <p class="quote">
                "You only need one book to fall in love with reading. Go find it."
            </p>


            <!-- Form Login -->

            <form action="controller/login.php" method="POST">

                <!-- Username -->

                <input
                    type="text"
                    name="username"
                    placeholder="username"
                    autocomplete="off"
                    required>


                <!-- Password -->

                <div class="password-box">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="password"
                        required>

                    <button
                        type="button"
                        class="toggle-password"
                        id="toggleButton"
                        onclick="togglePassword()"
                        aria-label="Tampilkan password">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                </div>


                <!-- Tombol Masuk -->

                <button
                    type="submit"
                    name="masuk">
                    Masuk
                </button>

            </form>


            <!-- Register -->

            <a
                href="views/register.php"
                class="register-link">
                Belum memiliki akun? Ayo daftar sekarang!
            </a>

        </div>

    </div>


    <!-- JavaScript Toggle Password -->

    <script>

        function togglePassword() {

            const password = document.getElementById("password");
            const toggleButton = document.getElementById("toggleButton");

            if (password.type === "password") {

                password.type = "text";

                toggleButton.innerHTML =
                    '<i class="fa-solid fa-eye-slash"></i>';

            } else {

                password.type = "password";

                toggleButton.innerHTML =
                    '<i class="fa-solid fa-eye"></i>';

            }

        }

    </script>

</body>

</html>