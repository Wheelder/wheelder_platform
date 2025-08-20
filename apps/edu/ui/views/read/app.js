document.addEventListener('DOMContentLoaded', () => {
    fetchBooks();
});

function fetchBooks() {
    fetch('api.php?action=getBooks')
    .then(response => response.json())
    .then(data => {
        const bookList = document.getElementById('bookList');
        data.forEach(book => {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.innerText = book.title;
            li.onclick = () => fetchBookContent(book.id);
            bookList.appendChild(li);
        });
    });
}

function fetchBookContent(id) {
    fetch(`api.php?action=getBook&id=${id}`)
    .then(response => response.json())
    .then(data => {
        const bookContent = document.getElementById('bookContent');
        bookContent.innerHTML = `
            <h3>${data.title}</h3>
            <iframe src="${data.filepath}" width="100%" height="600px"></iframe>
        `;
    });
}

let speech = new SpeechSynthesisUtterance();
let reading = false;

document.getElementById('readAloud').addEventListener('click', function() {
    if (!reading) {
        let text = document.getElementById('bookContent').innerText;
        speech.text = text;
        window.speechSynthesis.speak(speech);
        reading = true;
    }
});

document.getElementById('pause').addEventListener('click', function() {
    if (speechSynthesis.speaking) speechSynthesis.pause();
});

document.getElementById('resume').addEventListener('click', function() {
    if (speechSynthesis.paused) speechSynthesis.resume();
});

document.getElementById('stop').addEventListener('click', function() {
    speechSynthesis.cancel();
    reading = false;
});

document.getElementById('closeReader').addEventListener('click', function() {
    speechSynthesis.cancel();
    reading = false;
    document.getElementById('bookContent').innerHTML = "";
});

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    formData.append('action', 'uploadBook');

    fetch('api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Book uploaded successfully!');
            fetchBooks();  // refresh the list
        } else {
            alert(data.message);
        }
    });
});
