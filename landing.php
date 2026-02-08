<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheelder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #0a0a0a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .scene {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
            position: relative;
        }
        
        /* Scanlines */
        .scanlines {
            position: absolute;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(255, 255, 255, 0.03) 2px,
                rgba(255, 255, 255, 0.03) 4px
            );
            pointer-events: none;
            z-index: 100;
        }
        
        /* Vignette */
        .vignette {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(0,0,0,0.8) 100%);
            pointer-events: none;
            z-index: 99;
        }
        
        /* Central wheel container */
        .wheel-container {
            position: relative;
            width: 500px;
            height: 500px;
            transform-style: preserve-3d;
            animation: tiltScene 10s ease-in-out infinite;
        }
        
        @keyframes tiltScene {
            0%, 100% { transform: rotateX(10deg) rotateY(0deg); }
            50% { transform: rotateX(10deg) rotateY(360deg); }
        }
        
        /* Main holographic wheel */
        .holo-wheel {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 350px;
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 
                0 0 30px #ffffff,
                0 0 60px #ffffff,
                0 0 100px rgba(255, 255, 255, 0.5),
                inset 0 0 50px rgba(255, 255, 255, 0.3);
            animation: wheelPulse 3s ease-in-out infinite;
        }
        
        @keyframes wheelPulse {
            0%, 100% { box-shadow: 0 0 30px #ffffff, 0 0 60px #ffffff, 0 0 100px rgba(255, 255, 255, 0.5), inset 0 0 50px rgba(255, 255, 255, 0.3); }
            50% { box-shadow: 0 0 50px #ffffff, 0 0 100px #ffffff, 0 0 150px rgba(255, 255, 255, 0.7), inset 0 0 80px rgba(255, 255, 255, 0.5); }
        }
        
        /* Wheel spokes */
        .spoke {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 3px;
            height: 175px;
            background: linear-gradient(to bottom, #ffffff, transparent);
            transform-origin: top center;
            box-shadow: 0 0 10px #ffffff;
        }
        
        /* Inner rings */
        .inner-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            border: 2px solid;
            animation: ringRotate linear infinite;
        }
        
        .ring-1 {
            width: 280px;
            height: 280px;
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
            animation-duration: 20s;
        }
        
        .ring-2 {
            width: 200px;
            height: 200px;
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
            animation-duration: 15s;
            animation-direction: reverse;
        }
        
        .ring-3 {
            width: 120px;
            height: 120px;
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
            animation-duration: 10s;
        }
        
        @keyframes ringRotate {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* Center hub */
        .hub {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: radial-gradient(circle, #ffffff 0%, #a0a0a0 100%);
            box-shadow: 
                0 0 30px #ffffff,
                0 0 60px rgba(255, 255, 255, 0.5);
            animation: hubGlow 2s ease-in-out infinite;
        }
        
        @keyframes hubGlow {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
        }
        
        /* Data stream circles */
        .data-orbit {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            border: 1px dashed rgba(255, 255, 255, 0.3);
        }
        
        .orbit-1 { width: 400px; height: 400px; animation: orbitSpin 30s linear infinite; }
        .orbit-2 { width: 450px; height: 450px; animation: orbitSpin 40s linear infinite reverse; }
        
        @keyframes orbitSpin {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        .data-point {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 15px #ffffff;
        }
        
        /* Text elements */
        .brand {
            position: absolute;
            bottom: 15%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 50;
        }
        
        .title {
            font-size: 5rem;
            font-weight: 100;
            letter-spacing: 25px;
            color: transparent;
            background: linear-gradient(90deg, #ffffff, #00c8ff, #ff0080, #ffffff);
            background-size: 300% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            animation: gradientShift 5s linear infinite;
            text-transform: uppercase;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            100% { background-position: 300% 50%; }
        }
        
        .subtitle {
            font-size: 0.9rem;
            letter-spacing: 8px;
            color: #ffffff;
            opacity: 0.7;
            margin-top: 15px;
            text-transform: uppercase;
        }
        
        /* Glitch effect on title */
        .title::before,
        .title::after {
            content: 'WHEELDER';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: transparent;
        }
        
        .title::before {
            animation: glitch1 2s infinite linear alternate-reverse;
            color: #ff0080;
            z-index: -1;
        }
        
        .title::after {
            animation: glitch2 3s infinite linear alternate-reverse;
            color: #00c8ff;
            z-index: -2;
        }
        
        @keyframes glitch1 {
            0%, 90%, 100% { transform: translate(0); opacity: 0; }
            92%, 94%, 96%, 98% { transform: translate(-3px, 2px); opacity: 0.8; }
        }
        
        @keyframes glitch2 {
            0%, 90%, 100% { transform: translate(0); opacity: 0; }
            91%, 93%, 95%, 97%, 99% { transform: translate(3px, -2px); opacity: 0.8; }
        }
        
        /* Floating hex particles */
        .hex {
            position: absolute;
            width: 20px;
            height: 23px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            animation: hexFloat linear infinite;
            opacity: 0.5;
        }
        
        @keyframes hexFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.5; }
            90% { opacity: 0.5; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }
        
        /* Corner decorations */
        .corner {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            z-index: 50;
        }
        
        .corner-tl { top: 30px; left: 30px; border-right: none; border-bottom: none; }
        .corner-tr { top: 30px; right: 30px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: 30px; left: 30px; border-right: none; border-top: none; }
        .corner-br { bottom: 30px; right: 30px; border-left: none; border-top: none; }
        
        /* Status text */
        .status {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            letter-spacing: 3px;
            color: rgba(255, 255, 255, 0.5);
            z-index: 50;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .wheel-container { transform: scale(0.6); }
            .title { font-size: 3rem; letter-spacing: 15px; }
            .brand { bottom: 20%; }
        }
        
        @media (max-width: 480px) {
            .wheel-container { transform: scale(0.45); }
            .title { font-size: 2rem; letter-spacing: 10px; }
            .subtitle { font-size: 0.7rem; letter-spacing: 4px; }
        }
    </style>
</head>
<body>
    <div class="scene">
        <div class="scanlines"></div>
        <div class="vignette"></div>
        
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>
        
        
        <div class="wheel-container">
            <!-- Data orbits -->
            <div class="data-orbit orbit-1"></div>
            <div class="data-orbit orbit-2"></div>
            
            <!-- Main wheel -->
            <div class="holo-wheel" id="holoWheel"></div>
            
            <!-- Inner rings -->
            <div class="inner-ring ring-1"></div>
            <div class="inner-ring ring-2"></div>
            <div class="inner-ring ring-3"></div>
            
            <!-- Hub -->
            <div class="hub"></div>
        </div>
    </div>
    
    <script>
        // Create wheel spokes
        const wheel = document.getElementById('holoWheel');
        for (let i = 0; i < 12; i++) {
            const spoke = document.createElement('div');
            spoke.className = 'spoke';
            spoke.style.transform = `rotate(${i * 30}deg)`;
            wheel.appendChild(spoke);
        }
        
        // Create data points on orbits
        document.querySelectorAll('.data-orbit').forEach((orbit, orbitIndex) => {
            const numPoints = 4 + orbitIndex * 2;
            const radius = orbit.offsetWidth / 2;
            
            for (let i = 0; i < numPoints; i++) {
                const point = document.createElement('div');
                point.className = 'data-point';
                const angle = (i / numPoints) * 360;
                point.style.left = `calc(50% + ${Math.cos(angle * Math.PI / 180) * radius}px - 4px)`;
                point.style.top = `calc(50% + ${Math.sin(angle * Math.PI / 180) * radius}px - 4px)`;
                orbit.appendChild(point);
            }
        });
        
        // Create floating hexagons
        const scene = document.querySelector('.scene');
        for (let i = 0; i < 20; i++) {
            const hex = document.createElement('div');
            hex.className = 'hex';
            
            const size = Math.random() * 15 + 10;
            const x = Math.random() * 100;
            const duration = Math.random() * 20 + 15;
            const delay = Math.random() * 20;
            
            hex.style.width = size + 'px';
            hex.style.height = (size * 1.15) + 'px';
            hex.style.left = x + '%';
            hex.style.animationDuration = duration + 's';
            hex.style.animationDelay = delay + 's';
            
            if (Math.random() > 0.7) {
                hex.style.borderColor = 'rgba(255, 0, 128, 0.3)';
            } else if (Math.random() > 0.5) {
                hex.style.borderColor = 'rgba(0, 200, 255, 0.3)';
            }
            
            scene.appendChild(hex);
        }
        
        // Interactive tilt on mouse move
        const container = document.querySelector('.wheel-container');
        document.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            container.style.transform = `rotateX(${10 - y}deg) rotateY(${x}deg)`;
        });
    </script>
</body>
</html>