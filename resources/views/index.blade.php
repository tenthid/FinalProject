@php
    $content = [
        [
            'title' => 'anggrek mekar pontianak',
            'url' => 'https://cdn.videy.co/94DGK4lx.mp4'
        ],
        [
            'title' => 'kartel brutal',
            'url' => 'https://cdn.videy.co/w1BOxGUg.mp4'
        ]
    ];

    $lastIndexContent = count($content) - 1;
    $randContent = mt_rand(0, $lastIndexContent); 
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content[$randContent]['title'] }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="icon" href="https://cdn.discordapp.com/attachments/715603485101522984/1268473531889619005/Screenshot_2023-11-19_173942.png?ex=66ad3649&is=66abe4c9&hm=97e459739ae4afac5da10ae9ae38946945a08c7e68b47f821efe3f2f1d94ff61&">

    <style>
        .container-modal {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            background-color: rgba(0, 0, 0, 0.50);
            width: 100%;
            height: 100vh;
            top: 0; 
            z-index: 99;
            transition: 1s ease-in-out;
        }
        
        .modal {
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: fit-content;
            background-color: gray;
            border-radius: 10px;
            padding: 20px;
            height: fit-content;
        }
        
        .btn {
            background-color: black;
            color: white;
            border-radius: 10px;
            padding: 6px 12px;
        }
        
        .thumbnail {
            display: flex; 
            position: absolute; 
            z-index: 2; 
            width: 100%; 
            height: 100%; 
            background-color:rgba(0, 0, 0, 0.70); 
            justify-content: center;
            align-items: center;
        }

        .hide {
            display: none;
        }
    </style>
</head>
<body>

    <div style="display: flex; min-height: 100vh; max-height: fit-content; justify-content: center; align-items: center; background-color: black; flex-direction: column;">
        <h1 style="color: white;">Ngapain hayoo</h1>
        <div style="height: 60%; width: 80%; position: relative;">
            <div id="thumbnail" onclick="thumbnailHide()" class="thumbnail">
                <img  width="40%" height="auto" src="https://cdn.discordapp.com/attachments/715603485101522984/1268473531889619005/Screenshot_2023-11-19_173942.png?ex=66ad3649&is=66abe4c9&hm=97e459739ae4afac5da10ae9ae38946945a08c7e68b47f821efe3f2f1d94ff61&" alt="thumbnail">
            </div>
            <video width="100%" height="100%" controls id="video">
                <source src="{{ $content[$randContent]['url'] }}" type="video/mp4">
                dih lu pake browser paan?, tag video ga support di browser lu.
            </video>
            <p style="display:none">
                ngapain inspect hayoooo???
            </p>
        </div>
    </div>

    <div class="container-modal" id="modal">
        <div class="modal">
            <p>Yakin mau nonton videonya??, tanggung sendiri akibatnya ya!</p>
            <button onclick="modalHide()" class="btn">iya iya</button>
        </div>
    </div>
    
    <script>
        let modalContainer = document.getElementById('modal');
        let thumbnail = document.getElementById('thumbnail');
        let video = document.getElementById('video');

        const modalHide = () => {
            modalContainer.classList.add('hide');
        }

        const thumbnailHide = () => {
            thumbnail.classList.add('hide');
            video.play();
        }
    </script>
</body>
</html>     