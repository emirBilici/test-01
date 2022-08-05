import Tab from "/public/js/Tab.js";

!(function() {
    "use strict";

    const $post = async(url, data) => {
        const res = await fetch(`/admin/${url}`, {
            method: 'post',
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json'
            },
            body: JSON.stringify(data)
        }); return await res.json();
    }

    // Tab system
    const container = document.getElementById('root');
    new Tab(container, 'users');

    // Users about me section
    const aboutUser = document.querySelectorAll('[data-user-about]');
    [...aboutUser].map(row => {
        let text = row.textContent;
        if (text === '_null_') {
            row.innerText = 'No data..';
        }
    });

    // Delete user action
    const deleteUser = document.querySelectorAll('[data-delete-user]');
    [...deleteUser].forEach(link => {
        link.onclick = async(e) => {
            e.preventDefault();
            let userId = link.dataset.deleteUser;
            if (confirm('Silmek istediğine emin misin?')) {
                const jsonData = await $post('deleteUser', {
                    id: userId,
                    submit: 'delete_user'
                });
                // if (!jsonData) {
                //     alert('forbidden!');
                // } else {
                //     location.reload();
                // }
            }
        }
    });

    // Post description
    const postDesc = document.querySelectorAll('[data-post-desc]');
    [...postDesc].forEach(desc => {
        let {length} = desc.textContent;
        if (length > 255) {
            let text = desc.textContent.replace(/\s/g,'').substring(0, 252);
            desc.innerText = `${text}...`;
            desc.style.maxWidth = '466px';
            desc.style.wordBreak = 'break-all';
        }
    });

    // Delete post action
    const deletePost = document.querySelectorAll('[data-delete-post]');
    [...deletePost].forEach(link => {
        link.onclick = async(e) => {
            e.preventDefault();
            let postId = link.dataset.deletePost;
            if (confirm('Silmek istediğine emin misin?')) {
                const jsonData = await $post('deletePost', {
                    id: postId,
                    submit: 'delete_post'
                });
                if (!jsonData) {
                    alert('forbidden!');
                } else {
                    location.reload();
                }
            }
        }
    });

    // Delete tag action
    const deleteTag = document.querySelectorAll('[data-delete-tag]');
    [...deleteTag].forEach(link => {
        link.onclick = async(e) => {
            e.preventDefault();
            let tagId = link.dataset.deleteTag;
            if (confirm('Silmek istediğine emin misin?')) {
                const jsonData = await $post('deleteTag', {
                    id: postId,
                    submit: 'delete_post'
                });
                if (!jsonData) {
                    alert('forbidden!');
                } else {
                    location.reload();
                }
            }
        }
    });

})()