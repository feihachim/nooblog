console.log('Je suis like pas du tout');

function onClickBtnLike(event) {
    event.preventDefault();

    const url = this.href;
    const spanCount = this.querySelector('span.post-like-count');
    const icone = this.querySelector('i');
    const likeLabel = this.querySelector('span.like-label');

    let xhr = new XMLHttpRequest();

    xhr.open('GET', url);
    xhr.responseType = 'json';
    xhr.send();

    xhr.onload = function () {
        if (xhr.status === 403) {
            window.alert("Vous n'êtes pas autorisé à liker car vous n'êtes pas connecté!");
        }
        else if (xhr.status !== 200) {
            window.alert("Une erreur s'est produite,réessayez plus tard!");
        }
        else {
            let responseObj = xhr.response;
            spanCount.textContent = responseObj.likes;
            if (icone.textContent === '\u{2661}') {
                icone.textContent = '\u{1f5a4}';
                likeLabel.textContent = "Je n'aime plus";
            }
            else {
                icone.textContent = '\u{2661}';
                likeLabel.textContent = "J'aime"
            }
        }
    }
}

document.querySelector('a.js-like').addEventListener('click', onClickBtnLike);
