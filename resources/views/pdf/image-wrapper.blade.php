<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <style>
        /* กำหนดกฎสำหรับการพิมพ์โดยเฉพาะ */
        @page {
            margin: 0;
            size: A4 landscape;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
    </style>
</head>

<body>
    <img src="{{ $imagePath }}" alt="Certificate">
</body>

</html>
