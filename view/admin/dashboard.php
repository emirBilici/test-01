<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
    <script type="module" defer>
        import Tab from "../../public/js/Tab.js";
        (($) => {
            $.addEventListener('DOMContentLoaded', () => {
                let openers = $.querySelectorAll('.openers a');
                const hideAll = () => {
                    let contents = $.getElementsByClassName('content');
                    [...contents].forEach(c => c.style.display = 'none');
                }
                [...openers].forEach(o => {
                    o.addEventListener('click', e => {
                        e.preventDefault();
                        hideAll();
                        $.getElementById(e.target.dataset.content).style.display = 'block';
                    });
                });

                hideAll();
            });
            const container = document.getElementById('tab');
            new Tab(container, 'users');
        })(document)
    </script>
</head>
<body>

<div style="margin-bottom:12px;">
    <a href="/admin/logout" style="color:darkred;">logout</a>
</div>
<hr>
<div id="tab">
    <div class="openers">
        <a href="#" data-content="users">users</a>
        <a href="#" data-content="posts">posts</a>
        <a href="#" data-content="tags">tags</a>
        <a href="#" data-content="reports">reports</a>
        <a href="#" data-content="settings">settings</a>
    </div>
    <div class="contents">
        <div class="content" id="users">
            <table border="1px">
                <tr>
                    <td>#</td>
                    <td>username</td>
                    <td>email</td>
                    <td>about</td>
                    <td>notifications</td>
                    <td>creation time</td>
                    <td>action</td>
                </tr>
                <?php foreach ($data['users'] as $user => $userData): ?>
                <tr>
                    <td><?=$userData->user_id?></td>
                    <td><?=$userData->username?></td>
                    <td><?=$userData->email?></td>
                    <td><?=$userData->user_about?></td>
                    <td><?=$userData->notifications?></td>
                    <td data-time><?=$userData->user_created?></td>
                    <td>
                        <a href="/profile/<?=$userData->username?>">View</a>
                        <a href="/delete/user/<?=$userData->user_id?>" style="color:darkred">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="content" id="posts">
            <table border="1px">
                <tr>
                    <td>#</td>
                    <td>title</td>
                    <td>featured code</td>
                    <td>description</td>
                    <td>tag</td>
                    <td>owner</td>
                    <td>sub entry</td>
                    <td>creation time</td>
                    <td>action</td>
                </tr>
                <?php foreach ($data['posts'] as $post => $postData): ?>
                <tr>
                    <td><?=$postData->post_id?></td>
                    <td><?=$postData->post_title?></td>
                    <td><?=$postData->post_featured_code?></td>
                    <td><?=$postData->post_description?></td>
                    <td>
                        <a href="/tag/<?=$postData->slug?>">
                            <?=$postData->name?>
                        </a>
                    </td>
                    <td>
                        <a href="/profile/<?=$postData->username?>">
                            @<?=$postData->username?>
                        </a>
                    </td>
                    <td><?=$postData->sub_entry?></td>
                    <td><?=$postData->created_post?></td>
                    <td>
                        <a href="/entry/<?=$postData->puID?>">View</a>
                        <a href="/delete/post/<?=$postData->post_id?>" style="color:darkred">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="content" id="tags">
            <div style="margin-bottom:2em;">
                <h1>create tag</h1>
                <form action="/create/tag" method="post">
                    <label>
                        <input type="text" name="name" placeholder="Tag Name" maxlength="255" required>
                    </label><br><br>
                    <input type="hidden" name="submit" value="1">
                    <button type="submit">submit</button>
                </form>
                <hr>
            </div>
            <table border="1px">
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>Slug</td>
                    <td>Creation Time</td>
                    <td>Action</td>
                </tr>
                <?php foreach ($data['tags'] as $tag => $tagData): ?>
                    <tr>
                        <td><?=$tagData->tag_id?></td>
                        <td><?=$tagData->name?></td>
                        <td><?=$tagData->slug?></td>
                        <td><?=$tagData->created_tag?></td>
                        <td>
                            <a href="/tag/<?=$tagData->slug?>">View</a>
                            <a href="/delete/tag/<?=$tagData->tag_id?>" style="color:darkred;">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="content" id="reports">
            <table border="1px">
                <tr>
                    <td>#</td>
                    <td>Reporter</td>
                    <td>Post</td>
                    <td>Reporter Message</td>
                    <td>Creation Time</td>
                    <td>Action</td>
                </tr>
                <?php foreach ($data['reports'] as $report => $reportData): ?>
                <tr>
                    <td><?=$reportData->report_id?></td>
                    <td>
                        <a href="/profile/<?=$reportData->username?>">@<?=$reportData->username?></a>
                    </td>
                    <td>
                        <a href="/entry/<?=$reportData->puID?>">
                            <?=strlen($reportData->post_title) > 35 ? substr($reportData->post_title, 0, 65) . '...' : $reportData->post_title?>
                        </a>
                    </td>
                    <td><?=$reportData->report_message?></td>
                    <td><?=$reportData->created_report?></td>
                    <td>
                        <a href="/notify/report?id=<?=$reportData->report_id?>">Notify user</a>
                        <a href="/delete/report/<?=$reportData->report_id?>" style="color:darkred;">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="content" id="settings">
            <h3>Settings</h3>
            <form action="/change_default_tag" method="post">
                <label for="default_tag">homepage default tag: </label>
                <select name="homepage_default_tag" id="default_tag">
                    <option value="0">not set</option>
                    <?php foreach ($data['tags'] as $tag => $tagData): ?>
                        <option value="<?=$tagData->tag_id?>" <?php if($tagData->tag_id=== $data['default_tag']->homepage_default_tag): ?>selected<?php endif; ?>><?=$tagData->name?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" name="submit" value="change">
            </form>
        </div>
    </div>
</div>

</body>
</html>