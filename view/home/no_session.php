<!doctype html>
<html lang="en">
<head>
    <title><?=Title("ekşi sözlük", "kutsal bilgi kaynağı")?></title>
    <?php require_once realpath('.') . '/static/head/require.php'; ?>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
    <link rel="stylesheet" href="/public/lib/dracula.css">
    <script src="/public/js/Vote.js" type="module" defer></script>
    <script>
    !sessionStorage.getItem('selected_tag') ? sessionStorage.setItem('selected_tag', '<?=$defaultTag?>') : false;
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>
    <script type="module" defer>
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
</head>
<body>

<?php require_once realpath('.') . '/static/loader.php'; ?>
<?php require_once realpath('.') . '/static/header.php'; ?>
<div id="container">
    <?php require_once realpath('.') . '/static/sidebar.php'; ?>
    <div id="main">
        <div id="content">
            <section id="content-body">
                <div class="entry">
                    <a href="/entry/<?=$entry->puID?>" class="title"><?=$entry->post_title?></a>
                    <div class="post" id="<?=$entry->puID?>">
                        <span class="content">
                            <?php if($entry->post_featured_code !== '_null_'): ?>
                            <pre>
                                <code class="highlighted-code">
                                    <?=$entry->post_featured_code?>
                                </code>
                            </pre>
                            <?php endif; ?>
                            <?=$entry->post_description?>
                        </span>
                        <footer>
                            <div class="sharer">
                                <div class="share">
                                    <a href="#" class="s_facebook">
                                        <i aria-hidden="true" class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#" class="s_twitter">
                                        <i aria-hidden="true" class="bi bi-twitter"></i>
                                    </a>
                                </div>
                                <div class="vote">
                                    <a href="#" class="up<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $entry->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 1): ?> active<?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$entry->post_id?>, <?=session('user_id')?>, 1"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                                        <i aria-hidden="true" class="bi bi-chevron-up"></i>
                                    </a>
                                    <span><?=$entry->entry_count?></span>
                                    <a href="#" class="down<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $entry->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 2): ?> active <?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$entry->post_id?>, <?=session('user_id')?>, 2"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                                        <i aria-hidden="true" class="bi bi-chevron-down"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="author">
                                <span class="time"><?=$entry->created_post?></span>
                                <a href="/profile/<?=$entry->username?>"><?=$entry->username?></a>
                            </div>
                        </footer>
                        <div class="options">
                            <a href="#" class="dropdown-opener">
                                <i aria-hidden="true" class="bi bi-three-dots-vertical"></i>
                            </a>
                            <ul>
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
                                <li>
                                    <a href="#" class="copy-entry" id="<?=$entry->puID?>">
                                        <i aria-hidden="true" class="bi bi-link-45deg"></i>
                                        Entry link kopyala
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <?php require_once realpath('.') . '/static/aside.php'; ?>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

<?php if(get('reported')): ?>
<script type="module">
    import Alert from "../../public/js/Alert.js";
    !(function() {
        if (sessionStorage.getItem('reported')) {
            let a = new Alert('success');
            a.setMessage('Entry başarıyla rapor edildi!');
            a.showAlert();
            sessionStorage.removeItem('reported');
        }
    })()
</script>
<?php endif; ?>

</body>
</html>