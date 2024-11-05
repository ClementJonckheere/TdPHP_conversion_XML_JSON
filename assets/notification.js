document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('conversionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', 'index.php', true);
        httpRequest.onload = function() {
            if (httpRequest.status === 200) {
                try {
                    var response = JSON.parse(httpRequest.responseText);
                    if (response.error) {
                        showNotification(response.error, 'is-danger');
                    } else {
                        showNotification(response.message, 'is-success');
                        if (response.downloadLink && response.fileName) {
                            showDownloadLink(response.downloadLink, response.fileName);
                        }
                    }
                } catch (e) {
                    showNotification('Erreur lors de la conversion.', 'is-danger');
                }
            } else {
                showNotification('Erreur lors de la conversion.', 'is-danger');
            }
        };
        httpRequest.onerror = function() {
            showNotification('Erreur lors de la conversion.', 'is-danger');
        };
        httpRequest.send(formData);
    });
});

function showNotification(message, type) {
    var notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.innerHTML = message + '<button class="delete"></button>';
    document.body.appendChild(notification);
    notification.querySelector('.delete').addEventListener('click', function() {
        notification.remove();
    });
    setTimeout(function() {
        notification.remove();
    }, 10000);
}

function showDownloadLink(link, fileName) {
    var downloadLink = document.createElement('div');
    downloadLink.innerHTML = `<a href="${link}" download>Télécharger le fichier converti : (${fileName})</a>`;
    document.getElementById('notification').appendChild(downloadLink);
}