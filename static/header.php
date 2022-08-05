<header class="main-header">
    <div class="top-bar">
        <div class="logo">
            <a href="/">
                <img src="/public/img/eksisozluk_logo.svg" height="40" alt="logo">
            </a>
        </div>
        <form action="/search" method="get" class="search-form">
            <label>
                <input type="text" name="q" autocomplete="off" autocapitalize="off" placeholder="Kategorilerde ara.." required>
                <button type="submit">
                    <i aria-hidden="true" class="bi bi-search"></i>
                </button>
            </label>
        </form>
        <?php if(session('login')): ?>
        <div class="auth-buttons logged-in">
            <a href="/new/post" class="header-primary-btn" title="Yeni entry oluşturmak için tıkla">
                <i aria-hidden="true" class="bi bi-file-earmark-plus"></i>
                Yeni Entry
            </a>
            <a href="#" data-open-dropdown="desktop-notifications">
                <i aria-hidden="true" class="bi bi-bell-fill"></i>
                Bildirimler
                <span class="badge" id="notification-count"></span>
            </a>
            <a href="/profile/<?=session('username')?>">
                <i aria-hidden="true" class="bi bi-person-fill"></i>
                <?=session('username')?>
            </a>
            <a href="/logout" class="danger">
                <i aria-hidden="true" class="bi bi-box-arrow-right"></i>
                Çıkış
            </a>
            <ul data-dropdown="desktop-notifications">
                <div id="notifications"<?php if(!session('notifications')): ?> class="off"<?php endif; ?>>
                    <?php if (!session('notifications')): ?>
                    Bildirimleri <a href="/profile/<?=session('username')?>?tab=settings">ayarlardan</a> aktif edebilirsin.
                    <?php endif; ?>
                </div>
            </ul>
        </div>
        <?php else: ?>
        <div class="auth-buttons">
            <a href="/login">Giriş Yap</a>
            <a href="/signup">Kayıt Ol</a>
        </div>
        <?php endif; ?>
    </div>
    <nav class="sub-navigation mobile-hidden">
        <ul class="categories-list"></ul>
    </nav>
    <nav class="sub-navigation mobile-show">
        <ul class="categories-list">
            <li data-mobile-category="1"></li>
            <li data-mobile-category="2"></li>
            <li class="dropdown" data-open-dropdown="mobile-header">
                <a href="#">
                    <i aria-hidden="true" class="bi bi-caret-down"></i>
                    Kategoriler
                </a>
                <ul class="dropdown-list" data-dropdown="mobile-header"></ul>
            </li>
            <?php if(session('login')): ?>
                <li data-open-dropdown="mobile-notifications">
                    <a href="#">
                        <i aria-hidden="true" class="bi bi-bell-fill"></i>
                        Bildirimler
                        <span class="badge" id="notification-count-m"></span>
                    </a>

                    <ul class="dropdown-list notify <?php if(!session('notifications')): ?>off<?php endif; ?>" data-dropdown="mobile-notifications">
                        Bildirimleri <a href="/profile/<?=session('username')?>?tab=settings">ayarlardan</a> aktif edebilirsin.
                    </ul>
                </li>
                <li>
                    <a href="/profile/<?=session('username')?>">
                        <i aria-hidden="true" class="bi bi-person-fill"></i>
                        <?=session('username')?>
                    </a>
                </li>
            <?php else: ?>
            <li>
                <a href="/login">
                    <i aria-hidden="true" class="bi bi-box-arrow-in-right"></i>
                    Giriş Yap
                </a>
            </li>
            <li>
                <a href="/signup">
                    <i aria-hidden="true" class="bi bi-people"></i>
                    Kayıt Ol
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<div class="container"></div>
<?php if(session('login') && session('notifications')): ?>
    <script type="module">
        import Alert from '/public/js/Alert.js';

        !(async function() {
            "use strict";
            const $post = async function(data, uri) {
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
            const $get = async function(uri) {
                const res = await fetch(uri, {
                    method: 'get',
                    headers: {
                        'accept': 'application/json',
                        'content-type': 'application/json'
                    }
                });

                return await res.json();
            };
            const doNotifications = async () => {
                const notifications = await $get('/notifications?userId=<?=session('user_id')?>');
                const n_container = document.getElementById('notifications');
                const n_count = document.getElementById('notification-count');
                const mn_count = document.getElementById('notification-count-m');
                const mobileDNotifications = document.querySelector('[data-dropdown="mobile-notifications"]');

                if (notifications.length !== 0) {
                    n_count.style.display = 'inline-block';
                    n_count.innerText = String(notifications.length);
                    mn_count.style.display = 'inline-block';
                    mn_count.innerText = String(notifications.length);
                    mn_count.style.textAlign = 'center';
                    let list = notifications.reduce((acc, text) => {
                        let li = `<li><a href="#" data-notification="${text.notification_id}">${text.notify_text}</a></li>`;
                        acc += li;
                        return acc;
                    }, '');
                    n_container.innerHTML = `<div class="notification-text-area"><div class="text">Bildirimler</div></div><p>Bildirimleri silmek için düğmelere basın.</p> ${list}`;
                    mobileDNotifications.innerHTML = list;
                    console.log(mobileDNotifications);
                    [...document.querySelectorAll('[data-notification]')].forEach(a => {
                        a.onclick = async (e) => {
                            e.preventDefault();
                            let data = a.dataset.notification;
                            const deleteNotificationRes = await $post({
                                id: data,
                                submit: 1
                            }, '/delete/notification');

                            if (deleteNotificationRes === 'deleted') {
                                let a = new Alert('success');
                                a.setMessage('Bildirim başarıyla silindi!')
                                a.showAlert();
                                await doNotifications();
                            }
                            if (deleteNotificationRes === 'error') {
                                let a = new Alert('danger');
                                a.setMessage('Bildirim silinirken bir hata oluştu!')
                                a.showAlert();
                            }
                        }
                    });
                } else {
                    n_count.style.display = 'none';
                    mn_count.style.display = 'none';
                    n_container.innerHTML = '<p class="no-notification">Henüz hiç bildirim yok.</p>';
                    mobileDNotifications.innerHTML = '<p class="no-notification">Henüz hiç bildirim yok.</p>';
                }
            }
            await doNotifications();
        })()
    </script>
<?php endif; ?>
