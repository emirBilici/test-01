<!doctype html>
<html lang="en">
<head>
    <title><?=Title("Post ekle")?></title>
    <?php require_once realpath('.') . '/static/head/require.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/0.5.3/tailwind.min.css">
    <link rel="stylesheet" href="/public/lib/mde.min.css">
    <script src="/public/lib/mde.min.js"></script>
    <script src="/public/js/Authentication.js" type="module"></script>
</head>
<body>

<?php require_once realpath('.') . '/static/loader.php'; ?>
<?php require_once realpath('.') . '/static/header.php'; ?>
<div id="container">
    <?php require_once realpath('.') . '/static/sidebar.php'; ?>
    <div id="main">
        <div id="content">
            <section id="content-body">
                <h1 class="title">Post Ekle</h1>

                <form class="font-sans text-sm rounded w-full pt-8 pb-8 newpost" id="new-post">
                    <div class="relative border rounded mb-6 shadow appearance-none label-floating form-field">
                        <input class="w-full py-2 px-3 text-green-darker leading-normal rounded" id="post_title" type="text" placeholder="Başlık*" maxlength="75">
                        <label class="absolute block text-green-darker pin-t pin-l w-full px-3 py-2 leading-normal" for="post_title">
                            Başlık*
                        </label>
                        <small class="error"></small>
                    </div>
                    <div class="relative border rounded my-6 shadow appearance-none label-floating form-field">
                        <input class="w-full py-2 px-3 text-green-darker leading-normal rounded" id="featured_code" type="text" placeholder="Öne Çıkarılan Kod" data-novalidate="true" maxlength="255">
                        <label class="absolute block text-green-darker pin-t pin-l w-full px-3 py-2 leading-normal" for="featured_code">
                            Öne Çıkarılan Kod
                        </label>
                        <small class="error"></small>
                    </div>
                    <div class="relative border rounded mb-4 shadow appearance-none label-floating form-field markdown" style="box-shadow: none;border-color: transparent;">
                        <label class="block text-green-darker pin-t pin-l w-full px-2 py-2 leading-normal" for="md_editor">Açıklama*</label>
                        <textarea class="w-full py-2 px-3 text-green-darker leading-normal rounded" id="md_editor" data-novalidate="true"></textarea>
                        <small class="error">Bu alan zorunludur</small>
                    </div>
                    <div class="relative border rounded mb-4 shadow tag appearance-none label-floating form-field" style="box-shadow: none;border-color: transparent;">
                        <label for="tag" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Tag*</label>
                        <select id="tag" class="bg-gray-50 border shadow border-gray-300 py-2 px-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0" selected>--Seçin--</option>
                            <?php foreach ($tags as $index => $value):  ?>
                                <option value="<?=$value->tag_id?>"><?=$value->name?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="error">Bir tane tag seçmelisin</small>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-green-darkest hover:bg-black mt-8 text-white py-2 px-4 rounded" type="submit">
                            Post oluştur
                        </button>
                        <span class="inline-block align-baseline text-grey hover:text-grey-darker">
                            * Zorunlu
                        </span>
                    </div>
                </form>
            </section>
            <?php require_once realpath('.') . '/static/aside.php'; ?>
        </div>
        <?php require_once realpath('.') . '/static/footer.php'; ?>
    </div>
</div>

</body>
</html>