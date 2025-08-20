 // Enable drag-and-drop functionality
 const draggables = document.querySelectorAll('.draggable');
 const columns = document.querySelectorAll('.col-md-6');

 let keyHeld = false;

 // Listen for a specific key (e.g., Shift) to enable dragging
 document.addEventListener('keydown', (e) => {
     if (e.key === 'Shift') {
         keyHeld = true;
         draggables.forEach((item) => item.setAttribute('draggable', 'true'));
     }
 });

 document.addEventListener('keyup', (e) => {
     if (e.key === 'Shift') {
         keyHeld = false;
         draggables.forEach((item) => item.setAttribute('draggable', 'false'));
     }
 });

 draggables.forEach((draggable) => {
     draggable.addEventListener('dragstart', () => {
         draggable.classList.add('dragging');
     });

     draggable.addEventListener('dragend', () => {
         draggable.classList.remove('dragging');
     });
 });

 columns.forEach((column) => {
     column.addEventListener('dragover', (e) => {
         e.preventDefault();
         const dragging = document.querySelector('.dragging');
         if (keyHeld) {
             column.classList.add('drag-over');
             column.appendChild(dragging);
         }
     });

     column.addEventListener('dragleave', () => {
         column.classList.remove('drag-over');
     });

     column.addEventListener('drop', () => {
         column.classList.remove('drag-over');
     });
 });