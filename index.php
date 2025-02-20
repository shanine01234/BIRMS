<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Error</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #000;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            font-size: 10rem;
            color: transparent;
            -webkit-text-stroke: 2px #0f0;
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
            animation: borderRun 3s linear infinite;
            cursor: pointer;
        }

        @keyframes borderRun {
            0% {
                -webkit-text-stroke-color: #f00;
            }
            33% {
                -webkit-text-stroke-color: #0f0;
            }
            66% {
                -webkit-text-stroke-color: #00f;
            }
            100% {
                -webkit-text-stroke-color: #f00;
            }
        }

        p {
            font-size: 1.5rem;
            color: transparent;
            -webkit-text-stroke: 1px #fff;
            animation: borderRunText 3s linear infinite;
        }

        @keyframes borderRunText {
            0% {
                -webkit-text-stroke-color: #f00;
            }
            33% {
                -webkit-text-stroke-color: #0f0;
            }
            66% {
                -webkit-text-stroke-color: #00f;
            }
            100% {
                -webkit-text-stroke-color: #f00;
            }
        }

        button {
            display: none; /* Makes the button invisible */
        }
    </style>
</head>
<body>
    <div>
        <h1 id="led">404</h1>
        <p>Bachelor of Science in Information Technology</p>
    </div>

    <audio id="error-sound" loop>
        <source src="img/photos/bini.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <button onclick="playSound()">Play Sound</button> <!-- Invisible button -->

    <script>
        const sound = document.getElementById('error-sound');
        const led = document.getElementById('led');

        // Play sound on user interaction
        document.body.addEventListener('click', () => {
            sound.play().catch((error) => {
                console.log('Error playing sound:', error);
            });
        });

        // Ensure sound effect plays on hover
        led.addEventListener('mouseenter', () => {
            sound.play().catch((error) => {
                console.log('Error playing sound:', error);
            });
        });

        // Play sound function (still tied to button for accessibility if needed)
        function playSound() {
            sound.play().catch((error) => {
                console.log('Error playing sound:', error);
            });
        }
    </script>
</body>
</html>
