import Alert from "./Alert.js";

!(function($) {
    "use strict";
    const API_URL = '/vote';
    $.post = async function(data, uri = API_URL) {
        const res = await fetch(uri, {
            method: 'post',
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        return await res.json();
    };
    const dataVote = document.querySelectorAll('[data-vote]')
        , dataPost = document.querySelectorAll('[data-post]');
    if (dataVote.length > 0) {
        [...dataVote].forEach(data => {
            let dataset = String(data.dataset.vote)
                , params = dataset.split(',');

            const postId = params[0]
                , userId = params[1]
                , type = params[2];

            data.onclick = async (e) => {
                e.preventDefault();
                const pdata = {
                    post_id: postId,
                    user_id: userId,
                    type: type
                }
                let res = await post(pdata);
                const _v = () => {
                    let vote = data.closest('.vote')
                        , up = vote.querySelector('.up')
                        , down = vote.querySelector('.down');

                    if (data === up) {
                        if (down.classList.contains('active'))
                            down.classList.remove('active')
                        if (!up.classList.contains('active'))
                            up.classList.add('active')
                    }
                    if (data === down) {
                        if (up.classList.contains('active'))
                            up.classList.remove('active')
                        if (!down.classList.contains('active'))
                            down.classList.add('active')
                    }
                    let a = new Alert('success');
                    a.setMessage('Oy kullanıldı!');
                    a.showAlert();
                }
                if (res === 'voted!' || res === "updated!") {
                    let counter = data.closest('.vote').querySelector('span');
                    if(Number(pdata.type.trim()) === 1 && !data.closest('.vote').querySelector('.up').classList.contains('active')) {
                        counter.innerText = Number(counter.textContent) + 1;
                    }
                    if(Number(pdata.type.trim()) === 2 && !data.closest('.vote').querySelector('.down').classList.contains('active')) {
                        counter.innerText = Number(counter.textContent) - 1;
                    }
                    console.log(pdata.type, data.closest('.vote').querySelector('span'))
                    if (!sessionStorage.getItem('updated_votes')) {
                        sessionStorage.setItem('updated_votes', JSON.stringify([]));
                    }
                    let arr = sessionStorage.getItem('updated_votes')
                        , ssDt = JSON.parse(arr); // session storage data
                    let filter = ssDt.filter(d => {
                        if (d.post_id === pdata.post_id && d.user_id === pdata.user_id) {
                            return d;
                        }
                    });
                    if (filter.length > 0) {
                        // ekleme
                        // console.log('old: ' , filter);
                        filter[0].type = pdata.type
                        // console.log('new: ' , pdata.type);
                        // console.log(filter[0]);
                    } else {
                        ssDt.push(pdata);
                    }
                    sessionStorage.setItem('updated_votes', JSON.stringify(ssDt))
                    // console.log(JSON.parse(sessionStorage.getItem('updated_votes')));
                    _v();
                }
            }
        });
    }

    if (dataPost.length > 0) {
        [...dataPost].forEach(elem => {
            // update vote
            // console.log(elem.dataset.post, JSON.parse(sessionStorage.getItem('updated_votes')))
            let sessionData = JSON.parse(sessionStorage.getItem('updated_votes'));
            if (sessionData) {
                let f = sessionData.filter(_d => elem.dataset.post == _d.post_id);
                if (f.length !== 0) {
                    let check = f[0];
                    // console.log(elem, check);
                    let status = Number(check.type.trim());
                    let u = elem.querySelector('.up')
                        , d = elem.querySelector('.down')
                    if(u.classList.contains('active'))
                        u.classList.remove('active')
                    if(d.classList.contains('active'))
                        d.classList.remove('active')

                    if (status === 1) {
                        u.classList.add('active');
                    }
                    if (status === 2) {
                        d.classList.add('active');
                    }
                }
            }
        })
    }

})(window)