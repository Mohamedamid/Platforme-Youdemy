<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Enseignants</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #34495e;
        }

        .header {
            background-color: #34495e;
            color: white;
            padding: 60px 20px;
            text-align: center;
            height: calc(100vh - 177.5px);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .header h1 {
            margin-bottom: 20px;
        }

        .header p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 18px;
            line-height: 1.6;
        }

        .teachers-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        @media (max-width: 768px) {
            .teachers-container {
                grid-template-columns: 1fr;
            }

            .header {
                padding: 40px 20px;
            }

            .header p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="logo">Académie d'Apprentissage</a>
            <ul class="nav-links">
                <li><a href="./index.php">Accueil</a></li>
            </ul>
        </div>
    </nav>
    <header class="header">
        <h1><strong>Objet :</strong> Statut de votre compte en tant qu'enseignant</h1>
        <pre>
            Nous vous informons que votre compte d'enseignant sur <strong>Académie d'Apprentissage</strong> 
            a été fermé.Vous n'avez plus accès à votre compte à ce jour. 

            Si vous pensez qu'il s'agit d'une erreur, veuillez contacter notre équipe pour plus d'informations.

            Nous vous remercions pour votre compréhension.</pre>
    </header>
</body>
</html>
