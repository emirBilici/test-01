<?php

require_once realpath('.') . '/parsedown/Parsedown.php';
$parse = new Parsedown();

?>
<!doctype html>
<html lang="en">
<head>
    <title><?=Title('tag: ' . $data->name)?></title>
    <?php require_once realpath('.') . '/static/head/require.php'; ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>
    <script src="/public/js/Vote.js" type="module" defer></script>
    <link rel="stylesheet" href="/public/lib/dracula.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300&display=swap" rel="stylesheet">
</head>
<body>

<?php require_once realpath('.') . '/static/loader.php'; ?>
<?php require_once realpath('.') . '/static/header.php'; ?>
<div id="container">
    <?php require_once realpath('.') . '/static/sidebar.php'; ?>
    <div id="main">
        <div id="content" class="auth">
            <section id="content-body">
                <a href="/tag/<?=$data->slug?>" class="profile-title" style="text-decoration: underline!important">
                    tag: #<?=$data->name?>
                </a>
                <div class="entries">
                    <?php if(count($posts) > 0):foreach ($posts as $post => $postData): ?>
                    <div class="entry">
                        <a href="/entry/<?=$postData->puID?>" class="title">
                            <?=$postData->post_title?>
                        </a>
                        <div class="post">
                            <span class="content" id="<?=$postData->puID?>">
                                <?php if ($postData->post_featured_code !== "_null_"): ?>
                                    <span style="font-size: 12px;position: absolute;padding-left: 5px;line-height: 12px;">Öne çıkarılan kod</span>
                                    <pre>
                                        <code class="highlighted-code"><?=$postData->post_featured_code?></code>
                                    </pre>
                                <?php endif; ?>
                                <?=$parse->text($postData->post_description)?>
                            </span>
                            <footer>
                                <div class="sharer">
                                    <div class="share">
                                        <a href="#facebook_share" data-sharer="facebook" id="/entry/<?=$postData->puID?>" class="s_facebook">
                                            <i aria-hidden="true" class="bi bi-facebook"></i>
                                        </a>
                                        <a href="#twitter_share" data-sharer="twitter" id="/entry/<?=$postData->puID?>" class="s_twitter">
                                            <i aria-hidden="true" class="bi bi-twitter"></i>
                                        </a>
                                    </div>
                                    <div class="vote" data-post="<?=$postData->post_id?>">
                                        <a href="#" class="up<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $postData->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 1): ?> active<?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$postData->post_id?>, <?=session('user_id')?>, 1"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                                            <i aria-hidden="true" class="bi bi-chevron-up"></i>
                                        </a>
                                        <span><?=$postData->entry_count?></span>
                                        <a href="#" class="down<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $postData->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 2): ?> active <?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$postData->post_id?>, <?=session('user_id')?>, 2"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                                            <i aria-hidden="true" class="bi bi-chevron-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="author">
                                    <span class="time">04/08/2022 03.47 PM</span>
                                    <a href="/profile/<?=$postData->username?>"><?=$postData->username?></a>
                                </div>
                            </footer>
                            <div class="options">
                                <a href="#" class="dropdown-opener">
                                    <i aria-hidden="true" class="bi bi-three-dots-vertical"></i>
                                </a>
                                <ul>
                                    <li>
                                        <a href="#" class="copy-entry" id="oLEiCfTc">
                                            <i aria-hidden="true" class="bi bi-link-45deg"></i>
                                            Entry link kopyala
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i aria-hidden="true" class="bi bi-bookmark-fill"></i>
                                            Kaydet
                                        </a>
                                    </li>
                                    <?php if(session('login') && session('user_id') === $postData->user_id):  ?>
                                    <li class="danger">
                                        <a href="#" class="delete-post_dropdown" data-delete-action-data="5,oLEiCfTc">
                                            <i aria-hidden="true" class="bi bi-trash-fill"></i>
                                            Sil
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;endif; ?>
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

</body>
</html>