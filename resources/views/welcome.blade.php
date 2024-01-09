<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Poeditor</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
            color: #1b283d;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
        }

        .attribution {
            font-size: 14px;
            color: #9ca3af;
        }

        form {
            text-align: left;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #3490dc;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2779bd;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <img src="https://zupimages.net/up/22/11/ho3i.png" alt="Votre image de profil" class="profile-image">
    <h1>Poeditor</h1>
    <p>Remplisser vos fichiers .po avec un Excel</p>
    <form method="post" action="{{ route('transform') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="poFile">.po upload</label>
            <input name="po" type="file" id="poFile">
        </div>
        <div class="form-group">
            <label for="csvFile">.csv upload</label>
            <input name="csv" type="file" id="csvFile">
        </div>
        <div>
        <div class="form-group">
            <label for="sourceLang">Langue source (msgid)</label>
            <select name="sourceLang" id="sourceLang">
                <option value="fr">Français</option>
                <option value="en">Anglais</option>
                <!-- Ajoutez d'autres langues au besoin -->
            </select>
        </div>
        <div class="form-group">
            <label for="targetLang">Langue cible (msgstr)</label>
            <select name="targetLang" id="targetLang">
                <option value="en">Anglais</option>
                <option value="fr">Français</option>
                <!-- Ajoutez d'autres langues au besoin -->
            </select>
        </div>
        </div>
        <input type="submit" value="Uploader">
    </form>
</div>

<div class="container">
    <p>Swaper vos .po (msgid ➡️ msgstr, msgstr ➡️ msgid ) </p>
    <form method="post" action="{{ route('swap') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="poFile">.po upload</label>
            <input name="po" type="file" id="poFile">
        </div>
        <input type="submit" value="Uploader">
    </form>
    <a href="https://www.instagram.com/photographelu/"><p class="attribution">Créé par Léo 🦄 </p></a>
</div>
</body>
</html>
