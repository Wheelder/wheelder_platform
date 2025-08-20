<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wheelder</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: #000;
      color: #fff;
      overflow: hidden;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    /* Starfield background */
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(2px 2px at 20px 30px, #eee, transparent),
        radial-gradient(2px 2px at 40px 70px, #ddd, transparent),
        radial-gradient(1px 1px at 50px 160px, #fff, transparent),
        radial-gradient(1px 1px at 130px 40px, #eee, transparent),
        radial-gradient(2px 2px at 180px 200px, #ddd, transparent);
      background-size: 200px 200px;
      animation: stars 300s linear infinite;
      z-index: -2;
    }

    @keyframes stars {
      from { transform: translateY(0); }
      to { transform: translateY(-200px); }
    }

    /* Main container */
    .container {
      position: relative;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    /* Logo/Title */
    .logo {
      position: absolute;
      top: 40px;
      font-size: 2.5rem;
      font-weight: 300;
      letter-spacing: 8px;
      color: #fff;
      text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
      opacity: 0;
      animation: fadeIn 2s ease-out 0.5s forwards;
    }

    /* Solar system container */
    .solar-system {
      position: relative;
      width: 600px;
      height: 600px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Sun at center */
    .sun {
      position: absolute;
      width: 120px;
      height: 120px;
      background: radial-gradient(circle at 30% 30%, #fff8dc 0%, #ffd700 30%, #ff8c00 70%, #ff4500 100%);
      border-radius: 50%;
      box-shadow: 
        0 0 80px #ff8c00,
        0 0 120px #ff6347,
        0 0 200px #ff4500,
        inset 0 0 40px #ff6347;
      animation: sunPulse 4s ease-in-out infinite;
      z-index: 10;
    }

    /* Sun corona effect */
    .sun::before {
      content: '';
      position: absolute;
      top: -20px;
      left: -20px;
      right: -20px;
      bottom: -20px;
      background: radial-gradient(circle at center, transparent 40%, rgba(255, 140, 0, 0.1) 100%);
      border-radius: 50%;
      animation: coronaRotate 60s linear infinite;
    }

    @keyframes sunPulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    @keyframes coronaRotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Orbital paths */
    .orbit {
      position: absolute;
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .orbit-earth {
      width: 300px;
      height: 300px;
      border-color: rgba(65, 105, 225, 0.3);
    }

    .orbit-species-1 {
      width: 200px;
      height: 200px;
      border-color: rgba(144, 238, 144, 0.3);
    }

    .orbit-species-2 {
      width: 250px;
      height: 250px;
      border-color: rgba(135, 206, 235, 0.3);
    }

    .orbit-species-3 {
      width: 350px;
      height: 350px;
      border-color: rgba(222, 184, 135, 0.3);
    }

    .orbit-species-4 {
      width: 400px;
      height: 400px;
      border-color: rgba(255, 218, 185, 0.3);
    }

    .orbit-species-5 {
      width: 450px;
      height: 450px;
      border-color: rgba(221, 160, 221, 0.3);
    }

    /* Planets and species */
    .celestial-body {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      transform: translate(-50%, -50%);
    }

    /* Earth */
    .earth-container {
      animation: orbit 60s linear infinite;
    }

    .earth {
      position: absolute;
      width: 40px;
      height: 40px;
      background: 
        radial-gradient(circle at 30% 30%, #4169e1 0%, #191970 50%, #000080 100%);
      border-radius: 50%;
      top: -150px;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 
        0 0 20px #4169e1,
        inset -5px -5px 10px rgba(0, 0, 0, 0.5);
      animation: planetRotate 10s linear infinite;
    }

    /* Earth continents */
    .earth::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 50%, #228b22 0%, transparent 30%),
        radial-gradient(circle at 60% 30%, #228b22 0%, transparent 25%),
        radial-gradient(circle at 50% 70%, #228b22 0%, transparent 20%);
      border-radius: 50%;
      opacity: 0.8;
    }

    /* Species representations */
    .species {
      position: absolute;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }

    .species-1-container {
      animation: orbit 40s linear infinite reverse;
    }

    .species-1 {
      width: 30px;
      height: 30px;
      background: radial-gradient(circle at 30% 30%, #90ee90 0%, #228b22 100%);
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 0 0 15px #90ee90;
    }

    .species-1::after {
      content: '🌿';
      font-size: 1.2rem;
    }

    .species-2-container {
      animation: orbit 50s linear infinite;
    }

    .species-2 {
      width: 35px;
      height: 35px;
      background: radial-gradient(circle at 30% 30%, #87ceeb 0%, #4682b4 100%);
      top: -125px;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 0 0 15px #87ceeb;
    }

    .species-2::after {
      content: '🐟';
      font-size: 1.3rem;
    }

    .species-3-container {
      animation: orbit 70s linear infinite reverse;
    }

    .species-3 {
      width: 30px;
      height: 30px;
      background: radial-gradient(circle at 30% 30%, #deb887 0%, #8b4513 100%);
      top: -175px;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 0 0 15px #deb887;
    }

    .species-3::after {
      content: '🦎';
      font-size: 1.2rem;
    }

    .species-4-container {
      animation: orbit 80s linear infinite;
    }

    .species-4 {
      width: 35px;
      height: 35px;
      background: radial-gradient(circle at 30% 30%, #ffdab9 0%, #ff6347 100%);
      top: -200px;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 0 0 15px #ffdab9;
    }

    .species-4::after {
      content: '🦅';
      font-size: 1.3rem;
    }

    .species-5-container {
      animation: orbit 90s linear infinite reverse;
    }

    .species-5 {
      width: 40px;
      height: 40px;
      background: radial-gradient(circle at 30% 30%, #dda0dd 0%, #8b008b 100%);
      top: -225px;
      left: 50%;
      transform: translateX(-50%);
      box-shadow: 0 0 20px #dda0dd;
    }

    .species-5::after {
      content: '🧬';
      font-size: 1.5rem;
    }

    /* Orbit animation */
    @keyframes orbit {
      from { transform: translate(-50%, -50%) rotate(0deg); }
      to { transform: translate(-50%, -50%) rotate(360deg); }
    }

    @keyframes planetRotate {
      from { transform: translateX(-50%) rotate(0deg); }
      to { transform: translateX(-50%) rotate(360deg); }
    }

    /* Clock integrated in sun */
    .sun-clock {
      position: absolute;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .clock-number {
      position: absolute;
      width: 100px;
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: 600;
      color: #fff;
      text-shadow: 0 0 5px rgba(0, 0, 0, 0.8);
      z-index: 15;
    }

    .number {
      position: absolute;
      transform-origin: center;
      width: 20px;
      text-align: center;
    }

    /* Clock hands */
    .clock-hands {
      position: absolute;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 20;
    }

    .hand {
      position: absolute;
      background: rgba(255, 255, 255, 0.9);
      transform-origin: bottom center;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .hour-hand {
      width: 3px;
      height: 35px;
      bottom: 50%;
      border-radius: 1.5px;
    }

    .minute-hand {
      width: 2px;
      height: 45px;
      bottom: 50%;
      border-radius: 1px;
    }

    .second-hand {
      width: 1px;
      height: 50px;
      bottom: 50%;
      background: #ff0000;
      border-radius: 0.5px;
      box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
    }

    .center-dot {
      position: absolute;
      width: 8px;
      height: 8px;
      background: #fff;
      border-radius: 50%;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
      z-index: 21;
    }

    /* Digital time display */
    .digital-time {
      position: absolute;
      bottom: 80px;
      font-size: 3rem;
      font-weight: 100;
      letter-spacing: 4px;
      color: #fff;
      text-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
      opacity: 0;
      animation: fadeIn 2s ease-out 1s forwards;
    }

    .time-separator {
      animation: blink 1s ease-in-out infinite;
    }

    .time-period {
      font-size: 1.5rem;
      margin-left: 10px;
      opacity: 0.8;
    }

    .timezone {
      position: absolute;
      bottom: 40px;
      font-size: 1rem;
      letter-spacing: 2px;
      color: #fff;
      opacity: 0.6;
      animation: fadeIn 2s ease-out 1.5s forwards;
    }

    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.3; }
    }

    /* Evolution info */
    .evolution-info {
      position: absolute;
      top: 100px;
      right: 40px;
      max-width: 200px;
      font-size: 0.8rem;
      opacity: 0.7;
      text-align: right;
    }

    .evolution-info h3 {
      font-size: 1rem;
      margin-bottom: 10px;
      color: #ffd700;
    }

    .evolution-info p {
      margin: 5px 0;
      line-height: 1.4;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .solar-system {
        width: 400px;
        height: 400px;
      }

      .sun {
        width: 80px;
        height: 80px;
      }

      .orbit-earth { width: 200px; height: 200px; }
      .orbit-species-1 { width: 140px; height: 140px; }
      .orbit-species-2 { width: 170px; height: 170px; }
      .orbit-species-3 { width: 230px; height: 230px; }
      .orbit-species-4 { width: 270px; height: 270px; }
      .orbit-species-5 { width: 310px; height: 310px; }

      .earth { width: 30px; height: 30px; top: -100px; }
      .species-1 { width: 25px; height: 25px; top: -70px; }
      .species-2 { width: 28px; height: 28px; top: -85px; }
      .species-3 { width: 25px; height: 25px; top: -115px; }
      .species-4 { width: 28px; height: 28px; top: -135px; }
      .species-5 { width: 32px; height: 32px; top: -155px; }

      .clock-number {
        width: 70px;
        height: 70px;
        font-size: 0.6rem;
      }

      .hour-hand { height: 25px; }
      .minute-hand { height: 32px; }
      .second-hand { height: 35px; }

      .digital-time { font-size: 2rem; }
      .evolution-info { display: none; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="logo">WHEELDER</h1>
    
    <div class="solar-system">
      <!-- Orbital paths -->
      <div class="orbit orbit-species-1"></div>
      <div class="orbit orbit-species-2"></div>
      <div class="orbit orbit-earth"></div>
      <div class="orbit orbit-species-3"></div>
      <div class="orbit orbit-species-4"></div>
      <div class="orbit orbit-species-5"></div>
      
      <!-- Sun with clock -->
      <div class="sun">
        <div class="sun-clock">
          <!-- Clock numbers -->
          <div class="clock-number">
            <div class="number" style="top: 5px; left: 50%; transform: translateX(-50%);">12</div>
            <div class="number" style="top: 8px; right: 20px;">1</div>
            <div class="number" style="top: 25px; right: 8px;">2</div>
            <div class="number" style="top: 50%; right: 5px; transform: translateY(-50%);">3</div>
            <div class="number" style="bottom: 25px; right: 8px;">4</div>
            <div class="number" style="bottom: 8px; right: 20px;">5</div>
            <div class="number" style="bottom: 5px; left: 50%; transform: translateX(-50%);">6</div>
            <div class="number" style="bottom: 8px; left: 20px;">7</div>
            <div class="number" style="bottom: 25px; left: 8px;">8</div>
            <div class="number" style="top: 50%; left: 5px; transform: translateY(-50%);">9</div>
            <div class="number" style="top: 25px; left: 8px;">10</div>
            <div class="number" style="top: 8px; left: 20px;">11</div>
          </div>
          
          <!-- Clock hands -->
          <div class="clock-hands">
            <div class="hand hour-hand" id="hourHand"></div>
            <div class="hand minute-hand" id="minuteHand"></div>
            <div class="hand second-hand" id="secondHand"></div>
            <div class="center-dot"></div>
          </div>
        </div>
      </div>
      
      <!-- Orbiting bodies -->
      <div class="celestial-body species-1-container">
        <div class="species species-1"></div>
      </div>
      
      <div class="celestial-body species-2-container">
        <div class="species species-2"></div>
      </div>
      
      <div class="celestial-body earth-container">
        <div class="earth"></div>
      </div>
      
      <div class="celestial-body species-3-container">
        <div class="species species-3"></div>
      </div>
      
      <div class="celestial-body species-4-container">
        <div class="species species-4"></div>
      </div>
      
      <div class="celestial-body species-5-container">
        <div class="species species-5"></div>
      </div>
    </div>
    
    <!-- Digital time display -->
    <div class="digital-time" id="digitalTime">
      <span id="hours">00</span><span class="time-separator">:</span><span id="minutes">00</span><span class="time-separator">:</span><span id="seconds">00</span><span class="time-period" id="period">AM</span>
    </div>
    <div class="timezone">PST</div>
    
    
  </div>

  <script>
    function updateClock() {
      const now = new Date();
      
      // Get PST/PDT time properly
      const pstString = now.toLocaleString("en-US", {
        timeZone: "America/Los_Angeles",
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
      });
      
      // Parse the PST string to get components
      const [datePart, timePart] = pstString.split(', ');
      const [month, day, year] = datePart.split('/');
      const [hours, minutes, seconds] = timePart.split(':').map(num => parseInt(num));
      
      // For milliseconds, we'll use the current milliseconds
      const milliseconds = now.getMilliseconds();
      
      // Calculate angles
      const secondAngle = (seconds + milliseconds / 1000) * 6; // 360/60
      const minuteAngle = (minutes + seconds / 60) * 6; // 360/60
      const hourAngle = ((hours % 12) + minutes / 60) * 30; // 360/12
      
      // Update analog clock hands
      document.getElementById('secondHand').style.transform = `rotate(${secondAngle}deg)`;
      document.getElementById('minuteHand').style.transform = `rotate(${minuteAngle}deg)`;
      document.getElementById('hourHand').style.transform = `rotate(${hourAngle}deg)`;
      
      // Convert to 12-hour format
      const period = hours >= 12 ? 'PM' : 'AM';
      const displayHours = hours % 12 || 12; // Convert 0 to 12 for midnight
      
      // Update digital time
      document.getElementById('hours').textContent = displayHours.toString().padStart(2, '0');
      document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
      document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
      document.getElementById('period').textContent = period;
    }
    
    // Update clock immediately and then every 10ms for smooth second hand movement
    updateClock();
    setInterval(updateClock, 10);
    
    // Create additional wave on hour change
    let lastHour = null;
    setInterval(() => {
      const now = new Date();
      const pstHour = parseInt(now.toLocaleString("en-US", {
        timeZone: "America/Los_Angeles",
        hour: '2-digit',
        hour12: false
      }));
      
      if (lastHour !== null && pstHour !== lastHour) {
        // Create special effect for hour change
        const sun = document.querySelector('.sun');
        sun.style.animation = 'none';
        setTimeout(() => {
          sun.style.animation = 'sunPulse 4s ease-in-out infinite';
        }, 100);
      }
      lastHour = pstHour;
    }, 1000);
  </script>
</body>
</html>