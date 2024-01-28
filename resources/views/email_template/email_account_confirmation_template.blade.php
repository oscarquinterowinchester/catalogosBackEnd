<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nanum+Gothic">
    

    <style>
        .text-dark{
            color: #252525;
        }
        .blue-text{
            color: #002c74;
        }
        .text-300{
            font-weight: 300;
        }.text-bold{
            font-weight: bold;
        }
        .text-s{
            font-size: small;
        }
        .text-l{
            font-size: larger;
        }.button{
            border-radius: 5px;
            padding: 10px;
            border:#02020c;
            background: #003649;
            color: white;
            font-weight: bold;
            font-size: large;
            box-shadow: 0px 1px 10px 1px rgba(0, 0, 0, 0.466);
            cursor: pointer;
        }

        .Arimo{
            font-family: 'Arimo', sans-serif;
        }
        .Nanum{
            font-family: 'Nanum Gothic', sans-serif;
        }
    </style>
</head>
<body>
    <div class="text-300 text-dark Arimo blue-text" style="width: 100%; text-align: center; padding-top: 5%; padding-bottom: 0%;">
        <h2>GRACIAS POR REALIZAR SU REGISTRO DE USUARIO EN <?= strtoupper(env("COMPANY_NAME")) ?></h2> 
    </div>
    <div class="text-300 text-l Nanum" style="width: 100%; text-align: center; padding-top: 1%; padding-bottom: 1%;">
        Para concluir el proceso de registro pulse el enlace debajo, al hacerlo podremos comprobar que quien solicitó el registro es el usuario de este email y programar su contraseña de acceso al sistema 
    </div>
    <div class="text-bold Nanum" style="width: 100%; text-align: center; padding-top: 0%; padding-bottom: 1%;">
        Si usted no fue quien solicitó el registro solo haga caso omiso a este mensaje. 
     </div>
     <div class="text-300 text-s" style="width: 100%; text-align: center; padding-top: 0%; padding-bottom: 5%;">
        <a href="{{$url}}">
            <button class="button Arimo">Verificar registro</button>
        </a>
     </div>
</body>
</html>
