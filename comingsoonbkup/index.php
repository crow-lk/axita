<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon | Axita Computers</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: black;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100%;
            padding: 20px;
        }
        .content {
            max-width: 900px;
            width: 100%;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #ff6600;
        }
        .coming-soon {
            font-size: 40px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            color: #fff;
        }
        .tagline {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .image-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .image-container img {
            width: 100%;
            height: auto;
            max-width: 100%;
            border-radius: 10px;
        }
        .contact-info {
            margin-top: 20px;
            font-size: 18px;
        }
        .contact-info p {
            margin: 5px 0;
        }
        .contact-info a {
            color: #ff6600;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .logo {
                font-size: 26px;
            }
            .coming-soon {
                font-size: 32px;
            }
            .tagline {
                font-size: 16px;
            }
            .image-container {
                width: 100%;
            }
            .image-container img {
                border-radius: 5px;
            }
            .contact-info {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="content">
        <div class="logo">Axita Computers (Pvt) Ltd</div>
        <p class="tagline">Laptop Repairing Workshop & Selling Showroom</p>
        
        <p class="coming-soon">We're working hard to bring you an amazing experience.</p>

        <div class="image-container">
            <img src="images/test image (1).webp" alt="Axita Computers Coming Soon">
        </div>

        <div class="contact-info">
            <p>📍 Address: 438/2, Beligaha Junction, Galle, Sri Lanka</p>
            <p>📞 Contact: <a href="tel:+94771284323">077 128 4323</a></p>
            <p>🌐 <a href="https://g.co/kgs/ix1CGWr" target="_blank">Google Business Page</a></p>
        </div>
    </div>

</body>
</html>
