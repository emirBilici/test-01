<?php

require_once realpath('.') . '/parsedown/Parsedown.php';
$parse = new Parsedown();

?>
<!doctype html>
<html lang="en">
<head>
    <title><?=Title('kullanıcı: ' . $user->username)?></title>
    <?php require_once realpath('.') . '/static/head/require.php'; ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>
    <script src="/public/js/Vote.js" type="module" defer></script>
    <link rel="stylesheet" href="/public/lib/dracula.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300&display=swap" rel="stylesheet">
    <style>
        body {
            overflow: auto scroll!important;
        }
    </style>
</head>
<body>

<?php require_once realpath('.') . '/static/loader.php'; ?>
<?php require_once realpath('.') . '/static/header.php'; ?>
<div id="container">
    <?php require_once realpath('.') . '/static/sidebar.php'; ?>
    <div id="main">
        <div id="content" class="auth">
            <section id="content-body">
                <div class="share-profile" id="share" data-open-dropdown="share-profile">
                    <a href="#" title="Profili paylaş" class="share">
                        <i aria-hidden="true" class="bi bi-share"></i>
                    </a>
                    <ul class="dropdown" data-dropdown="share-profile">
                        <li>
                            <a href="#" data-sharer="facebook">
                                <i aria-hidden="true" class="bi bi-facebook"></i>
                                Facebook
                            </a>
                        </li>
                        <li>
                            <a href="#" data-sharer="twitter">
                                <i aria-hidden="true" class="bi bi-twitter"></i>
                                Twitter
                            </a>
                        </li>
                        <li>
                            <a href="#" class="copy">
                                <i aria-hidden="true" class="bi bi-link"></i>
                                Linki kopyala
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="/profile/<?=$user->username?>" class="profile-title">
                    <i aria-hidden="true" class="bi bi-person-circle"></i>
                    @<?=$user->username?>
                </a>
                <div class="about-me">
                    <span class="info <?php if ($user->user_about === "_null_"): ?>hide<?php endif; ?>">Hakkımda</span>
                    <p class="user-content <?php if ($user->user_about === "_null_"): ?>hide<?php endif; ?>"><?=$user->user_about?></p>
                    <div class="info time-info">
                        <i aria-hidden="true" class="bi bi-info-circle"></i>
                        <span id="user_creation_date"><?=$user->user_created?></span> tarihinde katıldı
                    </div>
                </div>
                <div class="logout-mobile">
                    <a href="/logout">
                        <i aria-hidden="true" class="bi bi-box-arrow-left"></i>Çıkış yap
                    </a>
                    <a href="/new/post">
                        <i aria-hidden="true" class="bi bi-file-earmark-plus"></i>Post ekle
                    </a>
                </div>
                <div class="tab">
                    <div class="openers">
                        <a href="#" data-content="entries"><i aria-hidden="true" class="bi bi-card-text"></i> Entry'ler</a>
                        <a href="#" data-content="saved"><i aria-hidden="true" class="bi bi-collection"></i> Favoriler</a>
                        <?php if(session('login') && session('user_id') === $user->user_id): ?>
                        <a href="#" data-content="settings"><i aria-hidden="true" class="bi bi-gear-fill"></i> Ayarlar</a>
                        <?php endif; ?>
                        <span class="total-points">Toplam Puan: <?=$totalPoints?></span>
                    </div>
                    <div class="contents">
                        <div class="content" id="entries">
                            <?php if(count($posts) > 0): ?>
                                <?php foreach ($posts as $post => $value): ?>
                                    <div class="entry">
                                        <a href="/entry/<?=$value->puID?>" class="title"><?=$value->post_title?></a>
                                        <div class="post">
                                            <span class="content" id="<?=$value->puID?>">
                                                <?php if(isset($value->post_featured_code) && $value->post_featured_code !== "_null_"): ?>
                                                    <pre>
                                                        <code><?=$parse->text($value->post_featured_code)?></code>
                                                    </pre>
                                                <?php endif; ?>
                                                <?=$parse->text($value->post_description)?>
                                            </span>
                                            <footer>
                                                <div class="sharer">
                                                    <div class="share">
                                                        <a href="#facebook_share" data-sharer="facebook" id="/entry/<?=$value->puID?>" class="s_facebook">
                                                            <i aria-hidden="true" class="bi bi-facebook"></i>
                                                        </a>
                                                        <a href="#twitter_share" data-sharer="twitter" id="/entry/<?=$value->puID?>" class="s_twitter">
                                                            <i aria-hidden="true" class="bi bi-twitter"></i>
                                                        </a>
                                                    </div>
                                                    <div class="vote" data-post="<?=$value->post_id?>">
                                                        <a href="#" class="up<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $value->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 1): ?> active<?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$value->post_id?>, <?=session('user_id')?>, 1"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                                                            <i aria-hidden="true" class="bi bi-chevron-up"></i>
                                                        </a>
                                                        <span><?=$value->entry_count?></span>
                                                        <a href="#" class="down<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $value->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 2): ?> active <?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$value->post_id?>, <?=session('user_id')?>, 2"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                                                            <i aria-hidden="true" class="bi bi-chevron-down"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="author">
                                                    <span class="time"><?=$value->created_post?></span>
                                                    <a href="/profile/<?=$user->username?>"><?=$user->username?></a>
                                                </div>
                                            </footer>
                                            <div class="options">
                                                <a href="#" class="dropdown-opener">
                                                    <i aria-hidden="true" class="bi bi-three-dots-vertical"></i>
                                                </a>
                                                <ul>
                                                    <li>
                                                        <a href="#" class="copy-entry" id="<?=$value->puID?>">
                                                            <i aria-hidden="true" class="bi bi-link-45deg"></i>
                                                            Entry link kopyala
                                                        </a>
                                                    </li>
                                                    <?php if(session('login') && session('user_id') === $user->user_id): ?>
                                                        <li>
                                                            <a href="#">
                                                                <i aria-hidden="true" class="bi bi-bookmark-fill"></i>
                                                                Kaydet
                                                            </a>
                                                        </li>
                                                        <li class="danger">
                                                            <a href="#" class="delete-post_dropdown" data-delete-action-data="<?=$value->post_id?>,<?=$value->puID?>">
                                                                <i aria-hidden="true" class="bi bi-trash-fill"></i>
                                                                Sil
                                                            </a>
                                                        </li>
                                                    <?php else: ?>
                                                        <li>
                                                            <a href="#">
                                                                <i aria-hidden="true" class="bi bi-bookmark-fill"></i>
                                                                Kaydet
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <i aria-hidden="true" class="bi bi-flag-fill"></i>
                                                                Rapor et
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <p class="no-post">Burada gösterilecek bir şey yok..</p>
                            <?php endif; ?>
                        </div>
                        <div class="content" id="saved">
                            <p class="no-post">Burada gösterilecek bir şey yok..</p>
<!--                            <div class="entry">-->
<!--                                <a href="#" class="title">ux tasarım diye bir şey</a>-->
<!--                                <div class="post">-->
<!--                                    <span class="content">-->
<!--                                        öyle bi güruh var ki resmen ötv kalkmasın bir araba da devlete almaya devam edelim diyor inanılır gibi değil…-->
<!--                                    </span>-->
<!--                                    <footer>-->
<!--                                        <div class="sharer">-->
<!--                                            <div class="share">-->
<!--                                                <a href="#" class="s_facebook">-->
<!--                                                    <i aria-hidden="true" class="bi bi-facebook"></i>-->
<!--                                                </a>-->
<!--                                                <a href="#" class="s_twitter">-->
<!--                                                    <i aria-hidden="true" class="bi bi-twitter"></i>-->
<!--                                                </a>-->
<!--                                            </div>-->
<!--                                            <div class="vote">-->
<!--                                                <a href="#" class="up">-->
<!--                                                    <i aria-hidden="true" class="bi bi-chevron-up"></i>-->
<!--                                                </a>-->
<!--                                                100k-->
<!--                                                <a href="#" class="down">-->
<!--                                                    <i aria-hidden="true" class="bi bi-chevron-down"></i>-->
<!--                                                </a>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="author">-->
<!--                                            <span class="time">2022-08-01 12:13:11</span>-->
<!--                                            <a href="#">d6b</a>-->
<!--                                        </div>-->
<!--                                    </footer>-->
<!--                                    <div class="options">-->
<!--                                        <a href="#" class="dropdown-opener">-->
<!--                                            <i aria-hidden="true" class="bi bi-three-dots-vertical"></i>-->
<!--                                        </a>-->
<!--                                        <ul>-->
<!--                                            --><?php //if(isset($user->username)): ?>
<!--                                                <li>-->
<!--                                                    <a href="#">-->
<!--                                                        <i aria-hidden="true" class="bi bi-flag-fill"></i>-->
<!--                                                        Rapor et-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li class="danger">-->
<!--                                                    <a href="#">-->
<!--                                                        <i aria-hidden="true" class="bi bi-trash-fill"></i>-->
<!--                                                        Favorilerden Sil-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                            --><?php //else: ?>
<!--                                                <li>-->
<!--                                                    <a href="#">-->
<!--                                                        <i aria-hidden="true" class="bi bi-bookmark-fill"></i>-->
<!--                                                        Kaydet-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li>-->
<!--                                                    <a href="#">-->
<!--                                                        <i aria-hidden="true" class="bi bi-flag-fill"></i>-->
<!--                                                        Rapor et-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                            --><?php //endif; ?>
<!--                                        </ul>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>
                        <?php if(session('login')): ?>
                        <div class="content" id="settings">
                            <form id="settings-form">
                                <div class="block">
                                    <h2 class="subtitle">Genel</h2>
                                    <div class="inline-flex">
                                        <input class="toggle" id="openNotifications" type="checkbox" <?php if($user->notifications == 1): ?>checked<?php endif; ?>>
                                        <label for="openNotifications">Bildirimleri Aç/Kapat</label>
                                    </div>
                                </div>
                                <div class="block about-me--block">
                                    <h2 class="subtitle">Hakkımda</h2>
                                    <div class="inline-flex">
                                        <input class="toggle" id="openAboutMe" type="checkbox" <?php if ($user->user_about !== "_null_"): ?>checked<?php endif; ?>>
                                        <label for="openAboutMe">Aç/Kapat</label>
                                    </div>
                                    <textarea cols="30" rows="10" class="styled--01" id="edit-aboutme" placeholder="Profilini ziyaret edenlere bilgi ver.."><?php if ($user->user_about !== "_null_"): ?><?=$user->user_about?><?php endif; ?></textarea>
                                </div>
                                <div class="block">
                                    <button type="submit" class="primary-btn">Ayarları Kaydet</button>
                                </div>
                            </form>
                            <div class="block">
                                <h2 class="subtitle">Hesap</h2>
                                <div class="useful-links">
                                    <a href="/change-password">Şifreni Değiştir</a>
                                    <a href="/change-username">Kullanıcı Adını Değiştir</a>
                                    <a href="/delete-myaccount" class="danger">Hesabı Sil</a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

<script type="module">
    import Alert from "/public/js/Alert.js";

    !(function() {
        "use strict";

        document.addEventListener("DOMContentLoaded", () => {
            const code = document.querySelectorAll("code");
            [...code].forEach(elem => !elem.classList.contains("highlighted-code") ? elem.classList.add("highlighted-code") : false);
            const highlighted = document.querySelectorAll(".highlighted-code");
            [...highlighted].forEach(el => hljs.highlightElement(el));
            let symbol = document.querySelectorAll("span.hljs-symbol");
            [...symbol].forEach(elem => elem.innerText = "&quot;" ? elem.innerText = `"` : false);

            [...document.querySelectorAll("span.time")].forEach(time => {
                time.innerText = Date.parse(time.textContent).toString("dd/MM/yyyy hh.mm tt")
            });
            const uCreationDate = document.getElementById("user_creation_date");
            uCreationDate.innerText = Date.parse(uCreationDate.textContent).toString("dd/MM/yyyy")
        });

        const copyEntry = document.querySelectorAll(".copy-entry");
        [...copyEntry].forEach(elem => {
            elem.onclick = e => {
                e.preventDefault();
                let url = new URL(location.href);
                const copy = url.origin;
                navigator.clipboard.writeText(`${copy}/entry/${elem.id}`);
                let alert = new Alert("success");
                alert.setMessage("Entry linki başarıyla kopyalandı!");
                alert.showAlert();
            }
        });

        const deleteEntry = document.querySelectorAll('.delete-post_dropdown');
        [...deleteEntry].forEach(el => {
            el.onclick = async (e) => {
                e.preventDefault();
                let _d = e.target.dataset.deleteActionData.split(',')
                let data = {
                        submit: "delete",
                        entry: {
                            id: _d[0],
                            key: _d[1]
                        }
                    };
                if (confirm('Silmek istediğine emin misin?')) {
                    const res = await fetch('/delete/entry', {
                        method: 'post',
                        body: JSON.stringify(data),
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }), jData = await res.json();
                    if (jData === "post_deleted") {
                        e.target.closest(".entry").remove();
                    }
                    if (jData === "error!") {
                        location.reload();
                    }
                }
            }
        });

        const postContent = document.querySelectorAll('.post .content');
        [...postContent].forEach(elem => {
            if (elem.textContent.length > 255) {
                let readMore = document.createElement('div')
                    , a = document.createElement('a');
                readMore.className = 'read-more';
                a.innerText = 'Devamını oku';
                a.href = `/entry/${elem.id}`
                readMore.appendChild(a);
                elem.appendChild(readMore);
            }
        });
    })()
</script>

<?php if(session('login') && session('user_id') === $user->user_id): ?>
<script type="module">
    !(function() {
        "use strict";
        const aboutMeToggle = document.getElementById("openAboutMe");
        const checkToggle = elem => {
            let textarea = document.getElementById("edit-aboutme");
            if(elem.checked && !textarea.classList.contains("open"))
                textarea.classList.add("open");
            if (!elem.checked && textarea.classList.contains("open"))
                textarea.classList.remove("open");
        }
        aboutMeToggle.addEventListener("click", e => checkToggle(e.target));
        window.onload = () => checkToggle(aboutMeToggle);
        // form
        const settingsForm = document.getElementById('settings-form')
        settingsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            let notifications = document.getElementById('openNotifications')
                , aboutMe = document.getElementById('edit-aboutme')
                , openAboutMe = document.getElementById('openAboutMe');

            let data = {
                notifications: notifications.checked ? 1 : 0,
                about_me: aboutMe.value.trim().length === 0 ? '_null_' : aboutMe.value.trim(),
                openAboutMe: openAboutMe.checked ? 1 : 0,
                submit: 'change_user_settings'
            };
            const res = await fetch('/change_user_settings', {
                method: 'post',
                body: JSON.stringify(data),
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            }), jData = await res.json();
            if (jData === "user_settings_updated") {
                location.reload();
            }
            if (jData === "error!") {
                let a = new Alert('danger');
                a.setMessage('Ayarlar kaydedilirken bir sorun oluştu');
                a.showAlert();
            }

        });
    })()
</script>
<?php endif; ?>

</body>
</html>