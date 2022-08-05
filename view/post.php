<!doctype html>
<html lang="en">
<head>
    <title><?=Title($data->post_title . ' - #' . $data->puID)?></title>
    <?php require_once realpath('.') . '/static/head/require.php'; ?>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
    <script src="/public/js/Vote.js" type="module"></script>
    <link rel="stylesheet" href="/public/lib/dracula.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300&display=swap" rel="stylesheet">
    <style>
        .vote {
            display: flex;
            width: 100%;
            max-width: 150px;
            margin-left: auto;
            align-items: center;
            justify-content: space-around;
            margin-top: 14px;
        }
        .vote a {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 24px;
            color: #888;
        }
        .vote .up.active,
        .vote .up:hover {
            color: #81C14B !important;
        }
        .vote .down.active,
        .vote .down:hover {
            color: #e50914 !important;
        }
    </style>
</head>
<body>

<?php require_once realpath('.') . '/static/loader.php'; ?>
<?php require_once realpath('.') . '/static/header.php'; ?>
<div id="container">
    <?php require_once realpath('.') . '/static/sidebar.php'; ?>
    <div id="main">
        <div id="content">
            <section id="content-body" class="single-post">
                <div class="entry-options">
                    <a href="#" class="share-entry" title="Entry linkini kopyala" data-share-entry="<?=$data->puID?>">
                        <i aria-hidden="true" class="bi bi-link-45deg"></i>
                    </a>
                    <?php if (session('user_id') === $data->user_id): ?>
                    <a href="#" class="delete-entry" title="Entry'yi sil">
                        <i aria-hidden="true" class="bi bi-trash"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <h1 class="title">
                    <a href="/entry/<?=$data->puID?>"><?=$data->post_title?></a>
                </h1>
                <?php if ($data->post_featured_code !== "_null_"): ?>
                    <span style="font-size: 12px;position: absolute;padding-left: 5px;line-height: 12px;">Öne çıkarılan kod</span>
                    <pre>
                        <code class="highlighted-code"><?=$data->post_featured_code?></code>
                    </pre>
                <?php endif; ?>
                <div id="description">
                    <?=$desc?>
                </div>
                <div class="seperate"></div>
                <div class="author" style="padding-bottom: 25px;">
                    <div class="social-icons">
                        <a href="#facebook_share" class="sm-icon" data-sharer="facebook" title="Facebook'ta paylaş">
                            <i aria-hidden="true" class="bi bi-facebook"></i>
                        </a>
                        <a href="#twitter_share" class="sm-icon" data-sharer="twitter" title="Twitter'da paylaş">
                            <i aria-hidden="true" class="bi bi-twitter"></i>
                        </a>
                        <a href="/tag/<?=$data->slug?>" class="post-tag"><?=$data->name?></a>
                    </div>
                    <a href="/profile/<?=$data->username?>" class="author_page" title="Profili gör..">@<?=$data->username?></a>
                </div>
                <div class="post_b_options" style="padding-bottom: 25px;">
                    <div id="post_creation_date">
                        01/08/2021 tarihinde oluşturuldu
                    </div>
                    <div class="post-report">
                        <button id="report-post" data-report-entry="<?=$data->puID?>">
                            <i aria-hidden="true" class="bi bi-flag-fill"></i>
                            Rapor et
                        </button>
                    </div>
                </div>
            </section>
            <aside class="related-posts" id="aside">
                <?php if(count($related) !== 0): ?>
                <h4>Benzer entry'ler</h4>
                <ul>
                    <?php foreach ($related as $key => $item): ?>
                        <li>
                            <a href="/entry/<?=$item->puID?>">
                                <?=$item->post_title?>
                                <small>(0)</small>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <div class="vote" data-post="<?=$data->post_id?>">
                    <a href="#" class="up<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $data->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 1): ?> active<?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$data->post_id?>, <?=session('user_id')?>, 1"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                        <i aria-hidden="true" class="bi bi-chevron-up"></i>
                    </a>
                    <span><?=$data->entry_count?></span>
                    <a href="#" class="down<?php foreach (session('user_votes') as $vote => $voteData): ?><?php if($voteData['vote_entry'] == $data->post_id && $voteData['user_vote'] === session('user_id')): ?><?php if ($voteData['status'] == 2): ?> active <?php break; endif; ?><?php endif; ?><?php endforeach; ?>" <?php if (session('login')): ?>data-vote="<?=$data->post_id?>, <?=session('user_id')?>, 2"<?php else: ?>onclick="location.href='/login'"<?php endif; ?>>
                        <i aria-hidden="true" class="bi bi-chevron-down"></i>
                    </a>
                </div>
            </aside>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

<script type="module">
    import Alert from "../public/js/Alert.js";
    document.addEventListener("DOMContentLoaded", () => {
        const code = document.querySelectorAll("code");
        [...code].forEach(elem => !elem.classList.contains("highlighted-code") ? elem.classList.add("highlighted-code") : false);
        const highlighted = document.querySelectorAll(".highlighted-code");
        [...highlighted].forEach(el => hljs.highlightElement(el));
        let symbol = document.querySelectorAll("span.hljs-symbol");
        [...symbol].forEach(elem => elem.innerText = "&quot;" ? elem.innerText = `"` : false);
        const shareEntry = document.querySelector('.share-entry');
        let u = new URL(location.href)
            , c = `${u.origin}${u.pathname}`;
        shareEntry.addEventListener('click', e => {
            e.preventDefault();
            navigator.clipboard.writeText(c);
            let a = new Alert('success');
            a.setMessage("Entry linki başarıyla panoya kopyalandı!");
            a.showAlert();
        });
        [...document.querySelectorAll('[data-sharer]')].forEach(elem => elem.dataset.url = c);
        const dataReport = document.querySelector('[data-report-entry]');
        dataReport.addEventListener('click', function(e) {
            e.preventDefault();
            let data = this.dataset.reportEntry.toString();
            location.href = `/reportEntry?key=${data}`;
        });
    });
</script>
<?php if(session('user_id') === $data->user_id): ?>
    <script>
        let deletePost = document.querySelector('.delete-entry');
        deletePost.onclick = async (e) => {
            e.preventDefault();
            if (confirm('Silmek istediğine emin misin?')) {
                let data = {
                    submit: 'delete',
                    entry: {
                        id: <?=$data->post_id?>,
                        key: "<?=$data->puID?>"
                    }
                };
                const res = await fetch('/delete/entry', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                }), jData = await res.json();
                if (jData === 'post_deleted') {
                    location.href = '/';
                }
                if (jData === 'post_not_deleted') {
                    let alert = new Alert('danger');
                    alert.setMessage('Entry silinirken bir hata oluştu')
                    alert.showAlert();
                }
            }
        }
    </script>
<?php endif; ?>

</body>
</html>